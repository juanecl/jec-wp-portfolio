<?php

require_once plugin_dir_path(__FILE__) . '../partials/abstract-meta-box.php';

class ProfilePostType extends AbstractMetaBox {
    private static $instance = null;

    private function __construct() {
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
            'name' => _x('Profiles', 'Post Type General Name', 'text_domain'),
            'singular_name' => _x('Profile', 'Post Type Singular Name', 'text_domain'),
            'menu_name' => __('Profiles', 'text_domain'),
            'name_admin_bar' => __('Profile', 'text_domain'),
            'archives' => __('Profile Archives', 'text_domain'),
            'attributes' => __('Profile Attributes', 'text_domain'),
            'parent_item_colon' => __('Parent Profile:', 'text_domain'),
            'all_items' => __('All Profiles', 'text_domain'),
            'add_new_item' => __('Add New Profile', 'text_domain'),
            'add_new' => __('Add New', 'text_domain'),
            'new_item' => __('New Profile', 'text_domain'),
            'edit_item' => __('Edit Profile', 'text_domain'),
            'update_item' => __('Update Profile', 'text_domain'),
            'view_item' => __('View Profile', 'text_domain'),
            'view_items' => __('View Profiles', 'text_domain'),
            'search_items' => __('Search Profile', 'text_domain'),
            'not_found' => __('Not found', 'text_domain'),
            'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
            'featured_image' => __('Featured Image', 'text_domain'),
            'set_featured_image' => __('Set featured image', 'text_domain'),
            'remove_featured_image' => __('Remove featured image', 'text_domain'),
            'use_featured_image' => __('Use as featured image', 'text_domain'),
            'insert_into_item' => __('Insert into profile', 'text_domain'),
            'uploaded_to_this_item' => __('Uploaded to this profile', 'text_domain'),
            'items_list' => __('Profiles list', 'text_domain'),
            'items_list_navigation' => __('Profiles list navigation', 'text_domain'),
            'filter_items_list' => __('Filter profiles list', 'text_domain'),
        ];

        $args = [
            'label' => __('Profile', 'text_domain'),
            'description' => __('Description of the Profile post type', 'text_domain'),
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
        add_meta_box('profile_basic_info', __('Basic Information', 'text_domain'), [$this, 'render_basic_info_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_cv_bio_es', __('Bio (ES)', 'text_domain'), [$this, 'render_cv_bio_es_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_cv_bio_en', __('Bio (EN)', 'text_domain'), [$this, 'render_cv_bio_en_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_social_urls', __('Social URLs', 'text_domain'), [$this, 'render_social_urls_meta_box'], 'profile', 'normal', 'high');
    }

    public function render_basic_info_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_text_meta_box($post, 'name', __('Name', 'text_domain'), __('Enter the name.', 'text_domain'));
        $this->render_date_meta_box($post, 'birthdate', __('Birthdate', 'text_domain'), __('Enter the birthdate.', 'text_domain'));
        $this->render_text_meta_box($post, 'phone', __('Phone', 'text_domain'), __('Enter the phone number.', 'text_domain'));
        $this->render_text_meta_box($post, 'location', __('Location', 'text_domain'), __('Enter the location.', 'text_domain'));
        $this->render_text_meta_box($post, 'email', __('Email', 'text_domain'), __('Enter the email address.', 'text_domain'));
        $this->render_text_meta_box($post, 'career', __('Career', 'text_domain'), __('Enter the career.', 'text_domain'));
    }

    public function render_cv_bio_es_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_file_meta_box($post, 'cv_es', __('CV ES', 'text_domain'), __('Upload the CV in Spanish.', 'text_domain'));
        $this->render_textarea_meta_box($post, 'bio_es', __('Bio ES', 'text_domain'), __('Enter the bio in Spanish.', 'text_domain'));
    }

    public function render_cv_bio_en_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_file_meta_box($post, 'cv_en', __('CV EN', 'text_domain'), __('Upload the CV in English.', 'text_domain'));
        $this->render_textarea_meta_box($post, 'bio_en', __('Bio EN', 'text_domain'), __('Enter the bio in English.', 'text_domain'));
    }

    public function render_social_urls_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_url_meta_box($post, 'git_url', __('Git URL', 'text_domain'), __('Enter the Git URL.', 'text_domain'));
        $this->render_url_meta_box($post, 'linkedin_url', __('LinkedIn URL', 'text_domain'), __('Enter the LinkedIn URL.', 'text_domain'));
        $this->render_url_meta_box($post, 'stackoverflow_url', __('StackOverflow URL', 'text_domain'), __('Enter the StackOverflow URL.', 'text_domain'));
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

        // Guardar campos personalizados
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

// Inicializar la clase como singleton
ProfilePostType::get_instance();