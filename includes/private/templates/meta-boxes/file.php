<?php
/**
 * File Meta Box Partial
 *
 * This file is responsible for rendering a file input meta box in the WordPress admin.
 * It displays a description, a file input field, and a link to view the uploaded file if it exists.
 *
 * @param string $description The description to display above the file input.
 * @param string $field The field name used to save the file URL in the post meta.
 * @param string $value The current value of the file URL from the post meta.
 */

?>

<div class="inside">
    <!-- Display the description -->
    <h4><?php echo esc_html($description); ?></h4>
    
    <!-- Render the file input field -->
    <input type="file" id="wpcf-<?php echo esc_attr($field); ?>" name="wpcf-<?php echo esc_attr($field); ?>" value="<?php echo esc_attr($value); ?>" class="large-text">
    
    <?php if ($value): ?>
        <!-- Display a link to view the uploaded file if it exists -->
        <p><a href="<?php echo esc_url($value); ?>" target="_blank"><?php _e('View File', 'jec-portfolio'); ?></a></p>
    <?php endif; ?>
</div>