<?php
/**
 * Template Name: Payment - USD (Stripe)
 * Template Post Type: page
 */

get_header();
?>

<?php 
$current_lang = weoryx_get_current_lang();
$direction = ($current_lang === 'ar') ? 'rtl' : 'ltr';
?>
<section class="payment-page payment-usd" id="paymentSection" dir="<?php echo $direction; ?>">
    <div class="payment-layout">

        <!-- Left Branding Panel -->
        <div class="payment-panel-left">

            <div class="pay-logo-wrap">
                <?php
                $logo_id = get_theme_mod('custom_logo');
                if ($logo_id) :
                    $logo_src = wp_get_attachment_image_src($logo_id, 'full');
                ?>
                    <img src="<?php echo esc_url($logo_src[0]); ?>" alt="<?php bloginfo('name'); ?>" class="pay-site-logo">
                <?php else : ?>
                    <span class="pay-site-name"><?php bloginfo('name'); ?></span>
                <?php endif; ?>
            </div>

            <h2 class="pay-panel-title"><?php echo weoryx_translate('Secure Payment'); ?><br><span><?php echo weoryx_translate('USD'); ?></span></h2>
            <p class="pay-panel-subtitle"><?php echo weoryx_translate('Complete Payment Subtitle'); ?></p>

            <ul class="pay-features-list">
                <li><i class="fas fa-shield-halved"></i> <?php echo weoryx_translate('SSL Encryption'); ?></li>
                <li><i class="fas fa-credit-card"></i> <?php echo weoryx_translate('Major Cards'); ?></li>
                <li><i class="fas fa-bolt"></i> <?php echo weoryx_translate('Instant Confirmation'); ?></li>
                <li><i class="fas fa-headset"></i> <?php echo weoryx_translate('24/7 Support'); ?></li>
                <li><i class="fas fa-rotate-left"></i> <?php echo weoryx_translate('Reliable Processing'); ?></li>
            </ul>

            <div class="pay-trust-badges">
                <span class="pay-trust-badge"><i class="fas fa-lock"></i> <?php echo weoryx_translate('SSL Secured'); ?></span>
                <span class="pay-trust-badge"><i class="fab fa-stripe"></i> <?php echo weoryx_translate('Stripe'); ?></span>
                <span class="pay-trust-badge"><i class="fas fa-shield-alt"></i> <?php echo weoryx_translate('PCI Compliant'); ?></span>
            </div>

        </div>

        <!-- Right Form Panel -->
        <div class="payment-panel-right">
            <div class="pay-form-wrap">

                <h3 class="pay-form-title"><?php the_title(); ?></h3>
                <p class="pay-form-subtitle"><?php echo weoryx_translate('Enter Details Subtitle'); ?></p>

                <form id="paymentForm" novalidate>

                    <div class="pay-form-group">
                        <label for="pay_name"><i class="fas fa-user"></i> <?php echo weoryx_translate('Full Name'); ?></label>
                        <input type="text" id="pay_name" name="pay_name" placeholder="<?php echo ($current_lang === 'ar' ? 'محمد أحمد' : 'John Smith'); ?>" autocomplete="name" required>
                    </div>

                    <div class="pay-form-group">
                        <label for="pay_email"><i class="fas fa-envelope"></i> <?php echo weoryx_translate('Email Address'); ?></label>
                        <input type="email" id="pay_email" name="pay_email" placeholder="example@email.com" autocomplete="email" required>
                    </div>

                    <div class="pay-form-group">
                        <label for="pay_phone"><i class="fas fa-phone"></i> <?php echo weoryx_translate('Phone Number'); ?></label>
                        <input type="tel" id="pay_phone" name="pay_phone" placeholder="+966..." required>
                    </div>

                    <div class="pay-form-group">
                        <label for="pay_amount"><i class="fas fa-coins"></i> <?php echo weoryx_translate('Amount (USD)'); ?></label>
                        <div class="pay-amount-wrap <?php echo ($direction === 'rtl' ? 'pay-amount-rtl' : ''); ?>">
                            <span class="pay-currency-sym">$</span>
                            <input type="number" id="pay_amount" name="pay_amount" placeholder="0.00" min="1" step="0.01" required style="<?php echo ($direction === 'rtl' ? 'padding-right:56px;padding-left:16px;' : ''); ?>">
                        </div>
                    </div>

                    <div class="pay-form-group">
                        <label for="pay_desc"><i class="fas fa-file-lines"></i> <?php echo weoryx_translate('Description'); ?></label>
                        <input type="text" id="pay_desc" name="pay_desc" placeholder="<?php echo ($current_lang === 'ar' ? 'مثال: باقة تصميم موقع إلكتروني' : 'e.g. Website Package'); ?>" required>
                    </div>

                    <!-- Order Summary (shown dynamically) -->
                    <div class="pay-order-summary" id="orderSummary" style="display:none;">
                        <p class="pay-order-label"><?php echo weoryx_translate('Order Summary'); ?></p>
                        <div class="pay-order-row">
                            <span id="summaryDesc"><?php echo weoryx_translate('Service'); ?></span>
                            <span id="summaryAmt" class="pay-order-amount"><?php echo ($direction === 'rtl' ? '0.00 $' : '$0.00'); ?></span>
                        </div>
                        <div class="pay-order-row pay-order-total">
                            <span><?php echo weoryx_translate('Total'); ?></span>
                            <span id="summaryTotal"><?php echo ($direction === 'rtl' ? '0.00 $' : '$0.00'); ?></span>
                        </div>
                    </div>

                    <div class="pay-spacer" style="height: 20px;"></div>

                    <button type="submit" id="payBtn" class="btn-pay">
                        <span class="pay-spinner"></span>
                        <span class="pay-btn-text"><i class="fas fa-lock"></i> <?php echo weoryx_translate('Pay Now'); ?></span>
                    </button>

                    <div id="pay-message" class="pay-message" style="display:none;"></div>

                </form>

                <p class="pay-powered-by">
                    <i class="fab fa-stripe"></i> <?php echo weoryx_translate('Stripe Secured Footer'); ?>
                </p>

            </div>
        </div><!-- .payment-panel-right -->

    </div><!-- .payment-layout -->
</section>

<?php get_footer(); ?>
