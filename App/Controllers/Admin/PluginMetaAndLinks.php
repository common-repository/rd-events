<?php
/**
 * Plugin meta and links.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Admin;

if (!class_exists('\\RdEvents\\App\\Controllers\\Admin\\PluginMetaAndLinks')) {
    class PluginMetaAndLinks implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * Add links to plugin actions area
         * 
         * @access protected Do not access this method directly. It is for callback only.
         * @param array $actions Current plugin actions. (including deactivate, edit).
         * @param string $plugin_file The plugin file for checking.
         * @return array Return modified links
         */
        public function actionLinks($actions, $plugin_file)
        {
            static $plugin;
            
            if (!isset($plugin)) {
                $plugin = plugin_basename(RDEVENTS_FILE);
            }
            
            if ($plugin === $plugin_file) {
                if (current_user_can('manage_options')) {
                    $link['settings'] = '<a href="'.  esc_url(get_admin_url(null, 'edit.php?post_type='.$this->post_type.'&page=rd-events-settings')).'">'.__('Settings').'</a>';
                    $actions = array_merge($link, $actions);
                }
                // $actions['after_actions'] = '<a href="#" onclick="return false;">'.__('After Actions', 'rd-yte').'</a>';
                unset($link);
            }
            
            return $actions;
        }// actionLinks


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // add filter action links. this will be displayed in actions area of plugin page. for example: xxxActionLinksBefore | Activate | Edit | Delete | xxxActionLinksAfter
            add_filter('plugin_action_links', [$this, 'actionLinks'], 10, 5);
            // add filter to row meta. (in plugin page below description) Version xx | By xxx | View details | xxxRowMetaxxx | xxxRowMetaxxx
        }// registerHooks


    }
}