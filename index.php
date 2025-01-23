<?php
/*
Plugin Name: Portfolio Plugin
Plugin URI: https://www.juane.cl
Description: Un plugin para gestionar el portafolio.
Version: 1.0
Author: Juan Enrique Chomon Del Campo
Author URI: https://www.juane.cl
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PortfolioPlugin {
    private static $instance = null;

    private function __construct() {
        $this->load_textdomain();
        $this->includes();
        $this->init_hooks();
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new PortfolioPlugin();
        }
        return self::$instance;
    }

    private function load_textdomain() {
        load_plugin_textdomain('portfolio-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    private function includes() {
        require_once plugin_dir_path(__FILE__) . 'includes/helpers.php';
        require_once plugin_dir_path(__FILE__) . 'includes/post_types/company.php';
        require_once plugin_dir_path(__FILE__) . 'includes/post_types/position.php';
        require_once plugin_dir_path(__FILE__) . 'includes/post_types/profile.php';
        require_once plugin_dir_path(__FILE__) . 'includes/post_types/project.php';
        require_once plugin_dir_path(__FILE__) . 'includes/front/profile-widget.php';
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

PortfolioPlugin::get_instance();
?>