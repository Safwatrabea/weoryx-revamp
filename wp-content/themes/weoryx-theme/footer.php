    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo-white.svg" alt="<?php bloginfo('name'); ?>">
                    </a>
                    <p class="footer-description">
                        <?php
                        $lang = weoryx_get_current_lang();
                        $desc_default = 'WeOryx is a leading digital transformation agency dedicated to delivering world-class software solutions and marketing strategies.';
                        $footer_desc = get_option('weoryx_footer_description_' . $lang);
                        if (!$footer_desc) {
                            $footer_desc = get_option('weoryx_footer_description', $desc_default);
                        }
                        echo esc_html(weoryx_translate($footer_desc));
                        ?>
                    </p>
                    <div class="footer-social">
                        <?php if ($fb = get_option('weoryx_facebook_url')) : ?>
                            <a href="<?php echo esc_url($fb); ?>" class="social-link" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if ($tw = get_option('weoryx_twitter_url')) : ?>
                            <a href="<?php echo esc_url($tw); ?>" class="social-link" target="_blank"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        <?php if ($ig = get_option('weoryx_instagram_url')) : ?>
                            <a href="<?php echo esc_url($ig); ?>" class="social-link" target="_blank"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ($li = get_option('weoryx_linkedin_url')) : ?>
                            <a href="<?php echo esc_url($li); ?>" class="social-link" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        <?php if ($wa = get_option('weoryx_whatsapp_number')) : ?>
                            <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^0-9]/', '', $wa)); ?>" class="social-link" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="footer-links">
                    <h4><?php echo esc_html(weoryx_translate('Quick Links')); ?></h4>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                    ?>
                </div>

                <div class="footer-contact">
                    <h4><?php echo esc_html(weoryx_translate('Contact Us')); ?></h4>
                    <ul class="contact-info">
                        <?php
                        $address = get_option('weoryx_footer_address_' . $lang);
                        if (!$address) {
                            $address = get_option('weoryx_footer_address', 'Riyadh, Saudi Arabia');
                        }
                        $phone = get_option('weoryx_footer_phone', '+966 123 456 789');
                        $email = get_option('weoryx_footer_email', 'info@weoryx.com');
                        ?>
                        <li><i class="fas fa-map-marker-alt"></i> <?php echo esc_html(weoryx_translate($address)); ?></li>
                        <li><i class="fas fa-phone-alt"></i> <?php echo esc_html(weoryx_translate($phone)); ?></li>
                        <li><i class="fas fa-envelope"></i> <?php echo esc_html(weoryx_translate($email)); ?></li>
                    </ul>
                </div>

                <div class="footer-newsletter">
                    <?php
                    $newsletter_title = get_option('weoryx_newsletter_title_' . $lang);
                    if (!$newsletter_title) {
                        $newsletter_title = weoryx_translate('Newsletter');
                    }
                    $newsletter_desc = get_option('weoryx_newsletter_description_' . $lang);
                    if (!$newsletter_desc) {
                        $newsletter_desc = weoryx_translate('Subscribe to get the latest tech trends and updates.');
                    }
                    $newsletter_form = get_option('weoryx_newsletter_cf7_shortcode');
                    ?>
                    <h4><?php echo esc_html($newsletter_title); ?></h4>
                    <p class="newsletter-desc"><?php echo esc_html($newsletter_desc); ?></p>

                    <?php if ($newsletter_form) : ?>
                        <div class="newsletter-form-cf7">
                            <?php echo do_shortcode($newsletter_form); ?>
                        </div>
                    <?php else : ?>
                        <form class="newsletter-form">
                            <div class="newsletter-input-group">
                                <input type="email" placeholder="<?php echo esc_attr(weoryx_translate('Your Email Address')); ?>" required>
                                <button type="submit" aria-label="Subscribe"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="footer-bottom">
                <p>
                    <?php
                    $copyright = get_option('weoryx_copyright_' . $lang);
                    if (!$copyright) {
                        $copyright = '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . weoryx_translate('All Rights Reserved.');
                    }
                    echo $copyright; // Already escaped or contains HTML entities
                    ?>
                </p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
    <elevenlabs-convai agent-id="agent_9101khgz72tzfr6vs08s18hgh4fp"></elevenlabs-convai>
    <script src="https://unpkg.com/@elevenlabs/convai-widget-embed" async type="text/javascript"></script>
    <?php if ($wa = get_option('weoryx_whatsapp_number')) : ?>
        <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^0-9]/', '', $wa)); ?>" class="whatsapp-float" target="_blank" aria-label="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    <?php endif; ?>
    </body>

    </html>