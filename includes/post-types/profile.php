<?php

require_once plugin_dir_path(__FILE__) . '../classes/meta-box-renderer.php';

class ProfilePostType extends AbstractMetaBoxRenderer {
    private static $instance = null;

    private function __construct() {
        parent::__construct();
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_fields']);
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new ProfilePostType();
        }
        return self::$instance;
    }

    public function register_post_type() {
        $labels = [
            'name' => _x('Profiles', 'Post Type General Name', 'portfolio-plugin'),
            'singular_name' => _x('Profile', 'Post Type Singular Name', 'portfolio-plugin'),
            'menu_name' => __('Profiles', 'portfolio-plugin'),
            'name_admin_bar' => __('Profile', 'portfolio-plugin'),
            'archives' => __('Profile Archives', 'portfolio-plugin'),
            'attributes' => __('Profile Attributes', 'portfolio-plugin'),
            'parent_item_colon' => __('Parent Profile:', 'portfolio-plugin'),
            'all_items' => __('All Profiles', 'portfolio-plugin'),
            'add_new_item' => __('Add New Profile', 'portfolio-plugin'),
            'add_new' => __('Add New', 'portfolio-plugin'),
            'new_item' => __('New Profile', 'portfolio-plugin'),
            'edit_item' => __('Edit Profile', 'portfolio-plugin'),
            'update_item' => __('Update Profile', 'portfolio-plugin'),
            'view_item' => __('View Profile', 'portfolio-plugin'),
            'view_items' => __('View Profiles', 'portfolio-plugin'),
            'search_items' => __('Search Profile', 'portfolio-plugin'),
            'not_found' => __('Not found', 'portfolio-plugin'),
            'not_found_in_trash' => __('Not found in Trash', 'portfolio-plugin'),
            'featured_image' => __('Featured Image', 'portfolio-plugin'),
            'set_featured_image' => __('Set featured image', 'portfolio-plugin'),
            'remove_featured_image' => __('Remove featured image', 'portfolio-plugin'),
            'use_featured_image' => __('Use as featured image', 'portfolio-plugin'),
            'insert_into_item' => __('Insert into profile', 'portfolio-plugin'),
            'uploaded_to_this_item' => __('Uploaded to this profile', 'portfolio-plugin'),
            'items_list' => __('Profiles list', 'portfolio-plugin'),
            'items_list_navigation' => __('Profiles list navigation', 'portfolio-plugin'),
            'filter_items_list' => __('Filter profiles list', 'portfolio-plugin'),
        ];

        $args = [
            'label' => __('Profile', 'portfolio-plugin'),
            'description' => __('Description of the Profile post type', 'portfolio-plugin'),
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

    public function add_meta_boxes() {
        add_meta_box('profile_basic_info', __('Basic Information', 'portfolio-plugin'), [$this, 'render_basic_info_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_cv_bio_es', __('Bio (ES)', 'portfolio-plugin'), [$this, 'render_cv_bio_es_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_cv_bio_en', __('Bio (EN)', 'portfolio-plugin'), [$this, 'render_cv_bio_en_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_social_urls', __('Social URLs', 'portfolio-plugin'), [$this, 'render_social_urls_meta_box'], 'profile', 'normal', 'high');
    }

    public function render_basic_info_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('text', $post, 'name', __('Name', 'portfolio-plugin'), __('Enter the name.', 'portfolio-plugin'));
        $this->render_meta_box('date', $post, 'birthdate', __('Birthdate', 'portfolio-plugin'), __('Enter the birthdate.', 'portfolio-plugin'));
        $this->render_meta_box('text', $post, 'phone', __('Phone', 'portfolio-plugin'), __('Enter the phone number.', 'portfolio-plugin'));
        $this->render_meta_box('text', $post, 'location', __('Location', 'portfolio-plugin'), __('Enter the location.', 'portfolio-plugin'));
        $this->render_meta_box('text', $post, 'email', __('Email', 'portfolio-plugin'), __('Enter the email address.', 'portfolio-plugin'));
        $this->render_meta_box('text', $post, 'career', __('Career', 'portfolio-plugin'), __('Enter the career.', 'portfolio-plugin'));
    }

    public function render_cv_bio_es_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('file', $post, 'cv_es', __('CV ES', 'portfolio-plugin'), __('Upload the CV in Spanish.', 'portfolio-plugin'));
        $this->render_meta_box('textarea', $post, 'bio_es', __('Bio ES', 'portfolio-plugin'), __('Enter the bio in Spanish.', 'portfolio-plugin'));
    }

    public function render_cv_bio_en_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('file', $post, 'cv_en', __('CV EN', 'portfolio-plugin'), __('Upload the CV in English.', 'portfolio-plugin'));
        $this->render_meta_box('textarea', $post, 'bio_en', __('Bio EN', 'portfolio-plugin'), __('Enter the bio in English.', 'portfolio-plugin'));
    }

    public function render_social_urls_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('url', $post, 'git_url', __('Git URL', 'portfolio-plugin'), __('Enter the Git URL.', 'portfolio-plugin'));
        $this->render_meta_box('url', $post, 'linkedin_url', __('LinkedIn URL', 'portfolio-plugin'), __('Enter the LinkedIn URL.', 'portfolio-plugin'));
        $this->render_meta_box('url', $post, 'stackoverflow_url', __('StackOverflow URL', 'portfolio-plugin'), __('Enter the StackOverflow URL.', 'portfolio-plugin'));
    }

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
        $fields = ['name', 'birthdate', 'phone', 'location', 'email', 'career', 'cv_es', 'bio_es', 'cv_en', 'bio_en', 'git_url', 'linkedin_url', 'stackoverflow_url'];
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
ProfilePostType::get_instance();