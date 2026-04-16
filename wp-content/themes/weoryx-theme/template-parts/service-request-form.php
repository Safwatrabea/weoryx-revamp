<?php

/**
 * Service Request Form Template Part
 * 
 * Displays a CTA and contact form at the bottom of single service pages.
 */

$contact_form_shortcode = get_option('weoryx_contact_form_shortcode');

// Fallback if no shortcode is set
if (!$contact_form_shortcode) {
    $contact_form_shortcode = '[contact-form-7 id="123" title="Contact form 1"]';
}
?>

<section class="service-request-section section-padding">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left Side: Text & CTA -->
            <div class="service-request-content" data-aos="fade-right">
                <span class="section-tag"><?php echo esc_html(weoryx_translate('Ready to Start?')); ?></span>
                <h2 class="section-title"><?php echo weoryx_format_title(weoryx_translate('Book This Service | Now')); ?></h2>
                <p class="section-description">
                    <?php echo esc_html(weoryx_translate('Ready to take your business to the next level? Fill out the form below and our team will get back to you with a tailored strategy.')); ?>
                </p>

                <div class="service-features-list">
                    <div class="feature-item">
                        <i class="fas fa-check-circle text-primary"></i>
                        <span><?php echo esc_html(weoryx_translate('Free Consultation')); ?></span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle text-primary"></i>
                        <span><?php echo esc_html(weoryx_translate('Custom Strategy')); ?></span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle text-primary"></i>
                        <span><?php echo esc_html(weoryx_translate('Dedicated Support')); ?></span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="service-request-form-wrapper" data-aos="fade-left">
                <div class="form-card">
                    <?php echo do_shortcode($contact_form_shortcode); ?>
                </div>
            </div>
        </div>
    </div>
</section>