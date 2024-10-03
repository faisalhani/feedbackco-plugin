<?php

class FeedbackCo_Public {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name . '-public',
            plugin_dir_url(__FILE__) . 'css/feedbackco-public.css',
            array(),
            $this->version,
            'all'
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name . '-public',
            plugin_dir_url(__FILE__) . 'js/feedbackco-public.js',
            array('jquery'),
            $this->version,
            true // Load in footer
        );

        // Localize script for AJAX
        wp_localize_script($this->plugin_name . '-public', 'feedbackco_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('feedbackco_nonce'),
        ));
    }

    public function inject_widget() {
        if (get_option('feedbackco_widget_enabled', true)) { // Default to enabled
            include plugin_dir_path(__FILE__) . 'partials/feedbackco-widget.php';
        }
    }

    public function submit_feedback() {
        check_ajax_referer('feedbackco_nonce', 'nonce');

        $user_name  = sanitize_text_field($_POST['user_name']);
        $user_email = sanitize_email($_POST['user_email']);
        $message    = sanitize_textarea_field($_POST['message']);
        $rating     = intval($_POST['rating']);
        $form_id    = isset($_POST['form_id']) ? sanitize_text_field($_POST['form_id']) : '';

        global $wpdb;
        $table_name = $wpdb->prefix . 'feedbackco_entries';

        $result = $wpdb->insert(
            $table_name,
            array(
                'user_name'  => $user_name,
                'user_email' => $user_email,
                'message'    => $message,
                'rating'     => $rating,
                'form_id'    => $form_id,
            )
        );

        if ($result !== false) {
            wp_send_json_success('Thank you for your feedback!');
        } else {
            error_log('FeedbackCo Insert Error: ' . $wpdb->last_error);
            wp_send_json_error('Failed to submit feedback. Please try again.');
        }
    }
}
