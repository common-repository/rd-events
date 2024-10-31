<?php
/**
 * This example will show you how to display events in archive page by using normal code.
 * 
 * If you want to override css of the calendar, use <code>wp_dequeue_style('rd-events-calendar')</code> and then enqueue yours.
 * 
 * @package rundiz-events
 */


// Copy the code below into your archive template for custom post type. (refer from AppTrait->post_type it is rd_events).
?>
<div class="rundiz-events-loading-events-template">
    <span class="loading-icon">
        <img src="<?php echo trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/img/loading-squares.gif'; ?>"><br>
        <?php _e('Getting events data', 'rd-events'); ?> 
    </span>
</div><!--.rundiz-events-loading-events-template-->
<div class="rundiz-events-calendar"></div><!--.rundiz-events-calendar-->
<?php
// And then enqueue these scripts and styles in your theme functions.
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('rd-events-fullcalendar');
    wp_enqueue_style('rd-events-fullcalendar-print');
    wp_enqueue_style('rd-events-calendar');

    wp_enqueue_script('rd-events-fullcalendar-moment');
    wp_enqueue_script('rd-events-fullcalendar');
    wp_enqueue_script('rd-events-fullcalendar-locale');
    wp_enqueue_script('rd-events-calendar');
});