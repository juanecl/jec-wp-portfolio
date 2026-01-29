
<div class="col-md-6 mb-4">
    <div class="card project-card">
        <div class="card-header p-0 project-header-toggle jec-collapse-toggle" role="button" tabindex="0" data-jec-collapse-target="#project-<?php echo esc_attr($project['id']); ?>" aria-controls="project-<?php echo esc_attr($project['id']); ?>" aria-expanded="false">
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
                    <button class="btn text-secondary toggle-project-btn ms-2 jec-collapse-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#project-<?php echo esc_attr($project['id']); ?>" data-jec-collapse-target="#project-<?php echo esc_attr($project['id']); ?>" aria-expanded="false" aria-controls="project-<?php echo esc_attr($project['id']); ?>">
                        <span class="toggle-icon-wrapper" aria-hidden="true">
                            <i class="fa fa-plus toggle-icon toggle-icon-plus"></i>
                            <i class="fa fa-minus toggle-icon toggle-icon-minus"></i>
                        </span>
                    </button>
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
                <?php echo apply_filters('the_content', (string) ($project['description'] ?? '')); ?>
            </div>
        </div>
    </div>
</div>