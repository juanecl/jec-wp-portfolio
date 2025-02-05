<?php
/*
Plugin Name: jec-portfolio
Plugin URI: https://github.com/juanecl/jec-wp-portfolio
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

if (!defined('PLUGIN_ROOT_PATH')) {
    define('PLUGIN_ROOT_PATH', plugin_dir_path(__FILE__));
}

if (!defined('PLUGIN_ROOT_URL')) {
    define('PLUGIN_ROOT_URL', plugin_dir_url(__FILE__));
}

if (!defined('PLUGIN_VERSION')) {
    define('PLUGIN_VERSION', '1.0');
}


/**
 * Main class for the JecPortfolio plugin.
 */
class JecPortfolio {
    /**
     * Singleton instance of the class.
     *
     * @var JecPortfolio|null
     */
    private static $instance = null;

    /**
     * Constructor for the class.
     *
     * Initializes the plugin by setting up localization, shortcodes, and including necessary files.
     */
    private function __construct() {
        add_action('init', [$this, 'load_textdomain']);
        add_action('init', [$this, 'register_shortcodes']);
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Gets the singleton instance of the class.
     *
     * @return JecPortfolio The singleton instance of the class.
     */
    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new JecPortfolio();
        }
        return self::$instance;
    }

    /**
     * Loads the plugin's text domain for localization.
     */
    public function load_textdomain() {
        load_plugin_textdomain('jec-portfolio', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Includes necessary files for the plugin.
     */
    private function includes() {
        require_once PLUGIN_ROOT_PATH . 'includes/helpers.php';
        require_once PLUGIN_ROOT_PATH. 'includes/private/post-types/init.php';
        require_once PLUGIN_ROOT_PATH . 'includes/public/widgets/init.php';
        require_once PLUGIN_ROOT_PATH . 'includes/public/classes/init.php';
    }

    /**
     * Initializes hooks for plugin activation and deactivation.
     */
    private function init_hooks() {
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
    }
    
    /**
     * Handles plugin activation.
     *
     * Flushes rewrite rules to ensure custom post types are registered.
     */
    public function activate() {
        flush_rewrite_rules();
    }

    /**
     * Handles plugin deactivation.
     *
     * Flushes rewrite rules to clean up.
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Registers shortcodes for the plugin.
     */
    public function register_shortcodes() {
        add_shortcode('jec-portfolio', [$this, 'render_shortcode']);
    }

    /**
     * Renders the shortcode for the plugin.
     *
     * @param array $atts The shortcode attributes.
     * @return string The rendered shortcode content.
     */
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
            include plugin_dir_path(__FILE__) . 'includes/public/templates/profile/single-profile.php';
        } elseif ($atts['type'] == 'position') {
            $position_ids = array();

            if (!empty($atts['id'])) {
                // Convert the comma-separated string to an array of integers
                $position_ids = array_map('intval', explode(',', $atts['id']));
            }

            // Include the positions template
            include plugin_dir_path(__FILE__) . 'includes/public/templates/position/index.php';
        }

        return ob_get_clean();
    }
}

// Initialize the plugin
JecPortfolio::get_instance();