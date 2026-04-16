<?php

/**
 * Template Name: Contact Page
 */
get_header(); ?>

<main id="main-content">
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1 class="page-title"><?php echo esc_html(weoryx_translate('Contact Us')); ?></h1>
                <nav class="page-breadcrumb">
                    <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(weoryx_translate('Home')); ?></a>
                    <i class="fas fa-chevron-right"></i>
                    <span><?php echo esc_html(weoryx_translate('Contact Us')); ?></span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Info -->
                <div class="contact-info" data-aos="fade-right">
                    <h2><?php echo esc_html(weoryx_translate('Get In Touch')); ?></h2>
                    <p><?php echo esc_html(weoryx_translate('Have a project in mind? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.')); ?></p>

                    <div class="contact-info-list">
                        <!-- Address -->
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-info-content">
                                <h4><?php echo esc_html(weoryx_translate('Address')); ?></h4>
                                <?php
                                $lang = weoryx_get_current_lang();
                                $address = get_option('weoryx_footer_address_' . $lang);
                                if (!$address) {
                                    $address = get_option('weoryx_footer_address', 'Riyadh, Saudi Arabia');
                                }
                                ?>
                                <p><?php echo esc_html($address); ?></p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-info-content">
                                <h4><?php echo esc_html(weoryx_translate('Email')); ?></h4>
                                <?php $email = get_option('weoryx_footer_email', 'info@weoryx.com'); ?>
                                <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-info-content">
                                <h4><?php echo esc_html(weoryx_translate('Phone')); ?></h4>
                                <?php $phone = get_option('weoryx_footer_phone', '+966 50 123 4567'); ?>
                                <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="contact-social">
                        <h4><?php echo esc_html(weoryx_translate('Follow Us')); ?></h4>
                        <div class="social-links">
                            <?php
                            $socials = array(
                                'facebook-f' => 'weoryx_facebook_url',
                                'twitter'    => 'weoryx_twitter_url',
                                'linkedin-in' => 'weoryx_linkedin_url',
                                'instagram'  => 'weoryx_instagram_url'
                            );
                            foreach ($socials as $icon => $option) :
                                $url = get_option($option);
                                if ($url) : ?>
                                    <a href="<?php echo esc_url($url); ?>" class="social-link" target="_blank" rel="noopener">
                                        <i class="fab fa-<?php echo $icon; ?>"></i>
                                    </a>
                            <?php endif;
                            endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form-wrapper" data-aos="fade-left">
                    <?php
                    // Get the current language
                    $current_lang = weoryx_get_current_lang();

                    // Get the appropriate contact form shortcode based on language
                    $contact_form = get_option('weoryx_contact_form_shortcode_' . $current_lang);

                    // Fallback to default if language-specific form not found
                    if (!$contact_form) {
                        $contact_form = get_option('weoryx_contact_form_shortcode');
                    }

                    if ($contact_form) {
                        echo do_shortcode($contact_form);
                    } else {
                        // Fallback: Display a message if no form is configured
                        echo '<div class="contact-form-placeholder">';
                        echo '<h3>' . esc_html(weoryx_translate('Contact Form')) . '</h3>';
                        echo '<p>' . esc_html(weoryx_translate('Please configure the contact form shortcode in Theme Settings.')) . '</p>';
                        echo '<p style="color: #999; font-size: 0.9rem;">Run: <code>http://localhost/Weoryx/create_contact_forms.php</code></p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section (Optional) -->
    <?php
    $map_embed = get_option('weoryx_contact_map_embed');
    if ($map_embed) : ?>
        <section class="contact-map-section">
            <div class="contact-map-wrapper">
                <?php echo wp_kses_post($map_embed); ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>