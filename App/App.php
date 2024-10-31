<?php
/**
 * The main application file for this plugin.
 * 
 * @package rundiz-events
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace RdEvents\App;

if (!class_exists('\\RdEvents\\App\\App')) {
    /**
     * The main application class for this plugin.<br>
     * This class is the only main class that were called from main plugin file and it will be load any hook actions/filters to work inside the run() method.
     */
    class App
    {


        use \RdEvents\App\AppTrait;


        /**
         * load text domain. (language files)
         */
        public function loadLanguage()
        {
            load_plugin_textdomain('rd-events', false, dirname(plugin_basename(RDEVENTS_FILE)) . '/App/languages/');
        }// loadLanguage


        /**
         * Run the main application class (plugin).
         */
        public function run()
        {
            // include the functions file so developers can easily use it in their template.
            require_once __DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'utilities.php';

            add_action('plugins_loaded', function () {
                // @link https://codex.wordpress.org/Function_Reference/load_plugin_textdomain Reference.
                // load language of this plugin.
                $this->loadLanguage();
            });

            // Initialize the loader class.
            $this->Loader = new \RdEvents\App\Libraries\Loader();
            $this->Loader->autoRegisterControllers();

            // The rest of controllers that is not able to register via loader's auto register.
            // They must be manually write it down here, below this line.
            // For example:
            // $SomeController = new \RdEvents\App\Controllers\SomeController();
            // $SomeController->runItHere();
            // unset($SomeController);// for clean up memory.
            // ------------------------------------------------------------------------------------

            // Auto register all widgets available here.
            $AutoWidgets = new Widgets\AutoRegisterWidgets();
            $AutoWidgets->registerAll();
            unset($AutoWidgets);
        }// run


    }
}