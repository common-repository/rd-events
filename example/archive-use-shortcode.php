<?php
/**
 * Use shortcode for display events in archive template.
 * 
 * Add <code>echo do_shortcode('[rdevents_archive]');</code> into your archive.php template for rd_events post type.
 * 
 * Attributes.<br>
 * This shortcode has no attributes.
 * 
 * Example: <br>
 * <code>echo do_shortcode('[rdevents_archive]');</code> This will display events calendar in archive page.<br>
 * 
 * If you use this shortcode then you no need to enqueue any scripts. All you need to enqueue is only styles.
 * If you want to override css of the calendar, use <code>wp_dequeue_style('rd-events-calendar')</code> and then enqueue yours.
 * 
 * @package rundiz-events
 */


echo do_shortcode('[rdevents_archive]');// show events in archive page.


// Enqueue these styles in your theme function.
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('rd-events-fullcalendar');
    wp_enqueue_style('rd-events-fullcalendar-print');
    wp_enqueue_style('rd-events-calendar');
});