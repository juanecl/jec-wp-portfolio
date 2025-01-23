<?php

/**
 * Abstract class for defining meta boxes.
 * 
 * This class provides common methods for defining different types of meta boxes.
 */
abstract class AbstractMetaBox {
    /**
     * Validates the required parameters.
     * 
     * @param string $field The field name.
     * @param string $title The title of the meta box.
     * @param string $description The description of the meta box.
     * 
     * @throws InvalidArgumentException If any required parameter is missing.
     */
    protected function validate_params($field, $title, $description) {
        if (empty($field) || empty($title) || empty($description)) {
            throw new InvalidArgumentException(__('All parameters are required.', 'portfolio-plugin'));
        }
    }

    /**
     * Retrieves the posts for a given post type.
     * 
     * @param string $post_type The post type to retrieve items from.
     * @return array The list of posts.
     */
    protected function get_posts($post_type) {
        $args = [
            'post_type' => $post_type,
            'numberposts' => -1,
            'post_status' => 'publish',
        ];

        return get_posts($args);
    }
}