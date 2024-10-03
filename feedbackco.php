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


/**
 * Register the shortcode.
 */
function feedbackco_shortcode($atts) {
    // Shortcode attributes with defaults
    $atts = shortcode_atts(
        array(
            'title'       => 'Send us your feedback',
            'button_text' => 'Submit',
            'id'          => '1', // Optional: Use for multiple forms
        ),
        $atts,
        'feedbackco_form'
    );

    // Start output buffering
    ob_start();

    // Extract attributes into variables
    $title       = $atts['title'];
    $button_text = $atts['button_text'];
    $id          = $atts['id'];

    // Include the form template
    include plugin_dir_path(__FILE__) . 'public/partials/feedbackco-form-shortcode.php';

    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('feedbackco_form', 'feedbackco_shortcode');