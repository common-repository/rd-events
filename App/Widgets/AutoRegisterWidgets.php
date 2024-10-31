<?php
/**
 * Auto register front page widgets.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Widgets;

if (!class_exists('\\RdEvents\\App\\Widgets\\AutoRegisterWidgets')) {
    /**
     * Auto register all available widgets in this plugin. To make it works, you have to call <code>registerAll()</code> method.
     */
    class AutoRegisterWidgets
    {


        /**
         * Register all widgets that come with this theme.
         */
        public function registerAll()
        {
            $widgets_folder = __DIR__;
            $DirectoryIterator = new \DirectoryIterator($widgets_folder);

            foreach ($DirectoryIterator as $fileinfo) {
                if (!$fileinfo->isDot() && $fileinfo->isFile() && strtolower($fileinfo->getExtension()) === 'php') {
                    $file_name_only = $fileinfo->getBasename('.php');
                    $class_name = __NAMESPACE__ . '\\' . $file_name_only;

                    if (__CLASS__ !== $class_name && class_exists($class_name)) {
                        add_action('widgets_init', function () use ($class_name) {
                            return register_widget($class_name);
                        });
                    }

                    unset($class_name, $file_name_only);
                }
            }// endforeach;

            unset($DirectoryIterator, $fileinfo, $widgets_folder);
        }// registerAll


    }
}