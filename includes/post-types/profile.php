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
            'featured_image' => __('Featured Image', 'jec-portfolio'),
            'set_featured_image' => __('Set featured image', 'jec-portfolio'),
            'remove_featured_image' => __('Remove featured image', 'jec-portfolio'),
            'use_featured_image' => __('Use as featured image', 'jec-portfolio'),
            'insert_into_item' => __('Insert into profile', 'jec-portfolio'),
            'uploaded_to_this_item' => __('Uploaded to this profile', 'jec-portfolio'),
            'items_list' => __('Profiles list', 'jec-portfolio'),
            'items_list_navigation' => __('Profiles list navigation', 'jec-portfolio'),
            'filter_items_list' => __('Filter profiles list', 'jec-portfolio'),
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

    public function add_meta_boxes() {
        add_meta_box('profile_basic_info', __('Basic Information', 'jec-portfolio'), [$this, 'render_basic_info_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_cv_bio_es', __('Bio (ES)', 'jec-portfolio'), [$this, 'render_cv_bio_es_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_cv_bio_en', __('Bio (EN)', 'jec-portfolio'), [$this, 'render_cv_bio_en_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_social_urls', __('Social URLs', 'jec-portfolio'), [$this, 'render_social_urls_meta_box'], 'profile', 'normal', 'high');
    }

    public function render_basic_info_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('text', $post, 'name', __('Name', 'jec-portfolio'), __('Enter the name.', 'jec-portfolio'));
        $this->render_meta_box('date', $post, 'birthdate', __('Birthdate', 'jec-portfolio'), __('Enter the birthdate.', 'jec-portfolio'));
        $this->render_meta_box('text', $post, 'phone', __('Phone', 'jec-portfolio'), __('Enter the phone number.', 'jec-portfolio'));
        $this->render_meta_box('text', $post, 'location', __('Location', 'jec-portfolio'), __('Enter the location.', 'jec-portfolio'));
        $this->render_meta_box('text', $post, 'email', __('Email', 'jec-portfolio'), __('Enter the email address.', 'jec-portfolio'));
        $this->render_meta_box('text', $post, 'career', __('Career', 'jec-portfolio'), __('Enter the career.', 'jec-portfolio'));
    }

    public function render_cv_bio_es_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('file', $post, 'cv_es', __('CV ES', 'jec-portfolio'), __('Upload the CV in Spanish.', 'jec-portfolio'));
        $this->render_meta_box('textarea', $post, 'bio_es', __('Bio ES', 'jec-portfolio'), __('Enter the bio in Spanish.', 'jec-portfolio'));
    }

    public function render_cv_bio_en_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('file', $post, 'cv_en', __('CV EN', 'jec-portfolio'), __('Upload the CV in English.', 'jec-portfolio'));
        $this->render_meta_box('textarea', $post, 'bio_en', __('Bio EN', 'jec-portfolio'), __('Enter the bio in English.', 'jec-portfolio'));
    }

    public function render_social_urls_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('url', $post, 'git_url', __('Git URL', 'jec-portfolio'), __('Enter the Git URL.', 'jec-portfolio'));
        $this->render_meta_box('url', $post, 'linkedin_url', __('LinkedIn URL', 'jec-portfolio'), __('Enter the LinkedIn URL.', 'jec-portfolio'));
        $this->render_meta_box('url', $post, 'stackoverflow_url', __('StackOverflow URL', 'jec-portfolio'), __('Enter the StackOverflow URL.', 'jec-portfolio'));
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