<?php

/**
 * Class PositionQuery
 *
 * This class handles the construction and execution of WP_Query for positions.
 */
class PositionQuery {
    /**
     * @var array The arguments for WP_Query.
     */
    private $args;

    /**
     * PositionQuery constructor.
     *
     * Initializes the query arguments with default values.
     */
    public function __construct() {
        $this->args = [
            'post_type' => 'position',
            'meta_key' => 'wpcf-start-date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
        ];
    }

    /**
     * Add a taxonomy query to the WP_Query arguments.
     *
     * @param string $taxonomy The taxonomy to query.
     * @param array $terms The terms to query within the taxonomy.
     */
    public function add_tax_query($taxonomy, $terms) {
        if (!isset($this->args['tax_query'])) {
            $this->args['tax_query'] = ['relation' => 'AND'];
        }

        $this->args['tax_query'][] = [
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => array_map('sanitize_text_field', $terms),
            'operator' => 'AND',
        ];
    }

    /**
     * Execute the WP_Query with the constructed arguments.
     *
     * @return WP_Query The WP_Query object containing the results.
     */
    public function get_positions() {
        return new WP_Query($this->args);
    }
}