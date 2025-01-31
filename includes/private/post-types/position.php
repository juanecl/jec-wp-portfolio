<?php

require_once plugin_dir_path(__FILE__) . '../classes/meta-box-renderer.php';

/**
 * Class PositionPostType
 *
 * This class defines the custom post type "Position" and handles its meta boxes and custom fields.
 */
class PositionPostType extends AbstractMetaBoxRenderer {
    private static $instance = null;

    /**
     * Private constructor to ensure singleton pattern.
     */
    private function __construct() {
        parent::__construct();
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_fields']);
        add_filter('use_block_editor_for_post_type', [$this, 'disable_block_editor'], 10, 2);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    /**
     * Get the singleton instance of the class.
     *
     * @return PositionPostType The singleton instance.
     */
    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new PositionPostType();
        }
        return self::$instance;
    }

    /**
     * Disable the block editor for the "position" post type.
     *
     * @param bool $use_block_editor Whether to use the block editor.
     * @param string $post_type The post type.
     * @return bool Whether to use the block editor.
     */
    public function disable_block_editor($use_block_editor, $post_type) {
        if ($post_type === 'position') {
            return false;
        }
        return $use_block_editor;
    }

    /**
     * Register the custom post type "Position".
     */
    public function register_post_type() {
        $labels = [
            'name' => _x('Positions', 'Post Type General Name', PLUGIN_TEXT_DOMAIN),
            'singular_name' => _x('Position', 'Post Type Singular Name', PLUGIN_TEXT_DOMAIN),
            'menu_name' => __('Positions', PLUGIN_TEXT_DOMAIN),
            'name_admin_bar' => __('Position', PLUGIN_TEXT_DOMAIN),
            'archives' => __('Position Archives', PLUGIN_TEXT_DOMAIN),
            'attributes' => __('Position Attributes', PLUGIN_TEXT_DOMAIN),
            'parent_item_colon' => __('Parent Position:', PLUGIN_TEXT_DOMAIN),
            'all_items' => __('All Positions', PLUGIN_TEXT_DOMAIN),
            'add_new_item' => __('Add New Position', PLUGIN_TEXT_DOMAIN),
            'add_new' => __('Add New', PLUGIN_TEXT_DOMAIN),
            'new_item' => __('New Position', PLUGIN_TEXT_DOMAIN),
            'edit_item' => __('Edit Position', PLUGIN_TEXT_DOMAIN),
            'update_item' => __('Update Position', PLUGIN_TEXT_DOMAIN),
            'view_item' => __('View Position', PLUGIN_TEXT_DOMAIN),
            'view_items' => __('View Positions', PLUGIN_TEXT_DOMAIN),
            'search_items' => __('Search Position', PLUGIN_TEXT_DOMAIN),
            'not_found' => __('Not found', PLUGIN_TEXT_DOMAIN),
            'not_found_in_trash' => __('Not found in Trash', PLUGIN_TEXT_DOMAIN),
            'featured_image' => __('Featured Image', PLUGIN_TEXT_DOMAIN),
            'set_featured_image' => __('Set featured image', PLUGIN_TEXT_DOMAIN),
            'remove_featured_image' => __('Remove featured image', PLUGIN_TEXT_DOMAIN),
            'use_featured_image' => __('Use as featured image', PLUGIN_TEXT_DOMAIN),
            'insert_into_item' => __('Insert into position', PLUGIN_TEXT_DOMAIN),
            'uploaded_to_this_item' => __('Uploaded to this position', PLUGIN_TEXT_DOMAIN),
            'items_list' => __('Positions list', PLUGIN_TEXT_DOMAIN),
            'items_list_navigation' => __('Positions list navigation', PLUGIN_TEXT_DOMAIN),
            'filter_items_list' => __('Filter positions list', PLUGIN_TEXT_DOMAIN),
        ];

        $args = [
            'label' => __('Position', PLUGIN_TEXT_DOMAIN),
            'description' => __('Description of the Position post type', PLUGIN_TEXT_DOMAIN),
            'labels' => $labels,
            'supports' => ['title', 'thumbnail'],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'taxonomies' => ['knowledge', 'skills'], // Add taxonomies here
        ];

        register_post_type('position', $args);
    }

    /**
     * Register custom taxonomies for the "position" post type.
     */
    public function register_taxonomies() {
        // Register Knowledge Taxonomy
        $labels = [
            'name' => _x('Knowledge', 'Taxonomy General Name', PLUGIN_TEXT_DOMAIN),
            'singular_name' => _x('Knowledge', 'Taxonomy Singular Name', PLUGIN_TEXT_DOMAIN),
            'menu_name' => __('Knowledge', PLUGIN_TEXT_DOMAIN),
            'all_items' => __('All Knowledge', PLUGIN_TEXT_DOMAIN),
            'parent_item' => __('Parent Knowledge', PLUGIN_TEXT_DOMAIN),
            'parent_item_colon' => __('Parent Knowledge:', PLUGIN_TEXT_DOMAIN),
            'new_item_name' => __('New Knowledge Name', PLUGIN_TEXT_DOMAIN),
            'add_new_item' => __('Add New Knowledge', PLUGIN_TEXT_DOMAIN),
            'edit_item' => __('Edit Knowledge', PLUGIN_TEXT_DOMAIN),
            'update_item' => __('Update Knowledge', PLUGIN_TEXT_DOMAIN),
            'view_item' => __('View Knowledge', PLUGIN_TEXT_DOMAIN),
            'separate_items_with_commas' => __('Separate knowledge with commas', PLUGIN_TEXT_DOMAIN),
            'add_or_remove_items' => __('Add or remove knowledge', PLUGIN_TEXT_DOMAIN),
            'choose_from_most_used' => __('Choose from the most used', PLUGIN_TEXT_DOMAIN),
            'popular_items' => __('Popular Knowledge', PLUGIN_TEXT_DOMAIN),
            'search_items' => __('Search Knowledge', PLUGIN_TEXT_DOMAIN),
            'not_found' => __('Not Found', PLUGIN_TEXT_DOMAIN),
            'no_terms' => __('No knowledge', PLUGIN_TEXT_DOMAIN),
            'items_list' => __('Knowledge list', PLUGIN_TEXT_DOMAIN),
            'items_list_navigation' => __('Knowledge list navigation', PLUGIN_TEXT_DOMAIN),
        ];

        $args = [
            'labels' => $labels,
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
        ];

        register_taxonomy('knowledge', ['position'], $args);

        // Register Skills Taxonomy
        $labels = [
            'name' => _x('Skills', 'Taxonomy General Name', PLUGIN_TEXT_DOMAIN),
            'singular_name' => _x('Skill', 'Taxonomy Singular Name', PLUGIN_TEXT_DOMAIN),
            'menu_name' => __('Skills', PLUGIN_TEXT_DOMAIN),
            'all_items' => __('All Skills', PLUGIN_TEXT_DOMAIN),
            'parent_item' => __('Parent Skill', PLUGIN_TEXT_DOMAIN),
            'parent_item_colon' => __('Parent Skill:', PLUGIN_TEXT_DOMAIN),
            'new_item_name' => __('New Skill Name', PLUGIN_TEXT_DOMAIN),
            'add_new_item' => __('Add New Skill', PLUGIN_TEXT_DOMAIN),
            'edit_item' => __('Edit Skill', PLUGIN_TEXT_DOMAIN),
            'update_item' => __('Update Skill', PLUGIN_TEXT_DOMAIN),
            'view_item' => __('View Skill', PLUGIN_TEXT_DOMAIN),
            'separate_items_with_commas' => __('Separate skills with commas', PLUGIN_TEXT_DOMAIN),
            'add_or_remove_items' => __('Add or remove skills', PLUGIN_TEXT_DOMAIN),
            'choose_from_most_used' => __('Choose from the most used', PLUGIN_TEXT_DOMAIN),
            'popular_items' => __('Popular Skills', PLUGIN_TEXT_DOMAIN),
            'search_items' => __('Search Skills', PLUGIN_TEXT_DOMAIN),
            'not_found' => __('Not Found', PLUGIN_TEXT_DOMAIN),
            'no_terms' => __('No skills', PLUGIN_TEXT_DOMAIN),
            'items_list' => __('Skills list', PLUGIN_TEXT_DOMAIN),
            'items_list_navigation' => __('Skills list navigation', PLUGIN_TEXT_DOMAIN),
        ];

        $args = [
            'labels' => $labels,
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
        ];

        register_taxonomy('skills', ['position'], $args);
    }

    /**
     * Add meta boxes for the "position" post type.
     */
    public function add_meta_boxes() {
        add_meta_box('position_description', __('Description', PLUGIN_TEXT_DOMAIN), [$this, 'render_description_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_company', __('Company', PLUGIN_TEXT_DOMAIN), [$this, 'render_company_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_location', __('Location', PLUGIN_TEXT_DOMAIN), [$this, 'render_location_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_projects', __('Projects', PLUGIN_TEXT_DOMAIN), [$this, 'render_projects_meta_box'], 'position', 'side', 'default');
        add_meta_box('position_dates', __('Dates', PLUGIN_TEXT_DOMAIN), [$this, 'render_dates_meta_box'], 'position', 'side', 'default');
        add_meta_box('position_freelance', __('Freelance', PLUGIN_TEXT_DOMAIN), [$this, 'render_freelance_meta_box'], 'position', 'side', 'default'); // Add freelance meta box
    }

    /**
     * Render the projects meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_projects_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('multiselect', $post, 'project_ids', __('Select Projects', PLUGIN_TEXT_DOMAIN), __('Select the projects associated with this position.', PLUGIN_TEXT_DOMAIN), ['post_type' => 'project']);
    }

    /**
     * Render the description meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_description_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('textarea', $post, 'description', __('Description', PLUGIN_TEXT_DOMAIN), __('Enter the description of the position.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Render the company meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_company_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('select', $post, 'company_id', __('Select Company', PLUGIN_TEXT_DOMAIN), __('Select the company associated with this position.', PLUGIN_TEXT_DOMAIN), ['post_type' => 'company']);
    }

    /**
     * Render the location meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_location_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('text', $post, 'location', __('Location', PLUGIN_TEXT_DOMAIN), __('Enter the location for this position.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Render the dates meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_dates_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('checkbox', $post, 'active', __('Active', PLUGIN_TEXT_DOMAIN), __('Check if the position is currently active.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('date', $post, 'start-date', __('Start Date', PLUGIN_TEXT_DOMAIN), __('Enter the start date for the position.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('date', $post, 'end-date', __('End Date', PLUGIN_TEXT_DOMAIN), __('Enter the end date for the position.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Render the freelance meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_freelance_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('checkbox', $post, 'freelance', __('Freelance', PLUGIN_TEXT_DOMAIN), __('Check if the position is freelance.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Save custom fields for the "position" post type.
     *
     * @param int $post_id The ID of the current post.
     */
    public function save_custom_fields($post_id) {
        // Define the fields to be saved
        $fields = [
            ['description', true], // Enriched text area
            'company_id',
            'location',
            'project_ids',
            'active',
            'start-date',
            'end-date',
            'freelance' // Add freelance field
        ];
    
        // Call the external function to save custom meta fields
        save_custom_meta_fields($post_id, $fields, 'position_fields_nonce', 'save_position_fields_nonce');
    }
}

// Initialize the class as a singleton
PositionPostType::get_instance();