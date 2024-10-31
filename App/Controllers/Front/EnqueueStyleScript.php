<?php
/**
 * Enqueue/Register scripts and styles.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Front;

if (!class_exists('\\RdEvents\\App\\Controllers\\Front\\EnqueueStyleScript')) {
    /**
     * Enqueue styles and scripts that will be common use on front-end.
     */
    class EnqueueStyleScript implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * Enqueue styles and scripts by using only register.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         */
        public function enqueueScripts()
        {
            $fullcalendar_version = '3.2.0';

            // register styles -------------------------------------------------------------------
            // for display maps in single page or details page.
            wp_register_style('rd-events-map', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/css/front/rd-events-map.css', [], RDEVENTS_VERSION);
            // for display calendar in archive page.
            wp_register_style('rd-events-fullcalendar', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/css/front/fullcalendar/fullcalendar.min.css', [], $fullcalendar_version);
            wp_register_style('rd-events-fullcalendar-print', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/css/front/fullcalendar/fullcalendar.print.min.css', [], $fullcalendar_version, 'print');
            wp_register_style('rd-events-calendar', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/css/front/rd-events-calendar.css', [], RDEVENTS_VERSION);

            // register scripts ------------------------------------------------------------------
            // for display maps in single page or details page.
            wp_register_script('rd-events-map-functions', rdevents_getRundizEventsMapFunctionUrl(), ['jquery'], RDEVENTS_VERSION, true);
            $mapHTMLId = 'rundiz-events-map';
            $mapHTMLId = apply_filters('rd_events_maphtmlid', $mapHTMLId);
            wp_localize_script(
                'rd-events-map-functions',
                'RdEventsMap',
                [
                    'marker' => null,
                    'map' => null,
                    'mapHTMLId' => $mapHTMLId,
                ]
            );
            unset($mapHTMLId);
            wp_register_script('rd-events-google-map-api', rdevents_getGoogleMapsApiUrl() . '&callback=rdEventsFrontInitMap', ['rd-events-map-functions'], false, true);
            // for display calendar in archive page.
            wp_register_script('rd-events-fullcalendar-moment', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/js/front/fullcalendar/lib/moment.min.js', ['jquery'], $fullcalendar_version, true);
            wp_register_script('rd-events-fullcalendar', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/js/front/fullcalendar/fullcalendar.min.js', ['jquery'], $fullcalendar_version, true);
            wp_register_script('rd-events-fullcalendar-locale', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/js/front/fullcalendar/locale-all.js', ['rd-events-fullcalendar'], $fullcalendar_version, true);
            wp_register_script('rd-events-calendar', trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/js/front/rd-events-calendar.js', ['rd-events-fullcalendar'], RDEVENTS_VERSION, true);

            $rdevents_l10n = [
                'locale' => get_locale(),
                'nonce' => wp_create_nonce('rd-events-calendar'),
                'txtListDay' => __('Day', 'rd-events'),
                'txtListWeek' => __('Week', 'rd-events'),
                'txtListMonth' => __('Month', 'rd-events'),
                'txtToday' => __('Today', 'rd-events'),
            ];
            $options = get_option($this->main_option_name);
            if (!isset($options['useajax_events']) || (isset($options['useajax_events']) && '1' === $options['useajax_events'])) {
                $rdevents_l10n['ajaxurl'] = admin_url('admin-ajax.php');
            } else {
                $EventsModel = new \RdEvents\App\Models\EventsModel();
                $rdevents_l10n['all_events'] = json_encode($EventsModel->getAllEvents());
                unset($EventsModel);
            }
            unset($options);
            wp_localize_script(
                'rd-events-calendar',
                'RundizEvents',
                $rdevents_l10n
            );
            unset($rdevents_l10n);

            unset($fullcalendar_version);
        }// enqueueScripts


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        }// registerHooks


    }
}