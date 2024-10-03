<?php
/*
Plugin Name: FeedbackCo
Plugin URI: #
Description: A plugin to create and manage feedback widgets.
Version: 1.0.0
Author: Functions io
Author URI: #
License: GPLv2 or later
Text Domain: feedbackco
*/


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 */
define('FEEDBACKCO_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 */
register_activation_hook(__FILE__, 'activate_feedbackco');

function activate_feedbackco() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-feedbackco-activator.php';
    FeedbackCo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
// function deactivate_feedbackco() {
//     require_once plugin_dir_path(__FILE__) . 'includes/class-feedbackco-deactivator.php';
//     FeedbackCo_Deactivator::deactivate();
// }
// register_deactivation_hook(__FILE__, 'deactivate_feedbackco');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-feedbackco.php';

/**
 * Begins execution of the plugin.
 */
function run_feedbackco() {
    $plugin = new FeedbackCo();
    $plugin->run();
}


run_feedbackco();