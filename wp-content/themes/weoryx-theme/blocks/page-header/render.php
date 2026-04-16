<?php

/**
 * Render for Page Header Block
 */
$title = isset($attributes['title']) && !empty($attributes['title']) ? $attributes['title'] : get_the_title();
$show_breadcrumbs = isset($attributes['showBreadcrumbs']) ? $attributes['showBreadcrumbs'] : true;
?>
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <h1 class="page-title"><?php echo esc_html(weoryx_translate($title)); ?></h1>
            <?php if ($show_breadcrumbs) : ?>
                <nav class="page-breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo weoryx_translate('Home'); ?></a>
                    <i class="fas fa-chevron-right"></i>
                    <span><?php echo esc_html(weoryx_translate($title)); ?></span>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</section>