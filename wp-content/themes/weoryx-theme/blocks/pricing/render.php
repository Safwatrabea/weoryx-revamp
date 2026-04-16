<?php

/**
 * Render for Pricing Block
 */
$current_lang = weoryx_get_current_lang();
$is_ar = ($current_lang === 'ar');

$tag = isset($attributes['tag']) ? $attributes['tag'] : weoryx_translate('Pricing Plans');
$title = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('Choose Your Perfect Plan');
$subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : weoryx_translate('Flexible packages tailored to meet your business needs and goals.');
$plans = isset($attributes['plans']) && !empty($attributes['plans']) ? $attributes['plans'] : array();

if (empty($plans)) {
    $plans = array(
        array(
            'name' => weoryx_translate('Starter'),
            'price' => '499',
            'period' => '/month',
            'desc' => weoryx_translate('Perfect for small businesses getting started'),
            'features' => weoryx_translate('Website Maintenance, Basic SEO Optimization, 3 Social Media Posts/Week, Monthly Reporting'),
            'buttonText' => weoryx_translate('Get Started'),
            'buttonUrl' => '#',
            'isFeatured' => false
        ),
        array(
            'name' => weoryx_translate('Professional'),
            'price' => '999',
            'period' => '/month',
            'desc' => weoryx_translate('Ideal for growing businesses'),
            'features' => weoryx_translate('Website Maintenance, Advanced SEO Optimization, 5 Social Media Posts/Week, Weekly Reporting, PPC Management, Content Marketing'),
            'buttonText' => weoryx_translate('Get Started'),
            'buttonUrl' => '#',
            'isFeatured' => true,
            'badgeText' => weoryx_translate('Most Popular')
        ),
        array(
            'name' => weoryx_translate('Enterprise'),
            'price' => '1999',
            'period' => '/month',
            'desc' => weoryx_translate('For large organizations'),
            'features' => weoryx_translate('Everything in Professional, Dedicated Account Manager, Daily Social Media Posts, Real-time Reporting, Priority Support, Custom Strategy'),
            'buttonText' => weoryx_translate('Get Started'),
            'buttonUrl' => '#',
            'isFeatured' => false
        )
    );
}
?>
<section class="pricing-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-tag section-tag-center"><?php echo esc_html(weoryx_translate($tag)); ?></span>
            <h2 class="section-title"><?php echo weoryx_format_title($title); ?></h2>
            <?php if (!empty($subtitle)) : ?>
                <p class="section-subtitle"><?php echo esc_html(weoryx_translate($subtitle)); ?></p>
            <?php endif; ?>
        </div>

        <div class="pricing-grid">
            <?php foreach ($plans as $index => $plan) :
                $is_featured = isset($plan['isFeatured']) && $plan['isFeatured'];
                // Handle both English (,) and Arabic (،) commas
                $features_raw = isset($plan['features']) ? $plan['features'] : '';
                $features_list = preg_split('/[,،]/u', $features_raw);
            ?>
                <div class="pricing-card <?php echo $is_featured ? 'pricing-featured' : ''; ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <?php if ($is_featured && !empty($plan['badgeText'])) : ?>
                        <div class="pricing-badge"><?php echo esc_html(weoryx_translate($plan['badgeText'])); ?></div>
                    <?php endif; ?>

                    <div class="pricing-header">
                        <h3><?php echo esc_html(weoryx_translate($plan['name'])); ?></h3>
                        <div class="pricing-price">
                            <span class="currency"><?php echo ($is_ar ? weoryx_translate('SAR') : weoryx_translate('$')); ?></span>
                            <span class="amount"><?php echo esc_html($plan['price']); ?></span>
                            <span class="period"><?php echo esc_html(weoryx_translate($plan['period'])); ?></span>
                        </div>
                        <p><?php echo esc_html(weoryx_translate($plan['desc'])); ?></p>
                    </div>

                    <ul class="pricing-features">
                        <?php foreach ($features_list as $feature) :
                            $feature = trim($feature);
                            if (empty($feature)) continue;
                        ?>
                            <li><i class="fas fa-check"></i> <?php echo esc_html(weoryx_translate($feature)); ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="pricing-cta">
                        <a href="<?php echo esc_url($plan['buttonUrl']); ?>" class="btn <?php echo $is_featured ? 'btn-primary' : 'btn-outline-primary'; ?> btn-block">
                            <?php echo esc_html(weoryx_translate($plan['buttonText'])); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
        .pricing-section {
            padding: 100px 0;
            background: #fff;
            position: relative;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .pricing-card {
            background: #fff;
            padding: 50px 40px;
            border-radius: 30px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }

        .pricing-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px rgba(26, 95, 122, 0.12);
            border-color: rgba(232, 90, 60, 0.2);
        }

        .pricing-featured {
            background: var(--primary-blue, #1A5F7A);
            color: #fff;
            transform: scale(1.05);
            z-index: 2;
            box-shadow: 0 20px 50px rgba(26, 95, 122, 0.2);
            border: none;
        }

        .pricing-featured:hover {
            transform: scale(1.05) translateY(-10px);
            background: var(--primary-blue-dark, #134B5F);
        }

        .pricing-featured .pricing-header h3,
        .pricing-featured .pricing-price .amount,
        .pricing-featured .pricing-price .currency,
        .pricing-featured .pricing-header p {
            color: #fff;
        }

        .pricing-featured .pricing-features li {
            color: rgba(255, 255, 255, 0.9);
        }

        .pricing-featured .pricing-features li i {
            color: var(--accent-orange, #E85A3C);
        }

        .pricing-badge {
            position: absolute;
            top: 25px;
            right: 25px;
            background: var(--accent-orange, #E85A3C);
            color: #fff;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .pricing-header {
            margin-bottom: 30px;
        }

        .pricing-header h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--primary-blue, #1A5F7A);
        }

        .pricing-price {
            margin-bottom: 15px;
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .pricing-price .currency {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-orange, #E85A3C);
        }

        .pricing-price .amount {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1;
            color: var(--primary-blue, #1A5F7A);
        }

        .pricing-price .period {
            font-size: 1rem;
            opacity: 0.6;
        }

        .pricing-header p {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.5;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 0 0 40px 0;
            flex-grow: 1;
        }

        .pricing-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 0.95rem;
            color: #555;
        }

        .pricing-features li i {
            color: var(--accent-orange, #E85A3C);
            font-size: 0.9rem;
        }

        .pricing-cta .btn-block {
            width: 100%;
            padding: 15px;
        }

        .pricing-featured .btn-outline-primary {
            background: #fff;
            color: var(--primary-blue, #1A5F7A);
            border: none;
        }

        .pricing-featured .btn-primary {
            background: var(--accent-orange, #E85A3C);
            border: none;
        }

        [dir="rtl"] .pricing-badge {
            right: auto;
            left: 25px;
        }

        [dir="rtl"] .pricing-price {
            flex-direction: row;
            justify-content: flex-start;
        }

        [dir="rtl"] .pricing-price .currency {
            order: 2;
            margin-right: 5px;
            margin-left: 0;
            font-size: 1.1rem;
        }

        [dir="rtl"] .pricing-price .amount {
            order: 1;
        }

        [dir="rtl"] .pricing-price .period {
            order: 3;
            margin-right: 5px;
        }

        @media (max-width: 992px) {
            .pricing-featured {
                transform: none;
            }

            .pricing-featured:hover {
                transform: translateY(-15px);
            }
        }
    </style>
</section>