<?php
$event_date_start = null;
$event_time_start = null;
$event_date_end = null;
$event_time_end = null;
$event_time_allday = '0';

if (isset($post->ID)) {
    $post_metas = get_post_meta($post->ID);
    if (is_array($post_metas)) {
        if (array_key_exists('_event_date_start', $post_metas)) {
            $event_date_start = $post_metas['_event_date_start'];
            if (is_array($event_date_start)) {
                $event_date_start = $event_date_start[0];
            }
        }
        if (array_key_exists('_event_time_start', $post_metas)) {
            $event_time_start = $post_metas['_event_time_start'];
            if (is_array($event_time_start)) {
                $event_time_start = $event_time_start[0];
            }
        }
        if (array_key_exists('_event_date_end', $post_metas)) {
            $event_date_end = $post_metas['_event_date_end'];
            if (is_array($event_date_end)) {
                $event_date_end = $event_date_end[0];
            }
        }
        if (array_key_exists('_event_time_end', $post_metas)) {
            $event_time_end = $post_metas['_event_time_end'];
            if (is_array($event_time_end)) {
                $event_time_end = $event_time_end[0];
            }
        }
        if (array_key_exists('_event_time_allday', $post_metas)) {
            if (is_array($post_metas['_event_time_allday'])) {
                $event_time_allday = $post_metas['_event_time_allday'][0];
            }
            if ('1' !== $event_time_allday && '0' !== $event_time_allday) {
                $event_time_allday = '0';
            }
        }
    }
    unset($post_metas);
}


// add nonce field for check.
wp_nonce_field(plugin_basename(dirname(RDEVENTS_FILE)).'-datepicker', plugin_basename(dirname(RDEVENTS_FILE)).'-datepicker');

?>
<div class="rd-events-datetime-container">
    <div class="rd-events-datetime-column">
        <h4><?php _e('Event start', 'rd-events'); ?></h4>
        <fieldset>
            <legend class="sr-only screen-reader-text"><?php _e('Event start', 'rd-events'); ?></legend>
            <label class="rd-events-date-input" for="rd-events-date-start">
                <?php _e('Date', 'rd-events'); ?> <input id="rd-events-date-start" class="rd-events-datetime-input rd-events-date-field" type="date" name="_event_date_start" value="<?php echo $event_date_start; ?>" required="required" autocomplete="off">
            </label>
            <label class="rd-events-time-input<?php if (isset($event_time_allday) && '1' === $event_time_allday) {echo ' hidden';} ?>" for="rd-events-time-start">
                <?php _e('Time', 'rd-events'); ?> <input id="rd-events-time-start" class="rd-events-datetime-input rd-events-time-field" type="time" name="_event_time_start" value="<?php echo $event_time_start; ?>">
            </label>
        </fieldset>
    </div><!--.rd-events-column-->
    <div class="rd-events-datetime-column">
        <h4><?php _e('Event end', 'rd-events'); ?></h4>
        <fieldset>
            <legend class="sr-only screen-reader-text"><?php _e('Event end', 'rd-events'); ?></legend>
            <label class="rd-events-date-input" for="rd-events-date-end">
                <?php _e('Date', 'rd-events'); ?> <input id="rd-events-date-end" class="rd-events-datetime-input rd-events-date-field" type="date" name="_event_date_end" value="<?php echo $event_date_end; ?>" required="required" autocomplete="off">
            </label>
            <label class="rd-events-time-input<?php if (isset($event_time_allday) && '1' === $event_time_allday) {echo ' hidden';} ?>" for="rd-events-time-end">
                <?php _e('Time', 'rd-events'); ?> <input id="rd-events-time-end" class="rd-events-datetime-input rd-events-time-field" type="time" name="_event_time_end" value="<?php echo $event_time_end; ?>">
            </label>
        </fieldset>
    </div><!--.rd-events-column-->
</div><!--.rd-events-container-->

<p>
    <label for="rd-events-time-allday">
        <input id="rd-events-time-allday" type="checkbox" name="_event_time_allday" value="1"<?php if (isset($event_time_allday) && '1' === $event_time_allday) {echo ' checked="checked"';} ?>>
        <?php _e('All day', 'rd-events'); ?> 
    </label>
</p>