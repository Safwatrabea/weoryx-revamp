<?php

/**
 * Render for Contact Block
 */
$title = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('Get In Touch');
$description = isset($attributes['description']) ? $attributes['description'] : weoryx_translate('Have a project in mind? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.');
$address = isset($attributes['address']) ? $attributes['address'] : get_option('weoryx_footer_address', 'Riyadh, Saudi Arabia');
$email = isset($attributes['email']) ? $attributes['email'] : get_option('weoryx_footer_email', 'info@weoryx.com');
?>
<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info" data-aos="fade-right">
                <h2><?php echo esc_html($title); ?></h2>
                <p><?php echo esc_html($description); ?></p>

                <div class="contact-info-list" style="margin-top: 30px;">
                    <div class="contact-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4><?php echo weoryx_translate('Address'); ?></h4>
                            <p><?php echo esc_html(weoryx_translate($address)); ?></p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4><?php echo weoryx_translate('Email'); ?></h4>
                            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form-wrapper" data-aos="fade-left">
                <!-- Fallback if CF7 not installed -->
                <div class="contact-form" id="contactForm">
                    <div class="form-row">
                        <div class="form-group"><input type="text" placeholder="<?php echo esc_attr(weoryx_translate('Your Name')); ?>"></div>
                        <div class="form-group"><input type="email" placeholder="<?php echo esc_attr(weoryx_translate('Your Email')); ?>"></div>
                    </div>
                    <div class="form-group"><textarea rows="5" placeholder="<?php echo esc_attr(weoryx_translate('Your Message')); ?>"></textarea></div>
                    <button class="btn btn-primary btn-lg"><?php echo weoryx_translate('Send Message'); ?></button>
                </div>
            </div>
        </div>
    </div>
</section>