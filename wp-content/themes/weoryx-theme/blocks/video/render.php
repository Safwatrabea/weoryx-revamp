<?php

/**
 * Render for Video Block
 */
$tag = isset($attributes['tag']) ? $attributes['tag'] : weoryx_translate('Video Marketing');
$title = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('Get Your Message Across | Via Video');
$description = isset($attributes['description']) ? $attributes['description'] : weoryx_translate('Tell your success story and engage your audience with professional video content.');
$videoUrl = isset($attributes['videoUrl']) ? $attributes['videoUrl'] : '#';
$thumbnailUrl = isset($attributes['thumbnail']) ? $attributes['thumbnail'] : (isset($attributes['thumbnailUrl']) ? $attributes['thumbnailUrl'] : get_template_directory_uri() . '/images/video-thumb.png');
?>
<section class="video-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <span class="section-tag section-tag-center"><?php echo esc_html($tag); ?></span>
            <h2 class="section-title"><?php echo weoryx_format_title($title); ?></h2>
            <p class="section-subtitle"><?php echo esc_html($description); ?></p>
        </div>
        <div class="video-section-bg"></div>
        <div class="video-container" data-aos="zoom-in" data-video-url="<?php echo esc_url($videoUrl); ?>">
            <img src="<?php echo esc_url($thumbnailUrl); ?>" alt="<?php echo esc_attr($title); ?>" class="video-thumbnail">
            <div class="video-overlay" data-video-id="<?php
                                                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $match);
                                                        echo isset($match[1]) ? esc_attr($match[1]) : '';
                                                        ?>">
                <div class="play-button">
                    <i class="fas fa-play"></i>
                </div>
            </div>
        </div>
    </div>
</section>