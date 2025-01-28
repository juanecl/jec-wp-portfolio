<?php
/**
 * Template for displaying the projects associated with a Position.
 *
 * @param array $args The arguments for the template.
 */

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
    foreach ($projects as $project): ?>
        <div class="col-md-6 mb-4">
            <div class="card project-card">
                <div class="card-header p-0">
                    <div class="d-flex flex-column p-2 w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="project-title d-block">
                                <h6>
                                    <?php if (!empty($project['url'])): ?>
                                        <a href="<?php echo esc_url($project['url']); ?>"
                                            target="_blank"><?php echo esc_html($project['title']); ?></a>
                                    <?php else: ?>
                                        <?php echo esc_html($project['title']); ?>
                                    <?php endif; ?>
                                </h6>
                            </span>
                            <i class="toggle_project fa fa-plus-circle ms-2" data-bs-toggle="collapse"
                                data-bs-target="#project-<?php echo esc_attr($project['id']); ?>"
                                style="cursor: pointer;"></i>
                        </div>
                        <span class="project-dates text-muted mt-2 text-small">
                            <i class="fa fa-calendar text-primary"></i> <?php echo esc_html($project['start_date']); ?> -
                            <?php if ($project['active'] && empty($project['end_date'])): ?>
                                <?php _e('active', 'jec-portfolio'); ?>
                            <?php else: ?>
                                <i class="fa fa-calendar text-primary"></i> <?php echo esc_html($project['end_date']); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="card-body text-justify">
                    <div id="project-<?php echo esc_attr($project['id']); ?>" class="collapse project-description">
                        <?php echo wp_kses_post($project['description']); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>