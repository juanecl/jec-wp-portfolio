<?php
/**
 * Template for displaying the projects associated with a Position.
 *
 * @param array $args The arguments for the template.
 */

 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$projects = $args['projects'];
if (!empty($projects)) {
    ?>
    <div class="row mt-5 d-block mb-3">
        <h5><?php _e('Related projects', 'jec-portfolio'); ?></h5>
    </div>
    <?php
}
?>
<div class="row">
    <?php
    foreach ($projects as $project):
        include plugin_dir_path(__FILE__) . 'single-project.php';
    endforeach; ?>
</div>