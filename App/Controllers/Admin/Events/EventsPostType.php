<?php
/**
 * Register events custom post type.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Admin\Events;

if (!class_exists('\\RdEvents\\App\\Controllers\\Admin\\Events\\EventsPostType')) {
    /**
     * Add events menu to admin page and register custom post type.
     */
    class EventsPostType implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            add_action('init', [$this, 'registerPostType']);
        }// registerHooks


        /**
         * Register post type and add menu to admin page.
         * 
         * @access protected Do not access this method directly. It is for callback only (including in Activate class).
         * @link https://developer.wordpress.org/reference/functions/get_post_type_labels/ Reference for labels.
         * @link https://developer.wordpress.org/reference/functions/register_post_type/ Reference for args.
         */
        public function registerPostType()
        {
            $labels = [
                'name' => __('Events', 'rd-events'),
                'singular_name' => __('Event', 'rd-events'),
                'add_new_item' => __('Add New Event', 'rd-events'),
                'edit_item' => __('Edit Event', 'rd-events'),
                'new_item' => __('New Event', 'rd-events'),
                'view_item' => __('View Event', 'rd-events'),
                'view_items' => __('View Events', 'rd-events'),
                'search_items' => __('Search Events', 'rd-events'),
                'not_found' => __('No event found.', 'rd-events'),
                'not_found_in_trash' => __('No event found in trash.', 'rd-events'),
                'all_items' => __('All Events', 'rd-events'),
                'archives' => __('Event Archives', 'rd-events'),
                'insert_into_item' => __('Insert into event', 'rd-events'),// required WP 4.4
                'uploaded_to_this_item' => __('Upload to this event', 'rd-events'),// required WP 4.4
                'filter_items_list' => __('Filter events list', 'rd-events'),// required WP 4.4
                'items_list_navigation' => __('Events list navigation', 'rd-events'),// required WP 4.4
                'items_list' => __('Events list', 'rd-events'),// required WP 4.4
            ];

            $args = [
                'labels' => $labels,
                'description' => __('Manage events and display it on front-end.', 'rd-events'),
                'public' => true,
                'exclude_from_search' => false,
                'show_in_menu' => true,
                'show_in_admin_bar' => false,

                // make new editor support in WordPress 5 (Gutenberg).
                // 'show_in_rest' => true,
                // Skip this for now and wait until WordPress completely drop TinyMCE because old editor is a lot easier to understand.
                // required WP 4.7.

                'menu_position' => 26,
                'menu_icon' => 'dashicons-calendar-alt',
                'supports' => ['title', 'editor', 'comments', 'author', 'thumbnail'],
                'has_archive' => true,
                'rewrite' => [
                    'slug' => 'rundiz-events',
                ],
            ];

            register_post_type($this->post_type, $args);

            // clear
            unset($args, $labels);
        }// registerPostType


    }
}