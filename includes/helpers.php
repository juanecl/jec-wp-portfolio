<?php
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


/**
 * Save custom meta fields for a post.
 *
 * This function handles the validation and saving of custom meta fields for a post.
 * It verifies the nonce, checks for autosave, and ensures the user has the necessary permissions.
 * If the meta field exists, it updates it; otherwise, it deletes it if the field is not set.
 * Any errors encountered during the process are logged and displayed as WordPress settings errors.
 *
 * @param int $post_id The ID of the post being saved.
 * @param array $fields An array of field names to be saved. Each field can be a string or an array with the field name and a boolean indicating if it is an enriched text area.
 * @param string $nonce_name The name of the nonce field.
 * @param string $nonce_action The action name for the nonce.
 */
function save_custom_meta_fields($post_id, $fields, $nonce_name, $nonce_action) {
    // Verify nonce.
    if (!isset($_POST[$nonce_name]) || !wp_verify_nonce($_POST[$nonce_name], $nonce_action)) {
        return;
    }

    // Verify autosave.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verify user permissions.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    foreach ($fields as $field) {
        $field_name = is_array($field) ? $field[0] : $field;
        $is_enriched_text = is_array($field) ? $field[1] : false;
        $field_id = 'wpcf-' . $field_name;

        try {
            if (isset($_POST[$field_id]) && !empty($_POST[$field_id])) {
                $value = $_POST[$field_id];
                if ($is_enriched_text) {
                    $value = wp_kses_post($value); // Allow enriched text for this field
                } else {
                    if (is_array($value)) {
                        $value = array_map('sanitize_text_field', $value);
                    } else {
                        $value = sanitize_text_field($value);
                    }
                }

                // Verify if the meta field already exists
                $current_value = get_post_meta($post_id, $field_id, true);
                if ($current_value !== $value) {
                    if (!update_post_meta($post_id, $field_id, $value)) {
                        throw new Exception('Failed to update post meta for field: ' . $field_id);
                    }
                }
            } else {
                if (metadata_exists('post', $post_id, $field_id)) {
                    if (!delete_post_meta($post_id, $field_id)) {
                        throw new Exception('Failed to delete post meta for field: ' . $field_id);
                    }
                }
            }
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            add_settings_error(
                'project_meta_box_errors',
                esc_attr('settings_updated'),
                $e->getMessage(),
                'error'
            );
        }
    }

    // Display errors on the admin screen
    settings_errors('project_meta_box_errors');
}