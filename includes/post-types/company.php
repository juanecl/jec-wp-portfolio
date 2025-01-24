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
            'name' => _x('Companies', 'Post Type General Name', 'jec-portfolio'),
            'singular_name' => _x('Company', 'Post Type Singular Name', 'jec-portfolio'),
            'menu_name' => __('Companies', 'jec-portfolio'),
            'name_admin_bar' => __('Company', 'jec-portfolio'),
            'archives' => __('Company Archives', 'jec-portfolio'),
            'attributes' => __('Company Attributes', 'jec-portfolio'),
            'parent_item_colon' => __('Parent Company:', 'jec-portfolio'),
            'all_items' => __('All Companies', 'jec-portfolio'),
            'add_new_item' => __('Add New Company', 'jec-portfolio'),
            'add_new' => __('Add New', 'jec-portfolio'),
            'new_item' => __('New Company', 'jec-portfolio'),
            'edit_item' => __('Edit Company', 'jec-portfolio'),
            'update_item' => __('Update Company', 'jec-portfolio'),
            'view_item' => __('View Company', 'jec-portfolio'),
            'view_items' => __('View Companies', 'jec-portfolio'),
            'search_items' => __('Search Company', 'jec-portfolio'),
            'not_found' => __('Not found', 'jec-portfolio'),
            'not_found_in_trash' => __('Not found in Trash', 'jec-portfolio'),
            'featured_image' => __('Featured Image', 'jec-portfolio'),
            'set_featured_image' => __('Set featured image', 'jec-portfolio'),
            'remove_featured_image' => __('Remove featured image', 'jec-portfolio'),
            'use_featured_image' => __('Use as featured image', 'jec-portfolio'),
            'insert_into_item' => __('Insert into company', 'jec-portfolio'),
            'uploaded_to_this_item' => __('Uploaded to this company', 'jec-portfolio'),
            'items_list' => __('Companies list', 'jec-portfolio'),
            'items_list_navigation' => __('Companies list navigation', 'jec-portfolio'),
            'filter_items_list' => __('Filter companies list', 'jec-portfolio'),
        ];

        $args = [
            'label' => __('Company', 'jec-portfolio'),
            'description' => __('Description of the Company post type', 'jec-portfolio'),
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
        add_meta_box('company_url', __('Company URL', 'jec-portfolio'), [$this, 'render_company_url_meta_box'], 'company', 'normal', 'high');
        add_meta_box('company_category', __('Category', 'jec-portfolio'), [$this, 'render_category_meta_box'], 'company', 'normal', 'high');
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
        $this->render_meta_box('url', $post, 'url', __('Company URL', 'jec-portfolio'), __('Enter the URL of the company.', 'jec-portfolio'));
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
        $this->render_meta_box('text', $post, 'category', __('Category', 'jec-portfolio'), __('Enter the category of the company.', 'jec-portfolio'));
    }

    /**
     * Save custom fields
     * 
     * Saves the custom fields for the 'company' post type.
     * 
     * @param int $post_id The ID of the post being saved.
     */
    public function save_custom_fields($post_id) {
        // Verify nonce.
        if (!isset($_POST['company_fields_nonce']) || !wp_verify_nonce($_POST['company_fields_nonce'], 'save_company_fields_nonce')) {
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
        $fields = ['url', 'category'];
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
CompanyPostType::get_instance();