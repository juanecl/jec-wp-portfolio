<?php
/**
 * Date Meta Box Partial
 *
 * This file is responsible for rendering a date input meta box in the WordPress admin.
 * It displays a description and a date input field.
 *
 * @param string $description The description to display above the date input.
 * @param string $field The field name used to save the date value in the post meta.
 * @param WP_Post $post The current post object.
 */

?>

<div class="inside">
    <!-- Display the description -->
    <p><?php echo esc_html($description); ?></p>
    
    <?php
    // Retrieve the current value of the date input from the post meta
    $value = get_post_meta($post->ID, 'wpcf-' . $field, true);
    ?>
    
    <!-- Render the date input field -->
    <input type="date" id="wpcf-<?php echo esc_attr($field); ?>" name="wpcf-<?php echo esc_attr($field); ?>" value="<?php echo esc_attr($value); ?>" class="large-text">
</div>