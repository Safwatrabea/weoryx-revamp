<?php

/**
 * Archive Template: Portfolio
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
                <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
                <nav class="page-breadcrumb">
                    <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(weoryx_translate('Home')); ?></a>
                    <i class="fas fa-chevron-right"></i>
                    <span><?php post_type_archive_title(); ?></span>
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
                // Standard loop for Archive, but we need to ensure we have all posts if pagination is issue, 
                // but for now let's use global query but add terms classes
                if (have_posts()) :
                    while (have_posts()) : the_post();
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
                else :
                    ?>
                    <div class="no-projects-found">
                        <p><?php echo esc_html(weoryx_translate('No projects found.')); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination Removed by User Request -->
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

<?php
/**
 * Ensure all portfolio items are shown if the archive is viewed
 */
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('portfolio')) {
        $query->set('posts_per_page', -1);
    }
});

get_footer(); ?>