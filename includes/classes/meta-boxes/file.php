<?php
require_once 'interface.php';
require_once 'abstract.php';
/**
 * Class FileMetaBox
 * 
 * This class is responsible for rendering a file meta box.
 */
class FileMetaBox extends AbstractMetaBox implements MetaBoxInterface {
    /**
     * Renders the file meta box.
     * 
     * @param WP_Post $post The post object.
     * @param string $field The field name.
     * @param string $title The title of the meta box.
     * @param string $description The description of the meta box.
     * @param array $additional_params Additional parameters for the meta box.
     */
    public function render($post, $field, $title, $description, $additional_params = []) {
        $this->validate_params($field, $title, $description);
        $value = get_post_meta($post->ID, 'wpcf-' . $field, true);
        load_partial('file', compact('post', 'field', 'title', 'description', 'value'));
    }
}