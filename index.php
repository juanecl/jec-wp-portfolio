<?php
/*
Plugin Name: Portfolio Plugin
Plugin URI: https://www.juane.cl
Description: A plugin to manage portfolios.
Version: 1.0
Author: Juan Enrique Chomon Del Campo
Author URI: https://www.juane.cl
License: GPL2
Text Domain: jec-portfolio
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class JecPortfolio {
    private static $instance = null;

    private function __construct() {
        add_action('init', [$this, 'load_textdomain']);
        add_action('init', [$this, 'register_shortcodes']);
        $this->includes();
        $this->init_hooks();
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new JecPortfolio();
        }
        return self::$instance;
    }

    public function load_textdomain() {
        load_plugin_textdomain('jec-portfolio', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    private function includes() {
        require_once plugin_dir_path(__FILE__) . 'includes/helpers.php';
        require_once plugin_dir_path(__FILE__) . 'includes/post-types/index.php';
        require_once plugin_dir_path(__FILE__) . 'includes/widgets/profile-widget.php';
        require_once plugin_dir_path(__FILE__) . 'includes/widgets/position-widget.php';
        require_once plugin_dir_path(__FILE__) . 'includes/classes/profile-renderer.php';
        require_once plugin_dir_path(__FILE__) . 'includes/classes/position-renderer.php';
    }

    private function init_hooks() {
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
    }
    
    public function activate() {
        flush_rewrite_rules();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }

    public function register_shortcodes() {
        add_shortcode('jec-portfolio', [$this, 'render_shortcode']);
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(
            array(
                'type' => 'profile',
                'id' => '',
            ),
            $atts,
            'jec-portfolio'
        );

        ob_start();

        if ($atts['type'] == 'profile' && !empty($atts['id'])) {
            $profile_id = intval($atts['id']);
            include plugin_dir_path(__FILE__) . 'includes/templates/profile.php';
        } elseif ($atts['type'] == 'position') {
            $position_ids = array();

            if (!empty($atts['id'])) {
                // Convert the comma-separated string to an array of integers
                $position_ids = array_map('intval', explode(',', $atts['id']));
            }

            // Include the positions template
            include plugin_dir_path(__FILE__) . 'includes/templates/positions.php';
        }

        return ob_get_clean();
    }
}

JecPortfolio::get_instance();