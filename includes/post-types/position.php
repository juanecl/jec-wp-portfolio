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
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_fields']);
        add_filter('use_block_editor_for_post_type', [$this, 'disable_block_editor'], 10, 2);
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
            'name' => _x('Positions', 'Post Type General Name', 'jec-portfolio'),
            'singular_name' => _x('Position', 'Post Type Singular Name', 'jec-portfolio'),
            'menu_name' => __('Positions', 'jec-portfolio'),
            'name_admin_bar' => __('Position', 'jec-portfolio'),
            'archives' => __('Position Archives', 'jec-portfolio'),
            'attributes' => __('Position Attributes', 'jec-portfolio'),
            'parent_item_colon' => __('Parent Position:', 'jec-portfolio'),
            'all_items' => __('All Positions', 'jec-portfolio'),
            'add_new_item' => __('Add New Position', 'jec-portfolio'),
            'add_new' => __('Add New', 'jec-portfolio'),
            'new_item' => __('New Position', 'jec-portfolio'),
            'edit_item' => __('Edit Position', 'jec-portfolio'),
            'update_item' => __('Update Position', 'jec-portfolio'),
            'view_item' => __('View Position', 'jec-portfolio'),
            'view_items' => __('View Positions', 'jec-portfolio'),
            'search_items' => __('Search Position', 'jec-portfolio'),
            'not_found' => __('Not found', 'jec-portfolio'),
            'not_found_in_trash' => __('Not found in Trash', 'jec-portfolio'),
            'featured_image' => __('Featured Image', 'jec-portfolio'),
            'set_featured_image' => __('Set featured image', 'jec-portfolio'),
            'remove_featured_image' => __('Remove featured image', 'jec-portfolio'),
            'use_featured_image' => __('Use as featured image', 'jec-portfolio'),
            'insert_into_item' => __('Insert into position', 'jec-portfolio'),
            'uploaded_to_this_item' => __('Uploaded to this position', 'jec-portfolio'),
            'items_list' => __('Positions list', 'jec-portfolio'),
            'items_list_navigation' => __('Positions list navigation', 'jec-portfolio'),
            'filter_items_list' => __('Filter positions list', 'jec-portfolio'),
        ];

        $args = [
            'label' => __('Position', 'jec-portfolio'),
            'description' => __('Description of the Position post type', 'jec-portfolio'),
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

        register_post_type('position', $args);
    }

    /**
     * Add meta boxes for the "position" post type.
     */
    public function add_meta_boxes() {
        add_meta_box('position_description', __('Description', 'jec-portfolio'), [$this, 'render_description_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_company', __('Company', 'jec-portfolio'), [$this, 'render_company_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_location', __('Location', 'jec-portfolio'), [$this, 'render_location_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_projects', __('Projects', 'jec-portfolio'), [$this, 'render_projects_meta_box'], 'position', 'side', 'default');
    }

    /**
     * Render the projects meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_projects_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('multiselect', $post, 'project_ids', __('Select Projects', 'jec-portfolio'), __('Select the projects associated with this position.', 'jec-portfolio'), ['post_type' => 'project']);
    }

    /**
     * Render the description meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_description_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('textarea', $post, 'description', __('Description', 'jec-portfolio'), __('Enter the description of the position.', 'jec-portfolio'));
    }

    /**
     * Render the company meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_company_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('select', $post, 'company_id', __('Select Company', 'jec-portfolio'), __('Select the company associated with this position.', 'jec-portfolio'), ['post_type' => 'company']);
    }

    /**
     * Render the location meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_location_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('text', $post, 'location', __('Location', 'jec-portfolio'), __('Enter the location for this position.', 'jec-portfolio'));
    }

    /**
     * Save custom fields for the "position" post type.
     *
     * @param int $post_id The ID of the current post.
     */
    public function save_custom_fields($post_id) {
        // Verify nonce.
        if (!isset($_POST['position_fields_nonce']) || !wp_verify_nonce($_POST['position_fields_nonce'], 'save_position_fields_nonce')) {
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
        $fields = ['description', 'company_id', 'location', 'project_ids'];
        foreach ($fields as $field) {
            $field_id = 'wpcf-' . $field;
            try {
                if (isset($_POST[$field_id])) {
                    $value = $_POST[$field_id];
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
                    if (!delete_post_meta($post_id, $field_id)) {
                        throw new Exception('Failed to delete post meta for field: ' . $field_id);
                    }
                }
            } catch (Exception $e) {
                error_log('Error: ' . $e->getMessage());
                add_settings_error(
                    'position_meta_box_errors',
                    esc_attr('settings_updated'),
                    $e->getMessage(),
                    'error'
                );
            }
        }

        // Display errors on the admin screen
        settings_errors('position_meta_box_errors');
    }
}

// Initialize the class as a singleton
PositionPostType::get_instance();