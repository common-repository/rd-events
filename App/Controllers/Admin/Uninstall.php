<?php
/**
 * Uninstallation.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Admin;

if (!class_exists('\\RdEvents\\App\\Controllers\\Admin\\Uninstall')) {
    /**
     * The controller that will be working on uninstall (delete) the plugin.
     */
    class Uninstall implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // register uninstall hook
            register_uninstall_hook(RDEVENTS_FILE, ['\\RdEvents\\App\\Controllers\\Admin\\Uninstall', 'uninstallAction']);
        }// registerHooks


        /**
         * Do the uninstallation action (reset all values to its default).
         * 
         * @access protected Do not access this method directly. It is for callback only.
         * @global \wpdb $wpdb
         */
        private function doUninstallAction()
        {
            global $wpdb;

            // delete all posts by post type.
            $sql = 'DELETE `posts`, `pm`
                FROM `' . $wpdb->prefix . 'posts` AS `posts` 
                LEFT JOIN `' . $wpdb->prefix . 'postmeta` AS `pm` ON `pm`.`post_id` = `posts`.`ID`
                WHERE `posts`.`post_type` = \'' . $this->post_type . '\'';
            $result = $wpdb->query($sql);// phpcs:ignore
            // debug
            \RdEvents\App\Libraries\Debug::writeLog('Deleted custom post type: ' . print_r($result, true));

            // delete option
            delete_option($this->main_option_name);
            // finished uninstall plugin.
        }// doUninstallAction


        /**
         * Uninstall the plugin.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         * @global \wpdb $wpdb
         */
        public static function uninstallAction()
        {
            global $wpdb;
            $ThisClass = new self();

            \RdEvents\App\Libraries\Debug::writeLog('RdEvents uninstallAction() method was called.');

            if (is_multisite()) {
                $sites = get_sites();

                if (is_array($sites)) {
                    // loop thru each sites to do uninstall action (reset data to its default value).
                    foreach ($sites as $site) {
                        switch_to_blog($site->blog_id);
                        $ThisClass->doUninstallAction();
                        restore_current_blog();
                    }
                }
                unset($site, $sites);
            } else {
                $ThisClass->doUninstallAction();
            }
        }// uninstallAction


    }
}