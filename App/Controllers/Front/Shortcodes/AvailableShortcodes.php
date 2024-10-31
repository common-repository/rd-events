<?php
/**
 * Available shortcodes.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Front\Shortcodes;

if (!class_exists('\\RdEvents\\App\\Controllers\\Front\\Shortcodes\\AvailableShortcodes')) {
    /**
     * Works on all available shortcode for this plugin.
     */
    class AvailableShortcodes implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * Display event data for single post.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         * @param array $atts
         * @return string Return generated html and data.
         */
        public function displayEventDataSinglePost($atts)
        {
            if (is_single() && get_the_ID() !== false) {
                $atts = shortcode_atts(
                    [
                        'showdate' => 'true',
                        'showlocation' => 'true',
                    ],
                    $atts,
                    'rdevents_single'
                );

                $output = '';

                if (isset($atts['showdate']) && 'true' === $atts['showdate']) {
                    $output .= '<h4>' . __('Event date/time', 'rd-events') . '</h4>'."\n";
                    $output .= '<p>' . rdevents_getEventStart() . ' - ' . rdevents_getEventEnd();
                    if (rdevents_isAlldayEvent() === true) {
                        $output .= '<br>'."\n" . __('All day event.', 'rd-events');
                    }
                    $output .= '</p>'."\n";
                }

                if (isset($atts['showlocation']) && 'true' === $atts['showlocation']) {
                    $location = rdevents_getLocationValues();
                    if (null !== $location) {
                        $output .= '<h4>' . __('Location', 'rd-events') . '</h4>'."\n";
                        if (isset($location['location'])) {
                            $output .= '<p>'.$location['location'].'</p>'."\n";
                        }
                        $output .= '<div id="rundiz-events-map" class="rundiz-events-map" data-markerlat="'.(isset($location['lat']) ? $location['lat'] : '').'" data-markerlng="'.(isset($location['lng']) ? $location['lng'] : '').'" data-mapzoom="12"></div>'."\n";
                        wp_enqueue_script('rd-events-map-functions');
                        wp_enqueue_script('rd-events-google-map-api');
                    }
                    unset($location);
                }

                return $output;
            }
        }// displayEventDataSinglePost


        /**
         * Display events in archive page.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         * @param array $atts
         * @return string Return generated html and data.
         */
        public function displayEventsArchive($atts)
        {
            $output = '';

            $output .= '<div class="rundiz-events-loading-events-template">';
            $output .= '<span class="loading-icon">';
            $output .= '<img src="' . trailingslashit(plugin_dir_url(RDEVENTS_FILE)) . 'assets/img/loading-squares.gif"><br>';
            $output .= __('Getting events data', 'rd-events');
            $output .= '</span>';
            $output .= '</div><!--.rundiz-events-loading-events-template-->';
            $output .= '<div class="rundiz-events-calendar"></div><!--.rundiz-events-calendar-->';

            wp_enqueue_script('rd-events-fullcalendar-moment');
            wp_enqueue_script('rd-events-fullcalendar');
            wp_enqueue_script('rd-events-fullcalendar-locale');
            wp_enqueue_script('rd-events-calendar');

            return $output;
        }// displayEventsArchive


        /**
         * Enqueue styles on single post.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         */
        public function enqueueStyleSinglePost()
        {
            if (is_single() && get_the_ID() !== false) {
                wp_enqueue_style('rd-events-map');
            }
        }// enqueueStyleSinglePost


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            add_action('wp_enqueue_scripts', [$this, 'enqueueStyleSinglePost']);

            add_shortcode('rdevents_single', [$this, 'displayEventDataSinglePost']);
            add_shortcode('rdevents_archive', [$this, 'displayEventsArchive']);
        }// registerHooks


    }
}