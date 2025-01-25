<?php
/**
 * Template for displaying a Position with its associated Company and Projects.
 *
 * @param WP_Post $post The current post object.
 */

$post = get_query_var('post'); // Get the post object from the query variable
$position_id = $post->ID;
$post_meta = get_post_meta($post->ID);

$company_id = get_post_meta($position_id, 'wpcf-company_id', true);
$project_ids = get_post_meta($position_id, 'wpcf-project_ids', true);

// Get company details
$company_post = get_post($company_id);
$company_name = $company_post ? $company_post->post_title : '';
$company_website = get_post_meta($company_id, 'wpcf-url', true);
$company_category = get_post_meta($company_id, 'wpcf-category', true);

// Get position details
$position_title = get_the_title($position_id);
$position_description = get_post_meta($position_id, 'wpcf-description', true);
$position_start_date = get_post_meta($position_id, 'wpcf-start-date', true);
$position_end_date = get_post_meta($position_id, 'wpcf-end-date', true);
$position_active = get_post_meta($position_id, 'wpcf-active', true);

// Format dates based on locale
$position_start_date_formatted = date_i18n(get_option('date_format'), strtotime($position_start_date));
$position_end_date_formatted = $position_end_date ? date_i18n(get_option('date_format'), strtotime($position_end_date)) : '';

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
?>

<div class="container mt-5">
    <div class="card mb-4">
        <div class="card-header">
            <h2><?php echo esc_html($company_name); ?></h2>
            <span class="website">
                <?php echo esc_html($company_category); ?>
                <i class="fa fa-angle-double-right"></i>
                <a href="<?php echo esc_url($company_website); ?>" target="_blank">
                    <i class="fa fa-globe"></i> <?php _e('View website', 'jec-portfolio'); ?>
                </a>
            </span>
        </div>
        <div class="card-body">
            <div class="position-wrapper">
                <span class="position-dates">
                    <i class="fa fa-calendar-check-o"></i> <?php echo esc_html($position_start_date_formatted); ?> -
                    <?php if ($position_active && empty($position_end_date)): ?>
                        <?php _e('Current job', 'jec-portfolio'); ?>
                    <?php else: ?>
                        <i class="fa fa-calendar-times-o"></i> <?php echo esc_html($position_end_date_formatted); ?>
                    <?php endif; ?>
                </span>
                <h3 class="position-title"><?php echo esc_html($position_title); ?></h3>
                <p><?php echo wp_kses_post($position_description); ?></p>
            </div>
            <div class="mt-5 d-block">
                <h4><?php _e('Related projects', 'jec-portfolio'); ?></h4>
                <div class="row">
                    <?php foreach ($projects as $project): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header position-relative">
                                    <span class="project-title d-block w-100">
                                        <?php if (!empty($project['url'])): ?>
                                            <a href="<?php echo esc_url($project['url']); ?>" target="_blank"><?php echo esc_html($project['title']); ?></a>
                                        <?php else: ?>
                                            <?php echo esc_html($project['title']); ?>
                                        <?php endif; ?>
                                    </span>
                                    <span class="project-dates d-block w-100">
                                        <strong>
                                            <i class="fa fa-calendar"></i> <?php echo esc_html($project['start_date']); ?> -
                                            <?php if ($project['active'] && empty($project['end_date'])): ?>
                                                <?php _e('Active', 'jec-portfolio'); ?>
                                            <?php else: ?>
                                                <i class="fa fa-calendar"></i> <?php echo esc_html($project['end_date']); ?>
                                            <?php endif; ?>
                                        </strong>
                                    </span>
                                    <i class="toggle_project fa fa-plus-circle text-danger position-absolute" style="top: 10px; right: 10px; cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#project-<?php echo $project['id']; ?>"></i>
                                </div>
                                <div id="project-<?php echo $project['id']; ?>" class="collapse card-body project-description text-justify">
                                    <?php echo wp_kses_post($project['description']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleButtons = document.querySelectorAll('.toggle_project');
    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = document.querySelector(button.getAttribute('data-bs-target'));
            const bsCollapse = new bootstrap.Collapse(target, {
                toggle: false
            });
            if (target.classList.contains('show')) {
                bsCollapse.hide();
                button.classList.remove('fa-minus-circle');
                button.classList.add('fa-plus-circle');
            } else {
                bsCollapse.show();
                button.classList.remove('fa-plus-circle');
                button.classList.add('fa-minus-circle');
            }
        });
    });

    const collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(collapseElement => {
        collapseElement.addEventListener('show.bs.collapse', () => {
            const button = document.querySelector(`[data-bs-target="#${collapseElement.id}"]`);
            button.classList.remove('fa-plus-circle');
            button.classList.add('fa-minus-circle');
        });
        collapseElement.addEventListener('hide.bs.collapse', () => {
            const button = document.querySelector(`[data-bs-target="#${collapseElement.id}"]`);
            button.classList.remove('fa-minus-circle');
            button.classList.add('fa-plus-circle');
        });
    });
});
</script>