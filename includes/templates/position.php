<?php
/**
 * Template for displaying a Position with its associated Company and Projects.
 *
 * @param array $args The arguments for the template.
 */
require_once plugin_dir_path(__FILE__) . '../classes/position-renderer.php';
// Assign the arguments to variables

if (!isset($post)) {
    // Si $post no está definido, buscar el position_id en $args
    $position_id = isset($args['position_id']) ? $args['position_id'] : null;
    if ($position_id) {
        $post = get_post($position_id);
    }
} else {
    $position_id = $post->ID;
}
$args = PositionRenderer::prepare_position_args($position_id);

foreach ($args as $key => $value) {
    ${$key} = $value;
}
?>

<div class="container">
    <div class="card">
        <div class="card-header position-relative">
            <a class="btn btn-outline-primary position-absolute" style="top: 0; right: 0; margin: 0.5rem;" data-bs-toggle="collapse" href="#position-content-<?php echo esc_attr($position_id); ?>" role="button" aria-expanded="true" aria-controls="position-content-<?php echo esc_attr($position_id); ?>">
                <i class="fa fa-minus-circle toggle-icon"></i>
            </a>
            <div class="d-flex flex-column">
                <div class="bg-light p-2 mb-2">
                    <h4 class="position-title mb-1">
                        <?php echo esc_html($position_title); ?> 
                        <span class="text-muted" style="font-size: 0.9em;">
                            @ <a href="<?php echo esc_url($company_website); ?>" target="_blank" class="text-muted"><?php echo esc_html($company_name); ?></a>
                        </span>
                    </h4>
                    <div class="position-dates text-muted">
                        <i class="fa fa-calendar-check-o"></i> <?php echo esc_html($position_start_date_formatted); ?> -
                        <?php if ($position_active && empty($position_end_date)): ?>
                            <?php _e('Current job', 'jec-portfolio'); ?>
                        <?php else: ?>
                            <?php echo esc_html($position_end_date_formatted); ?>
                        <?php endif; ?>
                    </div>
                    <div class="mt-2">
                        <span class="bg-secondary text-white d-inline-block px-2 py-1 rounded fs-7"  style="font-size: 0.8em;"><?php echo esc_html($company_category); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="position-wrapper">
                <div class="collapse show p-4" id="position-content-<?php echo esc_attr($position_id); ?>">
                    <div class="position-description mb-4 py-4 px-3 border-top border-bottom">
                        <p><?php echo wp_kses_post($position_description); ?></p>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><?php _e('Knowledge', 'jec-portfolio'); ?></h5>
                            <?php if (!empty($knowledge_terms)): ?>
                                <?php foreach ($knowledge_terms as $term): ?>
                                    <span class="badge bg-primary"><?php echo esc_html($term); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p><?php _e('No knowledge terms assigned.', 'jec-portfolio'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h5><?php _e('Skills', 'jec-portfolio'); ?></h5>
                            <?php if (!empty($skills_terms)): ?>
                                <?php foreach ($skills_terms as $term): ?>
                                    <span class="badge bg-success"><?php echo esc_html($term); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p><?php _e('No skills terms assigned.', 'jec-portfolio'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="ro mt-5 d-block mb-3">
                        <h4><?php _e('Related projects', 'jec-portfolio'); ?></h4>
                    </div>
                    <div class="row">
                        <?php
                        include plugin_dir_path(__FILE__) . 'projects.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>