<?php
/**
 * Plugin Name: Rundiz Events
 * Plugin URI: https://rundiz.com/?p=319
 * Description: Manage your events and display in the calendar or list.
 * Version: 1.0.2
 * Requires at least: 4.6.0
 * Requires PHP: 5.5
 * Author: Vee Winch
 * Author URI: http://rundiz.com
 * License: MIT
 * License URI: http://opensource.org/licenses/MIT
 * Text Domain: rd-events
 * Domain Path: /App/languages/
 *
 * @package rundiz-events
 */


// Define this plugin main file path.
if (!defined('RDEVENTS_FILE')) {
    define('RDEVENTS_FILE', __FILE__);
}

// Define this plugin version. Useful in enqueue scripts and styles.
if (!defined('RDEVENTS_VERSION')) {
    $pluginData = (function_exists('get_file_data') ? get_file_data(__FILE__, ['Version' => 'Version']) : null);
    $pluginVersion = (isset($pluginData['Version']) ? $pluginData['Version'] : gmdate('Ym'));
    unset($pluginData);
    define('RDEVENTS_VERSION', $pluginVersion);
    unset($pluginVersion);
}


// Plugin's autoload.
require __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';


// Run this wp plugin.
$App = new \RdEvents\App\App();
$App->run();
unset($App);
