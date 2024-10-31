<?php
/**
 * Deactivation.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Admin;

if (!class_exists('\\RdEvents\\App\\Controllers\\Admin\\Deactivate')) {
    class Deactivate implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * Deactivate the plugin.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         */
        public function deactivateAction()
        {
            \RdEvents\App\Libraries\Debug::writeLog('RdEvents deactivateAction() method was called.');

            flush_rewrite_rules();
        }// deactivateAction


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // register activate hook
            register_deactivation_hook(RDEVENTS_FILE, [&$this, 'deactivateAction']);
        }// registerHooks


    }
}