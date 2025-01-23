<?php

require_once plugin_dir_path(__FILE__) . '../classes/meta-box-renderer.php';

class ProjectPostType extends AbstractMetaBoxRenderer {
    private static $instance = null;

    private function __construct() {
        parent::__construct();
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_fields']);
        add_filter('use_block_editor_for_post_type', [$this, 'disable_block_editor'], 10, 2);
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new ProjectPostType();
        }
        return self::$instance;
    }

    public function disable_block_editor($use_block_editor, $post_type) {
        if ($post_type === 'project') {
            return false;
        }
        return $use_block_editor;
    }

    public function register_post_type() {
        $labels = [
            'name' => _x('Projects', 'Post Type General Name', 'portfolio-plugin'),
            'singular_name' => _x('Project', 'Post Type Singular Name', 'portfolio-plugin'),
            'menu_name' => __('Projects', 'portfolio-plugin'),
            'name_admin_bar' => __('Project', 'portfolio-plugin'),
            'archives' => __('Project Archives', 'portfolio-plugin'),
            'attributes' => __('Project Attributes', 'portfolio-plugin'),
            'parent_item_colon' => __('Parent Project:', 'portfolio-plugin'),
            'all_items' => __('All Projects', 'portfolio-plugin'),
            'add_new_item' => __('Add New Project', 'portfolio-plugin'),
            'add_new' => __('Add New', 'portfolio-plugin'),
            'new_item' => __('New Project', 'portfolio-plugin'),
            'edit_item' => __('Edit Project', 'portfolio-plugin'),
            'update_item' => __('Update Project', 'portfolio-plugin'),
            'view_item' => __('View Project', 'portfolio-plugin'),
            'view_items' => __('View Projects', 'portfolio-plugin'),
            'search_items' => __('Search Project', 'portfolio-plugin'),
            'not_found' => __('Not found', 'portfolio-plugin'),
            'not_found_in_trash' => __('Not found in Trash', 'portfolio-plugin'),
            'featured_image' => __('Featured Image', 'portfolio-plugin'),
            'set_featured_image' => __('Set featured image', 'portfolio-plugin'),
            'remove_featured_image' => __('Remove featured image', 'portfolio-plugin'),
            'use_featured_image' => __('Use as featured image', 'portfolio-plugin'),
            'insert_into_item' => __('Insert into project', 'portfolio-plugin'),
            'uploaded_to_this_item' => __('Uploaded to this project', 'portfolio-plugin'),
            'items_list' => __('Projects list', 'portfolio-plugin'),
            'items_list_navigation' => __('Projects list navigation', 'portfolio-plugin'),
            'filter_items_list' => __('Filter projects list', 'portfolio-plugin'),
        ];

        $args = [
            'label' => __('Project', 'portfolio-plugin'),
            'description' => __('Description of the Project post type', 'portfolio-plugin'),
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

    public function add_meta_boxes() {
        add_meta_box('project_description', __('Description', 'portfolio-plugin'), [$this, 'render_description_meta_box'], 'project', 'normal', 'high');
        add_meta_box('project_dates', __('Dates', 'portfolio-plugin'), [$this, 'render_dates_meta_box'], 'project', 'side', 'default');
    }

    public function render_description_meta_box($post) {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_meta_box('textarea', $post, 'description', __('Description', 'portfolio-plugin'), __('Enter the description of the project.', 'portfolio-plugin'));
    }

    public function render_dates_meta_box($post) {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_meta_box('checkbox', $post, 'active', __('Active', 'portfolio-plugin'), __('Check if the project is currently active.', 'portfolio-plugin'));
        $this->render_meta_box('date', $post, 'start-date', __('Start Date', 'portfolio-plugin'), __('Enter the start date for the project.', 'portfolio-plugin'));
        $this->render_meta_box('date', $post, 'end-date', __('End Date', 'portfolio-plugin'), __('Enter the end date for the project.', 'portfolio-plugin'));
    }

    public function save_custom_fields($post_id) {
        // Verify nonce.
        if (!isset($_POST['project_fields_nonce']) || !wp_verify_nonce($_POST['project_fields_nonce'], 'save_project_fields_nonce')) {
            return;
        }

        // Verify autosave.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Verify user permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        // Save custom fields
        $fields = ['description', 'active', 'start-date', 'end-date'];
        foreach ($fields as $field) {
            $field_id = 'wpcf-' . $field;
            if (isset($_POST[$field_id])) {
                $value = sanitize_text_field($_POST[$field_id]);
                update_post_meta($post_id, $field_id, $value);
            } else {
                delete_post_meta($post_id, $field_id);
            }
        }
    }
}

// Initialize the class as a singleton
ProjectPostType::get_instance();