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
        add_filter('use_block_editor_for_post_type', [$this, 'disable_block_editor'], 10, 2);
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
     * Disable the block editor for the "profile" post type.
     *
     * @param bool $use_block_editor Whether to use the block editor.
     * @param string $post_type The post type.
     * @return bool Whether to use the block editor.
     */
    public function disable_block_editor($use_block_editor, $post_type) {
        if ($post_type === 'profile') {
            return false;
        }
        return $use_block_editor;
    }

    /**
     * Register the custom post type "Profile".
     */
    public function register_post_type() {
        $labels = [
            'name' => _x('Profiles', 'Post Type General Name', PLUGIN_TEXT_DOMAIN),
            'singular_name' => _x('Profile', 'Post Type Singular Name', PLUGIN_TEXT_DOMAIN),
            'menu_name' => __('Profiles', PLUGIN_TEXT_DOMAIN),
            'name_admin_bar' => __('Profile', PLUGIN_TEXT_DOMAIN),
            'archives' => __('Profile Archives', PLUGIN_TEXT_DOMAIN),
            'attributes' => __('Profile Attributes', PLUGIN_TEXT_DOMAIN),
            'parent_item_colon' => __('Parent Profile:', PLUGIN_TEXT_DOMAIN),
            'all_items' => __('All Profiles', PLUGIN_TEXT_DOMAIN),
            'add_new_item' => __('Add New Profile', PLUGIN_TEXT_DOMAIN),
            'add_new' => __('Add New', PLUGIN_TEXT_DOMAIN),
            'new_item' => __('New Profile', PLUGIN_TEXT_DOMAIN),
            'edit_item' => __('Edit Profile', PLUGIN_TEXT_DOMAIN),
            'update_item' => __('Update Profile', PLUGIN_TEXT_DOMAIN),
            'view_item' => __('View Profile', PLUGIN_TEXT_DOMAIN),
            'view_items' => __('View Profiles', PLUGIN_TEXT_DOMAIN),
            'search_items' => __('Search Profile', PLUGIN_TEXT_DOMAIN),
            'not_found' => __('Not found', PLUGIN_TEXT_DOMAIN),
            'not_found_in_trash' => __('Not found in Trash', PLUGIN_TEXT_DOMAIN),
            'featured_image' => __('Featured Image', PLUGIN_TEXT_DOMAIN),
            'set_featured_image' => __('Set featured image', PLUGIN_TEXT_DOMAIN),
            'remove_featured_image' => __('Remove featured image', PLUGIN_TEXT_DOMAIN),
            'use_featured_image' => __('Use as featured image', PLUGIN_TEXT_DOMAIN),
            'insert_into_item' => __('Insert into profile', PLUGIN_TEXT_DOMAIN),
            'uploaded_to_this_item' => __('Uploaded to this profile', PLUGIN_TEXT_DOMAIN),
            'items_list' => __('Profiles list', PLUGIN_TEXT_DOMAIN),
            'items_list_navigation' => __('Profiles list navigation', PLUGIN_TEXT_DOMAIN),
            'filter_items_list' => __('Filter profiles list', PLUGIN_TEXT_DOMAIN),
        ];

        $args = [
            'label' => __('Profile', PLUGIN_TEXT_DOMAIN),
            'description' => __('Description of the Profile post type', PLUGIN_TEXT_DOMAIN),
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
        add_meta_box('profile_basic_info', __('Basic Information', PLUGIN_TEXT_DOMAIN), [$this, 'render_basic_info_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_cv_bio_es', __('Bio (ES)', PLUGIN_TEXT_DOMAIN), [$this, 'render_cv_bio_es_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_cv_bio_en', __('Bio (EN)', PLUGIN_TEXT_DOMAIN), [$this, 'render_cv_bio_en_meta_box'], 'profile', 'normal', 'high');
        add_meta_box('profile_social_urls', __('Social URLs', PLUGIN_TEXT_DOMAIN), [$this, 'render_social_urls_meta_box'], 'profile', 'normal', 'high');
    }

    /**
     * Render the basic information meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_basic_info_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('text', $post, 'name', __('Name', PLUGIN_TEXT_DOMAIN), __('Enter the name.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('date', $post, 'birthdate', __('Birthdate', PLUGIN_TEXT_DOMAIN), __('Enter the birthdate.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('text', $post, 'phone', __('Phone', PLUGIN_TEXT_DOMAIN), __('Enter the phone number.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('text', $post, 'location', __('Location', PLUGIN_TEXT_DOMAIN), __('Enter the location.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('text', $post, 'email', __('Email', PLUGIN_TEXT_DOMAIN), __('Enter the email address.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('text', $post, 'career', __('Career', PLUGIN_TEXT_DOMAIN), __('Enter the career.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Render the CV and bio (ES) meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_cv_bio_es_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('file', $post, 'cv_es', __('CV ES', PLUGIN_TEXT_DOMAIN), __('Upload the CV in Spanish.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('textarea', $post, 'bio_es', __('Bio ES', PLUGIN_TEXT_DOMAIN), __('Enter the bio in Spanish.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Render the CV and bio (EN) meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_cv_bio_en_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('file', $post, 'cv_en', __('CV EN', PLUGIN_TEXT_DOMAIN), __('Upload the CV in English.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('textarea', $post, 'bio_en', __('Bio EN', PLUGIN_TEXT_DOMAIN), __('Enter the bio in English.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Render the social URLs meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_social_urls_meta_box($post) {
        wp_nonce_field('save_profile_fields_nonce', 'profile_fields_nonce');
        $this->render_meta_box('url', $post, 'git_url', __('Git URL', PLUGIN_TEXT_DOMAIN), __('Enter the Git URL.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('url', $post, 'linkedin_url', __('LinkedIn URL', PLUGIN_TEXT_DOMAIN), __('Enter the LinkedIn URL.', PLUGIN_TEXT_DOMAIN));
        $this->render_meta_box('url', $post, 'stackoverflow_url', __('StackOverflow URL', PLUGIN_TEXT_DOMAIN), __('Enter the StackOverflow URL.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Save custom fields for the "profile" post type.
     *
     * @param int $post_id The ID of the current post.
     */
    public function save_custom_fields($post_id) {
        $fields = [
            'name', 
            'birthdate', 
            'phone', 
            'location', 
            'email', 
            'career', 
            'cv_es', 
            ['bio_es', true], // enriched text
            'cv_en', 
            ['bio_en', true], // enriched text
            'git_url', 
            'linkedin_url', 
            'stackoverflow_url'
        ];
        save_custom_meta_fields($post_id, $fields, 'profile_fields_nonce', 'save_profile_fields_nonce');
    }
}

// Initialize the class as a singleton
ProfilePostType::get_instance();