<?php
/**
 * Select Meta Box Partial
 *
 * This file is responsible for rendering a select input meta box in the WordPress admin.
 * It displays a label, a description, a select input field populated with items, and a link to add a new item.
 *
 * @param string $title The label to display above the select input.
 * @param string $description The description to display above the select input.
 * @param string $field The field name used to save the selected item ID in the post meta.
 * @param array $items The list of items to populate the select input.
 * @param array $additional_params Additional parameters for customization (e.g., post type).
 * @param WP_Post $post The current post object.
 */

$meta_key = isset($meta_key) ? $meta_key : 'wpcf-' . $field;
$post_type = isset($additional_params['post_type']) ? $additional_params['post_type'] : 'post';
$selected_item = get_post_meta($post->ID, $meta_key, true);
$new_item_url = admin_url('post-new.php?post_type=' . $post_type);
?>

<div class="inside">
    <!-- Display the label -->
    <label for="<?php echo esc_attr($field); ?>"><?php echo esc_html($title); ?></label>
    
    <!-- Display the description -->
    <p><?php echo esc_html($description); ?></p>
    
    <!-- Render the select input field -->
    <select name="<?php echo esc_attr($meta_key); ?>" id="<?php echo esc_attr($meta_key); ?>">
        <?php foreach ($items as $item) : ?>
            <option value="<?php echo esc_attr($item->ID); ?>" <?php selected($selected_item, $item->ID); ?>>
                <?php echo esc_html($item->post_title); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <!-- Display a link to add a new item -->
    <p><a href="<?php echo esc_url($new_item_url); ?>" target="_blank" class="button"><?php _e('Add New', PLUGIN_TEXT_DOMAIN); ?></a></p>
</div>