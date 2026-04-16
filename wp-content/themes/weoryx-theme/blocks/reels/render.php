<?php

/**
 * Render for Reels Block
 */
$tag = isset($attributes['tag']) ? $attributes['tag'] : weoryx_translate('Latest Content');
$title = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('Watch Our | Stories');
// Get current language
$current_lang = weoryx_get_current_lang();

// Fetch Reels from CPT
$reels_query = new WP_Query(array(
    'post_type'      => 'reels',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => array(
        'relation' => 'OR',
        array(
            'key'     => '_post_language',
            'value'   => $current_lang,
            'compare' => '='
        )
    )
));

$display_reels = array();

if ($reels_query->have_posts()) {
    while ($reels_query->have_posts()) {
        $reels_query->the_post();
        $video_url = get_post_meta(get_the_ID(), '_reel_video_url', true);
        $category = get_post_meta(get_the_ID(), '_reel_category', true);
        $display_reels[] = array(
            'img'      => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            'title'    => get_the_title(),
            'category' => $category ?: 'Feature',
            'video'    => $video_url
        );
    }
    wp_reset_postdata();
} else {
    // Fallback to manual attributes
    $display_reels = isset($attributes['reels']) && !empty($attributes['reels']) ? $attributes['reels'] : array(
        array('img' => 'reels-placeholder.png', 'title' => 'Sample Reel 1', 'category' => 'Marketing'),
        array('img' => 'reels-placeholder.png', 'title' => 'Sample Reel 2', 'category' => 'Design'),
        array('img' => 'reels-placeholder.png', 'title' => 'Sample Reel 3', 'category' => 'Strategy'),
        array('img' => 'reels-placeholder.png', 'title' => 'Sample Reel 4', 'category' => 'Tech'),
    );
}
?>
<section class="reels-section">
    <div class="container-fluid">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-tag section-tag-center"><?php echo esc_html($tag); ?></span>
            <h2 class="section-title section-title-center">
                <?php echo weoryx_format_title($title); ?>
            </h2>
        </div>

        <div class="swiper reels-swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php foreach ($display_reels as $reel) :
                    $reel_img = is_array($reel) ? (isset($reel['img']) ? $reel['img'] : '') : '';
                    $reel_title = is_array($reel) ? (isset($reel['title']) ? $reel['title'] : '') : '';
                    $reel_cat = is_array($reel) ? (isset($reel['category']) ? $reel['category'] : '') : '';
                    $reel_video = is_array($reel) ? (isset($reel['video']) ? $reel['video'] : '') : '';

                    $img_url = (strpos($reel_img, 'http') === 0) ? $reel_img : get_template_directory_uri() . '/images/' . ($reel_img ? $reel_img : 'reels-placeholder.png');
                ?>
                    <div class="swiper-slide">
                        <div class="reel-item video-container"
                            <?php echo $reel_video ? 'data-has-video="true" data-video-url="' . esc_attr($reel_video) . '"' : ''; ?>
                            style="cursor: <?php echo $reel_video ? 'pointer' : 'default'; ?>;">
                            <div class="reel-badge"><i class="fas fa-play"></i> REEL</div>
                            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($reel_title ? $reel_title : 'Reel'); ?>">
                            <?php if ($reel_video) : ?>
                            <div class="reel-play-icon"><i class="fas fa-play"></i></div>
                            <?php endif; ?>
                            <div class="reel-content">
                                <span class="reel-category"><?php echo esc_html($reel_cat); ?></span>
                                <h4 class="reel-title"><?php echo esc_html($reel_title); ?></h4>
                                <div class="reel-progress-bar"><span></span></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Bottom Controls: Prev | Pagination | Next -->
        <div class="reels-controls">
            <button class="reels-nav-btn reels-nav-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
            <div class="swiper-pagination reels-pagination"></div>
            <button class="reels-nav-btn reels-nav-next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</section>