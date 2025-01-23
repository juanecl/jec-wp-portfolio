<div class="inside">
    <p><?php echo esc_html($description); ?></p>
    <input type="file" id="wpcf-<?php echo esc_attr($field); ?>" name="wpcf-<?php echo esc_attr($field); ?>" value="<?php echo esc_attr($value); ?>" class="large-text">
    <?php if ($value): ?>
        <p><a href="<?php echo esc_url($value); ?>" target="_blank"><?php _e('View File', 'text_domain'); ?></a></p>
    <?php endif; ?>
</div>