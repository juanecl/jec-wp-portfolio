<?php
require_once 'interface.php';
require_once 'abstract.php';
/**
 * Class DateMetaBox
 * 
 * This class is responsible for rendering a date meta box.
 */
class DateMetaBox extends AbstractMetaBox implements MetaBoxInterface {
    /**
     * Renders the date meta box.
     * 
     * @param WP_Post $post The post object.
     * @param string $field The field name.
     * @param string $title The title of the meta box.
     * @param string $description The description of the meta box.
     * @param array $additional_params Additional parameters for the meta box.
     */
    public function render($post, $field, $title, $description, $additional_params = []) {
        $this->validate_params($field, $title, $description);
        load_partial('date', compact('post', 'field', 'title', 'description'));
    }
}