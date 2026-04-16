<?php

/**
 * The template for displaying single posts
 */
get_header(); ?>

<main>
    <?php while (have_posts()) : the_post(); ?>

        <!-- Page Header - Same as home.php -->
        <section class="page-header">
            <div class="container">
                <div class="page-header-content">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <nav class="page-breadcrumb">
                        <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(weoryx_translate('Home')); ?></a>
                        <i class="fas fa-chevron-right"></i>
                        <a href="<?php echo esc_url(weoryx_get_link('blog')); ?>"><?php echo esc_html(weoryx_translate('Blog')); ?></a>
                        <i class="fas fa-chevron-right"></i>
                        <span><?php echo wp_trim_words(get_the_title(), 4); ?></span>
                    </nav>
                </div>
            </div>
        </section>

        <!-- Post Content Section - Using blog-page-section like home.php -->
        <section class="blog-page-section">
            <div class="container">

                <!-- Single Post Container -->
                <div class="single-post-container">

                    <!-- Post Meta -->

                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="single-featured-image" data-aos="fade-up">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Post Content -->
                    <div class="single-content" data-aos="fade-up">
                        <?php the_content(); ?>
                    </div>

                    <!-- Tags & Share -->
                    <div class="single-footer" data-aos="fade-up">
                        <?php if (has_tag()) : ?>
                            <div class="single-tags">
                                <strong><?php echo esc_html(weoryx_translate('Tags:')); ?></strong>
                                <?php the_tags('<span class="single-tag">', '</span><span class="single-tag">', '</span>'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="single-share">
                            <strong><?php echo esc_html(weoryx_translate('Share:')); ?></strong>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" class="share-btn facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" target="_blank" class="share-btn twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>" target="_blank" class="share-btn linkedin">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://wa.me/?text=<?php the_permalink(); ?>" target="_blank" class="share-btn whatsapp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Post Navigation -->
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    if ($prev_post || $next_post) :
                    ?>
                        <div class="single-navigation" data-aos="fade-up">
                            <?php if ($prev_post) : ?>
                                <a href="<?php echo get_permalink($prev_post->ID); ?>" class="nav-btn prev">
                                    <i class="fas fa-arrow-right"></i>
                                    <span><?php echo get_the_title($prev_post->ID); ?></span>
                                </a>
                            <?php endif; ?>

                            <?php if ($next_post) : ?>
                                <a href="<?php echo get_permalink($next_post->ID); ?>" class="nav-btn next">
                                    <span><?php echo get_the_title($next_post->ID); ?></span>
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </section>

        <!-- Related Posts - Using same structure as home.php -->
        <?php
        $categories = get_the_category();
        if ($categories) {
            $category_ids = array();
            foreach ($categories as $individual_category) {
                $category_ids[] = $individual_category->term_id;
            }

            $args = array(
                'category__in' => $category_ids,
                'post__not_in' => array(get_the_ID()),
                'posts_per_page' => 3,
                'ignore_sticky_posts' => 1
            );

            $related_query = new WP_Query($args);

            if ($related_query->have_posts()) :
        ?>
                <section class="blog-page-section related-posts-section">
                    <div class="container">
                        <div class="section-header" data-aos="fade-up">
                            <span class="section-tag"><?php echo esc_html(weoryx_translate('More Articles')); ?></span>
                            <h2 class="section-title"><?php echo esc_html(weoryx_translate('Related Posts')); ?></h2>
                        </div>

                        <div class="blog-page-grid">
                            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                                <article class="blog-card" data-aos="fade-up">
                                    <div class="blog-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('medium_large'); ?>
                                            <?php else : ?>
                                                <img src="<?php echo get_template_directory_uri(); ?>/images/blog-placeholder.jpg" alt="<?php the_title(); ?>">
                                            <?php endif; ?>
                                        </a>
                                        <?php
                                        $cats = get_the_category();
                                        if ($cats) :
                                        ?>
                                            <span class="blog-category"><?php echo esc_html($cats[0]->name); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="blog-content">
                                        <div class="blog-meta">
                                            <span><i class="fas fa-calendar-alt"></i> <?php echo get_the_date(); ?></span>
                                        </div>
                                        <h3 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <p class="blog-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                        <a href="<?php the_permalink(); ?>" class="blog-link">
                                            <?php echo esc_html(weoryx_translate('Read More')); ?> <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </section>
        <?php
                wp_reset_postdata();
            endif;
        }
        ?>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>