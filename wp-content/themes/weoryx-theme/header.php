<!DOCTYPE html>
<?php
$current_lang = weoryx_get_current_lang();
$dir = $current_lang === 'ar' ? 'rtl' : 'ltr';

if ($current_lang === 'ar') {
    // Force RTL for Arabic
    $GLOBALS['text_direction'] = 'rtl';
} else {
    $GLOBALS['text_direction'] = 'ltr';
}
?>
<html <?php language_attributes(); ?> dir="<?php echo esc_attr($dir); ?>">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri()); ?>/images/logo.svg">
    <link rel="shortcut icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri()); ?>/images/logo.svg">
    <link rel="apple-touch-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/images/logo.svg">

    <?php wp_head(); ?>
</head>

<body <?php body_class($dir === 'rtl' ? 'rtl' : ''); ?>>
    <!-- Header / Navigation -->
    <header class="header" id="header">
        <div class="container">
            <nav class="navbar">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/logo.svg" alt="<?php bloginfo('name'); ?>" class="logo-img logo-colored">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/logo-white.svg" alt="<?php bloginfo('name'); ?>" class="logo-img logo-white">
                </a>

                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'nav-menu',
                    'menu_id'        => 'navMenu',
                    'fallback_cb'    => false,
                    'walker'         => new class extends Walker_Nav_Menu {
                        function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
                        {
                            $current_lang = weoryx_get_current_lang();

                            $title = weoryx_translate($item->title);

                            $classes = empty($item->classes) ? array() : (array) $item->classes;
                            if (strpos($item->title, 'Offer') !== false || strpos($item->title, 'عروض') !== false) {
                                $classes[] = 'nav-link-highlight';
                            }
                            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
                            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
                            $output .= '<li' . $class_names . '>';

                            $url = !empty($item->url) ? $item->url : '';
                            // Polylang handles URLs automatically if configured correctly.

                            $attributes = !empty($url) ? ' href="' . esc_url($url) . '"' : '';
                            $attributes .= ' class="nav-link' . (in_array('current-menu-item', $classes) ? ' active' : '') . (in_array('nav-link-highlight', $classes) ? ' nav-link-highlight' : '') . '"';
                            $item_output = $args->before;
                            $item_output .= '<a' . $attributes . '>';
                            $item_output .= $args->link_before . apply_filters('the_title', $title, $item->ID) . $args->link_after;
                            $item_output .= '</a>';
                            $item_output .= $args->after;
                            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
                        }
                    }
                ));
                ?>

                <div class="nav-actions">
                    <a href="<?php echo esc_url(weoryx_get_link('contact')); ?>" class="btn btn-primary">
                        <?php echo weoryx_translate('Contact Us'); ?>
                    </a>

                    <?php
                    if (function_exists('pll_the_languages')) {
                        $languages = pll_the_languages(array('raw' => 1));
                        foreach ($languages as $lang) {
                            if (!$lang['current_lang']) {
                    ?>
                                <a href="<?php echo esc_url($lang['url']); ?>" class="lang-toggle">
                                    <i class="fas fa-globe"></i>
                                    <span><?php echo $lang['name']; ?></span>
                                </a>
                            <?php
                            }
                        }
                    } else {
                        // Minimal fallback if Polylang is disabled
                        if ($current_lang === 'ar') {
                            ?>
                            <a href="<?php echo esc_url(add_query_arg('lang', 'en')); ?>" class="lang-toggle">
                                <i class="fas fa-globe"></i>
                                <span>English</span>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="lang-toggle">
                                <i class="fas fa-globe"></i>
                                <span>العربية</span>
                            </a>
                    <?php
                        }
                    }
                    ?>

                    <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </nav>
        </div>
    </header>