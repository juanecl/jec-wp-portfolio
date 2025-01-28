<?php

require_once 'interface.php';
require_once 'abstract.php';
/**
 * Class SelectMetaBox
 * 
 * This class is responsible for rendering a select meta box.
 */
class SelectMetaBox extends AbstractMetaBox implements MetaBoxInterface {
    /**
     * Renders the select meta box.
     * 
     * @param WP_Post $post The post object.
     * @param string $field The field name.
     * @param string $title The title of the meta box.
     * @param string $description The description of the meta box.
     * @param array $additional_params Additional parameters for the meta box.
     */
    public function render($post, $field, $title, $description, $additional_params = []) {
        $this->validate_params($field, $title, $description);

        if (!isset($additional_params['post_type'])) {
            throw new InvalidArgumentException(__('The post_type parameter is required.', PLUGIN_TEXT_DOMAIN));
        }

        $items = $this->get_posts($additional_params['post_type']);
        $meta_key = 'wpcf-' . $field;
        load_partial('select', compact('post', 'field', 'title', 'description', 'items', 'additional_params', 'meta_key'));
    }
}