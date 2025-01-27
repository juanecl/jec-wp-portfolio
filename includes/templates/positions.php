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



// Get terms for the taxonomies
$knowledge_terms = get_terms(array('taxonomy' => 'knowledge', 'hide_empty' => false));
$skills_terms = get_terms(array('taxonomy' => 'skills', 'hide_empty' => false));

// Variable con los IDs de las posiciones a filtrar (puede ser pasada desde un shortcode o cualquier otro método)
$position_ids = isset($position_ids) ? $position_ids : array();

?>

<div class="container-fluid mt-5">
    <!-- Filter Form -->
    <h2><?php _e('Work experience', 'jec-portfolio'); ?></h2>
    <div class=" p-3">
        <form id="filter-form">
            <div class="row mb-4">
                <h5><?php _e('Search Positions', 'jec-portfolio'); ?></h5>
                <p><?php _e('Filter by knowledge and skill terms', 'jec-portfolio'); ?></p>
                <div class="col-md-6">
                    <label for="knowledge"><?php _e('By knowledge', 'jec-portfolio'); ?></label>
                    <select id="knowledge" name="knowledge[]" multiple class="form-control select2">
                        <?php foreach ($knowledge_terms as $term): ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="skills"><?php _e('By skills', 'jec-portfolio'); ?></label>
                    <select id="skills" name="skills[]" multiple class="form-control select2">
                        <?php foreach ($skills_terms as $term): ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-12">
                    <button type="button" id="reset-filters" class="btn btn-secondary"><?php _e('Reset Filters', 'jec-portfolio'); ?></button>
                </div>
            </div>
        </form>
    </div>

    <div class="row mt-1" id="positions-container-fluid">
        <?php
        // Initial query to display all positions or filtered by IDs
        $args = array(
            'post_type' => 'position',
            'meta_key' => 'wpcf-start-date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
        );

        if (!empty($position_ids)) {
            $args['post__in'] = $position_ids;
        }

        $positions = new WP_Query($args);
        include plugin_dir_path(__FILE__) . 'positions-loop.php';
        ?>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        const filterForm = document.getElementById('filter-form');
        const positionsContainer = document.getElementById('positions-container-fluid');
        const resetFiltersButton = document.getElementById('reset-filters');
    
        // Initialize Bootstrap Select
        $('#knowledge').select2({
            allowClear: true,
            theme: "bootstrap-5",
        });
        $('#skills').select2({
            allowClear: true,
            theme: "bootstrap-5"
        });
    
        function fetchPositions(params) {
            const url = '<?php echo admin_url('admin-ajax.php'); ?>?action=filter_positions' + (params ? '&' + params : '');
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    positionsContainer.innerHTML = data;
                });
        }
        
        $('#knowledge, #skills').on('change', function() {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData).toString();
            if (params) {
                fetchPositions(params);
            }
        });

        resetFiltersButton.addEventListener('click', function() {
            // Reset the form
            filterForm.reset();
            // Reset the select2 fields
            $('#knowledge').val(null).trigger('change');
            $('#skills').val(null).trigger('change');
    
            // Trigger change event to reload positions
            fetchPositions('');
        });
    });
</script>