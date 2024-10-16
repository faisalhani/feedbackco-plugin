<?php

class FeedbackCo_Admin {

    private $plugin_name;

    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('admin_init', array($this, 'handle_category_actions'));
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

    public function enqueue_styles($hook) {
        // Enqueue styles only on plugin's settings page
        if ($hook !== 'toplevel_page_feedbackco' && $hook !== 'feedbackco_page_feedbackco-settings') {
            return;
        }

        // Enqueue the admin stylesheet
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/feedbackco-admin.css', array(), $this->version, 'all');

        // Enqueue Font Awesome for the admin
        wp_enqueue_style('feedbackco-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

    }

    public function enqueue_scripts($hook) {
        // Enqueue scripts only on plugin's settings page
        if ($hook !== 'toplevel_page_feedbackco' && $hook !== 'feedbackco_page_feedbackco-settings') {
            return;
        }
    
        // Enqueue WordPress jQuery UI Tabs
        wp_enqueue_script('jquery-ui-tabs');
    
        // Enqueue WordPress Admin Styles
        wp_enqueue_style('wp-jquery-ui-dialog');
    
        // Enqueue the admin stylesheet
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/feedbackco-admin.css', array(), $this->version, 'all');
    
        // Enqueue the color picker script and style
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');
    
        // Enqueue your admin script
        wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/feedbackco-admin.js', array('jquery', 'jquery-ui-tabs', 'wp-color-picker'), $this->version, true);
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
        register_setting('feedbackco_settings_group', 'feedbackco_button_icon');
 // Register shortcode settings
 register_setting('feedbackco_shortcode_settings_group', 'feedbackco_shortcode_display_mode');
 
        // Category settings
        register_setting('feedbackco_categories_group', 'feedbackco_feedback_categories', array($this, 'sanitize_categories'));

        // reCAPTCHA settings
        register_setting('feedbackco_recaptcha_settings_group', 'feedbackco_recaptcha_enabled');
        register_setting('feedbackco_recaptcha_settings_group', 'feedbackco_recaptcha_site_key');
        register_setting('feedbackco_recaptcha_settings_group', 'feedbackco_recaptcha_secret_key');
    }

    public function sanitize_categories($input) {
        // Ensure categories are stored as an array of sanitized strings
        if (is_array($input)) {
            $sanitized = array_map('sanitize_text_field', $input);
            return $sanitized;
        }
        return array();
    }
    
    public function handle_category_actions() {
        if (isset($_POST['add_category'])) {
            
            if (!isset($_POST['feedbackco_add_category_nonce']) || !wp_verify_nonce($_POST['feedbackco_add_category_nonce'], 'feedbackco_add_category_action')) {
                wp_die('Security check failed');
            }
    
            $new_category = sanitize_text_field($_POST['feedbackco_new_category']);
            if (!empty($new_category)) {
                $categories = get_option('feedbackco_feedback_categories', array());
                if (!in_array($new_category, $categories)) {
                    $categories[] = $new_category;
                    update_option('feedbackco_feedback_categories', $categories);
                }
            }
        }
    
        if (isset($_POST['delete_category'])) {
            // Verify nonce
            if (!isset($_POST['feedbackco_delete_category_nonce']) || !wp_verify_nonce($_POST['feedbackco_delete_category_nonce'], 'feedbackco_delete_category_action')) {
                wp_die('Security check failed');
            }
        
            $category_to_delete = sanitize_text_field($_POST['category_to_delete']);
            $categories = get_option('feedbackco_feedback_categories', array());
            if (($key = array_search($category_to_delete, $categories)) !== false) {
                unset($categories[$key]);
                update_option('feedbackco_feedback_categories', $categories);
            }
        }
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
