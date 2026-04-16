<?php

/**
 * Render for CTA Block
 */
$title = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('Ready to Start Your Project?');
$description = isset($attributes['description']) ? $attributes['description'] : weoryx_translate('Let\'s discuss how we can help you achieve your digital marketing goals. Get a free consultation today!');
$buttonText = isset($attributes['buttonText']) ? $attributes['buttonText'] : weoryx_translate('Contact Us');
$buttonUrl = isset($attributes['buttonUrl']) ? $attributes['buttonUrl'] : '/contact';
?>
<section class="cta-full-orange" id="cta">
    <div class="container" data-aos="fade-up">
        <div class="cta-full-content">
            <h2 class="cta-full-title"><?php echo wp_kses_post($title); ?></h2>
            <p class="cta-full-description">
                <?php echo wp_kses_post($description); ?>
            </p>
            <div class="cta-full-actions">
                <a href="<?php echo esc_url($buttonUrl); ?>" class="btn-full-white">
                    <?php echo esc_html($buttonText); ?>
                </a>
            </div>
        </div>
    </div>
</section>