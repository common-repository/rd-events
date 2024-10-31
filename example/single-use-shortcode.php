<?php
/**
 * Use shortcode for display event date/time and location in single post template.
 * 
 * Add <code>echo do_shortcode('[rdevents_single]');</code> into your single.php template where it is displaying the content.
 * 
 * Attributes.<br>
 * The first attribute is <code>showdate</code> its value is true or false. No need to add this attribute because its default is true.<br>
 * The second attribute is <code>showlocation</code> its value is true or false. No need to add this attribute because its default is true.
 * 
 * Example: <br>
 * <code>echo do_shortcode('[rdevents_single showdate="true" showlocation="false"]');</code> This will show only date.<br>
 * <code>echo do_shortcode('[rdevents_single showdate="false" showlocation="true"]');</code> This will show only location.<br>
 * <code>echo do_shortcode('[rdevents_single]');</code> This will show all.<br>
 * 
 * If you use this shortcode then you no need to enqueue map functions, Google Maps API, and css.
 * If you want to override the css of the map, use <code>wp_dequeue_style('rd-events-map');</code> and then enqueue yours.<br>
 * 
 * @package rundiz-events
 */


echo do_shortcode('[rdevents_single]');// show event date/time and location (including map).