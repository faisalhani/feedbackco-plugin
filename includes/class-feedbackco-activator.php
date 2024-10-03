<?php

class FeedbackCo_Activator {

    public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'feedbackco_entries';
        $charset_collate = $wpdb->get_charset_collate();

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

        update_option('feedbackco_db_version', FEEDBACKCO_DB_VERSION);
    }
}
