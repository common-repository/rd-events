<?php
/**
 * The basic template for display single post of event.<br>
 * You can copy and modify this in your theme folder.
 * 
 * @package rundiz-events
 */


add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('rd-events-map-functions');
    wp_enqueue_script('rd-events-google-map-api');
});


get_header();
?> 
                <main id="main" class="site-main" role="main">
                    <?php 
                    if (have_posts()) {
                        echo '<article>'."\n";
                        while (have_posts()) {
                            the_post();
                            echo '<h1>' . get_the_title() . '</h1>'."\n";
                            the_content();
                            echo "\n\n";
                            echo '<div style="clear:both;"></div>'."\n";

                            // for the event date/time and location below, please see more in example folder.
                            echo '<h4>' . __('Event date/time', 'rd-events') . '</h4>';
                            echo '<p>';
                            echo rdevents_getEventStart();
                            echo ' - ';
                            echo rdevents_getEventEnd();
                            if (rdevents_isAlldayEvent() === true) {
                                echo '<br>'."\n" . __('All day event.', 'rd-events');
                            }
                            echo '</p>';

                            $location = rdevents_getLocationValues();
                            if (null !== $location) {
                                echo '<h4>' . __('Location', 'rd-events') . '</h4>';
                                if (isset($location['location'])) {
                                    $gmapsParams = '';
                                    if (array_key_exists('lat', $location)) {
                                        $gmapsParams .= rawurlencode($location['lat']);
                                    }
                                    if (array_key_exists('lng', $location)) {
                                        if (!empty($gmapsParams)) {
                                            $gmapsParams .= ',';
                                        }
                                        $gmapsParams .= rawurlencode($location['lng']);
                                    }

                                    echo '<p><a href="https://www.google.com/maps/search/?api=1&amp;query=' . $gmapsParams . '" target="googlemaps" title="' . esc_attr__('Open on Google Maps', 'rd-events') . '">'.$location['location'].'</a></p>';
                                }
                                echo '<div id="rundiz-events-map" class="rundiz-events-map" data-markerlat="'.(isset($location['lat']) ? $location['lat'] : '').'" data-markerlng="'.(isset($location['lng']) ? $location['lng'] : '').'" data-mapzoom="12"></div>';
                            }
                            unset($location);
                        } //endwhile; 
                        echo '</article>'."\n";
                    } else {
                        echo '<h1 class="page-title">' . __('Not found', 'rd-events') . '</h1>'."\n";
                        echo '<p>' . __('Nothing found.', 'rd-events') . '</p>'."\n";
                    } //endif; 
                    ?> 
                </main>
<?php
get_footer();