<?php
/**
 * Calendar widget.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Widgets;

if (!class_exists('\\RdEvents\\App\\Widgets\\CalendarWidget')) {
    class CalendarWidget extends \WP_Widget
    {


        use \RdEvents\App\AppTrait;


        /**
         * @var string Widget title.
         */
        private $widget_title;


        /**
         * Class constructor.
         */
        public function __construct()
        {
            parent::__construct(
                    'rd_events_calendar_widget', // base ID
                    __('Events Calendar', 'rd-events'), 
                    [
                        'description' => __('Display events in the calendar widget.', 'rd-events')
                    ]
            );

            add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        }// __construct


        /**
         * Enqueue scripts and styles.
         */
        public function enqueueScripts()
        {
            if (is_active_widget(false, false, $this->id_base)) {
                // enqueue styles
                wp_enqueue_style('rd-events-fullcalendar');
                wp_enqueue_style('rd-events-fullcalendar-print');
                wp_enqueue_style('rd-events-calendar');

                // enqueue scripts
                wp_enqueue_script('rd-events-fullcalendar-moment');
                wp_enqueue_script('rd-events-fullcalendar');
                wp_enqueue_script('rd-events-fullcalendar-locale');
                wp_register_script('rd-events-calendar-widget', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/js/front/rd-events-calendar-widget.js', ['rd-events-fullcalendar'], RDEVENTS_VERSION, true);

                $widget_l10n = [
                    'locale' => get_locale(),
                    'nonce' => wp_create_nonce('rd-events-calendar'),
                    'txtListDay' => __('Day', 'rd-events'),
                    'txtListWeek' => __('Week', 'rd-events'),
                    'txtListMonth' => __('Month', 'rd-events'),
                    'txtToday' => __('Today', 'rd-events'),
                ];
                $options = get_option($this->main_option_name);
                if (!isset($options['useajax_events']) || (isset($options['useajax_events']) && '1' === $options['useajax_events'])) {
                    $widget_l10n['ajaxurl'] = admin_url('admin-ajax.php');
                } else {
                    $EventsModel = new \RdEvents\App\Models\EventsModel();
                    $widget_l10n['all_events'] = json_encode($EventsModel->getAllEvents());
                    unset($EventsModel);
                }
                unset($options);

                wp_localize_script(
                    'rd-events-calendar-widget',
                    'RundizEventsCalendarWidget',
                    $widget_l10n
                );
                unset($widget_l10n);
                wp_enqueue_script('rd-events-calendar-widget');
            }
        }// enqueueScripts


        /**
         * Admin widget form
         * 
         * @see WP_Widget::form()
         * @param array $instance Previously saved values from database.
         */
        public function form($instance) 
        {
            if (isset($instance['rd-events-calendar-widget-title'])) {
                $this->widget_title = $instance['rd-events-calendar-widget-title'];
            }

            // output form
            $output = '<p>';
            $output .= '<label for="' . $this->get_field_id('rd-events-calendar-widget-title') . '">' . __('Title') . ':</label>';
            $output .= '<input id="' . $this->get_field_id('rd-events-calendar-widget-title') . '" class="widefat" type="text" value="'.esc_attr($this->widget_title).'" name="' . $this->get_field_name('rd-events-calendar-widget-title') . '">';
            $output .= '</p>';

            echo $output;

            unset($output);
        }// form


        /**
         * Sanitize widget form values as they are saved.
         * 
         * @see WP_Widget::update()
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance) 
        {
            $instance = [];

            if (isset($new_instance['rd-events-calendar-widget-title'])) {
                $instance['rd-events-calendar-widget-title'] = strip_tags($new_instance['rd-events-calendar-widget-title']);
            }

            return $instance;
        }// update


        /**
         * Front-end display of widget
         * 
         * @see WP_Widget::widget()
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) 
        {
            $widget_title = $this->widget_title;
            if (isset($instance['rd-events-calendar-widget-title'])) {
                $widget_title = $instance['rd-events-calendar-widget-title'];
            }

            // set output front-end widget ---------------------------------
            $output = $args['before_widget']."\n";

            if (!empty($instance['rd-events-calendar-widget-title'])) {
                $output .= $args['before_title'] . apply_filters('widget_title', $instance['rd-events-calendar-widget-title']) . $args['after_title']."\n";
            }

            // display events in calendar
            $output .= '<div class="rundiz-events-loading-events-template-widget">';
            $output .= '<span class="loading-icon">';
            $output .= '<img class="rundiz-events-loading-image-widget" src="' . trailingslashit(plugin_dir_url(RDEVENTS_FILE)) . 'assets/img/loading-squares.gif"><br>';
            $output .= __('Getting events data', 'rd-events');
            $output .= '</span>';
            $output .= '</div><!--.rundiz-events-loading-events-template-widget-->';
            $output .= '<div class="rundiz-events-calendar-widget"></div><!--.rundiz-events-calendar-widget-->';
            $output .= '<a href="'.get_post_type_archive_link($this->post_type).'" target="_top">'.__('See more', 'rd-events').'</a>';

            $output .= $args['after_widget']."\n";

            echo $output;

            // clear unused variables
            unset($output);
        }// widget


    }
}