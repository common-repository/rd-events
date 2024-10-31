<?php
/**
 * This example will show you how the event date/time and location parts will display using <code>ViewsUtitlties</code> class.
 * 
 * If you want to override the css of the map, use <code>wp_dequeue_style('rd-events-map');</code> and then enqueue yours.
 * 
 * @package rundiz-events
 */


// Copy the code below into single template of your theme and change any text into translatable string.
echo '<h4>Event date/time</h4>';
echo '<p>';
echo \RdEvents\App\Libraries\ViewsUtilities::getEventStart();
echo ' - ';
echo \RdEvents\App\Libraries\ViewsUtilities::getEventEnd();
if (\RdEvents\App\Libraries\ViewsUtilities::isAlldayEvent() === true) {
    echo '<br>All day event.';
}
echo '</p>';

$location = \RdEvents\App\Libraries\ViewsUtilities::getLocationValues();
if (null !== $location) {
    echo '<h4>Location</h4>';
    if (isset($location['location'])) {
        echo '<p>'.$location['location'].'</p>';
    }
    echo '<div id="rundiz-events-map" class="rundiz-events-map" data-markerlat="'.(isset($location['lat']) ? $location['lat'] : '').'" data-markerlng="'.(isset($location['lng']) ? $location['lng'] : '').'" data-mapzoom="12"></div>';

    // These script tags are not recommended, just example.
    echo '<script src="'.\RdEvents\App\Libraries\ViewsUtilities::getRundizEventsMapFunctionUrl().'"></script>';// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
    echo '<script src="'.\RdEvents\App\Libraries\ViewsUtilities::getGoogleMapsApiUrl().'&callback=yourCallbackFunction"></script>';// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript

    // Use wp_enqueue_script() function instead.
    // You always need to enqueue these js files if you don't use shortcode.
    // Example: 
    // wp_enqueue_script('rd-events-map-functions');
    // wp_enqueue_script('rd-events-google-map-api');
}
unset($location);