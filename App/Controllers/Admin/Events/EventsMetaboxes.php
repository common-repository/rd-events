<?php
/**
 * Metaboxes in edit post page.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Admin\Events;

if (!class_exists('\\RdEvents\\App\\Controllers\\Admin\\Events\\EventsMetaboxes')) {
    /**
     * Add metaboxes and its functionality.
     */
    class EventsMetaboxes implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * Display notice if there is error.
         *
         * @access protected Do not access this method directly. It is for callback only.
         */
        public function adminNotices()
        {
            if (!isset($_GET['rundiz-events-error'])) {
                return ;
            }

            switch ($_GET['rundiz-events-error']) {
                case 1:
                    $error_message = __('The field date on start and end are not exists.', 'rd-events');
                    break;
                case 2:
                    $error_message = __('The start date is incorrect date format.', 'rd-events');
                    break;
                case 3:
                    $error_message = __('The end date is incorrect date format.', 'rd-events');
                    break;
                case 4:
                    $error_message = __('The selected end date must be after start date.', 'rd-events');
                    break;
                case 5:
                    $error_message = __('You had entered invalid time values.', 'rd-events');
                    break;
            }

            if (isset($error_message) && !empty(trim($error_message))) {
                echo '<div class="notice notice-error is-dismissible">';
                echo '<p>' . $error_message . '</p>';
                echo '</div>';
            }

            unset($error_message);
        }// adminNotices


        /**
         * Enqueue scripts and styles
         *
         * @access protected Do not access this method directly. It is for callback only.
         * @global \WP_Post $post The WP_Post class.
         * @param string $hook_suffix The current admin page.
         */
        public function enqueueScripts($hook_suffix)
        {
            global $post;

            if (
                isset($post->post_type) &&
                $post->post_type === $this->post_type &&
                (
                    'post.php' === $hook_suffix ||
                    'post-new.php' === $hook_suffix
                )
            ) {
                // enqueue css
                wp_enqueue_style('rd-events-jquery-ui', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/css/jquery-ui/jquery-ui.min.css', [], '1.11.4');
                wp_enqueue_style('rd-events-jquery-ui-timepicker', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/css/admin/jquery.ui.timepicker.css', ['rd-events-jquery-ui'], '0.3.3');
                wp_enqueue_style('rd-events-admin', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/css/admin/rd-events.css', ['rd-events-jquery-ui'], RDEVENTS_VERSION);

                // enqueue js
                wp_enqueue_script('rd-events-jquery-ui-timepicker', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/js/admin/jquery.ui.timepicker.js', ['jquery', 'jquery-ui-core'], '0.3.3', true);
                wp_enqueue_script('rd-events-datepicker', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/js/admin/rd-events-datepicker.js', ['jquery', 'jquery-ui-datepicker', 'rd-events-jquery-ui-timepicker'], RDEVENTS_VERSION, true);
                wp_register_script('rd-events-map', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/js/admin/rd-events-map.js', ['jquery'], RDEVENTS_VERSION, true);
                wp_localize_script(
                    'rd-events-map',
                    'RdEventsMap',
                    [
                        'autocomplete' => null,
                        'geocoder' => null,
                        'map' => null,
                        'marker' => null,
                        'infowindow' => null,
                        'infowindowContent' => null,
                    ]
                );
                wp_enqueue_script('rd-events-map');
                wp_enqueue_script('rd-events-google-map', rdevents_getGoogleMapsApiUrl() . '&callback=rdEventsInitMap', ['rd-events-map'], false, true);
            }
        }// enqueueScripts


        /**
         * Display event date metabox.
         *
         * @access protected Do not access this method directly. It is for callback only.
         * @param \WP_Post $post Current post object.
         */
        public function metaboxEventDate($post)
        {
            $Loader = new \RdEvents\App\Libraries\Loader();
            $Loader->loadView('Admin/Events/EventsMetaboxes/metaboxEventDate_v', ['post' => $post]);
            unset($Loader);
        }// metaboxEventDate


        /**
         * Display event map location metabox.
         *
         * @access protected Do not access this method directly. It is for callback only.
         * @param \WP_Post $post Current post object.
         */
        public function metaboxEventLocation($post)
        {
            $Loader = new \RdEvents\App\Libraries\Loader();
            $Loader->loadView('Admin/Events/EventsMetaboxes/metaboxEventLocation_v', ['post' => $post]);
            unset($Loader);
        }// metaboxEventLocation


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // hooks into edit page (including add) ------------
            // hooks to enqueue scripts and styles
            add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
            // add meta boxes
            add_action('add_meta_boxes', [$this, 'registerMetaBoxes']);
            // hooks while saving post.
            add_action('save_post_' . $this->post_type, [$this, 'saveEventMeta'], 10, 3);
            // display error message if there are error.
            add_action('admin_notices', [$this, 'adminNotices']);
        }// registerHooks


        /**
         * Register metabox(es).
         *
         * @access protected Do not access this method directly. It is for callback only.
         * @link https://developer.wordpress.org/reference/functions/add_meta_box/ Reference for <code>add_meta_box()</code> function.
         */
        public function registerMetaBoxes()
        {
            // add date/time pickers metabox.
            add_meta_box('rd-events_date', __('Event Date', 'rd-events'), [$this, 'metaboxEventDate'], $this->post_type, 'normal', 'high');
            // add google map metabox.
            add_meta_box('rd-events_map', __('Event Location', 'rd-events'), [$this, 'metaboxEventLocation'], $this->post_type, 'normal', 'high');
        }// registerMetaBoxes


        /**
         * Save post metadata when a post is saved.
         *
         * @link https://codex.wordpress.org/Plugin_API/Action_Reference/save_post Reference for <code>save_post</code> hook.
         * @param int $post_id The post ID.
         * @param \WP_Post $post The post object.
         * @param boolean $update Whether this is an existing post being updated or not.
         */
        public function saveEventMeta($post_id, $post, $update)
        {
            $validated_boxes = [];
            $data = [];

            // validate date picker box.
            if (
                isset($_POST[plugin_basename(dirname(RDEVENTS_FILE)).'-datepicker']) &&
                wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[plugin_basename(dirname(RDEVENTS_FILE)).'-datepicker'])), plugin_basename(dirname(RDEVENTS_FILE)).'-datepicker') &&
                isset($_POST['_event_date_start']) &&
                isset($_POST['_event_date_end'])
            ) {
                // if passed nonce check for datepicker
                // validate form
                if (\DateTime::createFromFormat('Y-m-d', sanitize_text_field(wp_unslash($_POST['_event_date_start']))) === false) {
                    // if start date incorrect format.
                    add_filter('redirect_post_location', function ($location) {
                        return add_query_arg(['message' => 4, 'rundiz-events-error' => 2], $location);
                    });
                } elseif (\DateTime::createFromFormat('Y-m-d', sanitize_text_field(wp_unslash($_POST['_event_date_end']))) === false) {
                    // if end date incorrect format.
                    add_filter('redirect_post_location', function ($location) {
                        return add_query_arg(['message' => 4, 'rundiz-events-error' => 3], $location);
                    });
                } elseif (strtotime(sanitize_text_field(wp_unslash($_POST['_event_date_start']))) > strtotime(sanitize_text_field(wp_unslash($_POST['_event_date_end'])))) {
                    // if start date after end date.
                    add_filter('redirect_post_location', function ($location) {
                        return add_query_arg(['message' => 4, 'rundiz-events-error' => 4], $location);
                    });
                } elseif (
                    (
                        !isset($_POST['_event_time_allday']) ||
                        (
                            isset($_POST['_event_time_allday']) &&
                            '1' !== $_POST['_event_time_allday']
                        )
                    ) &&
                    (
                        !isset($_POST['_event_time_start']) ||
                        !isset($_POST['_event_time_end'])
                    )
                ) {
                    // if not all day event and time start or time end not exists.
                    add_filter('redirect_post_location', function ($location) {
                        return add_query_arg(['message' => 4, 'rundiz-events-error' => 5], $location);
                    });
                } elseif (
                    (
                        !isset($_POST['_event_time_allday']) ||
                        (
                            isset($_POST['_event_time_allday']) &&
                            '1' !== $_POST['_event_time_allday']
                        )
                    ) &&
                    (
                        (
                            isset($_POST['_event_time_start']) &&
                            \DateTime::createFromFormat('Y-m-d H:i', sanitize_text_field(wp_unslash($_POST['_event_date_start'])).' '.sanitize_text_field(wp_unslash($_POST['_event_time_start']))) === false
                        ) ||
                        (
                            isset($_POST['_event_time_end']) &&
                            \DateTime::createFromFormat('Y-m-d H:i', sanitize_text_field(wp_unslash($_POST['_event_date_end'])).' '.sanitize_text_field(wp_unslash($_POST['_event_time_end']))) === false
                        )
                    )
                ) {
                    // if not all day event and time start or end is incorrect format.
                    add_filter('redirect_post_location', function ($location) {
                        return add_query_arg(['message' => 4, 'rundiz-events-error' => 5], $location);
                    });
                } else {
                    // all validation passed!
                    $validated_boxes[] = 'datepicker';
                    // prepare data for save
                    $data['_event_time_allday'] = (isset($_POST['_event_time_allday']) && '1' === $_POST['_event_time_allday'] ? '1' : '0');
                    $data['_event_date_start'] = sanitize_text_field(wp_unslash($_POST['_event_date_start']));
                    $data['_event_time_start'] = (isset($data['_event_time_allday']) && '1' !== $data['_event_time_allday'] ? sanitize_text_field(wp_unslash($_POST['_event_time_start'])) : '');
                    $data['_event_date_end'] = sanitize_text_field(wp_unslash($_POST['_event_date_end']));
                    $data['_event_time_end'] = (isset($data['_event_time_allday']) && '1' !== $data['_event_time_allday'] ? sanitize_text_field(wp_unslash($_POST['_event_time_end'])) : '');
                }
            }

            // validate location box.
            if (
                isset($_POST[plugin_basename(dirname(RDEVENTS_FILE)).'-map']) &&
                wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[plugin_basename(dirname(RDEVENTS_FILE)).'-map'])), plugin_basename(dirname(RDEVENTS_FILE)).'-map') &&
                isset($_POST['_event_location']) &&
                isset($_POST['_event_location_lat']) &&
                isset($_POST['_event_location_lng'])
            ) {
                // if passed nonce check for datepicker
                $validated_boxes[] = 'location';

                $data['_event_location'] = (isset($_POST['_event_location']) ? trim(sanitize_text_field(wp_unslash($_POST['_event_location']))) : '');
                $data['_event_location_lat'] = (isset($_POST['_event_location_lat']) ? trim(sanitize_text_field(wp_unslash($_POST['_event_location_lat']))) : '');
                $data['_event_location_lng'] = (isset($_POST['_event_location_lng']) ? trim(sanitize_text_field(wp_unslash($_POST['_event_location_lng']))) : '');
            }

            // save the data.
            if (isset($validated_boxes) && is_array($validated_boxes) && !empty($validated_boxes)) {
                // if all validation passed.
                if (isset($data) && is_array($data)) {
                    foreach ($data as $key => $value) {
                        update_post_meta($post_id, $key, $value);
                    }// endforeach;
                    unset($key, $value);
                }
            }

            unset($data, $validated_boxes);
        }// saveEventMeta


    }
}