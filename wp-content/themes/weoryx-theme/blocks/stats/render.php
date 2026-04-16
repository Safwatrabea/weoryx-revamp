<?php

/**
 * Render for Stats Block
 */
$variant = isset($attributes['variant']) ? $attributes['variant'] : 'simple';
$is_premium = ($variant === 'premium');

$stats = isset($attributes['stats']) ? $attributes['stats'] : array(
    array('icon' => 'fa-users', 'number' => '150', 'label' => weoryx_translate('Happy Clients')),
    array('icon' => 'fa-project-diagram', 'number' => '300', 'label' => weoryx_translate('Projects Completed')),
    array('icon' => 'fa-award', 'number' => '25', 'label' => weoryx_translate('Awards Won')),
    array('icon' => 'fa-clock', 'number' => '10', 'label' => weoryx_translate('Years Experience'))
);

// CSS logic based on variant
$section_class = $is_premium ? 'stats-section floating-glass-bar' : 'stats-section';
$section_style = $is_premium ? 'position: relative; z-index: 10; margin-top: -60px; margin-bottom: -60px;' : '';

$grid_style = $is_premium
    ? 'background: rgba(26, 95, 122, 0.95); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); padding: 50px; border-radius: 30px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; box-shadow: 0 30px 60px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.1);'
    : '';

?>
<section class="<?php echo esc_attr($section_class); ?>" style="<?php echo esc_attr($section_style); ?>">
    <div class="container">
        <div class="stats-grid" style="<?php echo esc_attr($grid_style); ?>">
            <?php foreach ($stats as $index => $stat) : ?>
                <div class="stat-item" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>"
                    style="<?php echo $is_premium ? 'text-align: center; color: #fff; position: relative;' : ''; ?>">

                    <?php if ($is_premium) : ?>
                        <?php if ($index > 0) : ?>
                            <div class="stat-divider" style="position: absolute; left: -15px; top: 20%; height: 60%; width: 1px; background: rgba(255,255,255,0.2);"></div>
                        <?php endif; ?>

                        <div class="stat-icon" style="font-size: 1.5rem; margin-bottom: 15px; color: var(--accent-orange, #E85A3C); opacity: 0.9;">
                            <i class="fas <?php echo esc_attr(isset($stat['icon']) ? $stat['icon'] : 'fa-circle'); ?>"></i>
                        </div>
                        <div class="stat-number-wrapper" style="font-size: 2.5rem; font-weight: 800; line-height: 1; margin-bottom: 10px;">
                            <span class="stat-number" data-target="<?php echo esc_attr($stat['number']); ?>">0</span><span style="color: var(--accent-orange, #E85A3C);">+</span>
                        </div>
                        <span class="stat-label" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; opacity: 0.7;"><?php echo esc_html($stat['label']); ?></span>

                    <?php else: ?>
                        <div class="stat-icon">
                            <i class="fas <?php echo esc_attr(isset($stat['icon']) ? $stat['icon'] : 'fa-circle'); ?>"></i>
                        </div>
                        <div class="stat-number" data-target="<?php echo esc_attr($stat['number']); ?>">0</div>
                        <span class="stat-label"><?php echo esc_html($stat['label']); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>