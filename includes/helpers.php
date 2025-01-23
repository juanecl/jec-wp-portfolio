<?php

function enqueue_admin_scripts() {
    wp_enqueue_script('admin-js', plugin_dir_url(__FILE__) . '../assets/js/admin.js', ['jquery'], null, true);
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');

function hide_editor_for_position() {
    global $pagenow;
    if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
        return;
    }

    $post_type = get_post_type();
    if ($post_type === 'position') {
        echo '<style type="text/css">
            #postdivrich {
                display: none;
            }
        </style>';
    }
}
add_action('admin_head', 'hide_editor_for_position');

/**
 * Disable Gutenberg for specific post types.
 */
function disable_gutenberg_for_custom_post_types($is_enabled, $post_type)
{
    $custom_post_types = array('article', 'profile', 'company', 'project', 'position');
    if (in_array($post_type, $custom_post_types)) {
        return false;
    }
    return $is_enabled;
}

add_filter('use_block_editor_for_post_type', 'disable_gutenberg_for_custom_post_types', 10, 2);

function load_partial($partial_name, $variables = []) {
    extract($variables);
    $template_path = plugin_dir_path(__FILE__) . "partials/meta-boxes/{$partial_name}.php";
    if ($template_path && file_exists($template_path)) {
        include $template_path;
    } else {
        throw new Exception("Partial not found: {$partial_name}");
    }
}