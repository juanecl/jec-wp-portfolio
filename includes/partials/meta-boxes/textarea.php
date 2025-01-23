<div class="inside">
    <p><?php echo $description; ?></p>
    <?php wp_editor(get_post_meta($post->ID, 'wpcf-' . $field, true), 'wpcf-' . $field, ['textarea_name' => 'wpcf-' . $field, 'editor_class' => 'large-text']); ?>
</div>