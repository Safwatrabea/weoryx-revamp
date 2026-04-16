<?php

/**
 * Render for Services Block
 */
$title = isset($attributes['title']) ? $attributes['title'] : 'Our Professional Services';
$tag = isset($attributes['tag']) ? $attributes['tag'] : __('Our Services', 'weoryx');
$description = isset($attributes['description']) ? $attributes['description'] : __('We provide comprehensive digital solutions tailored to elevate your brand and accelerate your business growth.', 'weoryx');

$count = isset($attributes['count']) ? intval($attributes['count']) : 6;

// Get current language
$current_lang = weoryx_get_current_lang();

// Fetch services from CPT by current language
$services_query = new WP_Query(array(
    'post_type'      => 'services',
    'posts_per_page' => $count,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
    'lang'           => $current_lang // Filter by Polylang language
));
$display_services = array();

if ($services_query->have_posts()) {
    while ($services_query->have_posts()) {
        $services_query->the_post();
        $icon = get_post_meta(get_the_ID(), '_service_icon', true);
        $display_services[] = array(
            'icon'  => $icon ?: 'fa-star', // Fallback icon
            'title' => get_the_title(),
            'desc'  => get_the_excerpt(),
            'link'  => get_the_permalink()
        );
    }
    wp_reset_postdata();
} else {
    // Use manual entries from block attributes if no CPT posts found
    $display_services = isset($attributes['services']) ? $attributes['services'] : array();

    if (empty($display_services)) {
        $display_services = array(
            array(
                'icon' => 'fas fa-code',
                'title' => 'Web Development',
                'desc' => 'Custom websites built with the latest technologies, optimized for performance and conversions.',
                'link' => '#contact'
            ),
            array(
                'icon' => 'fas fa-search',
                'title' => 'SEO Optimization',
                'desc' => 'Boost your search rankings and drive organic traffic with our proven SEO strategies.',
                'link' => '#contact'
            ),
            array(
                'icon' => 'fas fa-bullhorn',
                'title' => 'Digital Marketing',
                'desc' => 'Comprehensive marketing strategies that drive engagement and maximize ROI.',
                'link' => '#contact'
            ),
            array(
                'icon' => 'fas fa-paint-brush',
                'title' => 'Brand Identity',
                'desc' => 'Create a memorable brand identity that resonates with your target audience.',
                'link' => '#contact'
            ),
            array(
                'icon' => 'fas fa-share-alt',
                'title' => 'Social Media',
                'desc' => 'Engage your audience and build brand awareness across all social platforms.',
                'link' => '#contact'
            ),
            array(
                'icon' => 'fas fa-mobile-alt',
                'title' => 'App Development',
                'desc' => 'Native and cross-platform mobile applications for iOS and Android devices.',
                'link' => '#contact'
            ),
        );
    }
}
?>
<section class="services-section" id="services">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag section-tag-center"><?php echo esc_html($tag); ?></span>
            <h2 class="section-title section-title-center">
                <?php echo weoryx_format_title($title); ?>
            </h2>
            <p class="section-subtitle">
                <?php echo esc_html($description); ?>
            </p>
        </div>

        <div class="services-grid">
            <?php foreach ($display_services as $service) : ?>
                <div class="service-card" data-aos="fade-up">
                    <div class="service-icon">
                        <i class="<?php echo esc_attr($service['icon']); ?>"></i>
                    </div>
                    <h3 class="service-title"><?php echo esc_html($service['title']); ?></h3>
                    <p class="service-description"><?php echo esc_html($service['desc']); ?></p>
                    <a href="<?php echo isset($service['link']) ? esc_url($service['link']) : '#contact'; ?>" class="service-link"><?php _e('Learn More', 'weoryx'); ?> <i class="fas fa-arrow-right"></i></a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="services-cta" data-aos="fade-up">
            <a href="<?php echo esc_url(get_post_type_archive_link('services')); ?>" class="btn btn-outline-primary"><?php echo esc_html(weoryx_translate('View All Services')); ?></a>
        </div>
    </div>
</section>