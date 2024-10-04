<?php
/*
Plugin Name: FeedbackCo

Description: A plugin to create and manage feedback widgets.
Version: 1.0.0
Author: Functions io

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
define('FEEDBACKCO_DB_VERSION', '1.1');

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
 * Function to check and update the database schema.
 */
function feedbackco_update_db_check() {
    $installed_db_version = get_option('feedbackco_db_version');

    if ($installed_db_version != FEEDBACKCO_DB_VERSION) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'feedbackco_entries';
        $charset_collate = $wpdb->get_charset_collate();

        // SQL statement to create/update the table
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_name varchar(255) NOT NULL,
            user_email varchar(255) NOT NULL,
            message text NOT NULL,
            rating int(1) NOT NULL,
            category varchar(255) DEFAULT '' NOT NULL,
            form_id varchar(50) DEFAULT '' NOT NULL,
            date_submitted datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Update the database version
        update_option('feedbackco_db_version', FEEDBACKCO_DB_VERSION);
    }
}

add_action('plugins_loaded', 'feedbackco_update_db_check');