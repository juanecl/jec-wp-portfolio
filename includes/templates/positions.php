<?php
/**
 * Template for displaying a list of Positions with filters for Knowledge and Skills.
 *
 * This template displays a list of positions and allows users to filter the positions
 * based on the 'knowledge' and 'skills' taxonomies. Users can select multiple terms
 * from each taxonomy to filter the positions complementarily.
 *
 * @package JEC_Portfolio
 */

// Incluir la clase PositionRenderer
require_once plugin_dir_path(__FILE__) . '../classes/position-renderer.php';

// Get terms for the taxonomies
$knowledge_terms = get_terms(array('taxonomy' => 'knowledge', 'hide_empty' => false));
$skills_terms = get_terms(array('taxonomy' => 'skills', 'hide_empty' => false));
?>

<div class="container mt-5">
    <!-- Filter Form -->
    <h2><?php _e('Work experience', 'jec-portfolio'); ?></h2>
    <form id="filter-form">
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="knowledge"><?php _e('Knowledge', 'jec-portfolio'); ?>:</label>
                <select id="knowledge" name="knowledge[]" multiple class="form-control selectpicker" data-live-search="true">
                    <?php foreach ($knowledge_terms as $term): ?>
                        <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="skills"><?php _e('Skills', 'jec-portfolio'); ?>:</label>
                <select id="skills" name="skills[]" multiple class="form-control selectpicker" data-live-search="true">
                    <?php foreach ($skills_terms as $term): ?>
                        <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-12">
                <button type="button" id="reset-filters" class="btn btn-secondary"><?php _e('Reset Filters', 'jec-portfolio'); ?></button>
            </div>
        </div>
    </form>

    <div class="row mt-5" id="positions-container">
        <?php
        // Initial query to display all positions
        $args = array(
            'post_type' => 'position',
            'meta_key' => 'wpcf-start-date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
        );

        $positions = new WP_Query($args);
        ?>
        <div class="container">         
            <div class="row">
                <!-- results found -->
                <div class="col-md-12">
                    <p class="results-count bg-light d-block w-100 text-center"><?php echo $positions->found_posts . ' ' . __('positions found.', 'jec-portfolio'); ?></p>
                </div>
            </div>
        </div>
        <?php
        if ($positions->have_posts()) {
            while ($positions->have_posts()) {
                $positions->the_post();
                $post = get_post(get_the_ID());
                // Include the single position template
                include plugin_dir_path(__FILE__) . 'position.php';
            }
            wp_reset_postdata();
        } else {
            echo '<p>' . __('No positions found.', 'jec-portfolio') . '</p>';
        }
        ?>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        const filterForm = document.getElementById('filter-form');
        const positionsContainer = document.getElementById('positions-container');
        const resetFiltersButton = document.getElementById('reset-filters');
    
        // Initialize Bootstrap Select
        $('.selectpicker').selectpicker();
    
        function fetchPositions(params) {
            fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=filter_positions&' + params)
                .then(response => response.text())
                .then(data => {
                    positionsContainer.innerHTML = data;
                });
        }
    
        filterForm.addEventListener('change', function() {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData).toString();
            fetchPositions(params);
        });
    
        resetFiltersButton.addEventListener('click', function() {
            // Reset the form
            filterForm.reset();
            $('.selectpicker').selectpicker('refresh');
    
            // Trigger change event to reload positions
            fetchPositions('');
        });
    });
</script>