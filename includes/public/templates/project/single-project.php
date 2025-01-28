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
                        <?php _e('active', PLUGIN_TEXT_DOMAIN); ?>
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