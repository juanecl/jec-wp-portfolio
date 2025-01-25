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
        add_meta_box('project_description', __('Description', 'jec-portfolio'), [$this, 'render_description_meta_box'], 'project', 'normal', 'high');
        add_meta_box('project_dates', __('Dates', 'jec-portfolio'), [$this, 'render_dates_meta_box'], 'project', 'side', 'default');
    }

    /**
     * Render the description meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_description_meta_box($post) {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_meta_box('textarea', $post, 'description', __('Description', 'jec-portfolio'), __('Enter the description of the project.', 'jec-portfolio'));
    }

    /**
     * Render the dates meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_dates_meta_box($post) {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_meta_box('checkbox', $post, 'active', __('Active', 'jec-portfolio'), __('Check if the project is currently active.', 'jec-portfolio'));
        $this->render_meta_box('date', $post, 'start_date', __('Start Date', 'jec-portfolio'), __('Enter the start date for the project.', 'jec-portfolio'));
        $this->render_meta_box('date', $post, 'end_date', __('End Date', 'jec-portfolio'), __('Enter the end date for the project.', 'jec-portfolio'));
    }

    /**
     * Save custom fields for the "project" post type.
     *
     * @param int $post_id The ID of the current post.
     */
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
        $fields = ['description', 'active', 'start_date', 'end_date'];
        foreach ($fields as $field) {
            $field_id = 'wpcf-' . $field;
            try {
                if (isset($_POST[$field_id])) {
                    $value = sanitize_text_field($_POST[$field_id]);
                    if (is_array($value)) {
                        $value = array_map('sanitize_text_field', $value);
                    } else {
                        $value = sanitize_text_field($value);
                    }

                    // Verify if the value is valid
                    if (empty($value)) {
                        throw new Exception('The value for field ' . $field_id . ' is empty or invalid.');
                    }

                    // Verify if the meta field already exists
                    $current_value = get_post_meta($post_id, $field_id, true);
                    if ($current_value === $value) {
                        error_log('The value for field ' . $field_id . ' is already up to date.');
                    } else {
                        if (!update_post_meta($post_id, $field_id, $value)) {
                            throw new Exception('Failed to update post meta for field: ' . $field_id);
                        }
                    }
                } else {
                    if (metadata_exists('post', $post_id, $field_id)) {
                        if (!delete_post_meta($post_id, $field_id)) {
                            throw new Exception('Failed to delete post meta for field: ' . $field_id);
                        }
                    }
                }
            } catch (Exception $e) {
                error_log('Error: ' . $e->getMessage());
                add_settings_error(
                    'project_meta_box_errors',
                    esc_attr('settings_updated'),
                    $e->getMessage(),
                    'error'
                );
            }
        }

        // Display errors on the admin screen
        settings_errors('project_meta_box_errors');
    }
}

// Initialize the class as a singleton
ProjectPostType::get_instance();