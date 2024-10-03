<?php

class FeedbackCo_Admin {

    private $plugin_name;

    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            'FeedbackCo',
            'FeedbackCo',
            'manage_options',
            $this->plugin_name,
            array($this, 'display_dashboard'),
            'dashicons-feedback',
            6
        );

        add_submenu_page(
            $this->plugin_name,
            'Settings',
            'Settings',
            'manage_options',
            $this->plugin_name . '-settings',
            array($this, 'display_settings')
        );

        add_submenu_page(
            $this->plugin_name,
            'Feedback Entries',
            'Feedback Entries',
            'manage_options',
            $this->plugin_name . '-entries',
            array($this, 'display_entries')
        );

        add_submenu_page(
            $this->plugin_name,
            'Analytics',
            'Analytics',
            'manage_options',
            $this->plugin_name . '-analytics',
            array($this, 'display_analytics')
        );
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/feedbackco-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts($hook) {
        // Enqueue scripts only on your plugin's settings page
        if ($hook !== 'toplevel_page_feedbackco' && $hook !== 'feedbackco_page_feedbackco-settings') {
            return;
        }
    
        wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/feedbackco-admin.js', array('jquery', 'wp-color-picker'), $this->version, false);
    
        // Localize script for AJAX
        wp_localize_script($this->plugin_name . '-admin', 'feedbackco_admin_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('feedbackco_admin_nonce'),
        ));
    
        // Enqueue the color picker CSS
        wp_enqueue_style('wp-color-picker');
    }
    

    public function display_dashboard() {
        include_once 'partials/feedbackco-dashboard.php';
    }

    public function display_settings() {
        include_once 'partials/feedbackco-settings.php';
    }

    public function display_entries() {
        include_once 'partials/feedbackco-entries.php';
    }

    public function display_analytics() {
        include_once 'partials/feedbackco-analytics.php';
    }

    public function register_settings() {
        register_setting('feedbackco_settings_group', 'feedbackco_widget_enabled');
        register_setting('feedbackco_settings_group', 'feedbackco_widget_position', 'sanitize_text_field');
        register_setting('feedbackco_settings_group', 'feedbackco_button_text', 'sanitize_text_field');
        register_setting('feedbackco_settings_group', 'feedbackco_button_bg_color', 'sanitize_hex_color');
        register_setting('feedbackco_settings_group', 'feedbackco_button_text_color', 'sanitize_hex_color');
        // Add other settings as needed
    }

    public function export_csv() {
        check_ajax_referer('feedbackco_admin_nonce', 'nonce');

        global $wpdb;
        $table_name = $wpdb->prefix . 'feedbackco_entries';
        $entries = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC", ARRAY_A);

        if (empty($entries)) {
            wp_send_json_error('No entries to export.');
            return;
        }

        $filename = 'feedbackco_entries_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=$filename");

        $output = fopen('php://output', 'w');
        fputcsv($output, array('ID', 'Name', 'Email', 'Message', 'Rating', 'Date'));

        foreach ($entries as $entry) {
            fputcsv($output, $entry);
        }

        fclose($output);
        exit();
    }
}
