<?php

/**
 * Template for Services Archive
 */
get_header(); ?>

<main class="services-page-main">
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1 class="page-title"><?php echo esc_html(weoryx_translate('Our Services')); ?></h1>
                <nav class="page-breadcrumb">
                    <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(weoryx_translate('Home')); ?></a>
                    <i class="fas fa-chevron-right"></i>
                    <span><?php echo esc_html(weoryx_translate('Our Services')); ?></span>
                </nav>
            </div>
        </div>
    </section>

    <section class="services-list-section section-padding">
        <div class="container">
            <?php
            if (have_posts()) :
                $count = 0;
                while (have_posts()) : the_post();
            ?>
                    <div class="service-item-row <?php echo ($count % 2 != 0) ? 'reverse' : ''; ?>" data-aos="<?php echo ($count % 2 != 0) ? 'fade-left' : 'fade-right'; ?>">
                        <div class="service-content">
                            <?php
                            $icon_class = get_post_meta(get_the_ID(), '_service_icon', true);
                            if ($icon_class) :
                            ?>
                                <div class="service-icon-wrapper">
                                    <i class="<?php echo esc_attr($icon_class); ?>"></i>
                                </div>
                            <?php endif; ?>

                            <h2 class="service-item-title"><?php the_title(); ?></h2>
                            <div class="service-item-description">
                                <?php the_excerpt(); ?>
                            </div>

                            <?php
                            // Get features from native post meta
                            $features_text = get_post_meta(get_the_ID(), '_service_features', true);

                            if (!empty($features_text)) {
                                // Split by newline to get array of features
                                $features = explode("\n", $features_text);
                            ?>
                                <ul class="service-features-list">
                                    <?php foreach ($features as $feature) {
                                        $feature = trim($feature);
                                        if (!empty($feature)) {
                                    ?>
                                            <li>
                                                <i class="fas fa-check"></i>
                                                <span><?php echo esc_html(weoryx_translate($feature)); ?></span>
                                            </li>
                                    <?php
                                        }
                                    } ?>
                                </ul>
                            <?php } ?>

                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary service-read-more">
                                <?php echo esc_html(weoryx_translate('Read More')); ?> <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="service-detail-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large'); ?>
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/portfolio-1.jpg" alt="Service Image">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
                    $count++;
                endwhile;
                wp_reset_postdata();
            else:
                ?>
                <p><?php echo esc_html(weoryx_translate('No services found.')); ?></p>
            <?php
            endif;
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>