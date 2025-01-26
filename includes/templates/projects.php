<?php
/**
 * Template for displaying the projects associated with a Position.
 *
 * @param array $args The arguments for the template.
 */
$projects = $args['projects'];

foreach ($projects as $project): ?>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header p-0">
                <div class="d-flex flex-column bg-light text-dark p-2 w-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="project-title d-block">
                            <h6>
                            <?php if (!empty($project['url'])): ?>
                                <a href="<?php echo esc_url($project['url']); ?>" target="_blank" class="text-dark"><?php echo esc_html($project['title']); ?></a>
                            <?php else: ?>
                                <?php echo esc_html($project['title']); ?>
                            <?php endif; ?>
                            </h6>
                        </span>
                        <i class="toggle_project fa fa-plus-circle text-dark ms-2" data-bs-toggle="collapse" data-bs-target="#project-<?php echo esc_attr($project['id']); ?>" style="cursor: pointer;"></i>
                    </div>
                    <span class="project-dates text-muted mt-2">
                        <i class="fa fa-calendar"></i> <?php echo esc_html($project['start_date']); ?> -
                        <?php if ($project['active'] && empty($project['end_date'])): ?>
                            <?php _e('active', 'jec-portfolio'); ?>
                        <?php else: ?>
                            <i class="fa fa-calendar"></i> <?php echo esc_html($project['end_date']); ?>
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