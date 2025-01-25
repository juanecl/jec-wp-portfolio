<?php
/**
 * Template for displaying a Position with its associated Company and Projects.
 *
 * @param array $args The arguments for the template.
 */

$position_id = $args['position_id'];
$company_name = $args['company_name'];
$company_category = $args['company_category'];
$company_website = $args['company_website'];
$position_title = $args['position_title'];
$position_description = $args['position_description'];
$position_start_date_formatted = $args['position_start_date_formatted'];
$position_end_date_formatted = $args['position_end_date_formatted'];
$position_active = $args['position_active'];
$projects = $args['projects'];
?>

<div class="container mt-5">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0"><?php echo esc_html($company_name); ?></h2>
                <span class="text-muted"><?php echo esc_html($company_category); ?></span>
                <a href="<?php echo esc_url($company_website); ?>" target="_blank" class="ms-2">
                    <i class="fa fa-globe"></i> <?php _e('View website', 'jec-portfolio'); ?>
                </a>
            </div>
            <a class="btn btn-outline-primary" data-bs-toggle="collapse" href="#position-content-<?php echo esc_attr($position_id); ?>" role="button" aria-expanded="true" aria-controls="position-content-<?php echo esc_attr($position_id); ?>">
                <i class="fa fa-minus-circle toggle-icon"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="position-wrapper">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="position-title mb-0"><?php echo esc_html($position_title); ?></h3>
                    <span class="position-dates">
                        <i class="fa fa-calendar-check-o"></i> <?php echo esc_html($position_start_date_formatted); ?> -
                        <?php if ($position_active && empty($position_end_date)): ?>
                            <?php _e('Current job', 'jec-portfolio'); ?>
                        <?php else: ?>
                            <i class="fa fa-calendar-times-o"></i> <?php echo esc_html($position_end_date_formatted); ?>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="collapse show" id="position-content-<?php echo esc_attr($position_id); ?>">
                    <div class="position-description mb-4 py-4 px-3 border-top border-bottom">
                        <p><?php echo wp_kses_post($position_description); ?></p>
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