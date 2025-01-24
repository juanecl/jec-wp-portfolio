<?php
/**
 * Profile Display Template
 *
 * This template is used to display the profile information in the WordPress admin.
 * It includes the profile image, name, career, location, email, and links to LinkedIn and Stack Overflow profiles.
 * It also includes a language selection dropdown to switch between English and Spanish content.
 *
 * @param WP_Post $post The current post object.
 */
?>

<div class="container mt-5">
    <div class="jumbotron text-center">
        <!-- Display the profile image if it exists -->
        <?php if (has_post_thumbnail($post->ID)): ?>
            <div class="profile-image mb-3">
                <?php echo get_the_post_thumbnail($post->ID, 'full', ['class' => 'img-fluid rounded-circle']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Display the profile name -->
        <h1 class="display-4"><?php echo esc_html(get_post_meta($post->ID, 'wpcf-name', true)); ?></h1>
        
        <!-- Display the career information -->
        <p class="lead"><?php echo esc_html(get_post_meta($post->ID, 'wpcf-career', true)); ?></p>
        
        <hr class="my-4">
        
        <!-- Display the location -->
        <p><?php echo esc_html(get_post_meta($post->ID, 'wpcf-location', true)); ?></p>
        
        <!-- Display the email as a mailto link -->
        <p><a href="mailto:<?php echo esc_attr(get_post_meta($post->ID, 'wpcf-email', true)); ?>" class="btn btn-primary"><?php echo esc_html(get_post_meta($post->ID, 'wpcf-email', true)); ?></a></p>
    </div>

    <div class="text-center mb-4">
        <!-- Language selection dropdown -->
        <label for="select-lang" class="form-label"><?php _e('Select Language:', 'jec-portfolio'); ?></label>
        <select class="form-select" id="select-lang">
            <option value="en"><?php _e('English', 'jec-portfolio'); ?></option>
            <option value="es"><?php _e('Spanish', 'jec-portfolio'); ?></option>
        </select>
    </div>

    <div class="row">
        <div class="col-md-6">
            <!-- Spanish Bio Card -->
            <div class="card mb-4 lang-es d-none">
                <div class="card-body">
                    <h5 class="card-title"><?php _e('Bio', 'jec-portfolio'); ?></h5>
                    <p class="card-text"><?php echo nl2br(esc_html(get_post_meta($post->ID, 'wpcf-bio_es', true))); ?></p>
                </div>
            </div>
            
            <!-- English Summary Card -->
            <div class="card mb-4 lang-en">
                <div class="card-body">
                    <h5 class="card-title"><?php _e('Summary', 'jec-portfolio'); ?></h5>
                    <p class="card-text"><?php echo nl2br(esc_html(get_post_meta($post->ID, 'wpcf-bio_en', true))); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Spanish CV Card -->
            <div class="card mb-4 lang-es d-none">
                <div class="card-body">
                    <h5 class="card-title"><?php _e('CV', 'jec-portfolio'); ?></h5>
                    <?php if ($cv_es = get_post_meta($post->ID, 'wpcf-cv_es', true)): ?>
                        <a href="<?php echo esc_url($cv_es); ?>" target="_blank" class="btn btn-primary"><?php _e('Download CV', 'jec-portfolio'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- LinkedIn Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php _e('LinkedIn', 'jec-portfolio'); ?></h5>
                    <a href="<?php echo esc_url(get_post_meta($post->ID, 'wpcf-linkedin_url', true)); ?>" target="_blank" class="btn btn-outline-primary">
                        <i class="fab fa-linkedin"></i> LinkedIn
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- Stack Overflow Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php _e('Stack Overflow', 'jec-portfolio'); ?></h5>
                    <a href="<?php echo esc_url(get_post_meta($post->ID, 'wpcf-stackoverflow_url', true)); ?>" target="_blank" class="btn btn-outline-warning">
                        <i class="fab fa-stack-overflow"></i> Stack Overflow
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('select-lang').addEventListener('change', function() {
    var lang = this.value;
    var esElements = document.querySelectorAll('.lang-es');
    var enElements = document.querySelectorAll('.lang-en');
    if (lang === 'es') {
        esElements.forEach(function(el) {
            el.classList.remove('d-none');
        });
        enElements.forEach(function(el) {
            el.classList.add('d-none');
        });
    } else {
        esElements.forEach(function(el) {
            el.classList.add('d-none');
        });
        enElements.forEach(function(el) {
            el.classList.remove('d-none');
        });
    }
});
</script>