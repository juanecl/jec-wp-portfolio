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
$position_start_date = get_post_meta($position_id, 'wpcf-start_date', true);
$position_end_date = get_post_meta($position_id, 'wpcf-end_date', true);

// Get projects details
$projects = [];
if (is_array($project_ids)) {
    foreach ($project_ids as $project_id) {
        $projects[] = [
            'title' => get_the_title($project_id),
            'url' => get_permalink($project_id),
            'description' => get_post_meta($project_id, 'wpcf-description', true),
            'start_date' => get_post_meta($project_id, 'wpcf-start_date', true),
            'end_date' => get_post_meta($project_id, 'wpcf-end_date', true),
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
                    <i class="fa fa-globe"></i> <?php _e('Ver sitio web', 'jec-portfolio'); ?>
                </a>
            </span>
        </div>
        <div class="card-body">
            <div class="position-wrapper">
                <span class="cargo-dates">
                    <i class="fa fa-calendar-check-o"></i> <?php echo esc_html($position_start_date); ?> -
                    <i class="fa fa-calendar-times-o"></i> <?php echo esc_html($position_end_date); ?>
                </span>
                <h3 class="cargo-title"><?php echo esc_html($position_title); ?></h3>
                <p><?php echo wp_kses_post($position_description); ?></p>
            </div>
            <h4><?php _e('Proyectos realizados', 'jec-portfolio'); ?></h4>
            <div class="row">
                <?php foreach ($projects as $project): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <span class="project-title">
                                    <a href="<?php echo esc_url($project['url']); ?>" target="_blank"><?php echo esc_html($project['title']); ?></a>
                                    <i class="toggle_project fa fa-plus-circle"></i>
                                </span>
                                <span class="project-dates">
                                    <strong>
                                        <i class="fa fa-calendar"></i> <?php echo esc_html($project['start_date']); ?> -
                                        <i class="fa fa-calendar"></i> <?php echo esc_html($project['end_date']); ?>
                                    </strong>
                                </span>
                            </div>
                            <div class="card-body project-description" style="display: none;">
                                <?php echo wp_kses_post($project['description']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggleButtons = document.querySelectorAll('.toggle_project');
    toggleButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var projectDescription = this.closest('.card').querySelector('.project-description');
            if (projectDescription.style.display === 'none') {
                projectDescription.style.display = 'block';
                this.classList.remove('fa-plus-circle');
                this.classList.add('fa-minus-circle');
            } else {
                projectDescription.style.display = 'none';
                this.classList.remove('fa-minus-circle');
                this.classList.add('fa-plus-circle');
            }
        });
    });
});
</script>