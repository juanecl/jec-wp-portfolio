<div class="inside">
    <p><?php echo $description; ?></p>
    <input type="date" id="wpcf-<?php echo $field; ?>" name="wpcf-<?php echo $field; ?>" value="<?php echo esc_attr(get_post_meta($post->ID, 'wpcf-' . $field, true)); ?>" class="large-text">
</div>