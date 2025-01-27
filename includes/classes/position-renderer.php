<?php

include plugin_dir_path(__FILE__) . 'position-query.php';
class PositionRenderer {
    private static $instance = null;

    private function __construct() {
        add_action('wp_ajax_filter_positions', [ $this, 'filter_positions' ]);
        add_action('wp_ajax_nopriv_filter_positions', [ $this, 'filter_positions' ]);
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Prepare the arguments for displaying a position.
     *
     * This method retrieves and formats the necessary data for displaying a position,
     * including company details, position details, taxonomies, and associated projects.
     *
     * @param int $position_id The ID of the position post.
     * @return array The prepared arguments for the position template.
     */
    public static function prepare_position_args($position_id) {
        // Get company details
        $company_id = get_post_meta($position_id, 'wpcf-company_id', true);
        $company_name = __('No company', 'jec-portfolio');
        $company_website = '';
        $company_category = __('No category', 'jec-portfolio');
    
        if ($company_id) {
            $company_post = get_post($company_id);
            if ($company_post) {
                $company_name = $company_post->post_title;
                $company_website = get_post_meta($company_id, 'wpcf-url', true);
                $company_category = get_post_meta($company_id, 'wpcf-category', true);
            }
        }
    
        // Get position details
        $position_title = get_the_title($position_id);
        $position_description = get_post_meta($position_id, 'wpcf-description', true);
        $position_start_date = get_post_meta($position_id, 'wpcf-start-date', true);
        $position_end_date = get_post_meta($position_id, 'wpcf-end-date', true);
        $position_active = get_post_meta($position_id, 'wpcf-active', true);
    
        // Format dates based on locale
        $position_start_date_formatted = $position_start_date ? date_i18n(get_option('date_format'), strtotime($position_start_date)) : '';
        $position_end_date_formatted = $position_end_date ? date_i18n(get_option('date_format'), strtotime($position_end_date)) : '';
    
        // Get knowledge and skills taxonomies
        $knowledge_terms = wp_get_post_terms($position_id, 'knowledge', ['fields' => 'names']);
        $skills_terms = wp_get_post_terms($position_id, 'skills', ['fields' => 'names']);
    
        // Get project details
        $project_ids = get_post_meta($position_id, 'wpcf-project_ids', true);
        $projects = [];
        if (is_array($project_ids)) {
            foreach ($project_ids as $project_id) {
                $projects[] = self::prepare_project_args($project_id);
            }
        }
    
        // Prepare arguments for the template
        $args = [
            'position_id' => $position_id,
            'company_name' => $company_name,
            'company_category' => $company_category,
            'company_website' => $company_website,
            'position_title' => $position_title,
            'position_description' => $position_description,
            'position_start_date_formatted' => $position_start_date_formatted,
            'position_end_date_formatted' => $position_end_date_formatted,
            'position_active' => $position_active,
            'knowledge_terms' => $knowledge_terms,
            'skills_terms' => $skills_terms,
            'projects' => $projects,
        ];
    
        return $args;
    }
    
    /**
     * Prepare the arguments for displaying a project.
     *
     * This method retrieves and formats the necessary data for displaying a project,
     * including project details and associated metadata.
     *
     * @param int $project_id The ID of the project post.
     * @return array The prepared arguments for the project template.
     */
    private static function prepare_project_args($project_id) {
        $project_start_date = get_post_meta($project_id, 'wpcf-start-date', true);
        $project_end_date = get_post_meta($project_id, 'wpcf-end-date', true);
        $project_url = get_post_meta($project_id, 'wpcf-url', true);
    
        return [
            'id' => $project_id,
            'title' => get_the_title($project_id),
            'url' => $project_url,
            'description' => get_post_meta($project_id, 'wpcf-description', true),
            'start_date' => $project_start_date ? date_i18n(get_option('date_format'), strtotime($project_start_date)) : '',
            'end_date' => $project_end_date ? date_i18n(get_option('date_format'), strtotime($project_end_date)) : '',
            'active' => get_post_meta($project_id, 'wpcf-active', true),
        ];
    }

    /**
     * Handle AJAX request to filter positions based on selected taxonomies.
     *
     * This method processes the AJAX request to filter positions based on the selected
     * 'knowledge' and 'skills' taxonomies. If no taxonomies are selected, it returns all positions.
     */
    public function filter_positions() {
        $position_query = new PositionQuery();

        // Add knowledge taxonomy to the query if set
        if (isset($_GET['knowledge']) && !empty($_GET['knowledge'])) {
            $position_query->add_tax_query('knowledge', $_GET['knowledge']);
        }

        // Add skills taxonomy to the query if set
        if (isset($_GET['skills']) && !empty($_GET['skills'])) {
            $position_query->add_tax_query('skills', $_GET['skills']);
        }

        // Execute the query
        $positions = $position_query->get_positions();
        include plugin_dir_path(__FILE__) . '../templates/positions-loop.php';

        wp_die();
    }
}

// Inicializar el singleton
PositionRenderer::get_instance();