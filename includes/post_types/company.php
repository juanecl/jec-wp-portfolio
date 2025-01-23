<?php

require_once plugin_dir_path(__FILE__) . '../partials/abstract-meta-box.php';

class CompanyPostType extends AbstractMetaBox {
    private static $instance = null;

    private function __construct() {
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
            'name' => _x('Companies', 'Post Type General Name', 'text_domain'),
            'singular_name' => _x('Company', 'Post Type Singular Name', 'text_domain'),
            'menu_name' => __('Companies', 'text_domain'),
            'name_admin_bar' => __('Company', 'text_domain'),
            'archives' => __('Company Archives', 'text_domain'),
            'attributes' => __('Company Attributes', 'text_domain'),
            'parent_item_colon' => __('Parent Company:', 'text_domain'),
            'all_items' => __('All Companies', 'text_domain'),
            'add_new_item' => __('Add New Company', 'text_domain'),
            'add_new' => __('Add New', 'text_domain'),
            'new_item' => __('New Company', 'text_domain'),
            'edit_item' => __('Edit Company', 'text_domain'),
            'update_item' => __('Update Company', 'text_domain'),
            'view_item' => __('View Company', 'text_domain'),
            'view_items' => __('View Companies', 'text_domain'),
            'search_items' => __('Search Company', 'text_domain'),
            'not_found' => __('Not found', 'text_domain'),
            'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
            'featured_image' => __('Featured Image', 'text_domain'),
            'set_featured_image' => __('Set featured image', 'text_domain'),
            'remove_featured_image' => __('Remove featured image', 'text_domain'),
            'use_featured_image' => __('Use as featured image', 'text_domain'),
            'insert_into_item' => __('Insert into company', 'text_domain'),
            'uploaded_to_this_item' => __('Uploaded to this company', 'text_domain'),
            'items_list' => __('Companies list', 'text_domain'),
            'items_list_navigation' => __('Companies list navigation', 'text_domain'),
            'filter_items_list' => __('Filter companies list', 'text_domain'),
        ];

        $args = [
            'label' => __('Company', 'text_domain'),
            'description' => __('Description of the Company post type', 'text_domain'),
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
        add_meta_box('company_url', __('Company URL', 'text_domain'), [$this, 'render_company_url_meta_box'], 'company', 'normal', 'high');
        add_meta_box('company_category', __('Category', 'text_domain'), [$this, 'render_category_meta_box'], 'company', 'normal', 'high');
    }

    public function render_company_url_meta_box($post) {
        wp_nonce_field('save_company_fields_nonce', 'company_fields_nonce');
        $this->render_url_meta_box($post, 'url', __('Company URL', 'text_domain'), __('Enter the URL of the company.', 'text_domain'));
    }

    public function render_category_meta_box($post) {
        wp_nonce_field('save_company_fields_nonce', 'company_fields_nonce');
        $this->render_text_meta_box($post, 'category', __('Category', 'text_domain'), __('Enter the category of the company.', 'text_domain'));
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

        // Guardar campos personalizados
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

// Inicializar la clase como singleton
CompanyPostType::get_instance();