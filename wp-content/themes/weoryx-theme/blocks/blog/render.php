<?php

/**
 * Render for Blog Block
 */
$tag = isset($attributes['tag']) ? $attributes['tag'] : 'Blog';
$title = isset($attributes['title']) ? $attributes['title'] : 'Latest Articles';
$subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : '';

$count = isset($attributes['count']) ? intval($attributes['count']) : 3;
$lang = function_exists('weoryx_get_current_lang') ? weoryx_get_current_lang() : 'ar';

$query_args = array(
    'post_type'      => 'post',
    'posts_per_page' => $count,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
);

// Filter by language if Polylang or our custom meta is present
if (function_exists('pll_current_language')) {
    $query_args['lang'] = $lang;
} else {
    $query_args['meta_query'] = array(
        array(
            'key'     => '_migrated_lang',
            'value'   => $lang,
            'compare' => '='
        )
    );
}

$recent_posts = new WP_Query($query_args);
$posts = array();

if ($recent_posts->have_posts()) {
    while ($recent_posts->have_posts()) {
        $recent_posts->the_post();
        $post_id = get_the_ID();

        // Get primary category
        $categories = get_the_category($post_id);
        $category_name = !empty($categories) ? $categories[0]->name : 'Marketing';

        $posts[] = array(
            'title'    => get_the_title(),
            'date'     => get_the_date(),
            'img'      => get_the_post_thumbnail_url($post_id, 'large') ?: get_template_directory_uri() . '/img/blog-placeholder.jpg',
            'link'     => get_permalink(),
            'category' => $category_name
        );
    }
    wp_reset_postdata();
}
?>
<section class="blog-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-tag section-tag-center"><?php echo esc_html($tag); ?></span>
            <h2 class="section-title"><?php echo weoryx_format_title($title); ?></h2>
            <?php if ($subtitle) : ?>
                <p class="section-subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>

        <div class="blog-grid">
            <?php foreach ($posts as $index => $post) : ?>
                <article class="blog-card" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="blog-image">
                        <img src="<?php echo esc_url($post['img']); ?>" alt="<?php echo esc_attr($post['title']); ?>" loading="lazy">
                        <div class="blog-overlay">
                            <span class="blog-category"><?php echo esc_html($post['category']); ?></span>
                        </div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="meta-date"><i class="far fa-calendar-alt"></i> <?php echo esc_html($post['date']); ?></span>
                        </div>
                        <h3 class="blog-title">
                            <a href="<?php echo esc_url($post['link']); ?>"><?php echo esc_html($post['title']); ?></a>
                        </h3>
                        <div class="blog-footer">
                            <a href="<?php echo esc_url($post['link']); ?>" class="blog-link">
                                <span><?php echo esc_html(weoryx_translate('Read More')); ?></span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="blog-all text-center" data-aos="fade-up">
            <a href="<?php echo esc_url(weoryx_get_link('blog')); ?>" class="btn btn-outline-primary">
                <?php echo esc_html(weoryx_translate('See All Articles')); ?>
            </a>
        </div>
    </div>
</section>