<?php

/**
 * The template for displaying single portfolio posts
 */
get_header(); ?>

<main class="single-portfolio-agency">
    <?php while (have_posts()) : the_post();
        // Category
        $terms = get_the_terms(get_the_ID(), 'portfolio_category');
        $category = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Branding';

        // Meta Data
        $client = get_post_meta(get_the_ID(), '_weoryx_client_name', true) ?: 'Envato Market';
        $year = get_post_meta(get_the_ID(), '_weoryx_project_year', true) ?: '2025';
        $url = get_post_meta(get_the_ID(), '_weoryx_project_url', true);
        $video_url = get_post_meta(get_the_ID(), '_weoryx_project_video_url', true);

        // Gallery Images
        $gallery_1 = get_post_meta(get_the_ID(), '_weoryx_gallery_image_1', true);
        $gallery_2 = get_post_meta(get_the_ID(), '_weoryx_gallery_image_2', true);
        $gallery_3 = get_post_meta(get_the_ID(), '_weoryx_gallery_image_3', true);
    ?>

        <!-- 1. STANDARD PAGE HEADER (Aligned with Services) -->
        <section class="page-header">
            <div class="container">
                <div class="page-header-content">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <nav class="page-breadcrumb" data-aos="fade-up" data-aos-delay="100">
                        <a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_html(weoryx_translate('Home')); ?></a>
                        <i class="fas fa-chevron-right"></i>
                        <a href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>"><?php echo esc_html(weoryx_translate('Portfolio')); ?></a>
                        <i class="fas fa-chevron-right"></i>
                        <span><?php the_title(); ?></span>
                    </nav>
                </div>
            </div>
        </section>

        <!-- 2. AGENCY SPLIT LAYOUT -->
        <section class="single-portfolio-content pb-5">
            <div class="container">
                <div class="row gx-lg-5">

                    <!-- COLUMN 1: STICKY INFO (Description + Details) -->
                    <!-- In LTR this is Left. In RTL this is Right. matches standard 'Info side, Image side' layouts -->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <div class="portfolio-sticky-info sticky-top">

                            <!-- Project Content -->
                            <div class="project-description-agency mb-5">
                                <div class="desc-content text-dark">
                                    <?php
                                    if (get_the_content()) {
                                        the_content();
                                    } else {
                                        echo '<p>No content available. Please add content in the WordPress editor.</p>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="project-meta-agency">
                                <div class="meta-item border-top py-3">
                                    <span class="d-block text-muted text-uppercase small fw-bold mb-1"><?php echo esc_html(weoryx_translate('Client')); ?></span>
                                    <span class="d-block fw-bold fs-5"><?php echo esc_html($client); ?></span>
                                </div>
                                <div class="meta-item border-top py-3">
                                    <span class="d-block text-muted text-uppercase small fw-bold mb-1"><?php echo esc_html(weoryx_translate('Date')); ?></span>
                                    <span class="d-block fw-bold fs-5"><?php echo esc_html($year); ?></span>
                                </div>
                                <div class="meta-item border-top py-3">
                                    <span class="d-block text-muted text-uppercase small fw-bold mb-1"><?php echo esc_html(weoryx_translate('Category')); ?></span>
                                    <span class="d-block fw-bold fs-5"><?php echo esc_html($category); ?></span>
                                </div>
                                <div class="meta-item border-top border-bottom py-3 mb-4">
                                    <span class="d-block text-muted text-uppercase small fw-bold mb-1"><?php echo esc_html(weoryx_translate('Share')); ?></span>
                                    <div class="agency-share-links mt-2">
                                        <a href="#" class="me-3 text-dark"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#" class="me-3 text-dark"><i class="fab fa-twitter"></i></a>
                                        <a href="#" class="text-dark"><i class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>

                                <?php if ($url): ?>
                                    <a href="<?php echo esc_url($url); ?>" target="_blank" class="btn btn-dark w-100 rounded-0 py-3 text-uppercase letter-spacing-2 fw-bold">
                                        <?php echo esc_html(weoryx_translate('Visit Project')); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>

                    <!-- COLUMN 2: SCROLLING IMAGES -->
                    <div class="col-lg-8">
                        <div class="portfolio-gallery-agency d-flex flex-column gap-5">
                            <!-- Dynamic Gallery Images -->
                            <?php if ($gallery_1): ?>
                                <div class="gallery-item" data-aos="fade-up">
                                    <img src="<?php echo esc_url($gallery_1); ?>" class="img-fluid w-100 shadow-sm" alt="Project Gallery 1">
                                </div>
                            <?php endif; ?>

                            <?php if ($gallery_2): ?>
                                <div class="gallery-item" data-aos="fade-up">
                                    <img src="<?php echo esc_url($gallery_2); ?>" class="img-fluid w-100 shadow-sm" alt="Project Gallery 2">
                                </div>
                            <?php endif; ?>

                            <?php if ($gallery_3): ?>
                                <div class="gallery-item" data-aos="fade-up">
                                    <img src="<?php echo esc_url($gallery_3); ?>" class="img-fluid w-100 shadow-sm" alt="Project Gallery 3">
                                </div>
                            <?php endif; ?>

                            <!-- Video Embed (if URL provided) -->
                            <?php if ($video_url):
                                // Convert YouTube/Vimeo URLs to embed format
                                $embed_url = '';
                                if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                                    // YouTube
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $video_url, $matches);
                                    if (!empty($matches[1])) {
                                        $embed_url = 'https://www.youtube.com/embed/' . $matches[1];
                                    }
                                } elseif (strpos($video_url, 'vimeo.com') !== false) {
                                    // Vimeo
                                    preg_match('/vimeo\.com\/(\d+)/', $video_url, $matches);
                                    if (!empty($matches[1])) {
                                        $embed_url = 'https://player.vimeo.com/video/' . $matches[1];
                                    }
                                }

                                if ($embed_url):
                            ?>
                                    <div class="gallery-item" data-aos="fade-up">
                                        <div class="ratio ratio-16x9 shadow-sm">
                                            <iframe src="<?php echo esc_url($embed_url); ?>" title="Project Video" allowfullscreen></iframe>
                                        </div>
                                    </div>
                            <?php endif;
                            endif; ?>


                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- 3. DELICATE MODERN NAVIGATION -->
        <section class="project-navigation-refined">
            <div class="container">
                <div class="row align-items-center justify-content-between">

                    <!-- Prev Project -->
                    <div class="col-md-5 col-5">
                        <?php if ($prev_post = get_previous_post()): ?>
                            <a href="<?php echo get_permalink($prev_post->ID); ?>" class="nav-refined-item prev text-decoration-none">
                                <span class="nav-refined-tag"><?php echo esc_html(weoryx_translate('Previous Project')); ?></span>
                                <span class="nav-refined-title"><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Delicate Separator -->
                    <div class="col-md-2 col-2 text-center">
                        <a href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>" class="nav-refined-grid text-decoration-none">
                            <div class="grid-icon-wrap">
                                <i class="fas fa-th-large"></i>
                            </div>
                        </a>
                    </div>

                    <!-- Next Project -->
                    <div class="col-md-5 col-5 text-end">
                        <?php if ($next_post = get_next_post()): ?>
                            <a href="<?php echo get_permalink($next_post->ID); ?>" class="nav-refined-item next text-decoration-none">
                                <span class="nav-refined-tag"><?php echo esc_html(weoryx_translate('Next Project')); ?></span>
                                <span class="nav-refined-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </section>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>