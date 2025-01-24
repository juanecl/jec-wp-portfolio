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
        error_log('jec-portfolio: Constructor llamado en ' . current_filter() . ' en ' . debug_backtrace()[1]['file'] . ' línea ' . debug_backtrace()[1]['line']);
        add_action('init', [$this, 'load_textdomain']);
        $this->includes();
        $this->init_hooks();
        add_action('init', [$this, 'log_translation']);
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new JecPortfolio();
        }
        return self::$instance;
    }

    public function load_textdomain() {
        $mofile = dirname(plugin_basename(__FILE__)) . '/languages/' . get_locale() . '.mo';
        $loaded = load_plugin_textdomain('jec-portfolio', false, dirname(plugin_basename(__FILE__)) . '/languages');
        if (!$loaded) {
            error_log('jec-portfolio: Error al cargar el archivo de traducción: ' . $mofile);
        } else {
            error_log('jec-portfolio: Archivo de traducción cargado correctamente: ' . $mofile . ' para el idioma ' . get_locale());
        }
    }

    private function includes() {
        require_once plugin_dir_path(__FILE__) . 'includes/helpers.php';
        require_once plugin_dir_path(__FILE__) . 'includes/post-types/index.php';
        require_once plugin_dir_path(__FILE__) . 'includes/widgets/profile-widget.php';
    }

    private function init_hooks() {
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
    }

    public function log_translation() {
        $translated_text = __('Profile Widget', 'jec-portfolio');
        error_log('jec-portfolio: Traducción de "Profile Widget": ' . $translated_text);
    }

    public function activate() {
        flush_rewrite_rules();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }
}

// Asegúrate de que la clase se instancie solo una vez
add_action('plugins_loaded', ['JecPortfolio', 'get_instance']);