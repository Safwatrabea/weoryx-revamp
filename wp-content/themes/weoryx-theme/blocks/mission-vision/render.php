<?php

/**
 * Render for Mission Vision Block
 */
$tag = isset($attributes['tag']) ? $attributes['tag'] : weoryx_translate('Who We Are');
$title = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('Our Mission & Vision');
$display_title = str_replace('|', '<span class="text-primary">', $title) . (strpos($title, '|') !== false ? '</span>' : '');

$items = isset($attributes['items']) && !empty($attributes['items']) ? $attributes['items'] : array(
    array(
        'icon' => 'fa-bullseye',
        'title' => weoryx_translate('Our Mission'),
        'desc' => weoryx_translate('To empower businesses with innovative digital marketing solutions that drive growth.')
    ),
    array(
        'icon' => 'fa-eye',
        'title' => weoryx_translate('Our Vision'),
        'desc' => weoryx_translate('To be the most trusted digital marketing partner for businesses worldwide.')
    ),
    array(
        'icon' => 'fa-heart',
        'title' => weoryx_translate('Our Values'),
        'desc' => weoryx_translate('Integrity, innovation, collaboration, and excellence drive everything we do.')
    )
);
?>
<section class="mission-vision-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-tag section-tag-center"><?php echo esc_html($tag); ?></span>
            <h2 class="section-title">
                <?php echo weoryx_format_title($title); ?>
            </h2>
        </div>
        <div class="mv-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 50px;">
            <?php foreach ($items as $index => $item) : ?>
                <div class="mv-card glass-morphism" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>" style="background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); padding: 50px 40px; border-radius: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(255, 255, 255, 0.6); transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); text-align: center; overflow: hidden; position: relative;">
                    <div class="mv-card-glow" style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(26, 95, 122, 0.05) 0%, transparent 70%); pointer-events: none;"></div>
                    <div class="mv-icon" style="width: 90px; height: 90px; background: #fff; color: var(--primary-blue, #1A5F7A); border-radius: 24px; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 30px; transition: all 0.5s ease; box-shadow: 0 10px 20px rgba(0,0,0,0.05); position: relative; z-index: 1;">
                        <i class="fas <?php echo esc_attr($item['icon']); ?>"></i>
                    </div>
                    <h3 style="font-size: 1.6rem; color: var(--primary-blue, #1A5F7A); margin-bottom: 20px; font-weight: 800; position: relative; z-index: 1;"><?php echo esc_html($item['title']); ?></h3>
                    <p style="color: #555; line-height: 1.7; font-size: 1.05rem; margin: 0; position: relative; z-index: 1; font-weight: 300;"><?php echo esc_html($item['desc']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <style>
            .mv-card:hover {
                transform: translateY(-15px) !important;
                background: rgba(255, 255, 255, 0.6) !important;
                border-color: var(--primary-blue, #1A5F7A) !important;
            }

            .mv-card:hover .mv-icon {
                background: var(--primary-blue, #1A5F7A) !important;
                color: #fff !important;
                transform: rotateY(180deg);
            }
        </style>
    </div>
</section>