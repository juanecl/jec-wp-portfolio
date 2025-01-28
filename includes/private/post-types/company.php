<?php

require_once plugin_dir_path(__FILE__) . '../classes/meta-box-renderer.php';

/**
 * Class CompanyPostType
 * 
 * This class handles the registration and management of the 'company' custom post type.
 */
class CompanyPostType extends AbstractMetaBoxRenderer {
    private static $instance = null;

    /**
     * Constructor
     * 
     * Initializes the custom post type by setting up actions and filters.
     */
    private function __construct() {
        parent::__construct();
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_fields']);
        add_filter('use_block_editor_for_post_type', [$this, 'disable_block_editor'], 10, 2);
    }

    /**
     * Get instance
     * 
     * Ensures only one instance of the class is loaded or can be loaded.
     * 
     * @return CompanyPostType|null
     */
    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new CompanyPostType();
        }
        return self::$instance;
    }

    /**
     * Disable block editor
     * 
     * Disables the block editor for the 'company' post type.
     * 
     * @param bool $use_block_editor
     * @param string $post_type
     * @return bool
     */
    public function disable_block_editor($use_block_editor, $post_type) {
        if ($post_type === 'company') {
            return false;
        }
        return $use_block_editor;
    }

    /**
     * Register post type
     * 
     * Registers the 'company' custom post type with various settings and labels.
     */
    public function register_post_type() {
        $labels = [
            'name' => _x('Companies', 'Post Type General Name', PLUGIN_TEXT_DOMAIN),
            'singular_name' => _x('Company', 'Post Type Singular Name', PLUGIN_TEXT_DOMAIN),
            'menu_name' => __('Companies', PLUGIN_TEXT_DOMAIN),
            'name_admin_bar' => __('Company', PLUGIN_TEXT_DOMAIN),
            'archives' => __('Company Archives', PLUGIN_TEXT_DOMAIN),
            'attributes' => __('Company Attributes', PLUGIN_TEXT_DOMAIN),
            'parent_item_colon' => __('Parent Company:', PLUGIN_TEXT_DOMAIN),
            'all_items' => __('All Companies', PLUGIN_TEXT_DOMAIN),
            'add_new_item' => __('Add New Company', PLUGIN_TEXT_DOMAIN),
            'add_new' => __('Add New', PLUGIN_TEXT_DOMAIN),
            'new_item' => __('New Company', PLUGIN_TEXT_DOMAIN),
            'edit_item' => __('Edit Company', PLUGIN_TEXT_DOMAIN),
            'update_item' => __('Update Company', PLUGIN_TEXT_DOMAIN),
            'view_item' => __('View Company', PLUGIN_TEXT_DOMAIN),
            'view_items' => __('View Companies', PLUGIN_TEXT_DOMAIN),
            'search_items' => __('Search Company', PLUGIN_TEXT_DOMAIN),
            'not_found' => __('Not found', PLUGIN_TEXT_DOMAIN),
            'not_found_in_trash' => __('Not found in Trash', PLUGIN_TEXT_DOMAIN),
            'featured_image' => __('Featured Image', PLUGIN_TEXT_DOMAIN),
            'set_featured_image' => __('Set featured image', PLUGIN_TEXT_DOMAIN),
            'remove_featured_image' => __('Remove featured image', PLUGIN_TEXT_DOMAIN),
            'use_featured_image' => __('Use as featured image', PLUGIN_TEXT_DOMAIN),
            'insert_into_item' => __('Insert into company', PLUGIN_TEXT_DOMAIN),
            'uploaded_to_this_item' => __('Uploaded to this company', PLUGIN_TEXT_DOMAIN),
            'items_list' => __('Companies list', PLUGIN_TEXT_DOMAIN),
            'items_list_navigation' => __('Companies list navigation', PLUGIN_TEXT_DOMAIN),
            'filter_items_list' => __('Filter companies list', PLUGIN_TEXT_DOMAIN),
        ];

        $args = [
            'label' => __('Company', PLUGIN_TEXT_DOMAIN),
            'description' => __('Description of the Company post type', PLUGIN_TEXT_DOMAIN),
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

        register_post_type('company', $args);
    }

    /**
     * Add meta boxes
     * 
     * Adds custom meta boxes to the 'company' post type.
     */
    public function add_meta_boxes() {
        add_meta_box('company_url', __('Company URL', PLUGIN_TEXT_DOMAIN), [$this, 'render_company_url_meta_box'], 'company', 'normal', 'high');
        add_meta_box('company_category', __('Category', PLUGIN_TEXT_DOMAIN), [$this, 'render_category_meta_box'], 'company', 'normal', 'high');
    }

    /**
     * Render company URL meta box
     * 
     * Renders the meta box for the company URL.
     * 
     * @param WP_Post $post The post object.
     */
    public function render_company_url_meta_box($post) {
        wp_nonce_field('save_company_fields_nonce', 'company_fields_nonce');
        $this->render_meta_box('url', $post, 'url', __('Company URL', PLUGIN_TEXT_DOMAIN), __('Enter the URL of the company.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Render category meta box
     * 
     * Renders the meta box for the company category.
     * 
     * @param WP_Post $post The post object.
     */
    public function render_category_meta_box($post) {
        wp_nonce_field('save_company_fields_nonce', 'company_fields_nonce');
        $this->render_meta_box('text', $post, 'category', __('Category', PLUGIN_TEXT_DOMAIN), __('Enter the category of the company.', PLUGIN_TEXT_DOMAIN));
    }

    /**
     * Save custom fields
     * 
     * Saves the custom fields for the 'company' post type.
     * 
     * @param int $post_id The ID of the post being saved.
     */
    public function save_custom_fields($post_id) {
        $fields = ['url', 'category'];
        save_custom_meta_fields($post_id, $fields, 'company_fields_nonce', 'save_company_fields_nonce');
    }
}

// Initialize the class as a singleton
CompanyPostType::get_instance();