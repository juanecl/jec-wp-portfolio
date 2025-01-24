<?php
/**
 * Checkbox Meta Box Partial
 *
 * This file is responsible for rendering a checkbox meta box in the WordPress admin.
 * It displays a description and a checkbox input field.
 *
 * @param string $description The description to display above the checkbox.
 * @param string $field The field name used to save the checkbox value in the post meta.
 * @param WP_Post $post The current post object.
 */

?>

<div class="inside">
    <!-- Display the description -->
    <p><?php echo esc_html($description); ?></p>
    
    <?php
    // Retrieve the current value of the checkbox from the post meta
    $value = get_post_meta($post->ID, 'wpcf-' . $field, true);
    ?>
    
    <!-- Render the checkbox input field -->
    <input type="checkbox" id="wpcf-<?php echo esc_attr($field); ?>" name="wpcf-<?php echo esc_attr($field); ?>" <?php checked($value, 'on'); ?>>
</div>