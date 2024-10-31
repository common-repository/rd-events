<?php
/**
 * The basic template for display category/archive page.<br>
 * You can copy and modify this in your theme folder.
 * 
 * @package rundiz-events
 */


add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('rd-events-fullcalendar');
    wp_enqueue_style('rd-events-fullcalendar-print');
    wp_enqueue_style('rd-events-calendar');

    wp_enqueue_script('rd-events-fullcalendar-moment');
    wp_enqueue_script('rd-events-fullcalendar');
    wp_enqueue_script('rd-events-fullcalendar-locale');
    wp_enqueue_script('rd-events-calendar');
});


get_header();
?> 
                <main id="main" class="site-main" role="main">
                    <header>
                        <?php
                        the_archive_title('<h1 class="page-title">', '</h1>');
                        the_archive_description('<div class="taxonomy-description">', '</div>');
                        ?>
                    </header><!-- .page-header -->

                    <div class="rundiz-events-loading-events-template">
                        <span class="loading-icon">
                            <img src="<?php echo trailingslashit(plugin_dir_url(RDEVENTS_FILE)).'assets/img/loading-squares.gif'; ?>"><br>
                            <?php _e('Getting events data', 'rd-events'); ?> 
                        </span>
                    </div><!--.rundiz-events-loading-events-template-->
                    <div class="rundiz-events-calendar"></div><!--.rundiz-events-calendar-->
                </main>
<?php
get_footer();