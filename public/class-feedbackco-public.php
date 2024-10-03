<?php

class FeedbackCo_Public {

    private $plugin_name;

    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('wp_ajax_feedbackco_submit_feedback', array($this, 'submit_feedback'));
        add_action('wp_ajax_nopriv_feedbackco_submit_feedback', array($this, 'submit_feedback'));
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name . '-public', plugin_dir_url(__FILE__) . 'css/feedbackco-public.css', array(), $this->version, 'all');
    
        // Enqueue dynamic styles
        wp_add_inline_style($this->plugin_name . '-public', $this->get_dynamic_styles());

         // Enqueue Font Awesome
    wp_enqueue_style('feedbackco-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
    }

    private function get_dynamic_styles() {
        $options = array(
            'position' => get_option('feedbackco_widget_position', 'bottom-right'),
            'button_bg_color' => get_option('feedbackco_button_bg_color', '#0073aa'),
            'button_text_color' => get_option('feedbackco_button_text_color', '#ffffff'),
            // Add other options as needed
        );
    
        $position_css = '';
        switch ($options['position']) {
            case 'bottom-right':
                $position_css = 'bottom: 20px; right: 20px;';
                break;
            case 'bottom-left':
                $position_css = 'bottom: 20px; left: 20px;';
                break;
            case 'top-right':
                $position_css = 'top: 20px; right: 20px;';
                break;
            case 'top-left':
                $position_css = 'top: 20px; left: 20px;';
                break;
            default:
                $position_css = 'bottom: 20px; right: 20px;';
        }
    
        $dynamic_css = "
        #feedbackco-widget {
            position: fixed;
            $position_css
            z-index: 9999;
        }
        #feedbackco-button {
            background-color: {$options['button_bg_color']};
            color: {$options['button_text_color']};
        }
        ";
    
        return $dynamic_css;
    }

   public function enqueue_scripts() {
    wp_enqueue_script(
        $this->plugin_name . '-public',
        plugin_dir_url(__FILE__) . 'js/feedbackco-public.js',
        array('jquery'),
        $this->version,
        true
    );

    // Localize script for AJAX
    wp_localize_script($this->plugin_name . '-public', 'feedbackco_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('feedbackco_nonce'),
    ));
}


    public function inject_widget() {
        if (get_option('feedbackco_widget_enabled')) {
            include plugin_dir_path(__FILE__) . 'partials/feedbackco-widget.php';
        }
    }

    public function submit_feedback() {
        check_ajax_referer('feedbackco_nonce', 'nonce');
    
        if (!empty($_POST['website'])) {
            // It's a spam submission; discard it
            wp_send_json_error('Spam detected. Submission discarded.');
            return;
        }

            // reCAPTCHA verification
    $recaptcha_enabled = get_option('feedbackco_recaptcha_enabled', false);
    if ($recaptcha_enabled) {
        $recaptcha_secret_key = get_option('feedbackco_recaptcha_secret_key', '');
        $recaptcha_response = isset($_POST['recaptcha_response']) ? sanitize_text_field($_POST['recaptcha_response']) : '';

        if (empty($recaptcha_response)) {
            wp_send_json_error('reCAPTCHA verification failed. Please try again.');
            return;
        }

        // Verify reCAPTCHA response with Google
        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret'   => $recaptcha_secret_key,
                'response' => $recaptcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        ));

        $response_body = wp_remote_retrieve_body($response);
        $result = json_decode($response_body, true);

        if (empty($result['success'])) {
            wp_send_json_error('reCAPTCHA verification failed. Please try again.');
            return;
        }
    }

        $user_name  = sanitize_text_field($_POST['user_name']);
        $user_email = sanitize_email($_POST['user_email']);
        $message    = sanitize_textarea_field($_POST['message']);
        $rating     = intval($_POST['rating']);
        $category   = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

        global $wpdb;
        $table_name = $wpdb->prefix . 'feedbackco_entries';
    
        $result = $wpdb->insert(
            $table_name,
            array(
                'user_name'  => $user_name,
                'user_email' => $user_email,
                'message'    => $message,
                'rating'     => $rating,
                'category'   => $category,
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

