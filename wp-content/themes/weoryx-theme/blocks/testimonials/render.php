<?php

/**
 * Render for Reviews Block
 */
// Get current language
$current_lang = weoryx_get_current_lang();

$testimonials_query = new WP_Query(array(
    'post_type'      => 'testimonial',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => array(
        'relation' => 'OR',
        array(
            'key'     => '_post_language',
            'value'   => $current_lang,
            'compare' => '='
        )
    )
));
?>
<section class="testimonials-stack-section" id="testimonials">
    <div class="container">
        <div class="stack-layout">
            <!-- Phase 21: Infinity Stack Content -->
            <div class="stack-info" data-aos="fade-right">
                <span class="stack-tag"><?php echo isset($attributes['tag']) ? esc_html(weoryx_translate($attributes['tag'])) : weoryx_translate('TRUSTED BY LEADERS'); ?></span>
                <h2 class="stack-title">
                    <?php echo weoryx_format_title(isset($attributes['title']) ? $attributes['title'] : 'Proven Results | Elevated', 'stack-accent'); ?>
                </h2>
                <p class="stack-description">
                    <?php
                    $desc = isset($attributes['description']) && !empty($attributes['description'])
                        ? $attributes['description']
                        : 'Discover why industry pioneers choose WeOryx to lead their digital evolution.';
                    echo esc_html(weoryx_translate($desc));
                    ?>
                </p>

                <!-- Stack Custom Nav -->
                <div class="stack-controls">
                    <div class="stack-nav swiper-button-prev"><i class="fas fa-arrow-left"></i></div>
                    <div class="stack-nav swiper-button-next"><i class="fas fa-arrow-right"></i></div>
                </div>
            </div>

            <div class="stack-visual" data-aos="fade-left" data-aos-delay="200">
                <div class="swiper stack-swiper">
                    <div class="swiper-wrapper">
                        <?php
                        $has_posts = $testimonials_query->have_posts();
                        $testimonials_list = array();

                        if (!$has_posts) {
                            if (isset($attributes['items']) && !empty($attributes['items'])) {
                                $testimonials_list = $attributes['items'];
                            } else {
                                $testimonials_list = array(
                                    array(
                                        'content' => 'The weoryx team delivered a system that redefined our operational efficiency. Their technical depth and modern design sense are truly world-class.',
                                        'name' => 'Alexander Pierce',
                                        'role' => 'CTO, NexaSystems',
                                        'image' => get_template_directory_uri() . '/images/testimonial-1.jpg'
                                    ),
                                );
                            }
                        }

                        if ($has_posts) :
                            while ($testimonials_query->have_posts()) : $testimonials_query->the_post();
                                $designation = get_post_meta(get_the_ID(), 'author_role', true);
                        ?>
                                <div class="swiper-slide">
                                    <div class="tech-card">
                                        <div class="tech-card-inner">
                                            <div class="tech-quote">“</div>
                                            <blockquote class="tech-text"><?php the_content(); ?></blockquote>

                                            <div class="tech-author">
                                                <div class="tech-avatar">
                                                    <?php if (has_post_thumbnail()) : ?>
                                                        <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>">
                                                    <?php else : ?>
                                                        <div class="tech-avatar-placeholder"></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="tech-meta">
                                                    <h3 class="tech-name"><?php the_title(); ?> <span class="verified-icon" title="Verified Client"><i class="fas fa-check"></i></span></h3>
                                                    <p class="tech-role"><?php echo esc_html($designation ?: 'Tech Partner'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile;
                            wp_reset_postdata();
                        elseif (!empty($testimonials_list)):
                            foreach ($testimonials_list as $testimonial):
                            ?>
                                <div class="swiper-slide">
                                    <div class="tech-card">
                                        <div class="tech-card-inner">
                                            <div class="tech-quote">“</div>
                                            <blockquote class="tech-text"><?php echo esc_html($testimonial['content']); ?></blockquote>

                                            <div class="tech-author">
                                                <div class="tech-avatar">
                                                    <img src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>">
                                                </div>
                                                <div class="tech-meta">
                                                    <h3 class="tech-name"><?php echo esc_html($testimonial['name']); ?> <span class="verified-icon" title="Verified Client"><i class="fas fa-check"></i></span></h3>
                                                    <p class="tech-role"><?php echo esc_html($testimonial['role']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            endforeach;
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>