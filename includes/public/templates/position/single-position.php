<?php
/**
 * Template for displaying a Position with its associated Company and Projects.
 *
 * This template is used to display detailed information about a position, including the company and related projects.
 * It expects an array of arguments ($args) to be passed to it for rendering the position.
 *
 * @package JEC_Portfolio
 * @version 1.0
 */

// Assign the arguments to variables
if (!isset($post)) {
    // If $post is not defined, look for position_id in $args
    $position_id = isset($args['position_id']) ? $args['position_id'] : null;
    if ($position_id) {
        $post = get_post($position_id);
    }
} else {
    $position_id = $post->ID;
}

// Prepare the arguments for rendering the position
$args = PositionRenderer::prepare_position_args($position_id);

// Assign each argument to a variable
foreach ($args as $key => $value) {
    ${$key} = $value;
}
?>

<div class="container-fluid"> 
    <div class="card mb-4 <?php echo esc_attr($container_class); ?>">
        <div class="card-header position-relative position-header-toggle jec-collapse-toggle <?php echo $toggle_open ? 'is-open' : ''; ?>" role="button" tabindex="0" data-jec-collapse-target="#position-content-<?php echo esc_attr($position_id); ?>" aria-controls="position-content-<?php echo esc_attr($position_id); ?>" aria-expanded="<?php echo $toggle_open ? 'true' : 'false'; ?>">
            <button class="btn text-secondary position-absolute toggle-description toggle-description-btn jec-collapse-toggle <?php echo $toggle_open ? 'is-open' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#position-content-<?php echo esc_attr($position_id); ?>" data-jec-collapse-target="#position-content-<?php echo esc_attr($position_id); ?>" aria-expanded="<?php echo $toggle_open ? 'true' : 'false'; ?>" aria-controls="position-content-<?php echo esc_attr($position_id); ?>">
                <span class="toggle-icon-wrapper" aria-hidden="true">
                    <i class="fa fa-plus toggle-icon toggle-icon-plus"></i>
                    <i class="fa fa-minus toggle-icon toggle-icon-minus"></i>
                </span>
            </button>
            <div class="d-flex flex-column">
                <div class="p-2 mb-2">
                    <h4 class="position-title mb-2">
                        <?php echo esc_html($position_title); ?> 
                        <span class="text-muted text-muted-small">
                            @ <a href="<?php echo esc_url($company_website); ?>" target="_blank" class="text-primary text-decoration-none fw-bolder"><?php echo esc_html($company_name); ?></a>
                        </span>
                        <?php if ($freelance): ?>
                            <span class="badge bg-warning text-dark ms-2"><?php _e('Freelance', 'jec-portfolio'); ?></span>
                        <?php endif; ?>
                    </h4>
                    <div class="position-dates text-muted mb-2">
                        <i class="fa fa-calendar-day text-primary"></i> <?php echo esc_html($position_start_date_formatted); ?> - <i class="fa fa-calendar-day text-primary"></i>
                        <?php if ($position_active && empty($position_end_date)): ?>
                            <?php _e('Current job', 'jec-portfolio'); ?>
                        <?php else: ?>
                            <?php echo esc_html($position_end_date_formatted); ?>
                        <?php endif; ?>
                    </div>
                    <div class="mt-2">
                        <span class="bg-secondary text-white d-inline-block px-2 py-1 rounded fs-7 company-category-badge"><?php echo esc_html($company_category); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="position-wrapper">
                <div class="collapse <?php echo $toggle_open; ?> p-4" id="position-content-<?php echo esc_attr($position_id); ?>">
                    <?php if (!empty($position_description)): ?>
                        <div class="position-description mb-2 py-2 px-3">
                            <?php echo apply_filters('the_content', $position_description); ?>
                        </div>
                    <?php endif; ?>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><?php _e('Knowledge', 'jec-portfolio'); ?></h5>
                            <?php if (!empty($knowledge_terms)): ?>
                                <?php foreach ($knowledge_terms as $term): ?>
                                    <span class="badge bg-primary knowledge-badge cursor-pointer text-decoration-underline-hover" data-term="<?php echo esc_attr($term['slug']); ?>"><?php echo esc_html($term['name']); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p><?php _e('No knowledge terms assigned.', 'jec-portfolio'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h5><?php _e('Skills', 'jec-portfolio'); ?></h5>
                            <?php if (!empty($skills_terms)): ?>
                                <?php foreach ($skills_terms as $term): ?>
                                    <span class="badge bg-secondary skills-badge cursor-pointer text-decoration-underline-hover" data-term="<?php echo esc_attr($term['slug']); ?>"><?php echo esc_html($term['name']); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p><?php _e('No skills terms assigned.', 'jec-portfolio'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                    // Include the related projects template
                    include plugin_dir_path(__FILE__) . '../project/index.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>