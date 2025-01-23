<?php
$meta_key = isset($meta_key) ? $meta_key : 'wpcf-' . $field;
$post_type = isset($additional_params['post_type']) ? $additional_params['post_type'] : 'post';
$selected_items = get_post_meta($post->ID, $meta_key, true) ?: [];
$new_item_url = admin_url('post-new.php?post_type=' . $post_type);
echo $post->ID . '<br>';
echo $meta_key . '<br>';
echo $field . '<br>';
echo $post_type . '<br>';
echo print_r(get_post_meta($post->ID), true) . '<br>';
echo print_r($selected_items, true) . '<br>';
echo $new_item_url . '<br>';
?>
<div class="inside">
    <label for="<?php echo $meta_key; ?>"><?php echo $title; ?></label>
    <p><?php echo $description; ?></p>
    <select name="<?php echo $meta_key; ?>[]" id="<?php echo $meta_key; ?>" multiple>
        <?php foreach ($items as $item) : ?>
            <option value="<?php echo $item->ID; ?>" <?php echo in_array($item->ID, $selected_items) ? 'selected' : ''; ?>>
                <?php echo $item->post_title; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p><a href="<?php echo esc_url($new_item_url); ?>" target="_blank" class="button"><?php _e('Add New', 'text_domain'); ?></a></p>
</div>