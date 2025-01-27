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

if (!isset($profile_id)) {
    echo esc_html__('Profile ID not provided.', 'jec-portfolio');
    return;
}

$post = get_post($profile_id);
if (!$post) {
    echo esc_html__('Profile not found.', 'jec-portfolio');
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
        <div class="container-fluid mt-0 p-0">
            <div class="card bg-secondary ">
                <div class="card-body text-center">
                    <!-- Display the profile image if it exists -->
                    <?php if (has_post_thumbnail($post->ID)): ?>
                        <div class="profile-image mb-3">
                            <?php echo get_the_post_thumbnail($post->ID, 'full', ['class' => 'img-fluid rounded-circle']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Display the profile name -->
                    <h2 class="text-white"><?php echo esc_html(get_post_meta($post->ID, 'wpcf-name', true)); ?></h2>
                    
                    <!-- Display the career information -->
                    <p class="lead text-white"><?php echo esc_html(get_post_meta($post->ID, 'wpcf-career', true)); ?></p>
                    
                    <hr class="my-4">
                    
                    <!-- Display the location -->
                    <p class="text-white"><?php echo esc_html(get_post_meta($post->ID, 'wpcf-location', true)); ?></p>
                    
                    <!-- Display the email and other links -->
                    <div class="contact-links">
                        <a href="mailto:<?php echo esc_attr(get_post_meta($post->ID, 'wpcf-email', true)); ?>" class="btn btn-lg btn-email text-email" title="<?php esc_attr_e('Email', 'jec-portfolio'); ?>">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <?php if ($cv_es = get_post_meta($post->ID, 'wpcf-cv_es', true)): ?>
                            <a href="<?php echo esc_url($cv_es); ?>" target="_blank" class="btn btn-lg btn-cv text-cv lang-es d-none" title="<?php esc_attr_e('Download CV', 'jec-portfolio'); ?>">
                                <i class="fas fa-file-download"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ($cv_en = get_post_meta($post->ID, 'wpcf-cv_en', true)): ?>
                            <a href="<?php echo esc_url($cv_en); ?>" target="_blank" class="btn btn-lg btn-cv text-cv lang-en" title="<?php esc_attr_e('Download Resume', 'jec-portfolio'); ?>">
                                <i class="fas fa-file-download"></i>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(get_post_meta($post->ID, 'wpcf-git_url', true)); ?>" target="_blank" class="btn btn-lg btn-gitlab text-gitlab" title="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="<?php echo esc_url(get_post_meta($post->ID, 'wpcf-linkedin_url', true)); ?>" target="_blank" class="btn btn-lg btn-linkedin text-linkedin" title="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="<?php echo esc_url(get_post_meta($post->ID, 'wpcf-stackoverflow_url', true)); ?>" target="_blank" class="btn btn-lg btn-stackoverflow text-stackoverflow" title="Stack Overflow">
                            <i class="fab fa-stack-overflow"></i>
                        </a>
                    </div>

                    <!-- Language toggle switch -->
                    <div class="text-center mb-4 mt-4">
                        <div class="d-flex justify-content-center align-items-center position-relative">
                            <span class="toggle-lang-text" id="toggle-lang-text-en">EN</span>
                            <div class="form-check form-switch mx-2">
                                <input class="form-check-input toggle-custom" type="checkbox" id="toggle-lang">
                            </div>
                            <span class="toggle-lang-text d-none" id="toggle-lang-text-es">ES</span>
                        </div>
                    </div>
                    
                    <!-- Bio Content -->
                    <div id="bio-content">
                        <p class="lang-es d-none text-white"><?php echo wp_kses_post(get_post_meta($post->ID, 'wpcf-bio_es', true)); ?></p>
                        <p class="lang-en text-white"><?php echo wp_kses_post(get_post_meta($post->ID, 'wpcf-bio_en', true)); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>