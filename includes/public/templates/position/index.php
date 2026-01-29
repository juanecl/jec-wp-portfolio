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

 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get terms for the taxonomies
$knowledge_terms = get_terms(array('taxonomy' => 'knowledge', 'hide_empty' => false));
$skills_terms = get_terms(array('taxonomy' => 'skills', 'hide_empty' => false));

// Variable con los IDs de las posiciones a filtrar (puede ser pasada desde un shortcode o cualquier otro método)
$position_ids = isset($position_ids) ? $position_ids : array();

?>

<div class="container-fluid section-padding" style="padding-bottom: 0!important;">
    <!-- Filter Form -->
    <h2 class="text-left mx-5"><?php _e('Work experience', 'jec-portfolio'); ?></h2>
    <div class="bg-dark-muted-padding">
        <form id="filter-form">
            <div class="row mb-4">
                <h5><?php _e('Search Positions', 'jec-portfolio'); ?></h5>
                <p><?php _e('Filter by knowledge and skill terms', 'jec-portfolio'); ?></p>
                <div class="col-md-6">
                    <label for="knowledge" class="mx-1 mb-2 mt-2"><?php _e('By knowledge', 'jec-portfolio'); ?></label>
                    <select id="knowledge" name="knowledge[]" multiple class="form-control select2">
                        <?php foreach ($knowledge_terms as $term): ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="skills" class="mx-1 mb-2 mt-2"><?php _e('By skills', 'jec-portfolio'); ?></label>
                    <select id="skills" name="skills[]" multiple class="form-control select2">
                        <?php foreach ($skills_terms as $term): ?>
                            <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-12 filter-actions">
                    <button type="button" id="reset-filters" class="btn btn-secondary mx-2">
                        <i class="fa fa-undo btn-icon" aria-hidden="true"></i>
                        <span class="btn-text"><?php _e('Reset Filters', 'jec-portfolio'); ?></span>
                    </button>
                    <a id="print-positions"
                        class="btn btn-secondary mx-2 btn-download-pdf js-print-positions"
                        href="<?php echo esc_url(admin_url('admin-ajax.php?action=download_positions_pdf')); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        >
                        <i class="fa fa-file-pdf btn-icon" aria-hidden="true"></i>
                        <span class="btn-text"><?php _e('Download', 'jec-portfolio'); ?></span>
                    </a>
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
        include plugin_dir_path(__FILE__) . 'content-position.php';
        ?>
    </div>
</div>