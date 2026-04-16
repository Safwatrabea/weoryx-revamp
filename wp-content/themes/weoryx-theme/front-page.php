<?php

/**
 * The template for displaying the front page
 *
 * @package WeOryx
 */

get_header(); ?>

<main id="main-content" class="site-main">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php
get_footer();
