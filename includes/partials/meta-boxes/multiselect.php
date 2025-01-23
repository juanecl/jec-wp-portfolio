<?php
/**
 * Multi-select Meta Box Template
 * 
 * This template is used to render a multi-select meta box in the WordPress admin.
 * 
 * @param WP_Post $post The post object.
 * @param string $field The field name.
 * @param string $title The title of the meta box.
 * @param string $description The description of the meta box.
 * @param array $items The items to display in the multi-select.
 * @param array $additional_params Additional parameters for the meta box.
 * @param string $meta_key The meta key for the field.
 */

// Ensure the meta key is set
$meta_key = $meta_key ?? 'wpcf-' . $field;

// Ensure the post type is set
$post_type = $additional_params['post_type'] ?? 'post';

// Get the selected items from the post meta
$selected_items = get_post_meta($post->ID, $meta_key, true) ?: [];

// Ensure selected items is an array
if (!is_array($selected_items)) {
    $selected_items = [];
}

// Generate the URL for creating a new item of the specified post type
$new_item_url = admin_url('post-new.php?post_type=' . $post_type);
?>
<div class="inside">
    <label for="<?php echo esc_attr($meta_key); ?>"><?php echo esc_html($title); ?></label>
    <p><?php echo esc_html($description); ?></p>
    <select name="<?php echo esc_attr($meta_key); ?>[]" id="<?php echo esc_attr($meta_key); ?>" multiple>
        <?php foreach ($items as $item) : ?>
            <option value="<?php echo esc_attr($item->ID); ?>" <?php selected(in_array($item->ID, $selected_items)); ?>>
                <?php echo esc_html($item->post_title); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p><a href="<?php echo esc_url($new_item_url); ?>" target="_blank" class="button"><?php esc_html_e('Add New', 'text_domain'); ?></a></p>
</div>