<?php
/**
 * URL Meta Box Partial
 *
 * This file is responsible for rendering a URL input meta box in the WordPress admin.
 * It displays a description and a URL input field.
 *
 * @param string $description The description to display above the URL input.
 * @param string $field The field name used to save the URL value in the post meta.
 * @param WP_Post $post The current post object.
 */

?>

<div class="inside">
    <!-- Display the description -->
    <p><?php echo esc_html($description); ?></p>
    
    <?php
    // Retrieve the current value of the URL input from the post meta
    $value = get_post_meta($post->ID, 'wpcf-' . $field, true);
    ?>
    
    <!-- Render the URL input field -->
    <input type="url" id="wpcf-<?php echo esc_attr($field); ?>" name="wpcf-<?php echo esc_attr($field); ?>" value="<?php echo esc_attr($value); ?>" class="large-text">
</div>