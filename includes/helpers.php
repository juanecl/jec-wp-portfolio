<?php

/**
 * Enqueue admin scripts.
 *
 * This function enqueues the JavaScript file for the admin area.
 */
function enqueue_admin_scripts() {
    wp_enqueue_script('admin-js', plugin_dir_url(__FILE__) . '../assets/js/admin.js', ['jquery'], null, true);
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');

/**
 * Disable Gutenberg for specific post types.
 *
 * This function disables the Gutenberg editor for the specified custom post types.
 *
 * @param bool $is_enabled Whether the block editor is enabled.
 * @param string $post_type The post type being checked.
 * @return bool Whether the block editor is enabled.
 */
function disable_gutenberg_for_custom_post_types($is_enabled, $post_type) {
    $custom_post_types = ['article', 'profile', 'company', 'project', 'position'];
    if (in_array($post_type, $custom_post_types)) {
        return false;
    }
    return $is_enabled;
}
add_filter('use_block_editor_for_post_type', 'disable_gutenberg_for_custom_post_types', 10, 2);

/**
 * Load a partial template.
 *
 * This function loads a partial template file and extracts the provided variables.
 *
 * @param string $partial_name The name of the partial template to load.
 * @param array $variables The variables to extract and use in the partial template.
 * @throws Exception If the partial template file is not found.
 */
function load_partial($partial_name, $variables = []) {
    extract($variables);
    $template_path = plugin_dir_path(__FILE__) . "partials/meta-boxes/{$partial_name}.php";
    if ($template_path && file_exists($template_path)) {
        include $template_path;
    } else {
        throw new Exception("Partial not found: {$partial_name}");
    }
}

/**
 * Include all PHP files in a directory.
 *
 * This function will include all PHP files in the specified directory,
 * except the file from which it is called.
 *
 * @param string $directory The directory path to include PHP files from.
 */
function include_all_php_files($directory) {
    foreach (glob("{$directory}/*.php") as $file) {
        if ($file !== __FILE__) {
            require_once $file;
        }
    }
}