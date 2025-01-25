<?php
/**
 * Template for displaying a Position with its associated Company and Projects.
 *
 * @param WP_Post $post The current post object.
 */

$post = get_query_var('post'); // Get the post object from the query variable

// Debugging: Print the post object
echo '<pre>';
echo 'Post Object: ';
var_dump($post);
echo '</pre>';

$position_id = $post->ID;
$post_meta = get_post_meta($post->ID);

// Mostrar los metadatos
if (!empty($post_meta)) {
    foreach ($post_meta as $key => $values) {
        echo "<strong>Meta Key:</strong> " . esc_html($key) . "<br>";
        echo "<strong>Values:</strong><br>";

        // Cada meta key puede tener múltiples valores
        foreach ($values as $value) {
            echo esc_html($value) . "<br>";
        }

        echo "<hr>";
    }
} else {
    echo "No hay metadatos disponibles para este post.";
}
$company_id = get_post_meta($position_id, 'wpcf-company_id', true);
$project_ids = get_post_meta($position_id, 'wpcf-project_ids', true);

// Debugging: Print the company ID and project IDs
echo '<pre>';
echo 'Company ID: ';
var_dump($company_id);
echo 'Project IDs: ';
var_dump($project_ids);
echo '</pre>';

// Get company details
$company_post = get_post($company_id);
$company_name = $company_post ? $company_post->post_title : '';
$company_website = get_post_meta($company_id, 'wpcf-url', true);

// Debugging: Print the company details
echo '<pre>';
echo 'Company Name: ';
var_dump($company_name);
echo 'Company Website: ';
var_dump($company_website);
echo '</pre>';

// Get position details
$position_title = get_the_title($position_id);
$position_description = get_post_meta($position_id, 'wpcf-description', true);
$position_start_date = get_post_meta($position_id, 'wpcf-start_date', true);
$position_end_date = get_post_meta($position_id, 'wpcf-end_date', true);

// Debugging: Print the position details
echo '<pre>';
echo 'Position Title: ';
var_dump($position_title);
echo 'Position Description: ';
var_dump($position_description);
echo 'Position Start Date: ';
var_dump($position_start_date);
echo 'Position End Date: ';
var_dump($position_end_date);
echo '</pre>';

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

// Debugging: Print the projects details
echo '<pre>';
echo 'Projects: ';
print_r($projects);
echo '</pre>';
?>

<div class="container mt-5">
    <div class="card mb-4">
        <div class="card-header">
            <h2><?php echo esc_html($company_name); ?></h2>
            <span class="website">
                <?php _e('Consultoría en innovación', 'jec-portfolio'); ?>
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
                <p><?php echo esc_html($position_description); ?></p>
            </div>
            <h4><?php _e('Proyectos realizados', 'jec-portfolio'); ?></h4>
            <div class="row">
                <?php foreach ($projects as $project): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <span class="project-title">
                                    <a href="<?php echo esc_url($project['url']); ?>" target="_blank"><?php echo esc_html($project['title']); ?></a>
                                    <i class="toggle_project fa fa-plus-circle" style="cursor:pointer;"></i>
                                </span>
                                <span class="project-dates">
                                    <strong>
                                        <i class="fa fa-calendar"></i> <?php echo esc_html($project['start_date']); ?> -
                                        <i class="fa fa-calendar"></i> <?php echo esc_html($project['end_date']); ?>
                                    </strong>
                                </span>
                            </div>
                            <div class="card-body project-description">
                                <?php echo esc_html($project['description']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>