<?php

require_once plugin_dir_path(__FILE__) . '../classes/meta-box-renderer.php';

/**
 * Class ProjectPostType
 *
 * This class defines the custom post type "Project" and handles its meta boxes and custom fields.
 */
class ProjectPostType extends AbstractMetaBoxRenderer {
    private static $instance = null;

    /**
     * Private constructor to ensure singleton pattern.
     */
    private function __construct() {
        parent::__construct();
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_fields']);
        add_filter('use_block_editor_for_post_type', [$this, 'disable_block_editor'], 10, 2);
    }

    /**
     * Get the singleton instance of the class.
     *
     * @return ProjectPostType The singleton instance.
     */
    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new ProjectPostType();
        }
        return self::$instance;
    }

    /**
     * Disable the block editor for the "project" post type.
     *
     * @param bool $use_block_editor Whether to use the block editor.
     * @param string $post_type The post type.
     * @return bool Whether to use the block editor.
     */
    public function disable_block_editor($use_block_editor, $post_type) {
        if ($post_type === 'project') {
            return false;
        }
        return $use_block_editor;
    }

    /**
     * Register the custom post type "Project".
     */
    public function register_post_type() {
        $labels = [
            'name' => _x('Projects', 'Post Type General Name', PLUGIN_TEXT_DOMAIN),
            'singular_name' => _x('Project', 'Post Type Singular Name', PLUGIN_TEXT_DOMAIN),
            'menu_name' => __('Projects', PLUGIN_TEXT_DOMAIN),
            'name_admin_bar' => __('Project', PLUGIN_TEXT_DOMAIN),
            'archives' => __('Project Archives', PLUGIN_TEXT_DOMAIN),
            'attributes' => __('Project Attributes', PLUGIN_TEXT_DOMAIN),
            'parent_item_colon' => __('Parent Project:', PLUGIN_TEXT_DOMAIN),
            'all_items' => __('All Projects', PLUGIN_TEXT_DOMAIN),
            'add_new_item' => __('Add New Project', PLUGIN_TEXT_DOMAIN),
            'add_new' => __('Add New', PLUGIN_TEXT_DOMAIN),
            'new_item' => __('New Project', PLUGIN_TEXT_DOMAIN),
            'edit_item' => __('Edit Project', PLUGIN_TEXT_DOMAIN),
            'update_item' => __('Update Project', PLUGIN_TEXT_DOMAIN),
            'view_item' => __('View Project', PLUGIN_TEXT_DOMAIN),
            'view_items' => __('View Projects', PLUGIN_TEXT_DOMAIN),
            'search_items' => __('Search Project', PLUGIN_TEXT_DOMAIN),
            'not_found' => __('Not found', PLUGIN_TEXT_DOMAIN),
            'not_found_in_trash' => __('Not found in Trash', PLUGIN_TEXT_DOMAIN),
            'featured_image' => __('Featured Image', PLUGIN_TEXT_DOMAIN),
            'set_featured_image' => __('Set featured image', PLUGIN_TEXT_DOMAIN),
            'remove_featured_image' => __('Remove featured image', PLUGIN_TEXT_DOMAIN),
            'use_featured_image' => __('Use as featured image', PLUGIN_TEXT_DOMAIN),
            'insert_into_item' => __('Insert into project', PLUGIN_TEXT_DOMAIN),
            'uploaded_to_this_item' => __('Uploaded to this project', PLUGIN_TEXT_DOMAIN),
            'items_list' => __('Projects list', PLUGIN_TEXT_DOMAIN),
            'items_list_navigation' => __('Projects list navigation', PLUGIN_TEXT_DOMAIN),
            'filter_items_list' => __('Filter projects list', PLUGIN_TEXT_DOMAIN),
        ];

        $args = [
            'label' => __('Project', PLUGIN_TEXT_DOMAIN),
            'description' => __('Description of the Project post type', PLUGIN_TEXT_DOMAIN),
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
        ];

        register_post_type('project', $args);
    }

    /**
     * Add meta boxes for the "project" post type.
     */
    public function add_meta_boxes() {
        add_meta_box('project_description', __('Description', PLUGIN_TEXT_DOMAIN), [$this, 'render_description_meta_box'], 'project', 'normal', 'high');
        add_meta_box('project_dates', __('Dates', PLUGIN_TEXT_DOMAIN), [$this, 'render_dates_meta_box'], 'project', 'side', 'default');
        add_meta_box('project_url', __('Project URL', PLUGIN_TEXT_DOMAIN), [$this, 'render_url_meta_box'], 'project', 'normal', 'high');
    }

    /**
     * Render the description meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_description_meta_box($post) {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_meta_box('textarea', $post, 'description', __('Description', PLUGIN_TEXT_DOMAIN), __('Enter the description of the project.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Render the dates meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_dates_meta_box($post) {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_meta_box('checkbox', $post, 'active', __('Active', PLUGIN_TEXT_DOMAIN), __('Check if the project is currently active.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('date', $post, 'start-date', __('Start Date', PLUGIN_TEXT_DOMAIN), __('Enter the start date for the project.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('date', $post, 'end-date', __('End Date', PLUGIN_TEXT_DOMAIN), __('Enter the end date for the project.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Render the URL meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_url_meta_box($post) {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_meta_box('text', $post, 'url', __('Project URL', PLUGIN_TEXT_DOMAIN), __('Enter the URL for the project.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Save custom fields for the "project" post type.
     *
     * @param int $post_id The ID of the current post.
     */
    public function save_custom_fields($post_id) {
        // Define the fields to be saved
        $fields = ['description', 'active', 'start-date', 'end-date', 'url'];
    
        // Call the external function to save custom meta fields
        save_custom_meta_fields($post_id, $fields, 'project_fields_nonce', 'save_project_fields_nonce');
    }
}

// Initialize the class as a singleton
ProjectPostType::get_instance();