<div class="inside">
    <p><?php echo $description; ?></p>
    <input type="text" id="wpcf-<?php echo $field; ?>" name="wpcf-<?php echo $field; ?>" value="<?php echo esc_attr(get_post_meta($post->ID, 'wpcf-' . $field, true)); ?>" class="widefat">
</div>
