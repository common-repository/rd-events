<div class="wrap">
    <h1><?php _e('Rundiz Events settings', 'rd-events'); ?></h1>

    <?php if (isset($form_result_class) && isset($form_result_msg)) { ?> 
    <div class="<?php echo $form_result_class; ?> notice is-dismissible">
        <p>
            <strong><?php echo $form_result_msg; ?></strong>
        </p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.'); ?></span></button>
    </div>
    <?php } ?> 

    <form id="rd-events-settings-form" method="post">
        <?php wp_nonce_field(); ?> 

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php _e('Google Map API Key', 'rd-events'); ?></th>
                    <td>
                        <input class="widefat" type="text" name="googlemap_api" value="<?php if (isset($googlemap_api)) {printf(esc_attr('%s'), $googlemap_api);} ?>">
                        <p class="description">
                            <?php 
                            echo sprintf(
                                /* translators: %1$s: Link to Google APIs manager, %2$s: Highlight Google Maps JS API text. */
                                __('Go to %1$s and enable %2$s.', 'rd-events'), 
                                '<a href="https://console.developers.google.com/apis/library" target="googleapis">' . __('Google APIs Manager', 'rd-events') . '</a>',
                                '<strong>' . __('Maps JavaScript API', 'rd-events') 
                                    . ', ' . __('Places API', 'rd-events') 
                                    . ', ' . __('Geocoding API', 'rd-events') 
                                    . '</strong>'
                            ) . '<br>'; 
                            echo sprintf(
                                /* translators: %s: Credentials text. */
                                __('Go to %s and create API key and then copy the key to this input.', 'rd-events'),
                                '<strong>' . __('Credentials', 'rd-events') . '</strong>'
                            );
                            ?> 
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Use Ajax events', 'rd-events'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="useajax_events" value="1"<?php if (isset($useajax_events) && '1' === $useajax_events) {echo ' checked="checked"';} ?>>
                            <?php _e('Check this to use Ajax to get events, otherwise it will render all events into js at once.', 'rd-events'); ?> 
                        </label>
                        <p class="description">
                            <?php
                            _e('If you want to reduce connection to server (such as ajax) or if you want to generate static HTML from WordPress, uncheck this box can help your front-end display events without any request to server.', 'rd-events');
                            ?> 
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?> 
    </form>
</div>