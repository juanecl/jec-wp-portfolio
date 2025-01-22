<div class="inside">
    <p><?php echo esc_html($description); ?></p>
    <?php $value = get_post_meta($post->ID, 'wpcf-' . $field, true); ?>
    <input type="checkbox" id="wpcf-<?php echo esc_attr($field); ?>" name="wpcf-<?php echo esc_attr($field); ?>" <?php checked($value, 'on'); ?>>
</div>