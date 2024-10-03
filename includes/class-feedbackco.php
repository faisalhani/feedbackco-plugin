<?php

class FeedbackCo {

    protected $loader;

    protected $plugin_name;

    protected $version;

    public function __construct() {
        $this->plugin_name = 'feedbackco';
        $this->version = FEEDBACKCO_VERSION;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        // Load required files
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-feedbackco-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-feedbackco-i18n.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-feedbackco-admin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-feedbackco-public.php';

        $this->loader = new FeedbackCo_Loader();
    }

    private function set_locale() {
        $plugin_i18n = new FeedbackCo_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    private function define_admin_hooks() {
        $plugin_admin = new FeedbackCo_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings'); // Add this line
    
        // Handle AJAX in admin
        $this->loader->add_action('wp_ajax_feedbackco_export_csv', $plugin_admin, 'export_csv');
    }
    

    private function define_public_hooks() {
        $plugin_public = new FeedbackCo_Public($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_footer', $plugin_public, 'inject_widget');

        // Handle AJAX in public
        $this->loader->add_action('wp_ajax_feedbackco_submit_feedback', $plugin_public, 'submit_feedback');
        $this->loader->add_action('wp_ajax_nopriv_feedbackco_submit_feedback', $plugin_public, 'submit_feedback');
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_loader() {
        return $this->loader;
    }

    public function get_version() {
        return $this->version;
    }
}
