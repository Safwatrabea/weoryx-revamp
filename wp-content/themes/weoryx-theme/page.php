<?php

/**
 * The template for displaying all pages
 */
get_header(); ?>



<main class="page-content">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php get_footer(); ?>