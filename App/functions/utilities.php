<?php
/**
 * This is the utilities functions that use ViewsUtilities class.
 * 
 * @package rundiz-events
 */


if (!function_exists('rdevents_getEventEnd')) {
    /**
     * Get event end date and time (if time was set and not all day event).
     * 
     * @param boolean $date_only Set to true to get date only, false to get date with time but if time was not set then only date will be return.
     * @return string Return date value in YYYY-mm-dd or YYYY-mm-dd HH:ii:ss format. Return empty string if something was wrong.
     */
    function rdevents_getEventEnd($date_only = false)
    {
        return \RdEvents\App\Libraries\ViewsUtilities::getEventEnd($date_only);
    }// rdevents_getEventEnd
}


if (!function_exists('rdevents_getEventStart')) {
    /**
     * Get event start date and time (if time was set and not all day event).
     * 
     * @param boolean $date_only Set to true to get date only, false to get date with time but if time was not set then only date will be return.
     * @return string Return date value in YYYY-mm-dd or YYYY-mm-dd HH:ii:ss format. Return empty string if something was wrong.
     */
    function rdevents_getEventStart($date_only = false)
    {
        return \RdEvents\App\Libraries\ViewsUtilities::getEventStart($date_only);
    }// rdevents_getEventStart
}


if (!function_exists('rdevents_getGoogleMapsApiUrl')) {
    /**
     * Get Google Maps API URL.
     * 
     * @return string Return Google Maps API URL with your API key included.
     */
    function rdevents_getGoogleMapsApiUrl()
    {
        return \RdEvents\App\Libraries\ViewsUtilities::getGoogleMapsApiUrl();
    }// rdevents_getGoogleMapsApiUrl
}


if (!function_exists('rdevents_getLocationValues')) {
    /**
     * Get location values.
     * 
     * @return mixed Return array with <code>location</code>, <code>lat</code>, <code>lng</code> for array keys. Return <code>null</code> if location was not set.
     */
    function rdevents_getLocationValues()
    {
        return \RdEvents\App\Libraries\ViewsUtilities::getLocationValues();
    }// rdevents_getLocationValues
}


if (!function_exists('rdevents_getRundizEventsMapFunctionUrl')) {
    /**
     * Get Rundiz Events map function URL to make Google Maps with marker works.
     * 
     * @return string Return the URL to js file that has function to initialize the Google Maps.
     */
    function rdevents_getRundizEventsMapFunctionUrl()
    {
        return \RdEvents\App\Libraries\ViewsUtilities::getRundizEventsMapFunctionUrl();
    }// rdevents_getRundizEventsMapFunctionUrl
}


if (!function_exists('rdevents_isAlldayEvent')) {
    /**
     * Check that is this all day event.
     * 
     * @return boolean Return true if it is all day event, false for otherwise.
     */
    function rdevents_isAlldayEvent()
    {
        return \RdEvents\App\Libraries\ViewsUtilities::isAlldayEvent();
    }// rdevents_isAlldayEvent
}