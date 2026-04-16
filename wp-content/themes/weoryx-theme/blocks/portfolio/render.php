<?php

/**
 * Render for Portfolio Block
 */
$tag = isset($attributes['tag']) && !empty($attributes['tag']) ? weoryx_translate($attributes['tag']) : weoryx_translate('Our Work');
$title = isset($attributes['title']) && !empty($attributes['title']) ? weoryx_translate($attributes['title']) : weoryx_translate('Recent | Projects');
$subtitle = isset($attributes['subtitle']) && !empty($attributes['subtitle']) ? weoryx_translate($attributes['subtitle']) : weoryx_translate('Samples of our work that helped our clients achieve their goals.');
$count = isset($attributes['count']) ? $attributes['count'] : 5;

$portfolio_query = new WP_Query(array(
    'post_type' => 'portfolio',
    'posts_per_page' => $count,
));
?>
<section class="portfolio-section" id="portfolio">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag section-tag-center"><?php echo esc_html($tag); ?></span>
            <h2 class="section-title section-title-center">
                <?php echo weoryx_format_title($title); ?>
            </h2>
            <?php if (!empty($subtitle)) : ?>
                <p class="section-desc section-desc-center"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>

        <div class="portfolio-grid">
            <?php
            // Check if we have posts, otherwise use fallback data
            $has_posts = $portfolio_query->have_posts();

            $portfolio_items = array();

            if (!$has_posts) {
                // Check if items were provided in attributes (e.g. from demo content)
                if (isset($attributes['items']) && !empty($attributes['items'])) {
                    $portfolio_items = $attributes['items'];
                } else {
                    // Fallback data matching index.html
                    $portfolio_items = array(
                        array(
                            'title' => 'E-commerce Platform',
                            'category' => 'Web Development',
                            'image' => get_template_directory_uri() . '/images/portfolio-1.jpg',
                            'link' => '/portfolio'
                        ),
                        array(
                            'title' => 'Marketing Campaign',
                            'category' => 'Digital Marketing',
                            'image' => get_template_directory_uri() . '/images/portfolio-2.jpg',
                            'link' => '/portfolio'
                        ),
                        array(
                            'title' => 'Brand Identity',
                            'category' => 'Branding',
                            'image' => get_template_directory_uri() . '/images/portfolio-3.jpg',
                            'link' => '/portfolio'
                        ),
                        array(
                            'title' => 'Mobile App',
                            'category' => 'App Development',
                            'image' => get_template_directory_uri() . '/images/portfolio-4.jpg',
                            'link' => '/portfolio'
                        ),
                        array(
                            'title' => 'SEO Strategy',
                            'category' => 'SEO',
                            'image' => get_template_directory_uri() . '/images/portfolio-5.jpg',
                            'link' => '/portfolio'
                        ),
                    );
                }
            }

            $i = 0;

            if ($has_posts) :
                // ... (WP Query Loop - largely unchanged, just ensure translations if needed)
                while ($portfolio_query->have_posts()) : $portfolio_query->the_post();
                    $is_large = ($i === 0) ? 'portfolio-item-large' : '';
            ?>
                    <div class="portfolio-item <?php echo $is_large; ?>" data-aos="fade-up">
                        <div class="portfolio-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large'); ?>
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/portfolio-placeholder.jpg" alt="<?php the_title(); ?>">
                            <?php endif; ?>

                            <div class="portfolio-overlay">
                                <span class="portfolio-category">
                                    <?php
                                    $cats = get_the_terms(get_the_ID(), 'portfolio_category');
                                    echo $cats ? $cats[0]->name : __('Project', 'weoryx');
                                    ?>
                                </span>
                                <h4 class="portfolio-title"><?php the_title(); ?></h4>
                                <a href="<?php the_permalink(); ?>" class="portfolio-link"><i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php
                    $i++;
                endwhile;
                wp_reset_postdata();
            elseif (!empty($portfolio_items)) :
                foreach ($portfolio_items as $index => $item) :
                    $is_large = ($index === 0) ? 'portfolio-item-large' : '';
                ?>
                    <div class="portfolio-item <?php echo $is_large; ?>" data-aos="fade-up">
                        <div class="portfolio-image">
                            <img src="<?php echo isset($item['image']) ? esc_url($item['image']) : ''; ?>" alt="<?php echo isset($item['title']) ? esc_attr($item['title']) : ''; ?>">
                            <div class="portfolio-overlay">
                                <span class="portfolio-category"><?php echo isset($item['category']) ? esc_html($item['category']) : ''; ?></span>
                                <h4 class="portfolio-title"><?php echo isset($item['title']) ? esc_html($item['title']) : ''; ?></h4>
                                <a href="<?php echo isset($item['link']) ? esc_url($item['link']) : '#'; ?>" class="portfolio-link"><i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
            <?php
                endforeach;
            endif; ?>
        </div>

        <div class="portfolio-cta" data-aos="fade-up">
            <a href="<?php echo isset($attributes['buttonUrl']) ? esc_url($attributes['buttonUrl']) : '/portfolio'; ?>" class="btn btn-primary">
                <?php echo isset($attributes['buttonText']) ? esc_html(weoryx_translate($attributes['buttonText'])) : weoryx_translate('View More Projects'); ?>
            </a>
        </div>
    </div>
</section>