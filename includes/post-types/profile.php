<?php

require_once plugin_dir_path(__FILE__) . '../classes/meta-box-renderer.php';

/**
 * Class ProfilePostType
 *
 * This class defines the custom post type "Profile" and handles its meta boxes and custom fields.
 */
class ProfilePostType extends AbstractMetaBoxRenderer {
    private static $instance = null;

    /**
     * Private constructor to ensure singleton pattern.
     */
    private function __construct() {
        parent::__construct();
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_fields']);
    }

    /**
     * Get the singleton instance of the class.
     *
     * @return ProfilePostType The singleton instance.
     */
    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new ProfilePostType();
        }
        return self::$instance;
    }

    /**
     * Register the custom post type "Profile".
     */
    public function register_post_type() {
        $labels = [
            'name' => _x('Profiles', 'Post Type General Name', 'jec-portfolio'),
            'singular_name' => _x('Profile', 'Post Type Singular Name', 'jec-portfolio'),
            'menu_name' => __('Profiles', 'jec-portfolio'),
            'name_admin_bar' => __('Profile', 'jec-portfolio'),
            'archives' => __('Profile Archives', 'jec-portfolio'),
            'attributes' => __('Profile Attributes', 'jec-portfolio'),
            'parent_item_colon' => __('Parent Profile:', 'jec-portfolio'),
            'all_items' => __('All Profiles', 'jec-portfolio'),
            'add_new_item' => __('Add New Profile', 'jec-portfolio'),
            'add_new' => __('Add New', 'jec-portfolio'),
            'new_item' => __('New Profile', 'jec-portfolio'),
            'edit_item' => __('Edit Profile', 'jec-portfolio'),
            'update_item' => __('Update Profile', 'jec-portfolio'),
            'view_item' => __('View Profile', 'jec-portfolio'),
            'view_items' => __('View Profiles', 'jec-portfolio'),
            'search_items' => __('Search Profile', 'jec-portfolio'),
            'not_found' => __('Not found', 'jec-portfolio'),
            'not_found_in_trash' => __('Not found in Trash', 'jec-portfolio'),
        ];

        $args = [
            'label' => __('Profile', 'jec-portfolio'),
            'description' => __('Description of the Profile post type', 'jec-portfolio'),
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

        register_post_type('profile', $args);
    }

    /**
     * Add meta boxes for the "profile" post type.
     */
    public function add_meta_boxes() {
        add_meta_box('profile_description', __('Description', 'jec-portfolio'), [$this, 'render_description_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_company', __('Company', 'jec-portfolio'), [$this, 'render_company_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_location', __('Location', 'jec-portfolio'), [$this, 'render_location_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_projects', __('Projects', 'jec-portfolio'), [$this, 'render_projects_meta_box'], 'profile', 'side', 'default');
    }

    /**
     * Render the projects meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_projects_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('multiselect', $post, 'project_ids', __('Select Projects', 'jec-portfolio'), __('Select the projects associated with this profile.', 'jec-portfolio'), ['post_type' => 'project']);
    }

    /**
     * Render the description meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_description_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('textarea', $post, 'description', __('Description', 'jec-portfolio'), __('Enter the description of the profile.', 'jec-portfolio'));
    }

    /**
     * Render the company meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_company_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('select', $post, 'company_id', __('Select Company', 'jec-portfolio'), __('Select the company associated with this profile.', 'jec-portfolio'), ['post_type' => 'company']);
    }

    /**
     * Render the location meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_location_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('text', $post, 'location', __('Location', 'jec-portfolio'), __('Enter the location for this profile.', 'jec-portfolio'));
    }

    /**
     * Save custom fields for the "profile" post type.
     *
     * @param int $post_id The ID of the current post.
     */
    public function save_custom_fields($post_id) {
        // Verify nonce.
        if (!isset($_POST['profile_fields_nonce']) || !wp_verify_nonce($_POST['profile_fields_nonce'], 'save_profile_fields_nonce')) {
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
            if (isset($_POST[$field_id])) {
                $value = $_POST[$field_id];
                if (is_array($value)) {
                    $value = array_map('sanitize_text_field', $value);
                } else {
                    $value = sanitize_text_field($value);
                }
                update_post_meta($post_id, $field_id, $value);
            } else {
                delete_post_meta($post_id, $field_id);
            }
        }
    }
}

// Initialize the class as a singleton
ProfilePostType::get_instance();