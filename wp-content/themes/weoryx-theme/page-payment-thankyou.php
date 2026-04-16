<?php
/**
 * Template Name: Payment - Thank You
 * Template Post Type: page
 */

get_header();

$amount = isset($_GET['amount']) ? sanitize_text_field($_GET['amount']) : '0.00';
$currency = isset($_GET['currency']) ? sanitize_text_field($_GET['currency']) : 'USD';
$name = isset($_GET['name']) ? sanitize_text_field($_GET['name']) : '';

$is_rtl = is_rtl() || trim(strtoupper($currency)) === 'SAR';
$currency_sym = trim(strtoupper($currency)) === 'SAR' ? 'ر.س' : '$';

?>

<section class="payment-page thankyou-page" <?php echo $is_rtl ? 'dir="rtl"' : 'dir="ltr"'; ?>>
    <style>
        .thankyou-page {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: var(--off-white, #f8f9fa);
        }
        .thankyou-card {
            background: #fff;
            padding: 60px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            max-width: 550px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(0,0,0,0.03);
            animation: cardFadeIn 0.8s ease-out;
        }
        @keyframes cardFadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .thankyou-icon {
            width: 90px;
            height: 90px;
            background: #10b981;
            color: #white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            border-radius: 50%;
            margin: 0 auto 30px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
            color: #fff;
        }
        .thankyou-card h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: var(--primary-blue, #12485b);
            font-weight: 800;
        }
        .thankyou-card p {
            font-size: 1.1rem;
            color: var(--gray, #6B7280);
            line-height: 1.7;
            margin-bottom: 40px;
        }
        .thankyou-amount-box {
            background: var(--off-white, #f9fafb);
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 40px;
            border: 1px solid rgba(0,0,0,0.02);
        }
        .thankyou-amount-box .label {
            font-size: 0.9rem;
            color: var(--medium-gray, #9CA3AF);
            margin-bottom: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .thankyou-amount-box .amount-wrap {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-blue, #12485b);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .thankyou-amount-box .currency {
            font-size: 1.2rem;
            opacity: 0.6;
            margin-top: 10px;
        }
        .btn-home {
            min-width: 220px;
        }
    </style>
    <div class="thankyou-card">
        <div class="thankyou-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <?php if ($is_rtl): ?>
            <h1>شكراً لك!</h1>
            <p>لقد تم استلام دفعتك بنجاح. سيتم التواصل معك قريباً لتأكيد تفاصيل طلبك والبدء في العمل.</p>
            
            <div class="thankyou-amount-box">
                <div class="label">المبلغ المدفوع</div>
                <div class="amount-wrap">
                    <span class="amount"><?php echo esc_html($amount); ?></span>
                    <span class="currency"><?php echo esc_html($currency_sym); ?></span>
                </div>
            </div>
            
            <a href="<?php echo home_url(); ?>" class="btn btn-primary btn-home">العودة للرئيسية</a>
            
        <?php else: ?>
            <h1>Thank You!</h1>
            <p>Your payment has been successfully processed. We will be in touch shortly to confirm your order details and begin work.</p>
            
            <div class="thankyou-amount-box">
                <div class="label">Amount Paid</div>
                <div class="amount-wrap">
                    <span class="currency"><?php echo esc_html($currency_sym); ?></span>
                    <span class="amount"><?php echo esc_html($amount); ?></span>
                </div>
            </div>
            
            <a href="<?php echo home_url(); ?>" class="btn btn-primary btn-home">Back to Home</a>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
