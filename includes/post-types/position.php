<?php

require_once plugin_dir_path(__FILE__) . '../classes/meta-box-renderer.php';

class PositionPostType extends AbstractMetaBoxRenderer {
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

    public function register_post_type() {
        $labels = [
            'name' => _x('Positions', 'Post Type General Name', 'portfolio-plugin'),
            'singular_name' => _x('Position', 'Post Type Singular Name', 'portfolio-plugin'),
            'menu_name' => __('Positions', 'portfolio-plugin'),
            'name_admin_bar' => __('Position', 'portfolio-plugin'),
            'archives' => __('Position Archives', 'portfolio-plugin'),
            'attributes' => __('Position Attributes', 'portfolio-plugin'),
            'parent_item_colon' => __('Parent Position:', 'portfolio-plugin'),
            'all_items' => __('All Positions', 'portfolio-plugin'),
            'add_new_item' => __('Add New Position', 'portfolio-plugin'),
            'add_new' => __('Add New', 'portfolio-plugin'),
            'new_item' => __('New Position', 'portfolio-plugin'),
            'edit_item' => __('Edit Position', 'portfolio-plugin'),
            'update_item' => __('Update Position', 'portfolio-plugin'),
            'view_item' => __('View Position', 'portfolio-plugin'),
            'view_items' => __('View Positions', 'portfolio-plugin'),
            'search_items' => __('Search Position', 'portfolio-plugin'),
            'not_found' => __('Not found', 'portfolio-plugin'),
            'not_found_in_trash' => __('Not found in Trash', 'portfolio-plugin'),
            'featured_image' => __('Featured Image', 'portfolio-plugin'),
            'set_featured_image' => __('Set featured image', 'portfolio-plugin'),
            'remove_featured_image' => __('Remove featured image', 'portfolio-plugin'),
            'use_featured_image' => __('Use as featured image', 'portfolio-plugin'),
            'insert_into_item' => __('Insert into position', 'portfolio-plugin'),
            'uploaded_to_this_item' => __('Uploaded to this position', 'portfolio-plugin'),
            'items_list' => __('Positions list', 'portfolio-plugin'),
            'items_list_navigation' => __('Positions list navigation', 'portfolio-plugin'),
            'filter_items_list' => __('Filter positions list', 'portfolio-plugin'),
        ];

        $args = [
            'label' => __('Position', 'portfolio-plugin'),
            'description' => __('Description of the Position post type', 'portfolio-plugin'),
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

        register_post_type('position', $args);
    }

    public function add_meta_boxes() {
        add_meta_box('position_description', __('Description', 'text_domain'), [$this, 'render_description_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_company', __('Company', 'portfolio-plugin'), [$this, 'render_company_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_location', __('Location', 'portfolio-plugin'), [$this, 'render_location_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_projects', __('Projects', 'portfolio-plugin'), [$this, 'render_projects_meta_box'], 'position', 'side', 'default');
    }

    public function render_projects_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        error_log(print_r($post, true));

        $this->render_meta_box('multiselect', $post, 'project_ids', __('Select Projects', 'portfolio-plugin'), __('Select the projects associated with this position.', 'portfolio-plugin'), ['post_type' => 'project']);
    }

    public function render_description_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('textarea', $post, 'description', __('Description', 'text_domain'), __('Enter the description of the position.', 'text_domain'));
    }

    public function render_company_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('select', $post, 'company_id', __('Select Company', 'portfolio-plugin'), __('Select the company associated with this position.', 'portfolio-plugin'), ['post_type' => 'company']);
    }

    public function render_location_meta_box($post) {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('text', $post, 'location', __('Location', 'portfolio-plugin'), __('Enter the location for this position.', 'portfolio-plugin'));
    }

    public function save_custom_fields($post_id) {
        // Verify nonce.
        if (!isset($_POST['position_fields_nonce']) || !wp_verify_nonce($_POST['position_fields_nonce'], 'save_position_fields_nonce')) {
            error_log('Nonce verification failed');
            return;
        }

        // Verify autosave.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            error_log('Autosave verification failed');
            return;
        }

        // Verify user permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                error_log('User permissions verification failed');
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                error_log('User permissions verification failed');
                return;
            }
        }
        // Save custom fields
        $fields = ['description', 'company_id', 'location', 'project_ids'];
        foreach ($fields as $field) {
            $field_id = 'wpcf-' . $field;
            try {
                if (isset($_POST[$field_id])) {
                    error_log('Saving field: ' . $field_id);
                    $value = $_POST[$field_id];
                    if (is_array($value)) {
                        $value = array_map('sanitize_text_field', $value);
                    } else {
                        $value = sanitize_text_field($value);
                    }
        
                    // Verificar si el valor es válido
                    if (empty($value)) {
                        throw new Exception('The value for field ' . $field_id . ' is empty or invalid.');
                    }
        
                    // Verificar si el meta campo ya existe
                    $current_value = get_post_meta($post_id, $field_id, true);
                    if ($current_value === $value) {
                        error_log('The value for field ' . $field_id . ' is already up to date.');
                    } else {
                        if (!update_post_meta($post_id, $field_id, $value)) {
                            throw new Exception('Failed to update post meta for field: ' . $field_id);
                        }
                    }
                } else {
                    if (!delete_post_meta($post_id, $field_id)) {
                        throw new Exception('Failed to delete post meta for field: ' . $field_id);
                    }
                }
            } catch (Exception $e) {
                error_log('Error: ' . $e->getMessage());
                add_settings_error(
                    'position_meta_box_errors',
                    esc_attr('settings_updated'),
                    $e->getMessage(),
                    'error'
                );
            }
        }
        
        // Mostrar errores en la pantalla de administración
        settings_errors('position_meta_box_errors');
    }
}

// Initialize the class as a singleton
PositionPostType::get_instance();