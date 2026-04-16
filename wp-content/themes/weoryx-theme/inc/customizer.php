<?php

/**
 * WeOryx Theme Customizer
 *
 * @package WeOryx
 */

function weoryx_customize_register($wp_customize)
{

    // Footer Section
    $wp_customize->add_section('weoryx_footer_section', array(
        'title'    => __('Footer Settings', 'weoryx'),
        'priority' => 120,
    ));

    // Footer Description
    $wp_customize->add_setting('weoryx_footer_desc', array(
        'default'           => __('WeOryx is a leading digital transformation agency dedicated to delivering world-class software solutions and marketing strategies.', 'weoryx'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('weoryx_footer_desc', array(
        'label'    => __('Footer Description', 'weoryx'),
        'section'  => 'weoryx_footer_section',
        'type'     => 'textarea',
    ));

    // Address
    $wp_customize->add_setting('weoryx_footer_address', array(
        'default'           => __('Riyadh, Saudi Arabia', 'weoryx'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('weoryx_footer_address', array(
        'label'    => __('Address', 'weoryx'),
        'section'  => 'weoryx_footer_section',
        'type'     => 'text',
    ));

    // Phone
    $wp_customize->add_setting('weoryx_footer_phone', array(
        'default'           => __('+966 123 456 789', 'weoryx'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('weoryx_footer_phone', array(
        'label'    => __('Phone Number', 'weoryx'),
        'section'  => 'weoryx_footer_section',
        'type'     => 'text',
    ));

    // Email
    $wp_customize->add_setting('weoryx_footer_email', array(
        'default'           => __('info@weoryx.com', 'weoryx'),
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('weoryx_footer_email', array(
        'label'    => __('Email Address', 'weoryx'),
        'section'  => 'weoryx_footer_section',
        'type'     => 'email',
    ));

    // Social Links
    $socials = array('facebook', 'twitter', 'instagram', 'linkedin');
    foreach ($socials as $social) {
        $wp_customize->add_setting("weoryx_social_$social", array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control("weoryx_social_$social", array(
            'label'    => ucfirst($social) . ' URL',
            'section'  => 'weoryx_footer_section',
            'type'     => 'url',
        ));
    }

    // Newsletter Content
    $wp_customize->add_setting('weoryx_newsletter_title', array(
        'default'           => __('Newsletter', 'weoryx'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('weoryx_newsletter_title', array(
        'label'    => __('Newsletter Title', 'weoryx'),
        'section'  => 'weoryx_footer_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('weoryx_newsletter_desc', array(
        'default'           => __('Subscribe to get the latest tech trends and updates.', 'weoryx'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Newsletter Description
    $wp_customize->add_control('weoryx_newsletter_desc', array(
        'label'    => __('Newsletter Description', 'weoryx'),
        'section'  => 'weoryx_footer_section',
        'type'     => 'textarea',
    ));

    /* --- English Settings --- */

    $wp_customize->add_setting('weoryx_footer_desc_en', array(
        'default'           => 'WeOryx is a leading digital transformation agency dedicated to delivering world-class software solutions and marketing strategies.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('weoryx_footer_desc_en', array(
        'label'    => __('Footer Description (English)', 'weoryx'),
        'section'  => 'weoryx_footer_section',
        'type'     => 'textarea',
    ));

    $wp_customize->add_setting('weoryx_footer_address_en', array(
        'default'           => 'Riyadh, Saudi Arabia',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('weoryx_footer_address_en', array(
        'label'    => __('Address (English)', 'weoryx'),
        'section'  => 'weoryx_footer_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('weoryx_newsletter_title_en', array(
        'default'           => 'Newsletter',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('weoryx_newsletter_title_en', array(
        'label'    => __('Newsletter Title (English)', 'weoryx'),
        'section'  => 'weoryx_footer_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('weoryx_newsletter_desc_en', array(
        'default'           => 'Subscribe to get the latest tech trends and updates.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('weoryx_newsletter_desc_en', array(
        'label'    => __('Newsletter Description (English)', 'weoryx'),
        'section'  => 'weoryx_footer_section',
        'type'     => 'textarea',
    ));
}
add_action('customize_register', 'weoryx_customize_register');
