<?php

/**
 * The template for displaying single service posts
 */
get_header(); ?>

<main class="single-service-page">
    <?php while (have_posts()) : the_post(); ?>

        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <div class="page-header-content">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <nav class="page-breadcrumb" data-aos="fade-up" data-aos-delay="100">
                        <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(weoryx_translate('Home')); ?></a>
                        <i class="fas fa-chevron-right"></i>
                        <a href="<?php echo esc_url(get_post_type_archive_link('services')); ?>"><?php echo esc_html(weoryx_translate('Services')); ?></a>
                        <i class="fas fa-chevron-right"></i>
                        <span><?php the_title(); ?></span>
                    </nav>
                </div>
            </div>
        </section>

        <!-- Main Content (Gutenberg Support) -->
        <div class="service-content-area">
            <?php the_content(); ?>
        </div>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>