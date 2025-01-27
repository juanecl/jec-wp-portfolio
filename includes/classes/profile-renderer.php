<?php

class ProfileRenderer {
    private static $instance = null;

    private function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function enqueue_assets() {
        wp_enqueue_style('jec-portfolio-profile', plugin_dir_url(__FILE__) . '../../assets/css/profile.css', array(), '1.0');
        wp_enqueue_script('jec-portfolio-profile', plugin_dir_url(__FILE__) . '../../assets/js/profile.js', array('jquery'), '1.0', true);
    }
}

// Inicializar el singleton
ProfileRenderer::get_instance();