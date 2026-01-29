<?php
/**
 * PDF template for positions list.
 *
 * @package JEC_Portfolio
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!isset($positions)) {
    return;
}

$blog_title = get_bloginfo('name');
$blog_description = get_bloginfo('description');
$pdf_title_parts = array_filter([
    $blog_title,
    $blog_description,
    __('Trayectoria', 'jec-portfolio'),
]);
$pdf_title = implode(' - ', $pdf_title_parts);
?>
<!doctype html>
<html lang="<?php echo esc_attr(get_locale()); ?>">
<head>
    <meta charset="utf-8" />
    <title><?php echo esc_html($pdf_title); ?></title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #000;
            background: #fff;
            font-size: 12px;
            margin: 24px;
        }
        h1 { font-size: 18px; margin: 0 0 16px; }
        h2 { font-size: 14px; margin: 0 0 8px; }
        h3 { font-size: 12px; margin: 0 0 6px; }
        .position {
            border: 1px solid #000;
            margin-bottom: 16px;
            padding: 12px;
        }
        .meta {
            font-size: 11px;
            margin-bottom: 6px;
        }
        .section { margin-top: 8px; }
        .badge {
            display: inline-block;
            border: 1px solid #000;
            padding: 2px 6px;
            margin: 2px 4px 2px 0;
            font-size: 10px;
        }
        a { color: #000; text-decoration: underline; }
    </style>
</head>
<body>
    <h1><?php echo esc_html($pdf_title); ?></h1>

    <?php if ($positions->have_posts()): ?>
        <?php while ($positions->have_posts()): $positions->the_post(); ?>
            <?php
                $args = PositionRenderer::prepare_position_args(get_the_ID());
                $title = $args['position_title'];
                $company = $args['company_name'];
                $company_url = $args['company_website'];
                $category = $args['company_category'];
                $start = $args['position_start_date_formatted'];
                $end = $args['position_end_date_formatted'];
                $active = $args['position_active'];
                $freelance = $args['freelance'];
                $description = $args['position_description'];
                if (is_string($description) && trim($description) !== '') {
                    $description = apply_filters('the_content', $description);
                }
                $knowledge_terms = $args['knowledge_terms'];
                $skills_terms = $args['skills_terms'];
            ?>
            <div class="position">
                <h2>
                    <?php echo esc_html($title); ?>
                    <?php if (!empty($company_url)): ?>
                        @ <a href="<?php echo esc_url($company_url); ?>"><?php echo esc_html($company); ?></a>
                    <?php else: ?>
                        @ <?php echo esc_html($company); ?>
                    <?php endif; ?>
                </h2>
                <div class="meta">
                    <?php echo esc_html($start); ?> -
                    <?php if ($active && empty($end)): ?>
                        <?php echo esc_html(__('Current job', 'jec-portfolio')); ?>
                    <?php else: ?>
                        <?php echo esc_html($end); ?>
                    <?php endif; ?>
                    <?php if (!empty($category)): ?>
                        · <?php echo esc_html($category); ?>
                    <?php endif; ?>
                    <?php if ($freelance): ?>
                        · <?php echo esc_html(__('Freelance', 'jec-portfolio')); ?>
                    <?php endif; ?>
                </div>
                <?php if (!empty($description)): ?>
                    <div class="section">
                        <?php echo $description; ?>
                    </div>
                <?php endif; ?>
                <div class="section">
                    <h3><?php echo esc_html(__('Knowledge', 'jec-portfolio')); ?></h3>
                    <?php if (!empty($knowledge_terms)): ?>
                        <?php foreach ($knowledge_terms as $term): ?>
                            <span class="badge"><?php echo esc_html($term['name']); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span><?php echo esc_html(__('No knowledge terms assigned.', 'jec-portfolio')); ?></span>
                    <?php endif; ?>
                </div>
                <div class="section">
                    <h3><?php echo esc_html(__('Skills', 'jec-portfolio')); ?></h3>
                    <?php if (!empty($skills_terms)): ?>
                        <?php foreach ($skills_terms as $term): ?>
                            <span class="badge"><?php echo esc_html($term['name']); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span><?php echo esc_html(__('No skills terms assigned.', 'jec-portfolio')); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    <?php else: ?>
        <p><?php echo esc_html(__('No positions found.', 'jec-portfolio')); ?></p>
    <?php endif; ?>
</body>
</html>
