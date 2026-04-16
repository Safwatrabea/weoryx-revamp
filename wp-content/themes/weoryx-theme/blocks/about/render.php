<?php

/**
 * Render for About Block
 */
$variant = isset($attributes['variant']) ? $attributes['variant'] : 'simple';
$is_premium = ($variant === 'premium');

$title_attr = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('Turning Your Ideas Into | Digital Reality');
$title = weoryx_format_title($title_attr);
$description = isset($attributes['description']) ? $attributes['description'] : weoryx_translate('WeOryx is a specialized digital marketing agency offering creative and innovative solutions for companies and organizations. We believe every brand deserves a unique digital presence.');
$imageUrl = isset($attributes['imageUrl']) ? $attributes['imageUrl'] : '';

if (empty($imageUrl)) {
    $imageUrl = get_template_directory_uri() . '/images/about-team.jpg';
}

// Styling & Class logic
$section_class = $is_premium ? 'about-section about-premium' : 'about-section';
$grid_style = $is_premium ? 'display: grid; grid-template-columns: 1.1fr 1fr; gap: 80px; align-items: center;' : '';
$content_style = $is_premium ? 'padding-left: 40px;' : '';

?>
<section class="<?php echo esc_attr($section_class); ?>" id="about">
    <div class="container">
        <div class="about-grid" style="<?php echo esc_attr($grid_style); ?>">

            <div class="about-image" data-aos="fade-right">
                <div class="about-image-wrapper">
                    <?php if ($is_premium) : ?>
                        <div class="about-decoration-blob" style="position: absolute; top: -20px; left: -20px; width: 100px; height: 100px; background: var(--accent-orange, #E85A3C); opacity: 0.1; filter: blur(40px); border-radius: 50%; z-index: -1;"></div>
                        <img src="<?php echo esc_url($imageUrl); ?>" alt="About Us" class="about-main-img" style="border-radius: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); position: relative; z-index: 1; border: 1px solid rgba(255,255,255,0.2); width: 100%;">
                    <?php else: ?>
                        <img src="<?php echo esc_url($imageUrl); ?>" alt="About Us" class="about-main-img">
                        <div class="about-image-decoration"></div>
                    <?php endif; ?>

                    <div class="about-experience-badge" data-aos="zoom-in" data-aos-delay="300"
                        style="<?php echo $is_premium ? 'background: var(--primary-blue, #1A5F7A); color: #fff; padding: 25px; border-radius: 20px; position: absolute; bottom: -30px; inset-inline-end: -30px; box-shadow: 0 15px 30px rgba(26, 95, 122, 0.2); border: 4px solid #fff; z-index: 2; text-align: center; min-width: 160px;' : ''; ?>">

                        <div class="badge-number" style="<?php echo $is_premium ? 'font-size: 2.8rem; font-weight: 800; line-height: 1; margin-bottom: 5px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);' : ''; ?>">
                            <?php echo !empty($attributes['expNumber']) ? esc_html($attributes['expNumber']) : '12+'; ?>
                        </div>
                        <div class="badge-text" style="<?php echo $is_premium ? 'font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; opacity: 1; line-height: 1.2;' : ''; ?>">
                            <?php echo !empty($attributes['expText']) ? esc_html($attributes['expText']) : weoryx_translate('Years of Experience'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-content" data-aos="fade-left" style="<?php echo esc_attr($content_style); ?> position: relative;">
                <?php if ($is_premium) : ?>
                    <div class="about-decoration-dots" style="position: absolute; top: 0; right: 0; opacity: 0.1; pointer-events: none;">
                        <svg width="100" height="100" viewBox="0 0 100 100" fill="currentColor">
                            <circle cx="2" cy="2" r="2" />
                            <circle cx="22" cy="2" r="2" />
                            <circle cx="42" cy="2" r="2" />
                            <circle cx="2" cy="22" r="2" />
                            <circle cx="22" cy="22" r="2" />
                            <circle cx="42" cy="22" r="2" />
                        </svg>
                    </div>
                <?php endif; ?>

                <div class="about-header">
                    <span class="section-tag"><?php echo isset($attributes['tag']) ? esc_html($attributes['tag']) : weoryx_translate('About WeOryx'); ?></span>
                    <h2 class="section-title" style="<?php echo $is_premium ? 'margin-top: 10px;' : ''; ?>"><?php echo wp_kses_post($title); ?></h2>
                </div>

                <p class="about-description" style="<?php echo $is_premium ? 'font-size: 1.15rem; line-height: 1.8; color: #555; margin-bottom: 40px; font-weight: 300;' : ''; ?>">
                    <?php echo wp_kses_post($description); ?>
                </p>

                <div class="about-features" style="<?php echo $is_premium ? 'display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 40px;' : ''; ?>">
                    <?php
                    $features = isset($attributes['features']) ? $attributes['features'] : array();
                    if (empty($features)) {
                        $features = array(
                            array('icon' => 'fas fa-rocket', 'title' => weoryx_translate('Fast Results'), 'description' => weoryx_translate('We focus on digital growth in record time.')),
                            array('icon' => 'fas fa-users', 'title' => weoryx_translate('Expert Team'), 'description' => weoryx_translate('Dedicated specialists for your success.'))
                        );
                    }
                    foreach ($features as $feature) :
                    ?>
                        <div class="feature-item" style="<?php echo $is_premium ? 'display: flex; gap: 15px; background: #fff; padding: 20px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.3s ease; border: none;' : ''; ?>">
                            <div class="feature-icon" style="<?php echo $is_premium ? 'flex-shrink: 0; width: 45px; height: 45px; background: rgba(232, 90, 60, 0.1); color: var(--accent-orange, #E85A3C); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; box-shadow: none;' : ''; ?>">
                                <i class="<?php echo esc_attr($feature['icon']); ?>"></i>
                            </div>
                            <div class="feature-content">
                                <h4 style="<?php echo $is_premium ? 'font-size: 1.05rem; margin-bottom: 5px; color: var(--primary-blue, #1A5F7A); font-weight: 700;' : ''; ?>"><?php echo esc_html(weoryx_translate($feature['title'])); ?></h4>
                                <p style="<?php echo $is_premium ? 'font-size: 0.85rem; line-height: 1.5; color: #777; margin: 0;' : ''; ?>"><?php echo esc_html(weoryx_translate($feature['description'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (!empty($attributes['buttonText'])) : ?>
                    <div class="about-cta">
                        <a href="<?php echo isset($attributes['buttonUrl']) ? esc_url($attributes['buttonUrl']) : '/about'; ?>" class="btn btn-primary <?php echo $is_premium ? 'btn-lg' : ''; ?>" style="<?php echo $is_premium ? 'padding: 15px 40px; border-radius: 50px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;' : ''; ?>">
                            <?php echo esc_html(weoryx_translate($attributes['buttonText'])); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>