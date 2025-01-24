<?php
/**
 * Text Meta Box Partial
 *
 * This file is responsible for rendering a text input meta box in the WordPress admin.
 * It displays a description and a text input field.
 *
 * @param string $description The description to display above the text input.
 * @param string $field The field name used to save the text value in the post meta.
 * @param WP_Post $post The current post object.
 */

?>

<div class="inside">
    <!-- Display the description -->
    <p><?php echo esc_html($description); ?></p>
    
    <!-- Render the text input field -->
    <input type="text" id="wpcf-<?php echo esc_attr($field); ?>" name="wpcf-<?php echo esc_attr($field); ?>" value="<?php echo esc_attr(get_post_meta($post->ID, 'wpcf-' . $field, true)); ?>" class="widefat">
</div>