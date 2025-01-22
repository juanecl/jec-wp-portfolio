<?php

require_once get_template_directory() . '/portfolio/partials/abstract-meta-box.php';

class ProjectPostType extends AbstractMetaBox {
    private static $instance = null;

    private function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_fields']);
        add_filter('use_block_editor_for_post_type', [$this, 'disable_block_editor'], 10, 2);
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new ProjectPostType();
        }
        return self::$instance;
    }

    public function disable_block_editor($use_block_editor, $post_type) {
        if ($post_type === 'project') {
            return false;
        }
        return $use_block_editor;
    }

    public function register_post_type() {
        $labels = [
            'name' => _x('Projects', 'Post Type General Name', 'text_domain'),
            'singular_name' => _x('Project', 'Post Type Singular Name', 'text_domain'),
            'menu_name' => __('Projects', 'text_domain'),
            'name_admin_bar' => __('Project', 'text_domain'),
            'archives' => __('Project Archives', 'text_domain'),
            'attributes' => __('Project Attributes', 'text_domain'),
            'parent_item_colon' => __('Parent Project:', 'text_domain'),
            'all_items' => __('All Projects', 'text_domain'),
            'add_new_item' => __('Add New Project', 'text_domain'),
            'add_new' => __('Add New', 'text_domain'),
            'new_item' => __('New Project', 'text_domain'),
            'edit_item' => __('Edit Project', 'text_domain'),
            'update_item' => __('Update Project', 'text_domain'),
            'view_item' => __('View Project', 'text_domain'),
            'view_items' => __('View Projects', 'text_domain'),
            'search_items' => __('Search Project', 'text_domain'),
            'not_found' => __('Not found', 'text_domain'),
            'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
            'featured_image' => __('Featured Image', 'text_domain'),
            'set_featured_image' => __('Set featured image', 'text_domain'),
            'remove_featured_image' => __('Remove featured image', 'text_domain'),
            'use_featured_image' => __('Use as featured image', 'text_domain'),
            'insert_into_item' => __('Insert into project', 'text_domain'),
            'uploaded_to_this_item' => __('Uploaded to this project', 'text_domain'),
            'items_list' => __('Projects list', 'text_domain'),
            'items_list_navigation' => __('Projects list navigation', 'text_domain'),
            'filter_items_list' => __('Filter projects list', 'text_domain'),
        ];

        $args = [
            'label' => __('Project', 'text_domain'),
            'description' => __('Description of the Project post type', 'text_domain'),
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

        register_post_type('project', $args);
    }

    public function add_meta_boxes() {
        // add_meta_box('project_name', __('Project Name', 'text_domain'), [$this, 'render_name_meta_box'], 'project', 'normal', 'high');
        add_meta_box('project_description', __('Description', 'text_domain'), [$this, 'render_description_meta_box'], 'project', 'normal', 'high');
        add_meta_box('project_dates', __('Dates', 'text_domain'), [$this, 'render_dates_meta_box'], 'project', 'side', 'default');
    }

    public function render_name_meta_box($post) {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_text_meta_box($post, 'name', __('Project Name', 'text_domain'), __('Enter the name of the project.', 'text_domain'));
    }

    public function render_description_meta_box($post) {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_textarea_meta_box($post, 'description', __('Description', 'text_domain'), __('Enter the description of the project.', 'text_domain'));
    }

    public function render_dates_meta_box($post) {
        wp_nonce_field('save_project_fields_nonce', 'project_fields_nonce');
        $this->render_checkbox_meta_box($post, 'active', __('Active', 'text_domain'), __('Check if the project is currently active.', 'text_domain'));
        $this->render_date_meta_box($post, 'start-date', __('Start Date', 'text_domain'), __('Enter the start date for the project.', 'text_domain'));
        $this->render_date_meta_box($post, 'end-date', __('End Date', 'text_domain'), __('Enter the end date for the project.', 'text_domain'));
    }

    public function save_custom_fields($post_id) {
        // Verify nonce.
        if (!isset($_POST['project_fields_nonce']) || !wp_verify_nonce($_POST['project_fields_nonce'], 'save_project_fields_nonce')) {
            error_log('Nonce verification failed.');
            return;
        }

        // Verify autosave.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            error_log('Autosave detected.');
            return;
        }

        // Verify user permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
            error_log('Page detected.');
            if (!current_user_can('edit_page', $post_id)) {
                error_log('User cannot edit page.');
                return;
            }
        } else {
            error_log('Post detected.');
            if (!current_user_can('edit_post', $post_id)) {
                error_log( 'User cannot edit post.');
                return;
            }
        }

        // Guardar campos personalizados
        $fields = ['name', 'description', 'active', 'start-date', 'end-date'];
        foreach ($fields as $field) {
            $field_id = 'wpcf-' . $field;
            error_log('Saving field: ' . $field_id);
            if (isset($_POST[$field_id])) {
                error_log('Field value: ' . $_POST[$field_id]);
                $value = sanitize_text_field($_POST[$field_id]);
                update_post_meta($post_id, $field_id, $value);
            } else {
                error_log('Deleting field: ' . $field_id);
                delete_post_meta($post_id, $field_id);
            }
        }
    }
}

// Inicializar la clase como singleton
ProjectPostType::get_instance();