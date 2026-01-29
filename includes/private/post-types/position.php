<?php

require_once plugin_dir_path(__FILE__) . '../classes/meta-box-renderer.php';

/**
 * Class PositionPostType
 *
 * This class defines the custom post type "Position" and handles its meta boxes and custom fields.
 */
class PositionPostType extends AbstractMetaBoxRenderer
{
    private static $instance = null;

    /**
     * Private constructor to ensure singleton pattern.
     */
    private function __construct()
    {
        parent::__construct();
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_custom_fields']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('admin_init', [$this, 'maybe_migrate_legacy_description']);
    }

    /**
     * Get the singleton instance of the class.
     *
     * @return PositionPostType The singleton instance.
     */
    public static function get_instance()
    {
        if (self::$instance == null) {
            self::$instance = new PositionPostType();
        }
        return self::$instance;
    }

    /**
     * Register the custom post type "Position".
     */
    public function register_post_type()
    {
        $labels = [
            'name' => _x('Positions', 'Post Type General Name', 'jec-portfolio'),
            'singular_name' => _x('Position', 'Post Type Singular Name', 'jec-portfolio'),
            'menu_name' => __('Positions', 'jec-portfolio'),
            'name_admin_bar' => __('Position', 'jec-portfolio'),
            'archives' => __('Position Archives', 'jec-portfolio'),
            'attributes' => __('Position Attributes', 'jec-portfolio'),
            'parent_item_colon' => __('Parent Position:', 'jec-portfolio'),
            'all_items' => __('All Positions', 'jec-portfolio'),
            'add_new_item' => __('Add New Position', 'jec-portfolio'),
            'add_new' => __('Add New', 'jec-portfolio'),
            'new_item' => __('New Position', 'jec-portfolio'),
            'edit_item' => __('Edit Position', 'jec-portfolio'),
            'update_item' => __('Update Position', 'jec-portfolio'),
            'view_item' => __('View Position', 'jec-portfolio'),
            'view_items' => __('View Positions', 'jec-portfolio'),
            'search_items' => __('Search Position', 'jec-portfolio'),
            'not_found' => __('Not found', 'jec-portfolio'),
            'not_found_in_trash' => __('Not found in Trash', 'jec-portfolio'),
            'featured_image' => __('Featured Image', 'jec-portfolio'),
            'set_featured_image' => __('Set featured image', 'jec-portfolio'),
            'remove_featured_image' => __('Remove featured image', 'jec-portfolio'),
            'use_featured_image' => __('Use as featured image', 'jec-portfolio'),
            'insert_into_item' => __('Insert into position', 'jec-portfolio'),
            'uploaded_to_this_item' => __('Uploaded to this position', 'jec-portfolio'),
            'items_list' => __('Positions list', 'jec-portfolio'),
            'items_list_navigation' => __('Positions list navigation', 'jec-portfolio'),
            'filter_items_list' => __('Filter positions list', 'jec-portfolio'),
        ];

        $args = [
            'label' => __('Position', 'jec-portfolio'),
            'description' => __('Description of the Position post type', 'jec-portfolio'),
            'labels' => $labels,
            'supports' => ['title', 'editor', 'thumbnail'],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-businessman',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'taxonomies' => ['knowledge', 'skills'], // Add taxonomies here
        ];

        register_post_type('position', $args);
    }

    /**
     * Register custom taxonomies for the "position" post type.
     */
    public function register_taxonomies()
    {
        // Register Knowledge Taxonomy
        $labels = [
            'name' => _x('Knowledge', 'Taxonomy General Name', 'jec-portfolio'),
            'singular_name' => _x('Knowledge', 'Taxonomy Singular Name', 'jec-portfolio'),
            'menu_name' => __('Knowledge', 'jec-portfolio'),
            'all_items' => __('All Knowledge', 'jec-portfolio'),
            'parent_item' => __('Parent Knowledge', 'jec-portfolio'),
            'parent_item_colon' => __('Parent Knowledge:', 'jec-portfolio'),
            'new_item_name' => __('New Knowledge Name', 'jec-portfolio'),
            'add_new_item' => __('Add New Knowledge', 'jec-portfolio'),
            'edit_item' => __('Edit Knowledge', 'jec-portfolio'),
            'update_item' => __('Update Knowledge', 'jec-portfolio'),
            'view_item' => __('View Knowledge', 'jec-portfolio'),
            'separate_items_with_commas' => __('Separate knowledge with commas', 'jec-portfolio'),
            'add_or_remove_items' => __('Add or remove knowledge', 'jec-portfolio'),
            'choose_from_most_used' => __('Choose from the most used', 'jec-portfolio'),
            'popular_items' => __('Popular Knowledge', 'jec-portfolio'),
            'search_items' => __('Search Knowledge', 'jec-portfolio'),
            'not_found' => __('Not Found', 'jec-portfolio'),
            'no_terms' => __('No knowledge', 'jec-portfolio'),
            'items_list' => __('Knowledge list', 'jec-portfolio'),
            'items_list_navigation' => __('Knowledge list navigation', 'jec-portfolio'),
        ];

        $args = [
            'labels' => $labels,
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
        ];

        register_taxonomy('knowledge', ['position'], $args);

        // Register Skills Taxonomy
        $labels = [
            'name' => _x('Skills', 'Taxonomy General Name', 'jec-portfolio'),
            'singular_name' => _x('Skill', 'Taxonomy Singular Name', 'jec-portfolio'),
            'menu_name' => __('Skills', 'jec-portfolio'),
            'all_items' => __('All Skills', 'jec-portfolio'),
            'parent_item' => __('Parent Skill', 'jec-portfolio'),
            'parent_item_colon' => __('Parent Skill:', 'jec-portfolio'),
            'new_item_name' => __('New Skill Name', 'jec-portfolio'),
            'add_new_item' => __('Add New Skill', 'jec-portfolio'),
            'edit_item' => __('Edit Skill', 'jec-portfolio'),
            'update_item' => __('Update Skill', 'jec-portfolio'),
            'view_item' => __('View Skill', 'jec-portfolio'),
            'separate_items_with_commas' => __('Separate skills with commas', 'jec-portfolio'),
            'add_or_remove_items' => __('Add or remove skills', 'jec-portfolio'),
            'choose_from_most_used' => __('Choose from the most used', 'jec-portfolio'),
            'popular_items' => __('Popular Skills', 'jec-portfolio'),
            'search_items' => __('Search Skills', 'jec-portfolio'),
            'not_found' => __('Not Found', 'jec-portfolio'),
            'no_terms' => __('No skills', 'jec-portfolio'),
            'items_list' => __('Skills list', 'jec-portfolio'),
            'items_list_navigation' => __('Skills list navigation', 'jec-portfolio'),
        ];

        $args = [
            'labels' => $labels,
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
        ];

        register_taxonomy('skills', ['position'], $args);
    }

    /**
     * Add meta boxes for the "position" post type.
     */
    public function add_meta_boxes()
    {
        add_meta_box('position_company', __('Company', 'jec-portfolio'), [$this, 'render_company_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_location', __('Location', 'jec-portfolio'), [$this, 'render_location_meta_box'], 'position', 'normal', 'high');
        add_meta_box('position_projects', __('Projects', 'jec-portfolio'), [$this, 'render_projects_meta_box'], 'position', 'side', 'default');
        add_meta_box('position_dates', __('Dates', 'jec-portfolio'), [$this, 'render_dates_meta_box'], 'position', 'side', 'default');
        add_meta_box('position_freelance', __('Freelance', 'jec-portfolio'), [$this, 'render_freelance_meta_box'], 'position', 'side', 'default'); // Add freelance meta box
    }

    /**
     * Render the projects meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_projects_meta_box($post)
    {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('multiselect', $post, 'project_ids', __('Select Projects', 'jec-portfolio'), __('Select the projects associated with this position.', 'jec-portfolio'), ['post_type' => 'project']);
    }

    /**
     * Render the company meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_company_meta_box($post)
    {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('select', $post, 'company_id', __('Select Company', 'jec-portfolio'), __('Select the company associated with this position.', 'jec-portfolio'), ['post_type' => 'company']);
    }

    /**
     * Render the location meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_location_meta_box($post)
    {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('text', $post, 'location', __('Location', 'jec-portfolio'), __('Enter the location for this position.', 'jec-portfolio'));
    }

    /**
     * Render the dates meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_dates_meta_box($post)
    {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('checkbox', $post, 'active', __('Active', 'jec-portfolio'), __('Check if the position is currently active.', 'jec-portfolio'));
        $this->render_meta_box('date', $post, 'start-date', __('Start Date', 'jec-portfolio'), __('Enter the start date for the position.', 'jec-portfolio'));
        $this->render_meta_box('date', $post, 'end-date', __('End Date', 'jec-portfolio'), __('Enter the end date for the position.', 'jec-portfolio'));
    }

    /**
     * Render the freelance meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_freelance_meta_box($post)
    {
        wp_nonce_field('save_position_fields_nonce', 'position_fields_nonce');
        $this->render_meta_box('checkbox', $post, 'freelance', __('Freelance', 'jec-portfolio'), __('Check if the position is freelance.', 'jec-portfolio'));
    }

    /**
     * Save custom fields for the "position" post type.
     *
     * @param int $post_id The ID of the current post.
     */
    public function save_custom_fields($post_id)
    {
        if (get_post_type($post_id) !== 'position') {
            return;
        }

        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }

        // Define the fields to be saved
        $fields = [
            'company_id',
            'location',
            'project_ids',
            'active',
            'start-date',
            'end-date',
            'freelance' // Add freelance field
        ];

        // Call the external function to save custom meta fields
        save_custom_meta_fields($post_id, $fields, 'position_fields_nonce', 'save_position_fields_nonce');

        $content = get_post_field('post_content', $post_id);
        $legacy_description = get_post_meta($post_id, 'wpcf-description', true);

        if (is_string($content) && trim($content) !== '') {
            update_post_meta($post_id, 'wpcf-description', wp_kses_post($content));
            return;
        }

        if (is_string($legacy_description) && trim($legacy_description) !== '') {
            remove_action('save_post', [$this, 'save_custom_fields']);
            wp_update_post([
                'ID' => $post_id,
                'post_content' => $legacy_description,
            ]);
            add_action('save_post', [$this, 'save_custom_fields']);
        }
    }

    /**
     * Migrate legacy description meta into the editor content for positions.
     */
    public function maybe_migrate_legacy_description()
    {
        if (!current_user_can('edit_posts')) {
            return;
        }

        if (get_option('jec_position_description_migrated', false)) {
            return;
        }

        $positions = get_posts([
            'post_type' => 'position',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'no_found_rows' => true,
        ]);

        foreach ($positions as $position_id) {
            $content = get_post_field('post_content', $position_id);
            if (is_string($content) && trim($content) !== '') {
                continue;
            }

            $legacy_description = get_post_meta($position_id, 'wpcf-description', true);
            if (!is_string($legacy_description) || trim($legacy_description) === '') {
                continue;
            }

            wp_update_post([
                'ID' => $position_id,
                'post_content' => $legacy_description,
            ]);
        }

        update_option('jec_position_description_migrated', 1);
    }
}

// Initialize the class as a singleton
PositionPostType::get_instance();