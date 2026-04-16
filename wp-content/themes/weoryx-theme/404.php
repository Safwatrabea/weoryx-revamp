<?php

/**
 * The template for displaying 404 pages (not found)
 */

get_header();
?>

<main class="page-404">
    <!-- Interactive Background -->
    <div class="portfolio-bg-aurora">
        <div class="aurora-blob blob-1"></div>
        <div class="aurora-blob blob-2"></div>
        <div class="aurora-blob blob-3"></div>
    </div>

    <div class="container">
        <div class="content-404" data-aos="zoom-in">
            <div class="error-code">404</div>

            <h1 class="error-title">
                <?php echo weoryx_translate('Page Not Found | Lost in Space'); ?>
            </h1>

            <p class="error-description">
                <?php echo weoryx_translate('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.'); ?>
            </p>

            <div class="error-actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    <?php echo weoryx_translate('Back to Home'); ?>
                </a>
                <a href="<?php echo esc_url(weoryx_get_link('contact')); ?>" class="btn btn-outline-white">
                    <i class="fas fa-envelope"></i>
                    <?php echo weoryx_translate('Contact Us'); ?>
                </a>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
