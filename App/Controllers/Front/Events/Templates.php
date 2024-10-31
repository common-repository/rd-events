<?php
/**
 * Event templates for specific front views.
 * 
 * @package rundiz-events
 */


namespace RdEvents\App\Controllers\Front\Events;

if (!class_exists('\\RdEvents\\App\\Controllers\\Front\\Events\\Templates')) {
    class Templates implements \RdEvents\App\Controllers\ControllerInterface
    {


        use \RdEvents\App\AppTrait;


        /**
         * Set the archive template part for category/archive page.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         * @link https://codex.wordpress.org/Post_Type_Templates Reference for custom post type templates.
         * @param string $archive_template
         * @return string
         */
        public function archiveTemplate($archive_template)
        {
            if (is_post_type_archive($this->post_type)) {
                $archive_template_file = locate_template('archive-'.$this->post_type.'.php');
                if (!empty($archive_template_file)) {
                    $archive_template = $archive_template_file;
                } else {
                    $archive_template_file = trailingslashit(plugin_dir_path(RDEVENTS_FILE)).'App'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'archive.php';
                    if (is_file($archive_template_file)) {
                        $archive_template = $archive_template_file;
                    }
                }
                unset($archive_template_file);
            }

            return $archive_template;
        }// archiveTemplate


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            // hooks to use custom template for category/archive page.
            add_filter('archive_template', [$this, 'archiveTemplate']);
            // hooks to use custom template for single post of event.
            add_filter('single_template', [$this, 'singleTemplate']);
        }// registerHooks


        /**
         * Set the single template for display event description.
         * 
         * @access protected Do not access this method directly. It is for callback only.
         * @link https://codex.wordpress.org/Post_Type_Templates Reference for custom post type templates.
         * @global \WP_Post $post
         * @param string $single_template
         * @return string
         */
        public function singleTemplate($single_template)
        {
            global $post;

            if ($post->post_type === $this->post_type) {
                $single_template_file = locate_template('single-'.$this->post_type.'.php');
                if (!empty($single_template_file)) {
                    $single_template = $single_template_file;
                } else {
                    $single_template_file = trailingslashit(plugin_dir_path(RDEVENTS_FILE)).'App'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'single.php';
                    if (is_file($single_template_file)) {
                        $single_template = $single_template_file;
                    }
                }
                unset($single_template_file);
            }

            return $single_template;
        }// singleTemplate


    }
}