<?php

require_once plugin_dir_path(__FILE__) . '../partials/abstract-meta-box.php';
class PositionPostType extends AbstractMetaBox {
    private static $instance = null;

    private function __construct() {
        add_action('init', [$this, 'register_taxonomies']);
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_fields']);
        add_filter('use_block_editor_for_post_type', [$this, 'disable_block_editor'], 10, 2);
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new PositionPostType();
        }
        return self::$instance;
    }

    public function disable_block_editor($use_block_editor, $post_type) {
        if ($post_type === 'position') {
            return false;
        }
        return $use_block_editor;
    }

    public function register_taxonomies() {
        $taxonomies = [
            'knowledge' => __('Knowledge', 'text_domain'),
            'skills' => __('Skills', 'text_domain')
        ];

        foreach ($taxonomies as $taxonomy => $label) {
            register_taxonomy($taxonomy, 'position', [
                'label' => $label,
                'rewrite' => ['slug' => $taxonomy],
                'hierarchical' => false,
            ]);
        }
    }

    public function register_post_type() {
        $labels = [
            'name' => _x('Positions', 'Post Type General Name', 'text_domain'),
            'singular_name' => _x('Position', 'Post Type Singular Name', 'text_domain'),
            'menu_name' => __('Positions', 'text_domain'),
            'name_admin_bar' => __('Position', 'text_domain'),
            'archives' => __('Position Archives', 'text_domain'),
            'attributes' => __('Position Attributes', 'text_domain'),
            'parent_item_colon' => __('Parent Position:', 'text_domain'),
            'all_items' => __('All Positions', 'text_domain'),
            'add_new_item' => __('Add New Position', 'text_domain'),
            'add_new' => __('Add New', 'text_domain'),
            'new_item' => __('New Position', 'text_domain'),
            'edit_item' => __('Edit Position', 'text_domain'),
            'update_item' => __('Update Position', 'text_domain'),
            'view_item' => __('View Position', 'text_domain'),
            'view_items' => __('View Positions', 'text_domain'),
            'search_items' => __('Search Position', 'text_domain'),
            'not_found' => __('Not found', 'text_domain'),
            'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
            'featured_image' => __('Featured Image', 'text_domain'),
            'set_featured_image' => __('Set featured image', 'text_domain'),
            'remove_featured_image' => __('Remove featured image', 'text_domain'),
            'use_featured_image' => __('Use as featured image', 'text_domain'),
            'insert_into_item' => __('Insert into position', 'text_domain'),
            'uploaded_to_this_item' => __('Uploaded to this position', 'text_domain'),
            'items_list' => __('Positions list', 'text_domain'),
            'items_list_navigation' => __('Positions list navigation', 'text_domain'),
            'filter_items_list' => __('Filter positions list', 'text_domain'),
        ];

        $args = [
            'label' => __('Position', 'text_domain'),
            'description' => __('Description of the Position post type', 'text_domain'),
            'labels' => $labels,
            'supports' => ['title', 'thumbnail'],
            'taxonomies' => ['category', 'post_tag'],
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

    public function add_meta_boxes() {
        add_meta_box('position_company', __('Company', 'text_domain'), [$this, 'render_company_meta_box'], 'position', 'side', 'default');
        add_meta_box('position_description', __('Description', 'text_domain'), [$this, 'render_description_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_dates', __('Dates', 'text_domain'), [$this, 'render_dates_meta_box'], 'position', 'side', 'default');
        add_meta_box('position_projects', __('Projects', 'text_domain'), [$this, 'render_projects_meta_box'], 'position', 'side', 'default');
    }

    public function render_projects_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_multi_select_meta_box($post, 'project_ids', __('Select Projects', 'text_domain'), __('Select the projects associated with this position.', 'text_domain'), 'project', '_project_ids');
    }

    public function render_company_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_select_meta_box($post, 'company_id', __('Select Company', 'text_domain'), __('Select the company associated with this position.', 'text_domain'), 'company', '_company_id');
    }

    public function render_description_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_textarea_meta_box($post, 'description', __('Description', 'text_domain'), __('Enter the description of the position.', 'text_domain'));
    }

    public function render_dates_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_checkbox_meta_box($post, 'active', __('Active', 'text_domain'), __('Check if the position is currently active.', 'text_domain'));
        $this->render_date_meta_box($post, 'start-date', __('Start Date', 'text_domain'), __('Enter the start date for the position.', 'text_domain'));
        $this->render_date_meta_box($post, 'end-date', __('End Date', 'text_domain'), __('Enter the end date for the position.', 'text_domain'));
    }

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

        // Guardar taxonomías
        if (isset($_POST['tax_input'])) {
            foreach ($_POST['tax_input'] as $taxonomy => $terms) {
                if (is_array($terms)) {
                    $terms = array_map('sanitize_text_field', $terms);
                } else {
                    $terms = sanitize_text_field($terms);
                }
                wp_set_post_terms($post_id, $terms, $taxonomy, false);
            }
        }

        // Guardar campos personalizados
        $fields = ['description', 'active', 'start-date', 'end-date'];
        foreach ($fields as $field) {
            $field_id = 'wpcf-' . $field;
            if (isset($_POST[$field_id])) {
                $value = sanitize_text_field($_POST[$field_id]);
                update_post_meta($post_id, $field_id, $value);
            } else {
                delete_post_meta($post_id, $field_id);
            }
        }

        // Guardar relación con la compañía
        if (isset($_POST['company_id'])) {
            $company_id = sanitize_text_field($_POST['company_id']);
            update_post_meta($post_id, '_company_id', $company_id);
        }

        // Guardar relación con los proyectos
        if (isset($_POST['project_ids'])) {
            $project_ids = array_map('sanitize_text_field', $_POST['project_ids']);
            update_post_meta($post_id, '_project_ids', $project_ids);
        } else {
            delete_post_meta($post_id, '_project_ids');
        }
    }
}

// Inicializar la clase como singleton
PositionPostType::get_instance();