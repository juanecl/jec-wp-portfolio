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

// Función de activación
function portfolio_plugin_activate() {
    // Código a ejecutar durante la activación del plugin
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'portfolio_plugin_activate');

// Función de desactivación
function portfolio_plugin_deactivate() {
    // Código a ejecutar durante la desactivación del plugin
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'portfolio_plugin_deactivate');

// Incluir archivos necesarios
require plugin_dir_path(__FILE__) . 'helpers.php';
require plugin_dir_path(__FILE__) . 'includes/post_types/company.php';
require plugin_dir_path(__FILE__) . 'includes/post_types/position.php';
require plugin_dir_path(__FILE__) . 'includes/post_types/profile.php';
require plugin_dir_path(__FILE__) . 'includes/post_types/project.php';
require_once plugin_dir_path(__FILE__) . 'includes/front/profile-widget.php';
?>