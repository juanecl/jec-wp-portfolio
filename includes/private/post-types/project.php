<?php

require_once plugin_dir_path(__FILE__) . '../classes/meta-box-renderer.php';

/**
 * Class ProjectPostType
 *
 * This class defines the custom post type "Project" and handles its meta boxes and custom fields.
 */
class ProjectPostType extends AbstractMetaBoxRenderer
{
    private static $instance = null;

    /**
     * Private constructor to ensure singleton pattern.
     */
    private function __construct()
    {
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
    public static function get_instance()
    {
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
    public function disable_block_editor($use_block_editor, $post_type)
    {
        if ($post_type === 'project') {
            return false;
        }
        return $use_block_editor;
    }

    /**
     * Register the custom post type "Project".
     */
    public function register_post_type()
    {
        $labels = [
            'name' => _x('Projects', 'Post Type General Name', 'jec-portfolio'),
            'singular_name' => _x('Project', 'Post Type Singular Name', 'jec-portfolio'),
            'menu_name' => __('Projects', 'jec-portfolio'),
            'name_admin_bar' => __('Project', 'jec-portfolio'),
            'archives' => __('Project Archives', 'jec-portfolio'),
            'attributes' => __('Project Attributes', 'jec-portfolio'),
            'parent_item_colon' => __('Parent Project:', 'jec-portfolio'),
            'all_items' => __('All Projects', 'jec-portfolio'),
            'add_new_item' => __('Add New Project', 'jec-portfolio'),
            'add_new' => __('Add New', 'jec-portfolio'),
            'new_item' => __('New Project', 'jec-portfolio'),
            'edit_item' => __('Edit Project', 'jec-portfolio'),
            'update_item' => __('Update Project', 'jec-portfolio'),
            'view_item' => __('View Project', 'jec-portfolio'),
            'view_items' => __('View Projects', 'jec-portfolio'),
            'search_items' => __('Search Project', 'jec-portfolio'),
            'not_found' => __('Not found', 'jec-portfolio'),
            'not_found_in_trash' => __('Not found in Trash', 'jec-portfolio'),
            'featured_image' => __('Featured Image', 'jec-portfolio'),
            'set_featured_image' => __('Set featured image', 'jec-portfolio'),
            'remove_featured_image' => __('Remove featured image', 'jec-portfolio'),
            'use_featured_image' => __('Use as featured image', 'jec-portfolio'),
            'insert_into_item' => __('Insert into project', 'jec-portfolio'),
            'uploaded_to_this_item' => __('Uploaded to this project', 'jec-portfolio'),
            'items_list' => __('Projects list', 'jec-portfolio'),
            'items_list_navigation' => __('Projects list navigation', 'jec-portfolio'),
            'filter_items_list' => __('Filter projects list', 'jec-portfolio'),
        ];

        $args = [
            'label' => __('Project', 'jec-portfolio'),
            'description' => __('Description of the Project post type', 'jec-portfolio'),
            'labels' => $labels,
            'supports' => ['title', 'thumbnail'],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-portfolio',
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
    public function add_meta_boxes()
    {
        add_meta_box('project_description', __('Description', 'jec-portfolio'), [$this, 'render_description_meta_box'], 'project', 'normal', 'high');
        add_meta_box('project_dates', __('Dates', 'jec-portfolio'), [$this, 'render_dates_meta_box'], 'project', 'side', 'default');
        add_meta_box('project_url', __('Project URL', 'jec-portfolio'), [$this, 'render_url_meta_box'], 'project', 'normal', 'high');
    }

    /**
     * Render the description meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_description_meta_box($post)
    {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_meta_box('textarea', $post, 'description', __('Description', 'jec-portfolio'), __('Enter the description of the project.', 'jec-portfolio'));
    }

    /**
     * Render the dates meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_dates_meta_box($post)
    {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_meta_box('checkbox', $post, 'active', __('Active', 'jec-portfolio'), __('Check if the project is currently active.', 'jec-portfolio'));
        $this->render_meta_box('date', $post, 'start-date', __('Start Date', 'jec-portfolio'), __('Enter the start date for the project.', 'jec-portfolio'));
        $this->render_meta_box('date', $post, 'end-date', __('End Date', 'jec-portfolio'), __('Enter the end date for the project.', 'jec-portfolio'));
    }

    /**
     * Render the URL meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_url_meta_box($post)
    {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_meta_box('text', $post, 'url', __('Project URL', 'jec-portfolio'), __('Enter the URL for the project.', 'jec-portfolio'));
    }

    /**
     * Save custom fields for the "project" post type.
     *
     * @param int $post_id The ID of the current post.
     */
    public function save_custom_fields($post_id)
    {
        // Define the fields to be saved
        $fields = [['description', true], 'active', 'start-date', 'end-date', 'url'];

        // Call the external function to save custom meta fields
        save_custom_meta_fields($post_id, $fields, 'project_fields_nonce', 'save_project_fields_nonce');
    }
}

// Initialize the class as a singleton
ProjectPostType::get_instance();