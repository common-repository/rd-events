<?php
/**
 * Events DB model.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Models;

if (!class_exists('\\RdEvents\\App\\Models\\EventsModel')) {
    /**
     * Works on database to get events data.
     * 
     * @since v0.2.3
     */
    class EventsModel
    {


        use \RdEvents\App\AppTrait;


        /**
         * Get all events.
         * 
         * @return array Return the associate array that is ready to json encode for fullcalendar.
         */
        public function getAllEvents()
        {
            $query_args = [
                'post_type' => $this->post_type,
                'nopaging' => true,
            ];
            $the_query = new \WP_Query($query_args);
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

            unset($query_args, $the_query);
            return $events_data;
        }// getAllEvents


    }
}