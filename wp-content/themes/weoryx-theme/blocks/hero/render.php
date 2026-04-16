<?php
/**
 * Render for Hero Block
 */
$slides = isset($attributes['slides']) ? $attributes['slides'] : array();

if (empty($slides)) {
    $slides = array(
        array(
            'title' => 'DIGITAL MARKETING SOLUTION<br>FOR YOUR BUSINESS',
            'tag' => 'Digital Marketing Agency',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'imageUrl' => get_template_directory_uri() . '/images/hero-person-1.png',
            'buttonText' => 'Contact Us',
            'buttonUrl' => '/contact'
        ),
        array(
            'title' => 'TRANSFORM YOUR ONLINE<br>PRESENCE TODAY',
            'tag' => 'Web Development',
            'description' => 'Build stunning, responsive websites that convert visitors into customers with our expert development team.',
            'imageUrl' => get_template_directory_uri() . '/images/hero-person-2.png',
            'buttonText' => 'Our Services',
            'buttonUrl' => '/services'
        ),
        array(
            'title' => 'GROW YOUR BUSINESS<br>WITH DATA-DRIVEN RESULTS',
            'tag' => 'SEO & Analytics',
            'description' => 'Boost your search rankings and maximize ROI with our proven SEO strategies and analytics solutions.',
            'imageUrl' => get_template_directory_uri() . '/images/hero-person-3.png',
            'buttonText' => 'View Portfolio',
            'buttonUrl' => '/portfolio'
        )
    );
}

// Fallback images icons
$floating_elements = array(
    'chat' => '<div class="floating-element floating-chat"><div class="chat-bubble"><span class="chat-dot"></span><span class="chat-dot"></span><span class="chat-dot"></span></div></div>',
    'heart' => '<div class="floating-element floating-heart"><i class="fas fa-heart"></i></div>',
    'like' => '<div class="floating-element floating-like"><i class="fas fa-thumbs-up"></i></div>',
    'target' => '<div class="floating-element floating-target"><i class="fas fa-bullseye"></i></div>',
    'chart' => '<div class="floating-element floating-chart"><i class="fas fa-chart-line"></i></div>',
    'code' => '<div class="floating-element floating-code"><i class="fas fa-code"></i></div>',
    'mobile' => '<div class="floating-element floating-mobile"><i class="fas fa-mobile-alt"></i></div>',
    'desktop' => '<div class="floating-element floating-desktop"><i class="fas fa-desktop"></i></div>',
    'search' => '<div class="floating-element floating-search"><i class="fas fa-search"></i></div>',
    'graph' => '<div class="floating-element floating-graph"><i class="fas fa-chart-bar"></i></div>'
);
?>
<section class="hero" id="home">
    <div class="hero-background">
        <div class="hero-curve"></div>
    </div>

    <div class="container">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($slides as $index => $slide) :
                    $img = !empty($slide['imageUrl']) ? $slide['imageUrl'] : get_template_directory_uri() . '/images/hero-person-' . (($index % 3) + 1) . '.png';
                ?>
                    <div class="swiper-slide">
                        <div class="hero-content">
                            <div class="hero-text" data-aos="fade-right">
                                <span class="hero-tag"><?php echo esc_html($slide['tag']); ?></span>
                                <h1 class="hero-title"><?php echo wp_kses_post($slide['title']); ?></h1>
                                <p class="hero-description"><?php echo esc_html($slide['description']); ?></p>

                                <div class="hero-btns">
                                    <a href="<?php echo esc_url($slide['buttonUrl']); ?>" class="btn btn-primary btn-lg"><?php echo esc_html(weoryx_translate($slide['buttonText'])); ?></a>
                                    <?php if (!empty($slide['button2Text'])) : ?>
                                        <a href="<?php echo esc_url($slide['button2Url']); ?>" class="btn btn-outline-white btn-lg ml-3"><?php echo esc_html(weoryx_translate($slide['button2Text'])); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="hero-image" data-aos="fade-left">
                                <div class="hero-image-wrapper">
                                    <img src="<?php echo esc_url($img); ?>" alt="Hero Image" class="hero-main-img">

                                    <!-- Floating Elements logic based on slide index -->
                                    <?php
                                    if ($index % 3 == 0) {
                                        echo $floating_elements['chat'];
                                        echo $floating_elements['heart'];
                                        echo $floating_elements['like'];
                                        echo $floating_elements['target'];
                                        echo $floating_elements['chart'];
                                    } elseif ($index % 3 == 1) {
                                        echo $floating_elements['code'];
                                        echo $floating_elements['mobile'];
                                        echo $floating_elements['desktop'];
                                    } else {
                                        echo $floating_elements['search'];
                                        echo $floating_elements['graph'];
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>