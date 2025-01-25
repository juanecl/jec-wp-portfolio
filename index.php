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
}

JecPortfolio::get_instance();