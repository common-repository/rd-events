<?php
/**
 * Manage events posts.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Admin\Events;

if (!class_exists('\\RdEvents\\App\\Controllers\\Admin\\Events\\EventsManagePosts')) {
    /**
     * Hooks to manage page to modify display columns. Any quick edit hooks should be here.
     */
    class EventsManagePosts implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * Hooks to add columns
         * 
         * @param array $columns The columns for this post type.
         * @return array Return modified columns.
         */
        public function hookAddColumns($columns)
        {
            $new_columns = [];
            foreach ($columns as $key => $value) {
                if ('title' === $key) {
                    $new_columns[$key] = $value;
                    $new_columns['rd_events_date'] = __('Event date', 'rd-events');
                    $new_columns['rd_events_location'] = __('Location', 'rd-events');
                } else {
                    $new_columns[$key] = $value;
                }
            }// endforeach;
            unset($key, $value);
            return $new_columns;
        }// hookAddColumns


        /**
         * Hooks into manage posts custom column.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         * @param string $column Column name.
         * @param integer $post_id The post ID.
         */
        public function hookManagePostsCustomColumn($column, $post_id)
        {
            switch ($column) {
                case 'rd_events_date':
                    $event_date_start = get_post_meta($post_id, '_event_date_start', true);
                    $event_time_start = get_post_meta($post_id, '_event_time_start', true);
                    $event_date_end = get_post_meta($post_id, '_event_date_end', true);
                    $event_time_end = get_post_meta($post_id, '_event_time_end', true);
                    $event_time_allday = get_post_meta($post_id, '_event_time_allday', true);

                    echo $event_date_start;
                    if (!empty($event_time_start)) {
                        echo ' '.$event_time_start;
                    }
                    if (!empty($event_date_end)) {
                        echo ' - '.$event_date_end;
                        if (!empty($event_time_end)) {
                            echo ' '.$event_time_end;
                        }
                    }
                    if ('1' === $event_time_allday) {
                        echo '<br>'.__('All day', 'rd-events');
                    }

                    unset($event_date_end, $event_date_start, $event_time_allday, $event_time_end, $event_time_start);
                    break;
                case 'rd_events_location':
                    $event_location = get_post_meta($post_id, '_event_location', true);
                    $event_location_lat = get_post_meta($post_id, '_event_location_lat', true);
                    $event_location_lng = get_post_meta($post_id, '_event_location_lng', true);

                    echo '<a href="';
                    echo 'https://maps.google.com/maps?z=12&amp;q=loc:'.$event_location_lat.','.$event_location_lng.'&amp;ll='.$event_location_lat.','.$event_location_lng.'&amp;mrt=all&amp;t=m';
                    echo '" target="googlemaps" title="'.$event_location.'">';
                    echo mb_strimwidth($event_location, 0, 30, '&hellip;');
                    echo '</a>';

                    unset($event_location, $event_location_lat, $event_location_lng);
                    break;
            }
        }// hookManagePostsCustomColumn


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // hooks into manage page --------------------------
            // hooks to add columns
            add_filter('manage_'.$this->post_type.'_posts_columns', [$this, 'hookAddColumns']);
            // hooks into manage post custom column
            add_action('manage_'.$this->post_type.'_posts_custom_column', [$this, 'hookManagePostsCustomColumn'], 10, 2);
        }// registerHooks


    }
}