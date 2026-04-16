<?php

/**
 * Template Name: About Page
 */
get_header(); ?>

<main class="page-content py-0">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php get_footer(); ?>