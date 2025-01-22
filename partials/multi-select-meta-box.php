<?php
$selected_items = get_post_meta($post->ID, $meta_key, true) ?: [];
$new_item_url = admin_url('post-new.php?post_type=' . $post_type);
?>
<div class="inside">
    <label for="<?php echo $field; ?>"><?php echo $title; ?></label>
    <p><?php echo $description; ?></p>
    <select name="<?php echo $field; ?>[]" id="<?php echo $field; ?>" multiple>
        <?php foreach ($items as $item) : ?>
            <option value="<?php echo $item->ID; ?>" <?php echo in_array($item->ID, $selected_items) ? 'selected' : ''; ?>>
                <?php echo $item->post_title; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p><a href="<?php echo esc_url($new_item_url); ?>" target="_blank" class="button"><?php _e('Add New', 'text_domain'); ?></a></p>
</div>