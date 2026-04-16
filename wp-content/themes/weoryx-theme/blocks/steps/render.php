<?php

/**
 * Render for Step Process Block
 */
$tag = isset($attributes['tag']) ? $attributes['tag'] : weoryx_translate('Our Process');
$title = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('How We Work');
$items = isset($attributes['items']) && !empty($attributes['items']) ? $attributes['items'] : array(
    array(
        'num' => '01',
        'title' => weoryx_translate('Discovery'),
        'desc' => weoryx_translate('We learn about your business, goals, and target audience to create a tailored strategy.')
    ),
    array(
        'num' => '02',
        'title' => weoryx_translate('Strategy'),
        'desc' => weoryx_translate('We develop a comprehensive strategy based on our research and your objectives.')
    ),
    array(
        'num' => '03',
        'title' => weoryx_translate('Execution'),
        'desc' => weoryx_translate('Our team brings the strategy to life with expert execution and attention to detail.')
    ),
    array(
        'num' => '04',
        'title' => weoryx_translate('Optimization'),
        'desc' => weoryx_translate('We continuously monitor, analyze, and optimize to maximize your results.')
    )
);
?>
<section class="process-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag section-tag-center"><?php echo esc_html(weoryx_translate($tag)); ?></span>
            <h2 class="section-title section-title-center">
                <?php echo weoryx_format_title($title); ?>
            </h2>
        </div>

        <div class="process-grid">
            <?php foreach ($items as $index => $item) : ?>
                <div class="process-step" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="process-number"><?php echo esc_html($item['num']); ?></div>
                    <div class="process-content">
                        <h4><?php echo esc_html(weoryx_translate($item['title'])); ?></h4>
                        <p><?php echo esc_html(weoryx_translate($item['desc'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>