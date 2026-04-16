<?php

/**
 * Template Name: Portfolio Page
 */

get_header(); ?>

<main class="portfolio-page-modern">
    <!-- Dynamic Luxury Background -->
    <div class="portfolio-bg-aurora">
        <div class="aurora-blob aurora-1"></div>
        <div class="aurora-blob aurora-2"></div>
        <div class="aurora-blob aurora-3"></div>
    </div>

    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1 class="page-title"><?php echo weoryx_format_title(get_the_title()); ?></h1>
                <nav class="page-breadcrumb">
                    <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(weoryx_translate('Home')); ?></a>
                    <i class="fas fa-chevron-right"></i>
                    <span><?php the_title(); ?></span>
                </nav>
            </div>
        </div>
    </section>

    <?php
    // Get all categories for filter
    $terms = get_terms(array(
        'taxonomy' => 'portfolio_category',
        'hide_empty' => true,
    ));
    ?>

    <!-- Modern Filter Navigation -->
    <div class="portfolio-filter-container" data-aos="fade-up" data-aos-delay="200">
        <div class="container">
            <ul class="portfolio-filter-modern">
                <?php if (!empty($terms) && !is_wp_error($terms)) :
                    $count = 0;
                    foreach ($terms as $term) :
                        $active_class = ($count == 0) ? 'active' : '';
                ?>
                        <li class="filter-item">
                            <button class="filter-btn <?php echo esc_attr($active_class); ?>" data-filter="<?php echo esc_attr($term->slug); ?>">
                                <?php echo esc_html($term->name); ?>
                            </button>
                        </li>
                <?php
                        $count++;
                    endforeach;
                endif; ?>
            </ul>
        </div>
    </div>

    <!-- Portfolio Grid -->
    <section class="portfolio-grid-section">
        <div class="container">
            <div class="portfolio-grid-modern" id="portfolio-grid">
                <?php
                $args = array(
                    'post_type'      => 'portfolio',
                    'posts_per_page' => -1, // Show all for JS filtering
                    'lang'           => weoryx_get_current_lang(),
                    'orderby'        => 'date',
                    'order'          => 'DESC'
                );
                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        $terms = get_the_terms(get_the_ID(), 'portfolio_category');
                        $term_slugs = array();
                        $term_names = array();
                        if ($terms && !is_wp_error($terms)) {
                            foreach ($terms as $term) {
                                $term_slugs[] = $term->slug;
                                $term_names[] = $term->name;
                            }
                        }
                        $filter_class = implode(' ', $term_slugs);
                        $category_list = implode(', ', $term_names);
                        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        if (!$image_url) $image_url = get_template_directory_uri() . '/images/portfolio-placeholder.jpg';
                ?>
                        <div class="portfolio-item-modern <?php echo esc_attr($filter_class); ?>" data-aos="fade-up">
                            <a href="<?php the_permalink(); ?>" class="portfolio-card-link">
                                <div class="portfolio-card-inner">
                                    <div class="card-image-wrapper">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>" loading="lazy">
                                        <div class="card-overlay">
                                            <div class="overlay-content">
                                                <span class="overlay-category"><?php echo esc_html($category_list); ?></span>
                                                <h3 class="overlay-title"><?php the_title(); ?></h3>
                                                <span class="overlay-arrow"><i class="fas fa-arrow-right"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="no-projects-found">
                        <p><?php echo esc_html(weoryx_translate('No projects found.')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Trigger Initial Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activeBtn = document.querySelector('.portfolio-filter-modern .filter-btn.active');
            if (activeBtn) {
                activeBtn.click();
            }
        });
    </script>
</main>

<?php get_footer(); ?>