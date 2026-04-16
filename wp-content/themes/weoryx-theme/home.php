<?php

/**
 * The template for displaying the blog posts page.
 */

get_header(); ?>

<main>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1 class="page-title"><?php echo esc_html(weoryx_translate('Our Blog')); ?></h1>
                <nav class="page-breadcrumb">
                    <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(weoryx_translate('Home')); ?></a>
                    <i class="fas fa-chevron-right"></i>
                    <span><?php echo esc_html(weoryx_translate('Blog')); ?></span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="blog-section">
        <div class="container">
            <div class="blog-page-grid">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post(); ?>
                        <article class="blog-card" data-aos="fade-up">
                            <div class="blog-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large'); ?>
                                <?php endif; ?>
                                <span class="blog-category"><?php the_category(', '); ?></span>
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span><i class="fas fa-calendar-alt"></i> <?php echo get_the_date(); ?></span>
                                    <span><i class="fas fa-user"></i> <?php the_author(); ?></span>
                                </div>
                                <h3 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="blog-excerpt"><?php echo get_the_excerpt(); ?></p>
                                <a href="<?php the_permalink(); ?>" class="blog-link">
                                    <?php echo esc_html(weoryx_translate('Read More')); ?> <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    <?php endwhile;
                else : ?>
                    <p><?php echo esc_html(weoryx_translate('No posts found.')); ?></p>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => '<i class="fas fa-chevron-left"></i>',
                    'next_text' => '<i class="fas fa-chevron-right"></i>',
                ));
                ?>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>