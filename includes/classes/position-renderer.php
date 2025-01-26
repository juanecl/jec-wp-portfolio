<?php
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

        if ($company_id) {
            $company_post = get_post($company_id);
            $company_name = $company_post ? $company_post->post_title : 'Undefined';
            $company_website = get_post_meta($company_id, 'wpcf-url', true);
            $company_category = get_post_meta($company_id, 'wpcf-category', true);
        } else {
            $company_name = 'Undefined';
            $company_website = '';
            $company_category = 'Undefined';
        }

        // Get position details
        $position_title = get_the_title($position_id);
        $position_description = get_post_meta($position_id, 'wpcf-description', true);
        $position_start_date = get_post_meta($position_id, 'wpcf-start-date', true);
        $position_end_date = get_post_meta($position_id, 'wpcf-end-date', true);
        $position_active = get_post_meta($position_id, 'wpcf-active', true);

        // Format dates based on locale
        $position_start_date_formatted = date_i18n(get_option('date_format'), strtotime($position_start_date));
        $position_end_date_formatted = $position_end_date ? date_i18n(get_option('date_format'), strtotime($position_end_date)) : '';

        // Get knowledge and skills taxonomies
        $knowledge_terms = wp_get_post_terms($position_id, 'knowledge', ['fields' => 'names']);
        $skills_terms = wp_get_post_terms($position_id, 'skills', ['fields' => 'names']);

        // Get project details
        $project_ids = get_post_meta($position_id, 'wpcf-project_ids', true);
        $projects = [];
        if (is_array($project_ids)) {
            foreach ($project_ids as $project_id) {
                $project_start_date = get_post_meta($project_id, 'wpcf-start-date', true);
                $project_end_date = get_post_meta($project_id, 'wpcf-end-date', true);
                $project_url = get_post_meta($project_id, 'wpcf-url', true);
                $projects[] = [
                    'id' => $project_id,
                    'title' => get_the_title($project_id),
                    'url' => $project_url,
                    'description' => get_post_meta($project_id, 'wpcf-description', true),
                    'start_date' => date_i18n(get_option('date_format'), strtotime($project_start_date)),
                    'end_date' => $project_end_date ? date_i18n(get_option('date_format'), strtotime($project_end_date)) : '',
                    'active' => get_post_meta($project_id, 'wpcf-active', true),
                ];
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

    public function filter_positions() {
        $tax_query = array('relation' => 'AND');

        if (isset($_GET['knowledge']) && !empty($_GET['knowledge'])) {
            $tax_query[] = array(
                'taxonomy' => 'knowledge',
                'field' => 'slug',
                'terms' => array_map('sanitize_text_field', $_GET['knowledge']),
                'operator' => 'AND',
            );
        }

        if (isset($_GET['skills']) && !empty($_GET['skills'])) {
            $tax_query[] = array(
                'taxonomy' => 'skills',
                'field' => 'slug',
                'terms' => array_map('sanitize_text_field', $_GET['skills']),
                'operator' => 'AND',
            );
        }

        $args = array(
            'post_type' => 'position',
            'tax_query' => $tax_query,
        );

        $positions = new WP_Query($args);
        ?>
        <div class="container">         
            <div class="row">
                <!-- results found -->
                <div class="col-md-12">
                    <p class="bg-light d-block w-100 text-center"><?php echo $positions->found_posts . ' ' . __('positions found.', 'jec-portfolio'); ?></p>
                </div>
            </div>
        </div>
        <?php
        if ($positions->have_posts()) {
            while ($positions->have_posts()) {
                $positions->the_post();
                // Prepare arguments for the single position template
                $args = self::prepare_position_args(get_the_ID());
                // Include the single position template
                include plugin_dir_path(__FILE__) . '../templates/position.php';
            }
            wp_reset_postdata();
        } else {
            echo '<p>' . __('No positions found.', 'jec-portfolio') . '</p>';
        }

        wp_die();
    }
}

// Inicializar el singleton
PositionRenderer::get_instance();