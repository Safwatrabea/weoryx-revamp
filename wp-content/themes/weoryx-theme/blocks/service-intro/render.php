<?php

/**
 * Service Intro Block Template
 */

$tag = isset($attributes['tag']) ? $attributes['tag'] : '';
$title = isset($attributes['title']) ? $attributes['title'] : '';
$content = isset($attributes['content']) ? $attributes['content'] : '';
$imageUrl = isset($attributes['imageUrl']) ? $attributes['imageUrl'] : '';
$imagePosition = isset($attributes['imagePosition']) ? $attributes['imagePosition'] : 'right';
$buttonText = isset($attributes['buttonText']) ? $attributes['buttonText'] : '';
$buttonUrl = isset($attributes['buttonUrl']) ? $attributes['buttonUrl'] : '';

// Resolve relative URL if needed
if (!empty($imageUrl) && !str_starts_with($imageUrl, 'http')) {
    $imageUrl = get_template_directory_uri() . '/images/' . $imageUrl;
}

$row_class = $imagePosition === 'left' ? 'flex-row-reverse' : '';
?>

<section class="service-intro-section section-padding">
    <div class="container">
        <div class="service-intro-grid <?php echo esc_attr($row_class); ?>">
            <!-- Content Column -->
            <div class="intro-content-col">
                <div class="service-intro-content" data-aos="fade-up">
                    <?php if ($tag): ?>
                        <span class="section-tag"><?php echo esc_html(weoryx_translate($tag)); ?></span>
                    <?php endif; ?>

                    <?php if ($title): ?>
                        <h2 class="section-title"><?php echo weoryx_format_title(weoryx_translate($title)); ?></h2>
                    <?php endif; ?>

                    <?php if ($content): ?>
                        <div class="section-description">
                            <?php echo wp_kses_post(wpautop(weoryx_translate($content))); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($buttonText && $buttonUrl): ?>
                        <div class="intro-btn mt-4">
                            <a href="<?php echo esc_url($buttonUrl); ?>" class="btn btn-primary"><?php echo esc_html(weoryx_translate($buttonText)); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Image Column -->
            <div class="intro-image-col">
                <div class="service-intro-image" data-aos="<?php echo $imagePosition === 'left' ? 'fade-right' : 'fade-left'; ?>">
                    <?php if ($imageUrl): ?>
                        <div class="image-wrapper">
                            <img src="<?php echo esc_url($imageUrl); ?>" alt="<?php echo esc_attr($title); ?>" class="img-fluid rounded-4 shadow-lg">
                        </div>
                    <?php else: ?>
                        <!-- Placeholder if no image -->
                        <div class="image-placeholder rounded-4" style="background: #f5f5f5; height: 400px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image fa-4x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>