<?php

/**
 * Service Request Block Template
 */

$tag = isset($attributes['tag']) ? $attributes['tag'] : 'Ready to Start?';
$title = isset($attributes['title']) ? $attributes['title'] : 'Book This Service | Now';
$description = isset($attributes['description']) ? $attributes['description'] : '';
$features = isset($attributes['features']) ? $attributes['features'] : array();
$formShortcode = isset($attributes['formShortcode']) && !empty($attributes['formShortcode']) ? $attributes['formShortcode'] : '';

// Resolve form shortcode based on language if not explicitly set
if (empty($formShortcode)) {
    $current_lang = weoryx_get_current_lang();
    if ($current_lang === 'ar') {
        $formShortcode = get_option('weoryx_contact_form_ar_shortcode');
    } else {
        $formShortcode = get_option('weoryx_contact_form_en_shortcode');
    }

    // Final fallback to global
    if (!$formShortcode) {
        $formShortcode = get_option('weoryx_contact_form_shortcode');
    }
}

// Global fallback if everything fails
if (!$formShortcode) {
    $formShortcode = '[contact-form-7 id="123" title="Contact form 1"]';
}
?>

<section class="service-request-section section-padding">
    <div class="container">
        <div class="service-request-grid">
            <!-- Left Side: Text & CTA -->
            <div class="request-content-col">
                <div class="service-request-content" data-aos="fade-right">
                    <span class="section-tag"><?php echo esc_html(weoryx_translate($tag)); ?></span>
                    <h2 class="section-title"><?php echo weoryx_format_title(weoryx_translate($title)); ?></h2>
                    <?php if ($description): ?>
                        <p class="section-description">
                            <?php echo esc_html(weoryx_translate($description)); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($features)): ?>
                        <div class="service-features-list">
                            <?php foreach ($features as $feature): ?>
                                <div class="feature-item">
                                    <i class="<?php echo esc_attr($feature['icon'] ?? 'fas fa-check-circle'); ?> text-primary"></i>
                                    <span><?php echo esc_html(weoryx_translate($feature['text'] ?? '')); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="request-form-col">
                <div class="service-request-form-wrapper" data-aos="fade-left">
                    <div class="form-card">
                        <?php echo do_shortcode($formShortcode); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>