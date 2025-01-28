<?php
/**
 * Textarea Meta Box Partial
 *
 * This file is responsible for rendering a textarea meta box in the WordPress admin.
 * It displays a description and a rich text editor (textarea).
 *
 * @param string $description The description to display above the textarea.
 * @param string $field The field name used to save the textarea value in the post meta.
 * @param WP_Post $post The current post object.
 */

?>

<div class="inside">
    <!-- Display the description -->
    <p><?php echo esc_html($description); ?></p>
    
    <?php
    // Retrieve the current value of the textarea from the post meta
    $value = get_post_meta($post->ID, 'wpcf-' . $field, true);
    
    // Render the rich text editor (textarea)
    wp_editor($value, 'wpcf-' . $field, [
        'textarea_name' => 'wpcf-' . $field,
        'editor_class' => 'large-text'
    ]);
    ?>
</div>