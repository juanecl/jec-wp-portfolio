<?php
$meta_key = isset($meta_key) ? $meta_key : 'wpcf-' . $field;
$post_type = isset($additional_params['post_type']) ? $additional_params['post_type'] : 'post';
$selected_item = get_post_meta($post->ID, $meta_key, true);
$new_item_url = admin_url('post-new.php?post_type=' . $post_type);
?>
<div class="inside">
    <label for="<?php echo $field; ?>"><?php echo $title; ?></label>
    <p><?php echo $description; ?></p>
    <select name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>">
        <?php foreach ($items as $item) : ?>
            <option value="<?php echo $item->ID; ?>" <?php selected($selected_item, $item->ID); ?>>
                <?php echo $item->post_title; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p><a href="<?php echo esc_url($new_item_url); ?>" target="_blank" class="button"><?php _e('Add New', 'text_domain'); ?></a></p>
</div>