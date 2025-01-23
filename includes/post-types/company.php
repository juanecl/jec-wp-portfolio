<?php

require_once plugin_dir_path(__FILE__) . '../classes/meta-box-renderer.php';

class CompanyPostType extends AbstractMetaBoxRenderer {
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
            self::$instance = new CompanyPostType();
        }
        return self::$instance;
    }

    public function disable_block_editor($use_block_editor, $post_type) {
        if ($post_type === 'company') {
            return false;
        }
        return $use_block_editor;
    }

    public function register_post_type() {
        $labels = [
            'name' => _x('Companies', 'Post Type General Name', 'portfolio-plugin'),
            'singular_name' => _x('Company', 'Post Type Singular Name', 'portfolio-plugin'),
            'menu_name' => __('Companies', 'portfolio-plugin'),
            'name_admin_bar' => __('Company', 'portfolio-plugin'),
            'archives' => __('Company Archives', 'portfolio-plugin'),
            'attributes' => __('Company Attributes', 'portfolio-plugin'),
            'parent_item_colon' => __('Parent Company:', 'portfolio-plugin'),
            'all_items' => __('All Companies', 'portfolio-plugin'),
            'add_new_item' => __('Add New Company', 'portfolio-plugin'),
            'add_new' => __('Add New', 'portfolio-plugin'),
            'new_item' => __('New Company', 'portfolio-plugin'),
            'edit_item' => __('Edit Company', 'portfolio-plugin'),
            'update_item' => __('Update Company', 'portfolio-plugin'),
            'view_item' => __('View Company', 'portfolio-plugin'),
            'view_items' => __('View Companies', 'portfolio-plugin'),
            'search_items' => __('Search Company', 'portfolio-plugin'),
            'not_found' => __('Not found', 'portfolio-plugin'),
            'not_found_in_trash' => __('Not found in Trash', 'portfolio-plugin'),
            'featured_image' => __('Featured Image', 'portfolio-plugin'),
            'set_featured_image' => __('Set featured image', 'portfolio-plugin'),
            'remove_featured_image' => __('Remove featured image', 'portfolio-plugin'),
            'use_featured_image' => __('Use as featured image', 'portfolio-plugin'),
            'insert_into_item' => __('Insert into company', 'portfolio-plugin'),
            'uploaded_to_this_item' => __('Uploaded to this company', 'portfolio-plugin'),
            'items_list' => __('Companies list', 'portfolio-plugin'),
            'items_list_navigation' => __('Companies list navigation', 'portfolio-plugin'),
            'filter_items_list' => __('Filter companies list', 'portfolio-plugin'),
        ];

        $args = [
            'label' => __('Company', 'portfolio-plugin'),
            'description' => __('Description of the Company post type', 'portfolio-plugin'),
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

    public function add_meta_boxes() {
        add_meta_box('company_url', __('Company URL', 'portfolio-plugin'), [$this, 'render_company_url_meta_box'], 'company', 'normal', 'high');
        add_meta_box('company_category', __('Category', 'portfolio-plugin'), [$this, 'render_category_meta_box'], 'company', 'normal', 'high');
    }

    public function render_company_url_meta_box($post) {
        wp_nonce_field('save_company_fields_nonce', 'company_fields_nonce');
        $this->render_meta_box('url', $post, 'url', __('Company URL', 'portfolio-plugin'), __('Enter the URL of the company.', 'portfolio-plugin'));
    }

    public function render_category_meta_box($post) {
        wp_nonce_field('save_company_fields_nonce', 'company_fields_nonce');
        $this->render_meta_box('text', $post, 'category', __('Category', 'portfolio-plugin'), __('Enter the category of the company.', 'portfolio-plugin'));
    }

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