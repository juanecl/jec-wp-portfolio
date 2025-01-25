<?php
/**
 * Logic for displaying a Position with its associated Company and Projects.
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

// Pass data to the template
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
    'projects' => $projects,
];

include plugin_dir_path(__FILE__) . '../templates/position.php';