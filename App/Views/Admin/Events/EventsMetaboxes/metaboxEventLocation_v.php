<?php
$event_location = null;
$event_location_lat = null;
$event_location_lng = null;

if (isset($post->ID)) {
    $post_metas = get_post_meta($post->ID);
    if (is_array($post_metas)) {
        if (array_key_exists('_event_location', $post_metas)) {
            $event_location = $post_metas['_event_location'];
            if (is_array($event_location)) {
                $event_location = $event_location[0];
            }
        }
        if (array_key_exists('_event_location_lat', $post_metas)) {
            $event_location_lat = $post_metas['_event_location_lat'];
            if (is_array($event_location_lat)) {
                $event_location_lat = $event_location_lat[0];
            }
        }
        if (array_key_exists('_event_location_lng', $post_metas)) {
            $event_location_lng = $post_metas['_event_location_lng'];
            if (is_array($event_location_lng)) {
                $event_location_lng = $event_location_lng[0];
            }
        }
    }
    unset($post_metas);
}


// add nonce field for check.
wp_nonce_field(plugin_basename(dirname(RDEVENTS_FILE)).'-map', plugin_basename(dirname(RDEVENTS_FILE)).'-map');

?>
<div class="rd-events-location-container">
    <h4><?php _e('Enter address or click on the map below', 'rd-events'); ?></h4>
    <fieldset>
        <legend class="sr-only screen-reader-text"><?php _e('Location', 'rd-events'); ?></legend>
        <label class="rd-events-location-label" for="rd-events-location">
            <input id="rd-events-location" class="rd-events-location-input rd-events-location-field widefat" type="text" name="_event_location" value="<?php echo $event_location; ?>" autocomplete="off">
        </label>

        <input id="rd-events-location-lat" type="hidden" name="_event_location_lat" value="<?php echo $event_location_lat; ?>">
        <input id="rd-events-location-lng" type="hidden" name="_event_location_lng" value="<?php echo $event_location_lng; ?>">
        <div id="map" class="rd-events-map"></div>
        <div id="rd-events-infowindow-content">
            <div id="rd-events-place-name"  class="rd-events-location-title"></div>
            <span id="rd-events-place-address"></span>
        </div>

        <button class="rd-events-clear-location button" type="button" onclick="return rdEventsClearLocation();"><?php _e('Clear', 'rd-events'); ?></button>
    </fieldset>
</div><!--.rd-events-location-container-->