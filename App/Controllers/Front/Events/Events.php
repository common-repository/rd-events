<?php
/**
 * Events AJAX.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Front\Events;

if (!class_exists('\\RdEvents\\App\\Controllers\\Front\\Events\\Events')) {
    /**
     * Controller for ajax request events.
     * 
     * @link https://codex.wordpress.org/AJAX_in_Plugins Reference.
     */
    class Events implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * Ajax get events.<br>
         * This will response json encoded data. Not return.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         */
        public function ajaxGetEvents()
        {
            if (is_admin() === true && isset($_SERVER['REQUEST_METHOD']) && strtolower(sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD']))) === 'post') {
                if (
                    isset($_POST['nonce']) &&
                    isset($_POST['start']) &&
                    isset($_POST['end']) &&
                    wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'rd-events-calendar')
                ) {
                    $start_date_month = sanitize_text_field(wp_unslash($_POST['start']));
                    $end_date_month = sanitize_text_field(wp_unslash($_POST['end']));
                    $query_args = [
                        'post_type' => $this->post_type,
                        'nopaging' => true,
                        'meta_query' => [// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                            // query event that start between this month or end between this month
                            [
                                [
                                    'key' => '_event_date_start',
                                    'value' => [$start_date_month, $end_date_month],
                                    'compare' => 'BETWEEN',
                                    'type' => 'DATE',
                                ],
                                'relation' => 'OR',
                                [
                                    'key' => '_event_date_end',
                                    'value' => [$start_date_month, $end_date_month],
                                    'compare' => 'BETWEEN',
                                    'type' => 'DATE',
                                ],
                            ],
                            'relation' => 'OR',
                            [
                                [
                                    'key' => '_event_date_start',
                                    'value' => $start_date_month,
                                    'compare' => '<',
                                    'type' => 'DATE',
                                ],
                                [
                                    'key' => '_event_date_end',
                                    'value' => $end_date_month,
                                    'compare' => '>=',
                                    'type' => 'DATE',
                                ],
                            ],
                        ],
                    ];
                    $the_query = new \WP_Query($query_args);
                    unset($end_date_month, $query_args, $start_date_month);
                    $events_data = [];
                    if ($the_query->have_posts()) {
                        while ($the_query->have_posts()) {
                            $the_query->the_post();

                            $evcal_start_date = \RdEvents\App\Libraries\ViewsUtilities::getEventStart();
                            $evcal_end_date = \RdEvents\App\Libraries\ViewsUtilities::getEventEnd();
                            if (empty($evcal_end_date)) {
                                $evcal_end_date = $evcal_start_date;
                            }

                            // add 1 date for fix end date not inclusive on fullcalendar.js
                            // read more at http://stackoverflow.com/questions/22634772/fullcalendar-end-date-is-not-inclusive
                            $Date = new \DateTime($evcal_end_date);
                            $Date->add(new \DateInterval('P1D'));
                            $evcal_end_date = $Date->format('Y-m-d');
                            unset($Date);

                            // the array keys refer from https://fullcalendar.io/docs/event_data/Event_Object/
                            $events_data[] = [
                                'id' => get_the_ID(),
                                'title' => get_the_title(),
                                'start' => $evcal_start_date,
                                'end' => $evcal_end_date,
                                'url' => get_the_permalink(),
                            ];

                            unset($evcal_end_date, $evcal_start_date);
                        }// endwhile;
                    }

                    // restore original post data.
                    // @link https://codex.wordpress.org/Function_Reference/wp_reset_postdata Reference.
                    wp_reset_postdata();

                    unset($the_query);
                    wp_send_json($events_data);
                    exit();// required.
                }
            }// endif is_admin()

            wp_die();// required
        }// ajaxGetEvents


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // ajax action for both logged in and not logged in users. (wp_ajax_ for logged in, wp_ajax_nopriv_ for not logged in.)
            add_action('wp_ajax_rundiz_events_get_events', [$this, 'ajaxGetEvents']);
            add_action('wp_ajax_nopriv_rundiz_events_get_events', [$this, 'ajaxGetEvents']);
        }// registerHooks


    }
}