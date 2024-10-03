<?php

class FeedbackCo {

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->plugin_name = 'feedbackco';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-feedbackco-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-feedbackco-public.php';

        $this->loader = new FeedbackCo_Loader();
    }

    private function define_public_hooks() {
        $plugin_public = new FeedbackCo_Public($this->plugin_name, $this->version);

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_footer', $plugin_public, 'inject_widget');

        // AJAX handlers
        $this->loader->add_action('wp_ajax_feedbackco_submit_feedback', $plugin_public, 'submit_feedback');
        $this->loader->add_action('wp_ajax_nopriv_feedbackco_submit_feedback', $plugin_public, 'submit_feedback');
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_version() {
        return $this->version;
    }
}
