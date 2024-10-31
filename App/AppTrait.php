<?php
/**
 * App traits.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App;

if (!trait_exists('\\RdEvents\\App\\AppTrait')) {
    /**
     * Main application trait for common works.
     */
    trait AppTrait
    {


        /**
         * Loader class into the property.
         * @var \RdEvents\App\Libraries\Loader 
         */
        public $Loader;


        /**
         * @var string Main options name for use with add_option() or get_option().
         */
        public $main_option_name = 'rundiz_events_options';


        /**
         * @var string Post type to use in posts tables.
         */
        public $post_type = 'rd_events';


    }
}