<?php

/**
 * Interface MetaBoxInterface
 * 
 * This interface defines the contract for rendering meta boxes.
 */
interface MetaBoxInterface {
    /**
     * Renders the meta box.
     * 
     * @param WP_Post $post The post object.
     * @param string $field The field name.
     * @param string $title The title of the meta box.
     * @param string $description The description of the meta box.
     * @param array $additional_params Additional parameters for the meta box.
     */
    public function render($post, $field, $title, $description, $additional_params = []);
}