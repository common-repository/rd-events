<?php
/**
 * Activation.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Admin;

if (!class_exists('\\RdEvents\\App\\Controllers\\Admin\\Activate')) {
    /**
     * The controller that will be working on activate the plugin.
     */
    class Activate implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * Activate the plugin.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         * @global \wpdb $wpdb WordPress db class.
         */
        public function activateAction()
        {
            global $wpdb;

            \RdEvents\App\Libraries\Debug::writeLog('RdEvents activateAction() method was called.');

            // check that there is an option from this plugin added, if not then add new.
            $plugin_option = get_option($this->main_option_name);
            if (false === $plugin_option) {
                // not exists, add new.
                add_option($this->main_option_name, []);
            }
            unset($plugin_option);
            // finished activate the plugin.

            // register post type
            // @link https://codex.wordpress.org/Function_Reference/register_post_type Reference.
            $EventsPostType = new \RdEvents\App\Controllers\Admin\Events\EventsPostType();
            $EventsPostType->registerPostType();
            unset($EventsPostType);

            // flush after register post type.
            flush_rewrite_rules();
        }// activateAction


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // register activate hook
            register_activation_hook(RDEVENTS_FILE, [&$this, 'activateAction']);
            // on update/upgrade plugin
            add_action('upgrader_process_complete', [$this, 'updatePlugin'], 10, 2);
        }// registerHooks


        /**
         * Works on update plugin.
         * 
         * @link https://developer.wordpress.org/reference/hooks/upgrader_process_complete/ Reference.
         * @param \WP_Upgrader $upgrader
         * @param array $hook_extra
         */
        public function updatePlugin(\WP_Upgrader $upgrader, array $hook_extra)
        {
            if (is_array($hook_extra) && array_key_exists('action', $hook_extra) && array_key_exists('type', $hook_extra) && array_key_exists('plugins', $hook_extra)) {
                if ('update' === $hook_extra['action'] && 'plugin' === $hook_extra['type'] && is_array($hook_extra['plugins']) && !empty($hook_extra['plugins'])) {
                    $this_plugin = plugin_basename(RDEVENTS_FILE);
                    foreach ($hook_extra['plugins'] as $key => $plugin) {
                        if ($this_plugin === $plugin) {
                            $this_plugin_updated = true;
                            break;
                        }
                    }// endforeach;
                    unset($key, $plugin, $this_plugin);

                    if (isset($this_plugin_updated) && true === $this_plugin_updated) {
                        \RdEvents\App\Libraries\Debug::writeLog('RdEvents updatePlugin() method was called.');

                        global $wpdb;
                        // do the update plugin task.
                        // leave this for the future use, if not then this code inside next update cannot working.
                    }// endif; $this_plugin_updated
                }// endif update plugin and plugins not empty.
            }// endif; $hook_extra
        }// updatePlugin


    }
}