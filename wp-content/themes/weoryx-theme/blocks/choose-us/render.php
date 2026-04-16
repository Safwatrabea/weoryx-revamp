<?php

/**
 * Render for Why Choose Us Block
 */
$tag = isset($attributes['tag']) ? $attributes['tag'] : weoryx_translate('Why WeOryx?');
$title = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('Why Choose | Our Agency');
$description = isset($attributes['description']) ? $attributes['description'] : weoryx_translate('We combine creativity with technical expertise to deliver results that matter.');
$items = isset($attributes['items']) && !empty($attributes['items']) ? $attributes['items'] : array(
    array(
        'icon' => 'fa-users',
        'title' => weoryx_translate('Expert Team'),
        'desc' => weoryx_translate('Our team consists of certified professionals with years of experience.')
    ),
    array(
        'icon' => 'fa-rocket',
        'title' => weoryx_translate('Proven Results'),
        'desc' => weoryx_translate('We focus on data-driven strategies that deliver measurable ROI.')
    ),
    array(
        'icon' => 'fa-headset',
        'title' => weoryx_translate('24/7 Support'),
        'desc' => weoryx_translate('We are always here to help you solve any issues and ensure continuity.')
    )
);
?>
<section class="choose-us">
    <div class="container">
        <div class="choose-container">
            <div class="choose-header-side" data-aos="fade-right">
                <span class="section-tag"><?php echo esc_html($tag); ?></span>
                <h2 class="section-title">
                    <?php echo weoryx_format_title(isset($attributes['title']) ? $attributes['title'] : 'Why Choose | Our Agency'); ?>
                </h2>
                <p class="section-subtitle"><?php echo esc_html($description); ?></p>

                <div class="tech-waves">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <div class="choose-items-side">
                <div class="choose-grid">
                    <?php foreach ($items as $index => $item) : ?>
                        <div class="choose-item">
                            <div class="choose-icon"><i class="fas <?php echo esc_attr($item['icon']); ?>"></i></div>
                            <div class="choose-content">
                                <h3 class="choose-title"><?php echo esc_html($item['title']); ?></h3>
                                <p class="choose-desc">
                                    <?php
                                    echo esc_html(isset($item['desc']) ? $item['desc'] : (isset($item['description']) ? $item['description'] : ''));
                                    ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>