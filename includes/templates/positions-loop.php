<?php
/**
 * Positions Template
 *
 * This template is used to display a list of positions.
 * It expects an array of arguments ($args) to be passed to it for querying the positions.
 * If the arguments are not provided, it will display an error message using Bootstrap alerts.
 *
 * @package JEC_Portfolio
 * @version 1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Check if the arguments have been passed
if (!isset($positions)) {
    echo '<div class="alert alert-danger" role="alert">' . __('Error: Positions query results not provided.', 'jec-portfolio') . '</div>';
    return;
}
?>
<div class="container-fluid">         
    <div class="row">
        <!-- Display the number of results found -->
        <div class="col-md-12">
            <p class="results-count  d-block w-100 text-center mb-1">
                <?php echo $positions->found_posts . ' ' . __('positions found.', 'jec-portfolio'); ?>
            </p>
        </div>
    </div>
</div>
<div id="positions-loop" class="overflow-auto border-top border-bottom mb-2 pt-2 pb-2 mt-1 mb-1" style="max-height: 400px;">
    <?php
    // Check if there are any positions to display
    if ($positions->have_posts()) {
        // Loop through the positions and display each one
        while ($positions->have_posts()) {
            $positions->the_post();
            $post = get_post(get_the_ID());
            // Include the single position template for each position
            include plugin_dir_path(__FILE__) . 'position.php';
        }
        // Reset the post data after the loop
        wp_reset_postdata();
    } else {
        // Display a message if no positions are found
        echo '<p>' . __('No positions found.', 'jec-portfolio') . '</p>';
    }
    ?>
</div>