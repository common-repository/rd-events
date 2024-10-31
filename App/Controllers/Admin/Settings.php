<?php
/**
 * Plugin settings page and functional.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Admin;

if (!class_exists('\\RdEvents\\App\\Controllers\\Settings')) {
    /**
     * This controller will be working as settings for rundiz events.
     */
    class Settings implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * Admin menu.<br>
         * Add sub menus in this method.
         */
        public function adminMenuAction()
        {
            $hook = add_submenu_page('edit.php?post_type=rd_events', __('Settings', 'rd-events'), __('Settings', 'rd-events'), 'manage_options', 'rd-events-settings', [$this, 'settingsPageAction']);
            unset($hook);
        }// adminMenuAction


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            if (is_admin()) {
                add_action('admin_menu', [$this, 'adminMenuAction']);
            }
        }// registerHooks


        /**
         * Display plugin settings page.
         */
        public function settingsPageAction()
        {
            // check permission.
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have permission to access this page.'));
            }

            $output = [];

            if ($_POST) {
                // if form submitted.
                if (!wp_verify_nonce((isset($_POST['_wpnonce']) ? sanitize_text_field(wp_unslash($_POST['_wpnonce'])) : ''))) {
                    wp_nonce_ays('-1');
                }

                $data = [];
                $data['googlemap_api'] = (isset($_POST['googlemap_api']) ? trim(sanitize_text_field(wp_unslash($_POST['googlemap_api']))) : '');
                $data['useajax_events'] = (isset($_POST['useajax_events']) && '1' === $_POST['useajax_events'] ? '1' : '');

                update_option($this->main_option_name, $data);

                $output['form_result_class'] = 'notice-success';
                $output['form_result_msg'] = __('Settings saved.');
            }

            // get all options
            $output['options'] = get_option($this->main_option_name);
            if (is_array($output['options'])) {
                if (isset($output['options']['googlemap_api'])) {
                    $output['googlemap_api'] = $output['options']['googlemap_api'];
                }
                if (isset($output['options']['useajax_events'])) {
                    $output['useajax_events'] = $output['options']['useajax_events'];
                } else {
                    $output['useajax_events'] = '1';// before v0.2.3 don't have this option. so, set this to enabled by default.
                }
            }

            $Loader = new \RdEvents\App\Libraries\Loader();
            $Loader->loadView('Admin/Settings/settingsPageAction_v', $output);
            unset($Loader, $output);
        }// settingsPageAction


    }
}