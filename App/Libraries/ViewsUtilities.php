<?php
/**
 * Views utility.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Libraries;

if (!class_exists('\\RdEvents\\App\\ibraries\\ViewsUtilities')) {
    /**
     * Views utilities class works with theme or template for displaying the data from this events plugin.
     */
    class ViewsUtilities
    {


        use \RdEvents\App\AppTrait;


        /**
         * Get event end date and time (if time was set and not all day event).
         * 
         * @param boolean $date_only Set to true to get date only, false to get date with time but if time was not set then only date will be return.
         * @return string Return date value in YYYY-mm-dd or YYYY-mm-dd HH:ii:ss format. Return empty string if something was wrong.
         */
        public static function getEventEnd($date_only = false)
        {
            $post_id = (function_exists('get_the_ID') ? get_the_ID() : '0');

            if (false === $post_id || '0' === $post_id) {
                return '';
            }

            $output = get_post_meta($post_id, '_event_date_end', true);
            if (false === $date_only) {
                $time = get_post_meta($post_id, '_event_time_end', true);
                if (!empty($time)) {
                    $output .= ' ' . $time;
                }
                unset($time);
            }

            unset($post_id);
            return $output;
        }// getEventEnd


        /**
         * Get event start date and time (if time was set and not all day event).
         * 
         * @param boolean $date_only Set to true to get date only, false to get date with time but if time was not set then only date will be return.
         * @return string Return date value in YYYY-mm-dd or YYYY-mm-dd HH:ii:ss format. Return empty string if something was wrong.
         */
        public static function getEventStart($date_only = false)
        {
            $post_id = (function_exists('get_the_ID') ? get_the_ID() : '0');

            if (false === $post_id || '0' === $post_id) {
                return '';
            }

            $output = get_post_meta($post_id, '_event_date_start', true);
            if (false === $date_only) {
                $time = get_post_meta($post_id, '_event_time_start', true);
                if (!empty($time)) {
                    $output .= ' ' . $time;
                }
                unset($time);
            }

            unset($post_id);
            return $output;
        }// getEventStart


        /**
         * Get Google Maps API URL.
         * 
         * @return string Return Google Maps API URL with your API key included.
         */
        public static function getGoogleMapsApiUrl()
        {
            $thisClass = new static();

            $options = get_option($thisClass->main_option_name);
            $googlemap_api = '';
            if (is_array($options) && array_key_exists('googlemap_api', $options)) {
                $googlemap_api = $options['googlemap_api'];
            }
            unset($options);

            return 'https://maps.googleapis.com/maps/api/js?key=' . rawurlencode($googlemap_api) . '&libraries=places';
        }// getGoogleMapsApiUrl


        /**
         * Get location values.
         * 
         * @return mixed Return array with <code>location</code>, <code>lat</code>, <code>lng</code> for array keys. Return <code>null</code> if location was not set.
         */
        public static function getLocationValues()
        {
            $post_id = (function_exists('get_the_ID') ? get_the_ID() : '0');

            if (false === $post_id || '0' === $post_id) {
                return '';
            }

            $output = [];
            $event_location = get_post_meta($post_id, '_event_location', true);
            $event_location_lat = get_post_meta($post_id, '_event_location_lat', true);
            $event_location_lng = get_post_meta($post_id, '_event_location_lng', true);

            if (!empty($event_location) && !empty($event_location_lat) && !empty($event_location_lng)) {
                $output['location'] = $event_location;
                $output['lat'] = $event_location_lat;
                $output['lng'] = $event_location_lng;
            } else {
                $output = null;
            }

            unset($event_location, $event_location_lat, $event_location_lng);
            return $output;
        }// getLocationValues


        /**
         * Get Rundiz Events map function URL to make Google Maps with marker works.
         * 
         * @return string Return the URL to js file that has function to initialize the Google Maps.
         */
        public static function getRundizEventsMapFunctionUrl()
        {
            return trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/js/front/rd-events-map.js';
        }// getRundizEventsMapFunctionUrl


        /**
         * Check that is this all day event.
         * 
         * @return boolean Return true if it is all day event, false for otherwise.
         */
        public static function isAlldayEvent()
        {
            $post_id = (function_exists('get_the_ID') ? get_the_ID() : '0');

            if (false === $post_id || '0' === $post_id) {
                return false;
            }

            $allday = get_post_meta($post_id, '_event_time_allday', true);

            if ('1' === $allday) {
                return true;
            }

            unset($allday, $post_id);
            return false;
        }// isAlldayEvent


    }
}