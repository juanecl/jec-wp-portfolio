<?php
/**
 * Profile Display Template
 *
 * This template is used to display the profile information in the WordPress admin.
 * It includes the profile image, name, career, location, email, and links to LinkedIn and Stack Overflow profiles.
 * It also includes a language selection dropdown to switch between English and Spanish content.
 *
 * @package JEC_Portfolio
 * @version 1.0
 * @param WP_Post $post The current post object.
 */

 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!isset($profile_id)) {
    echo esc_html__('Profile ID not provided.', 'jec-portfolio');
    return;
}

$post = get_post($profile_id);
if (!$post) {
    echo '<p class="text-center mt-3 alert alert-warning alert-dismissible fade show">' . esc_html__('Profile not found.', 'jec-portfolio') . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></p>';
    return;
}
?>
<!-- Button to open the offcanvas -->
<div class="offcanvas-tab bg-secondary " id="offcanvasTab" data-bs-toggle="offcanvas" data-bs-target="#offcanvasProfile" aria-controls="offcanvasProfile">
    <i class="fas fa-user"></i>
</div>

<!-- Offcanvas -->
<div class="offcanvas offcanvas-start bg-secondary  text-white" tabindex="-1" id="offcanvasProfile" aria-labelledby="offcanvasProfileLabel">
    <div class="offcanvas-header mt-2">
        <h5 class="offcanvas-title" id="offcanvasProfileLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e('Close', 'jec-portfolio'); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <?php
        include plugin_dir_path(__FILE__) . 'content-profile.php';
        ?>
    </div>
</div>