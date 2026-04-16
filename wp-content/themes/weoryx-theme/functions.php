<?php

/**
 * WeOryx Theme Functions
 */

// Polylang Fallback Functions
// These ensure the theme works even if Polylang is deactivated and clears linting errors.
if (!function_exists('pll__')) {
    function pll__($string)
    {
        return $string;
    }
}



if (!function_exists('pll_e')) {
    function pll_e($string)
    {
        echo $string;
    }
}

if (!function_exists('pll_register_string')) {
    function pll_register_string($name, $string, $group = 'polylang', $multiline = false)
    {
        // Do nothing
    }
}

if (!function_exists('pll_the_languages')) {
    function pll_the_languages($args = '')
    {
        return '';
    }
}

/**
 * Helper to get current language independent of Plugins
 */
function weoryx_get_current_lang()
{
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    if (isset($_GET['lang']) && $_GET['lang'] === 'en') {
        return 'en';
    }
    if (is_page('home-english') || (isset($GLOBALS['post']) && $GLOBALS['post']->post_name === 'home-english')) {
        return 'en';
    }
    return 'ar'; // Default to Arabic
}

/**
 * Enhanced Translation Helper using Polylang
 */
function weoryx_translate($string, $group = 'weoryx')
{
    $translated = $string;
    if (function_exists('pll__')) {
        $translated = pll__($string);
    }

    // If still exactly same as key, and we are in Arabic, provide default Arabic fallbacks
    if ($translated === $string && weoryx_get_current_lang() === 'ar') {
        $arabic_map = array(
            'Secure Payment'           => 'دفع آمن',
            'Complete Payment Subtitle' => 'أكمل عملية الدفع بأمان باستخدام بطاقتك الائتمانية. معلوماتك محمية بتشفير عالي المستوى.',
            'SSL Encryption'           => 'تشفير SSL بقوة 256 بت',
            'Major Cards'              => 'نقبل جميع البطاقات الرئيسية',
            'Instant Confirmation'     => 'تأكيد فوري للدفع',
            '24/7 Support'             => 'دعم فني متاح 24/7',
            'Reliable Processing'      => 'معالجة آمنة وموثوقة',
            'SSL Secured'              => 'SSL آمن',
            'PCI Compliant'            => 'متوافق مع PCI',
            'Enter Details Subtitle'   => 'أدخل بياناتك أدناه لإتمام عملية الدفع.',
            'Full Name'                => 'الاسم الكامل',
            'Email Address'            => 'البريد الإلكتروني',
            'Phone Number'             => 'رقم الهاتف',
            'Amount (USD)'             => 'المبلغ (بالدولار)',
            'Amount (SAR)'             => 'المبلغ (بالريال)',
            'Description'              => 'وصف الطلب',
            'optional'                 => 'اختياري',
            'Order Summary'            => 'ملخص الطلب',
            'Service'                  => 'الخدمة',
            'Total'                    => 'الإجمالي',
            'Pay Now'                  => 'ادفع الآن',
            'Stripe Secured Footer'    => 'عمليتك مؤمنة بالكامل بواسطة Stripe',
        );

        if (isset($arabic_map[$string])) {
            return $arabic_map[$string];
        }
    }

    return $translated;
}

/**
 * Register strings for Polylang
 */
function weoryx_register_polylang_strings() {
    if (function_exists('pll_register_string')) {
        $group = 'WeOryx Payments';
        pll_register_string('Secure Payment', 'Secure Payment', $group);
        pll_register_string('USD', 'USD', $group);
        pll_register_string('SAR', 'SAR', $group);
        pll_register_string('Complete Payment Subtitle', 'Complete your payment securely using your credit or debit card. Your information is protected with bank-level encryption.', $group);
        pll_register_string('SSL Encryption', '256-bit SSL Encryption', $group);
        pll_register_string('Major Cards', 'All major cards accepted', $group);
        pll_register_string('Instant Confirmation', 'Instant payment confirmation', $group);
        pll_register_string('24/7 Support', '24/7 support available', $group);
        pll_register_string('Reliable Processing', 'Secure & reliable processing', $group);
        pll_register_string('SSL Secured', 'SSL Secured', $group);
        pll_register_string('Stripe', 'Stripe', $group);
        pll_register_string('PCI Compliant', 'PCI Compliant', $group);
        pll_register_string('Enter Details Subtitle', 'Enter your details below to complete the payment.', $group);
        pll_register_string('Full Name', 'Full Name', $group);
        pll_register_string('Email Address', 'Email Address', $group);
        pll_register_string('Phone Number', 'Phone Number', $group);
        pll_register_string('Amount (USD)', 'Amount (USD)', $group);
        pll_register_string('Amount (SAR)', 'Amount (SAR)', $group);
        pll_register_string('Description', 'Description', $group);
        pll_register_string('optional', 'optional', $group);
        pll_register_string('Order Summary', 'Order Summary', $group);
        pll_register_string('Service', 'Service', $group);
        pll_register_string('Total', 'Total', $group);
        pll_register_string('Pay Now', 'Pay Now', $group);
        pll_register_string('Stripe Secured Footer', 'Your payment is secured by Stripe', $group);
    }
}
add_action('init', 'weoryx_register_polylang_strings');

/**
 * Get dynamic link for pages or archives, aware of Polylang.
 */
function weoryx_get_link($slug)
{
    if (empty($slug) || $slug === '/') return home_url('/');

    // Handle Blog/Posts archive
    if ($slug === 'blog' || $slug === 'post') {
        return get_post_type_archive_link('post');
    }

    // Handle Contact page by template
    if ($slug === 'contact') {
        $current_lang = weoryx_get_current_lang();

        // Find page with contact template in current language
        $contact_pages = get_posts(array(
            'post_type' => 'page',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => '_wp_page_template',
                    'value' => 'page-contact.php'
                )
            ),
            'lang' => $current_lang // Polylang filter
        ));

        if (!empty($contact_pages)) {
            return get_permalink($contact_pages[0]->ID);
        }

        // Fallback: find any contact page and get translation
        $contact_pages = get_posts(array(
            'post_type' => 'page',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => '_wp_page_template',
                    'value' => 'page-contact.php'
                )
            )
        ));

        if (!empty($contact_pages)) {
            $page_id = $contact_pages[0]->ID;

            // Get translated version if exists
            if (function_exists('pll_get_post')) {
                $translated_id = pll_get_post($page_id, $current_lang);
                if ($translated_id) {
                    $page_id = $translated_id;
                }
            }

            return get_permalink($page_id);
        }
    }

    // Handle other pages by slug
    $page = get_page_by_path($slug);
    if (!$page) {
        return home_url('/' . $slug);
    }

    $page_id = $page->ID;
    if (function_exists('pll_get_post')) {
        $translated_id = pll_get_post($page_id);
        if ($translated_id) {
            $page_id = $translated_id;
        }
    }

    return get_permalink($page_id);
}

/**
 * Format title to color the last word or the part after |
 */
function weoryx_format_title($title, $accent_class = 'text-primary')
{
    if (empty($title)) return '';

    // Ensure we work with translated value
    $title = weoryx_translate($title);

    // Safety check for HTML
    $title_escaped = wp_kses_post($title);

    // If | exists, use it for manual coloring
    if (strpos($title_escaped, '|') !== false) {
        return str_replace('|', '<span class="' . $accent_class . '">', $title_escaped) . '</span>';
    }

    // Otherwise, color the last word or two automatically
    $words = explode(' ', $title_escaped);
    $count = count($words);

    if ($count > 3) {
        $last_two = array_slice($words, -2);
        $remaining = array_slice($words, 0, -2);
        return implode(' ', $remaining) . ' <span class="' . $accent_class . '">' . implode(' ', $last_two) . '</span>';
    } elseif ($count > 1) {
        $last_word = array_pop($words);
        $remaining = implode(' ', $words);
        return $remaining . ' <span class="' . $accent_class . '">' . $last_word . '</span>';
    }

    return $title_escaped;
}

/**
 * Register more strings for Polylang
 */
function weoryx_register_additional_strings()
{
    if (function_exists('pll_register_string')) {
        // Block Defaults
        pll_register_string('weoryx', 'Our Professional Services', 'Blocks');
        pll_register_string('weoryx', 'We provide comprehensive digital solutions tailored to elevate your brand and accelerate your business growth.', 'Blocks');
        pll_register_string('weoryx', 'Ready to Start Your Project?', 'Blocks');
        pll_register_string('weoryx', 'Let\'s discuss how we can help you achieve your digital marketing goals. Get a free consultation today!', 'Blocks');
        pll_register_string('weoryx', 'Who We Are', 'Blocks');
        pll_register_string('weoryx', 'Our Mission & Vision', 'Blocks');
        pll_register_string('weoryx', 'Watch Our Stories', 'Blocks');

        // Footer & UI
        pll_register_string('weoryx', 'Quick Links', 'Footer');
        pll_register_string('weoryx', 'Contact Us', 'Footer');
        pll_register_string('weoryx', 'Your Email Address', 'Footer');
        pll_register_string('weoryx', 'Your Name', 'Forms');
        pll_register_string('weoryx', 'Your Email', 'Forms');
        pll_register_string('weoryx', 'Your Phone', 'Forms');
        pll_register_string('weoryx', 'Your Message', 'Forms');
        pll_register_string('weoryx', 'Subject', 'Forms');
        pll_register_string('weoryx', 'All Rights Reserved.', 'Footer');
        pll_register_string('weoryx', 'Riyadh, Saudi Arabia', 'Footer');
        pll_register_string('weoryx', 'View All Services', 'Blocks');
        pll_register_string('weoryx', 'View Case Study', 'Blocks');
        pll_register_string('weoryx', 'Previous | Work', 'Blocks');
        pll_register_string('weoryx', 'Portfolio', 'Blocks');
        pll_register_string('weoryx', 'TRUSTED BY LEADERS', 'Blocks');
        pll_register_string('weoryx', 'Proven Results | Elevated', 'Blocks');
        pll_register_string('weoryx', 'Discover why industry pioneers choose WeOryx to lead their digital evolution.', 'Blocks');
        pll_register_string('weoryx', 'Quality First', 'Blocks');
        pll_register_string('weoryx', 'Innovative Solutions', 'Blocks');
        pll_register_string('weoryx', 'Strategic Goals', 'Blocks');
        pll_register_string('weoryx', 'Our Mission | and Vision', 'Blocks');
        pll_register_string('weoryx', 'The Experts', 'Blocks');
        pll_register_string('weoryx', 'Meet Our | Leadership', 'Blocks');
        pll_register_string('weoryx', 'More About Us', 'Blocks');

        // Breadcrumbs & Headers
        pll_register_string('weoryx', 'Services', 'Navigation');
        pll_register_string('weoryx', 'Our Services', 'Navigation');
        pll_register_string('weoryx', 'Home', 'Navigation');

        // Contact Page
        pll_register_string('weoryx', 'Contact Us', 'Contact');
        pll_register_string('weoryx', 'Get In Touch', 'Contact');
        pll_register_string('weoryx', 'Have a project in mind? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.', 'Contact');
        pll_register_string('weoryx', 'Address', 'Contact');
        pll_register_string('weoryx', 'Email', 'Contact');
        pll_register_string('weoryx', 'Phone', 'Contact');
        pll_register_string('weoryx', 'Follow Us', 'Contact');
        pll_register_string('weoryx', 'Contact Form', 'Contact');
        pll_register_string('weoryx', 'Please configure the contact form shortcode in Theme Settings.', 'Contact');
    }
}
add_action('init', 'weoryx_register_additional_strings');

/**
 * Make Contact Form 7 placeholders bilingual
 */
function weoryx_translate_cf7_placeholders($tag)
{
    if (empty($tag)) return $tag;

    if (isset($tag['options'])) {
        foreach ($tag['options'] as $key => $option) {
            if (strpos($option, 'placeholder') === 0) {
                // Extract placeholder value
                $placeholder_text = trim(str_replace('placeholder', '', $option), ' "');
                if ($placeholder_text) {
                    // Translate it (this call pll__ if Polylang is active)
                    $translated_text = weoryx_translate($placeholder_text);
                    // Update the option
                    $tag['options'][$key] = 'placeholder "' . $translated_text . '"';
                }
            }
        }
    }
    return $tag;
}
add_filter('wpcf7_form_tag', 'weoryx_translate_cf7_placeholders', 10, 1);

/**
 * Manual Translation Filter (Fallback for no Polylang)
 */
function weoryx_translate_text($translated_text, $text, $domain)
{
    // Only translate 'weoryx' domain or specific strings
    if ($domain !== 'weoryx' && $domain !== 'default') {
        return $translated_text;
    }

    $current_lang = weoryx_get_current_lang();

    // If English, return original (assuming original is English)
    if ($current_lang === 'en') {
        return $text;
    }

    // Arabic Translations Map
    $translations = array(
        'Digital Marketing Agency' => 'وكالة تسويق رقمي',
        'Contact Us' => 'تواصل معنا',
        'Learn More' => 'اعرف المزيد',
        'Read More' => 'اقرأ المزيد',
        'Quick Links' => 'روابط سريعة',
        'All Rights Reserved.' => 'جميع الحقوق محفوظة.',
        'Address' => 'العنوان',
        'Email' => 'البريد الإلكتروني',
        'Phone' => 'الهاتف',
        'Follow Us' => 'تابعنا',
        'Subscribe to Our Newsletter' => 'اشترك في نشرتنا البريدية',
        'Subscribe' => 'اشترك',
        'Get In Touch' => 'تواصل معنا',
        'Our Services' => 'خدماتنا',
        'Our Work' => 'أعمالنا',
        'Testimonials' => 'آراء العملاء',
        'Latest Articles' => 'أحدث المقالات',
        'Why Choose WeOryx' => 'لماذا تختار ويوركس',
        'Portfolio' => 'مشاريعنا',
        'Blog' => 'المدونة',
        'See All Work' => 'شاهد جميع الأعمال',
        'What We Offer' => 'ماذا نقدم',
        'Your Name' => 'الاسم بالكامل',
        'Your Email' => 'البريد الإلكتروني',
        'Your Phone' => 'رقم الهاتف',
        'Your Message' => 'رسالتك',
        'Subject' => 'الموضوع',
        'Your Email Address' => 'بريدك الإلكتروني',
        'Send Message' => 'إرسال الرسالة',
        // Offer Block
        'Limited Time Offer' => 'عرض لفترة محدودة',
        'Own Your Website Starting From 1500 SAR' => 'امتلك موقعك الاحترافي بأسعار تبدأ من 1500 ريال',
        '1500 SAR' => '1500 ريال',
        'Starting From' => 'ابتداءً من',
        'Get your professional website launched in record time.' => 'احصل على موقعك الاحترافي وانطلق في عالم الأعمال في وقت قياسي.',
        'Limited spots available for this month.' => 'الأماكن محدودة لهذا الشهر، احجز مكانك الآن.',
        'Claim Offer Now' => 'احصل على العرض الآن',
        'Responsive Design' => 'تصميم متجاوب',
        'SEO Friendly' => 'صديق لمحركات البحث',
        'Fast Loading' => 'سرعة في التحميل',
        'Secure Hosting' => 'استضافة آمنة',
        'High Speed' => 'سرعة عالية',
        // 404 Page
        'Page Not Found | Lost in Space' => 'الصفحة غير موجودة | ضائع في الفضاء',
        'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.' => 'الصفحة التي تبحث عنها ربما تم حذفها أو تغيير اسمها أو أنها غير متاحة مؤقتاً.',
        'Back to Home' => 'العودة للرئيسية',
        // Add other common strings here
    );

    if (isset($translations[$text])) {
        return $translations[$text];
    }

    return $translated_text;
}
add_filter('gettext', 'weoryx_translate_text', 20, 3);

/**
 * Clean up archive titles by removing prefixes (Tag:, Category:, etc.)
 */
function weoryx_archive_title_filter($title)
{
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }

    return $title;
}
add_filter('get_the_archive_title', 'weoryx_archive_title_filter');

function weoryx_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_editor_style(array('css/styles.css', 'css/rtl.css', 'style-editor.css'));
    add_theme_support('wp-block-styles');

    // Add Editor Color Palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Primary', 'weoryx'),
            'slug'  => 'primary',
            'color' => '#f77f00',
        ),
        array(
            'name'  => __('Dark Blue', 'weoryx'),
            'slug'  => 'dark-blue',
            'color' => '#003049',
        ),
    ));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'weoryx'),
        'footer'  => __('Footer Menu', 'weoryx'),
    ));

    // Register Portfolio Category Taxonomy
    register_taxonomy('portfolio_category', 'portfolio', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Portfolio Categories', 'taxonomy general name', 'weoryx'),
            'singular_name' => _x('Portfolio Category', 'taxonomy singular name', 'weoryx'),
            'search_items' => __('Search Categories', 'weoryx'),
            'all_items' => __('All Categories', 'weoryx'),
            'parent_item' => __('Parent Category', 'weoryx'),
            'parent_item_colon' => __('Parent Category:', 'weoryx'),
            'edit_item' => __('Edit Category', 'weoryx'),
            'update_item' => __('Update Category', 'weoryx'),
            'add_new_item' => __('Add New Category', 'weoryx'),
            'new_item_name' => __('New Category Name', 'weoryx'),
            'menu_name' => __('Categories', 'weoryx'),
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'portfolio-category'),
    ));
}
add_action('after_setup_theme', 'weoryx_setup');

/**
 * Customizer Settings
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Register Polylang Strings
 */
function weoryx_register_strings()
{
    if (function_exists('pll_register_string')) {
        // Hero
        pll_register_string('weoryx', 'Digital Marketing Agency', 'Hero');
        pll_register_string('weoryx', 'DIGITAL MARKETING SOLUTION', 'Hero');
        pll_register_string('weoryx', 'FOR YOUR BUSINESS', 'Hero');
        pll_register_string('weoryx', 'Explore Now', 'Hero');

        // General & UI
        pll_register_string('weoryx', 'Contact Us', 'General');
        pll_register_string('weoryx', 'Learn More', 'General');
        pll_register_string('weoryx', 'Read More', 'General');
        pll_register_string('weoryx', 'Home', 'General');
        pll_register_string('weoryx', 'Blog', 'General');
        pll_register_string('weoryx', 'Address', 'General');
        pll_register_string('weoryx', 'Email', 'General');
        pll_register_string('weoryx', 'Your Name', 'General');
        pll_register_string('weoryx', 'Your Email', 'General');
        pll_register_string('weoryx', 'Your Message', 'General');
        pll_register_string('weoryx', 'Send Message', 'General');
        pll_register_string('weoryx', 'Years of Experience', 'General');
        pll_register_string('weoryx', 'Ready to Start Your Project?', 'General');
        pll_register_string('weoryx', 'Follow Us', 'General');
        pll_register_string('weoryx', 'All', 'General');
        pll_register_string('weoryx', 'Get In Touch', 'General');
        pll_register_string('weoryx', 'Have a project in mind? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.', 'General');

        // Sections
        pll_register_string('weoryx', 'Video Marketing', 'Sections');
        pll_register_string('weoryx', 'Get Your Message Across | Via Video', 'Sections');
        pll_register_string('weoryx', 'Latest Content', 'Sections');
        pll_register_string('weoryx', 'Watch Our | Stories', 'Sections');
        pll_register_string('weoryx', 'About Us', 'Sections');
        pll_register_string('weoryx', 'About WeOryx', 'Sections');
        pll_register_string('weoryx', 'Turning Your Ideas Into | Digital Reality', 'Sections');
        pll_register_string('weoryx', 'Why WeOryx?', 'Sections');
        pll_register_string('weoryx', 'Why Choose | Our Agency', 'Sections');
        pll_register_string('weoryx', 'Our Services', 'Sections');
        pll_register_string('weoryx', 'Our Professional Services', 'Sections');
        pll_register_string('weoryx', 'Pricing Plans', 'Sections');
        pll_register_string('weoryx', 'Investment Plans', 'Sections');
        pll_register_string('weoryx', 'Our Process', 'Sections');
        pll_register_string('weoryx', 'How We Work', 'Sections');
        pll_register_string('weoryx', 'Our Team', 'Sections');
        pll_register_string('weoryx', 'Meet Our Experts', 'Sections');
        pll_register_string('weoryx', 'Portfolio', 'Sections');
        pll_register_string('weoryx', 'Our Latest Projects', 'Sections');
        pll_register_string('weoryx', 'Latest Articles', 'Sections');
        pll_register_string('weoryx', 'Our Mission & Vision', 'Sections');
        pll_register_string('weoryx', 'Who We Are', 'Sections');
        pll_register_string('weoryx', 'Our Blog', 'Sections');
        pll_register_string('weoryx', 'Our Offers', 'Sections');
        pll_register_string('weoryx', 'Our Portfolio', 'Sections');
        pll_register_string('weoryx', 'Our Story', 'Sections');

        // Stats
        pll_register_string('weoryx', 'Happy Clients', 'Stats');
        pll_register_string('weoryx', 'Projects Completed', 'Stats');
        pll_register_string('weoryx', 'Awards Won', 'Stats');
        pll_register_string('weoryx', 'Years Experience', 'Stats');

        // Partner
        pll_register_string('weoryx', 'Our Partners', 'Partner');
        pll_register_string('weoryx', 'Trusted By | Leading Brands', 'Partner');

        // Offer
        pll_register_string('weoryx', 'Limited Time Offer', 'Offer');
        pll_register_string('weoryx', 'Own Your Website Starting From 1500 SAR', 'Offer');
        pll_register_string('weoryx', 'Claim Offer Now', 'Offer');
        pll_register_string('weoryx', '1500 SAR', 'Offer');
        pll_register_string('weoryx', 'Need a Custom Solution?', 'Offer');
        pll_register_string('weoryx', 'Contact us to discuss a tailored package for your business.', 'Offer');
        pll_register_string('weoryx', 'Starting From', 'Offer');
        pll_register_string('weoryx', 'Get your professional website launched in record time.', 'Offer');
        pll_register_string('weoryx', 'Limited spots available for this month.', 'Offer');
        pll_register_string('weoryx', 'Responsive Design', 'Offer');
        pll_register_string('weoryx', 'SEO Friendly', 'Offer');
        pll_register_string('weoryx', 'Fast Loading', 'Offer');
        pll_register_string('weoryx', 'Secure Hosting', 'Offer');
        pll_register_string('weoryx', 'High Speed', 'Offer');

        // Pricing
        pll_register_string('weoryx', 'Starter', 'Pricing');
        pll_register_string('weoryx', 'Professional', 'Pricing');
        pll_register_string('weoryx', 'Enterprise', 'Pricing');
        pll_register_string('weoryx', 'Most Popular', 'Pricing');
        pll_register_string('weoryx', 'month', 'Pricing');
        pll_register_string('weoryx', 'Get Started', 'Pricing');
        pll_register_string('weoryx', 'Choose Your', 'Pricing');
        pll_register_string('weoryx', 'Perfect Plan', 'Pricing');
        pll_register_string('weoryx', 'Flexible packages tailored to meet your business needs and goals.', 'Pricing');
        pll_register_string('weoryx', 'Perfect for small businesses getting started', 'Pricing');
        pll_register_string('weoryx', 'Website Maintenance', 'Pricing');
        pll_register_string('weoryx', 'Basic SEO Optimization', 'Pricing');
        pll_register_string('weoryx', '3 Social Media Posts/Week', 'Pricing');
        pll_register_string('weoryx', 'Monthly Reporting', 'Pricing');
        pll_register_string('weoryx', 'PPC Management', 'Pricing');
        pll_register_string('weoryx', 'Content Marketing', 'Pricing');
        pll_register_string('weoryx', 'Ideal for growing businesses', 'Pricing');
        pll_register_string('weoryx', 'Advanced SEO Optimization', 'Pricing');
        pll_register_string('weoryx', '5 Social Media Posts/Week', 'Pricing');
        pll_register_string('weoryx', 'Weekly Reporting', 'Pricing');
        pll_register_string('weoryx', 'Everything in Professional', 'Pricing');
        pll_register_string('weoryx', 'Dedicated Account Manager', 'Pricing');
        pll_register_string('weoryx', 'Daily Social Media Posts', 'Pricing');
        pll_register_string('weoryx', 'Real-time Reporting', 'Pricing');
        pll_register_string('weoryx', 'Priority Support', 'Pricing');
        pll_register_string('weoryx', 'Custom Strategy', 'Pricing');

        // Footer
        pll_register_string('weoryx', 'Quick Links', 'Footer');
        pll_register_string('weoryx', 'Your Email Address', 'Footer');
        pll_register_string('weoryx', 'All Rights Reserved.', 'Footer');

        // Buttons
        pll_register_string('weoryx', 'View All Services', 'Buttons');
        pll_register_string('weoryx', 'More About Us', 'Buttons');
        pll_register_string('weoryx', 'View More Projects', 'Buttons');
        pll_register_string('weoryx', 'See All Articles', 'Buttons');
        pll_register_string('weoryx', 'Subscribe', 'Buttons');
        pll_register_string('weoryx', 'View More', 'Buttons');

        // Messages
        pll_register_string('weoryx', 'No posts found.', 'Messages');
        pll_register_string('weoryx', 'No services found.', 'Messages');
        pll_register_string('weoryx', 'Subscribe to Our Newsletter', 'Messages');
        pll_register_string('weoryx', 'Get the latest insights delivered to your inbox.', 'Messages');
        pll_register_string('weoryx', 'Want to Start Your Project?', 'Messages');
        pll_register_string('weoryx', 'Let\'s create something amazing together.', 'Messages');
        pll_register_string('weoryx', 'Experience and Expertise', 'Messages');
        pll_register_string('weoryx', 'Years of collective experience in digital transformation.', 'Messages');

        // 404 Page
        pll_register_string('weoryx', 'Page Not Found | Lost in Space', '404');
        pll_register_string('weoryx', 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', '404');
        pll_register_string('weoryx', 'Back to Home', '404');
    }
}
add_action('init', 'weoryx_register_strings');

function weoryx_scripts()
{
    // Styles
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap', array(), null);
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');
    wp_enqueue_style('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.css', array(), '2.3.1');
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
    wp_enqueue_style('weoryx-main-style', get_template_directory_uri() . '/css/styles.css', array('aos', 'swiper'), '1.2.3');
    wp_enqueue_style('weoryx-theme-style', get_stylesheet_uri(), array('weoryx-main-style'), time());

    // Check for English mode (consistent with header.php logic)
    $is_english = weoryx_get_current_lang() === 'en';

    // Enqueue RTL if NOT English (Default to Arabic)
    if (!$is_english) {
        wp_enqueue_style('weoryx-rtl', get_template_directory_uri() . '/css/rtl.css', array('weoryx-theme-style'), '1.0.3');
    }

    // Scripts
    wp_enqueue_script('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '2.3.1', true);
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    wp_enqueue_script('weoryx-main-script', get_template_directory_uri() . '/js/main.js', array('jquery', 'aos', 'swiper'), time(), true);

    wp_localize_script('weoryx-main-script', 'weoryxData', array(
        'templateUrl' => get_template_directory_uri()
    ));

    // Payment Pages Specific Scripts
    if (is_page_template('page-payment-usd.php') || is_page_template('page-payment-sar.php')) {
        wp_enqueue_script('stripe-v3', 'https://js.stripe.com/v3/', array(), null, false);
        wp_enqueue_script('weoryx-payment-js', get_template_directory_uri() . '/js/payment.js', array('jquery', 'stripe-v3'), time(), true);

        $is_sar = is_page_template('page-payment-sar.php');
        $currency = $is_sar ? 'sar' : 'usd';
        $currency_sym = $is_sar ? 'ر.س' : '$';
        $locale = $is_sar ? 'ar' : 'en';

        wp_localize_script('weoryx-payment-js', 'weoryxPayment', array(
            'publishableKey' => get_option('weoryx_stripe_publishable_key', ''),
            'ajaxUrl'        => admin_url('admin-ajax.php'),
            'nonce'          => wp_create_nonce('weoryx_stripe_pay'),
            'thankYouUrl'    => get_option('weoryx_stripe_thankyou_url', home_url('/payment-thank-you/')),
            'currency'       => $currency,
            'currencySym'    => $currency_sym,
            'isRTL'          => $is_sar,
            'locale'         => $locale
        ));
    }
}
add_action('wp_enqueue_scripts', 'weoryx_scripts');

/**
 * Include Stripe AJAX Handler
 */
require get_template_directory() . '/inc/stripe-ajax.php';

/**
 * Stripe Settings Page
 */
function weoryx_stripe_settings_menu() {
    add_options_page('Stripe Payment Settings', 'Stripe Payments', 'manage_options', 'weoryx-stripe', 'weoryx_stripe_settings_page');
}
add_action('admin_menu', 'weoryx_stripe_settings_menu');

function weoryx_stripe_settings_page() {
    if (!current_user_can('manage_options')) return;
    if (isset($_POST['weoryx_stripe_save'])) {
        update_option('weoryx_stripe_publishable_key', sanitize_text_field($_POST['pub_key']));
        update_option('weoryx_stripe_secret_key', sanitize_text_field($_POST['sec_key']));
        update_option('weoryx_stripe_thankyou_url', esc_url_raw($_POST['thanks_url']));
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }
    $pub = get_option('weoryx_stripe_publishable_key', '');
    $sec = get_option('weoryx_stripe_secret_key', '');
    $thanks = get_option('weoryx_stripe_thankyou_url', home_url('/payment-thank-you/'));
    ?>
    <div class="wrap">
        <h1>Stripe Payment Settings</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row">Publishable Key (pk_...)</th>
                    <td><input type="text" name="pub_key" value="<?php echo esc_attr($pub); ?>" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row">Secret Key (sk_...)</th>
                    <td><input type="password" name="sec_key" value="<?php echo esc_attr($sec); ?>" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row">Thank You Page URL</th>
                    <td><input type="url" name="thanks_url" value="<?php echo esc_attr($thanks); ?>" class="regular-text"></td>
                </tr>
            </table>
            <?php submit_button('Save Settings', 'primary', 'weoryx_stripe_save'); ?>
        </form>
    </div>
    <?php
}

/**
 * Register Custom Post Types
 */
function weoryx_register_post_types()
{
    // Services Post Type
    register_post_type('services', array(
        'labels' => array(
            'name' => __('Services', 'weoryx'),
            'singular_name' => __('Service', 'weoryx'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-rest-api',
    ));

    // Portfolio Post Type
    register_post_type('portfolio', array(
        'labels' => array(
            'name' => __('Portfolio', 'weoryx'),
            'singular_name' => __('Project', 'weoryx'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-portfolio',
    ));

    // Testimonials Post Type
    register_post_type('testimonial', array(
        'labels' => array(
            'name' => __('Testimonials', 'weoryx'),
            'singular_name' => __('Testimonial', 'weoryx'),
        ),
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-format-quote',
    ));

    // Reels Post Type
    register_post_type('reels', array(
        'labels' => array(
            'name' => __('Reels', 'weoryx'),
            'singular_name' => __('Reel', 'weoryx'),
        ),
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'thumbnail'),
        'menu_icon' => 'dashicons-video-alt3',
    ));

    // WhatsApp Groups Post Type
    register_post_type('whatsapp_gcid', array(
        'labels' => array(
            'name' => __('WhatsApp Groups', 'weoryx'),
            'singular_name' => __('WhatsApp Group', 'weoryx'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-groups',
    ));
}
add_action('init', 'weoryx_register_post_types');


/**
 * Register Gutenberg Blocks
 */
function weoryx_register_blocks()
{
    // Hero Block
    register_block_type('weoryx/hero', array(
        'api_version' => 2,
        'attributes' => array(
            'slides' => array('type' => 'array', 'default' => array()),
        ),
        'supports' => array(
            'html' => false,
            'customClassName' => false,
        ),
        'example' => array(
            'attributes' => array(
                'slides' => array(),
            ),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/hero/render.php';
            return ob_get_clean();
        }
    ));

    // Services Block
    register_block_type('weoryx/services', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => ''),
            'description' => array('type' => 'string', 'default' => ''),
            'count' => array('type' => 'number', 'default' => 6),
        ),
        'supports' => array(
            'html' => false,
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/services/render.php';
            return ob_get_clean();
        }
    ));

    // Stats Block
    register_block_type('weoryx/stats', array(
        'attributes' => array(
            'stats' => array('type' => 'array', 'default' => array()),
            'variant' => array('type' => 'string', 'default' => 'simple'),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/stats/render.php';
            return ob_get_clean();
        }
    ));

    // Portfolio Block
    register_block_type('weoryx/portfolio', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => 'Our Work'),
            'title' => array('type' => 'string', 'default' => 'Recent | Projects'),
            'subtitle' => array('type' => 'string', 'default' => 'Samples of our work that helped our clients achieve their goals.'),
            'buttonText' => array('type' => 'string', 'default' => 'See All Work'),
            'buttonUrl' => array('type' => 'string', 'default' => '/portfolio'),
            'items' => array('type' => 'array', 'default' => array()),
            'count' => array('type' => 'number', 'default' => 5),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/portfolio/render.php';
            return ob_get_clean();
        }
    ));

    // Reviews Block
    register_block_type('weoryx/reviews', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => ''),
            'description' => array('type' => 'string', 'default' => ''),
            'subtitle' => array('type' => 'string', 'default' => ''),
            'items' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/testimonials/render.php';
            return ob_get_clean();
        }
    ));


    // About Block
    register_block_type('weoryx/about', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => ''),
            'description' => array('type' => 'string', 'default' => ''),
            'features' => array('type' => 'array', 'default' => array()),
            'expNumber' => array('type' => 'string', 'default' => '8+'),
            'expText' => array('type' => 'string', 'default' => 'Years of Experience'),
            'buttonText' => array('type' => 'string', 'default' => ''),
            'buttonUrl' => array('type' => 'string', 'default' => ''),
            'imageUrl' => array('type' => 'string', 'default' => ''),
            'variant' => array('type' => 'string', 'default' => 'simple'),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/about/render.php';
            return ob_get_clean();
        }
    ));

    // Clients Block
    register_block_type('weoryx/clients', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => ''),
            'clients' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/clients/render.php';
            return ob_get_clean();
        }
    ));

    // Video Block
    register_block_type('weoryx/video', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => ''),
            'description' => array('type' => 'string', 'default' => ''),
            'thumbnail' => array('type' => 'string', 'default' => ''),
            'videoUrl' => array('type' => 'string', 'default' => ''),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/video/render.php';
            return ob_get_clean();
        }
    ));

    // Reels Block
    register_block_type('weoryx/reels', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => ''),
            'reels' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/reels/render.php';
            return ob_get_clean();
        }
    ));

    // Choose Us Block
    register_block_type('weoryx/choose-us', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => ''),
            'description' => array('type' => 'string', 'default' => ''),
            'items' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/choose-us/render.php';
            return ob_get_clean();
        }
    ));

    // Offer Block
    register_block_type('weoryx/offer', array(
        'api_version' => 2,
        'attributes' => array(
            'price' => array('type' => 'string', 'default' => ''),
            'pricePrefix' => array('type' => 'string', 'default' => 'Starting From'),
            'tag' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => ''),
            'cardDescription' => array('type' => 'string', 'default' => ''),
            'cardNote' => array('type' => 'string', 'default' => ''),
            'features' => array('type' => 'array', 'default' => array()),
            'buttonText' => array('type' => 'string', 'default' => ''),
            'buttonUrl' => array('type' => 'string', 'default' => ''),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/offer/render.php';
            return ob_get_clean();
        }
    ));

    // Mission Vision Block
    register_block_type('weoryx/mission-vision', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => 'Who We Are'),
            'title' => array('type' => 'string', 'default' => 'Our Mission & Vision'),
            'items' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/mission-vision/render.php';
            return ob_get_clean();
        }
    ));

    // Steps Block
    register_block_type('weoryx/steps', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => 'Our Process'),
            'title' => array('type' => 'string', 'default' => 'How We Work'),
            'items' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/steps/render.php';
            return ob_get_clean();
        }
    ));

    // Pricing Block
    register_block_type('weoryx/pricing', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => ''),
            'subtitle' => array('type' => 'string', 'default' => ''),
            'plans' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/pricing/render.php';
            return ob_get_clean();
        }
    ));

    // Team Block
    register_block_type('weoryx/team', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => 'Our Team'),
            'title' => array('type' => 'string', 'default' => 'Meet Our Experts'),
            'members' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/team/render.php';
            return ob_get_clean();
        }
    ));

    // Pricing Block
    register_block_type('weoryx/pricing', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => 'Pricing Plans'),
            'title' => array('type' => 'string', 'default' => 'Investment Plans'),
            'plans' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/pricing/render.php';
            return ob_get_clean();
        }
    ));

    // Contact Block
    register_block_type('weoryx/contact', array(
        'api_version' => 2,
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => 'Get In Touch'),
            'description' => array('type' => 'string', 'default' => 'Have a project in mind? We\'d love to hear from you.'),
            'address' => array('type' => 'string', 'default' => '123 Business District, Dubai, UAE'),
            'email' => array('type' => 'string', 'default' => 'info@weoryx.com'),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/contact/render.php';
            return ob_get_clean();
        }
    ));

    // Blog Block
    register_block_type('weoryx/blog', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => 'Blog'),
            'title' => array('type' => 'string', 'default' => 'Latest Articles'),
            'subtitle' => array('type' => 'string', 'default' => ''),
            'count' => array('type' => 'number', 'default' => 3),
            'posts' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/blog/render.php';
            return ob_get_clean();
        }
    ));

    // CTA Block
    register_block_type('weoryx/cta', array(
        'api_version' => 2,
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => 'Ready to Start Your Project?'),
            'description' => array('type' => 'string', 'default' => 'Let\'s discuss how we can help you achieve your digital marketing goals. Get a free consultation today!'),
            'buttonText' => array('type' => 'string', 'default' => 'Contact Us'),
            'buttonUrl' => array('type' => 'string', 'default' => '/contact'),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/cta/render.php';
            return ob_get_clean();
        }
    ));

    // Service Intro Block
    register_block_type('weoryx/service-intro', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => ''),
            'title' => array('type' => 'string', 'default' => ''),
            'content' => array('type' => 'string', 'default' => ''),
            'imageUrl' => array('type' => 'string', 'default' => ''),
            'imagePosition' => array('type' => 'string', 'default' => 'right'), // left or right
            'buttonText' => array('type' => 'string', 'default' => ''),
            'buttonUrl' => array('type' => 'string', 'default' => ''),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/service-intro/render.php';
            return ob_get_clean();
        }
    ));

    // Related Portfolio Block (Dynamic)
    register_block_type('weoryx/related-portfolio', array(
        'api_version' => 2,
        'category' => 'weoryx',
        'title' => __('Related Portfolio', 'weoryx'),
        'description' => __('Displays related portfolio items based on the selected category.', 'weoryx'),
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => 'Previous | Work'),
            'tag' => array('type' => 'string', 'default' => 'Portfolio'),
            'count' => array('type' => 'number', 'default' => 3),
            'categoryId' => array('type' => 'string', 'default' => ''),
        ),
        'render_callback' => 'weoryx_render_related_portfolio_block'
    ));

    // Service Request Block
    register_block_type('weoryx/service-request', array(
        'api_version' => 2,
        'attributes' => array(
            'tag' => array('type' => 'string', 'default' => 'Ready to Start?'),
            'title' => array('type' => 'string', 'default' => 'Book This Service | Now'),
            'description' => array('type' => 'string', 'default' => 'Ready to take your business to the next level? Fill out the form below and our team will get back to you with a tailored strategy.'),
            'features' => array(
                'type' => 'array',
                'default' => array(
                    array('icon' => 'fas fa-check-circle', 'text' => 'Free Consultation'),
                    array('icon' => 'fas fa-check-circle', 'text' => 'Custom Strategy'),
                    array('icon' => 'fas fa-check-circle', 'text' => 'Dedicated Support'),
                )
            ),
            'formShortcode' => array('type' => 'string', 'default' => ''),
        ),
        'render_callback' => function ($attributes) {
            ob_start();
            include get_template_directory() . '/blocks/service-request/render.php';
            return ob_get_clean();
        }
    ));
}
add_action('init', 'weoryx_register_blocks');

/**
 * Enqueue block editor assets
 */
function weoryx_enqueue_block_editor_assets()
{
    wp_enqueue_script(
        'weoryx-blocks-editor',
        get_template_directory_uri() . '/js/blocks-editor.js',
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render'),
        filemtime(get_template_directory() . '/js/blocks-editor.js'),
        false
    );

    $categories = get_terms(array(
        'taxonomy' => 'portfolio_category',
        'hide_empty' => false,
    ));
    $cat_options = array(array('label' => __('Select Category', 'weoryx'), 'value' => ''));
    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $cat) {
            $cat_options[] = array('label' => $cat->name, 'value' => (string)$cat->term_id);
        }
    }

    wp_localize_script('weoryx-blocks-editor', 'weoryxData', array(
        'templateUrl' => get_template_directory_uri(),
        'portfolioCategories' => $cat_options
    ));

    // Enqueue Frontend Assets for Editor Preview
    wp_enqueue_style('weoryx-aos', 'https://unpkg.com/aos@2.3.1/dist/aos.css');
    wp_enqueue_script('weoryx-aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), null, true);

    wp_enqueue_style('weoryx-swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('weoryx-swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);

    wp_enqueue_script('weoryx-main-js', get_template_directory_uri() . '/js/main.js', array('weoryx-aos-js', 'weoryx-swiper-js'), null, true);

    wp_localize_script('weoryx-main-js', 'weoryxData', array(
        'templateUrl' => get_template_directory_uri()
    ));

    // Force AOS elements to be visible in the editor to prevent invisibility issues
    wp_add_inline_style('wp-block-library', '
        .editor-styles-wrapper [data-aos], 
        .block-editor-block-list__layout [data-aos] { 
            opacity: 1 !important; 
            transform: none !important; 
            visibility: visible !important;
            transition: none !important;
        }
    ');

    // Enqueue Google Fonts and Font Awesome for the editor
    wp_enqueue_style('weoryx-google-fonts', 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap', array(), null);
    wp_enqueue_style('weoryx-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), '6.4.2');

    // Enqueue Main Styles for Editor Preview
    wp_enqueue_style('weoryx-styles', get_template_directory_uri() . '/css/styles.css', array(), time());
}
add_action('enqueue_block_editor_assets', 'weoryx_enqueue_block_editor_assets');

/**
 * Add default supports to all WeOryx blocks
 */
function weoryx_add_block_supports($args, $block_type)
{
    // Only apply to WeOryx blocks
    if (strpos($block_type, 'weoryx/') === 0) {
        if (!isset($args['supports'])) {
            $args['supports'] = array();
        }
        if (!isset($args['supports']['html'])) {
            $args['supports']['html'] = false;
        }
        if (!isset($args['supports']['customClassName'])) {
            $args['supports']['customClassName'] = false;
        }
        if (!isset($args['api_version'])) {
            $args['api_version'] = 2;
        }
        // Add example to indicate the block is supported
        if (!isset($args['example'])) {
            $args['example'] = array();
        }
    }
    return $args;
}
add_filter('register_block_type_args', 'weoryx_add_block_supports', 10, 2);



/**
 * Add custom block category
 */
function weoryx_block_categories($categories, $post)
{
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'weoryx',
                'title' => __('WeOryx Blocks', 'weoryx'),
            ),
        )
    );
}
add_filter('block_categories_all', 'weoryx_block_categories', 10, 2);

/**
 * Register Meta Boxes
 */
function weoryx_add_service_meta_boxes()
{
    add_meta_box(
        'testimonial_role_meta',
        __('Author Role', 'weoryx'),
        'weoryx_render_testimonial_role_meta_box',
        'testimonial',
        'side',
        'default'
    );
    add_meta_box(
        'reels_meta',
        __('Reel Details', 'weoryx'),
        'weoryx_render_reels_meta_box',
        'reels',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'weoryx_add_service_meta_boxes');

/**
 * Render Testimonial Role Meta Box
 */
function weoryx_render_testimonial_role_meta_box($post)
{
    wp_nonce_field('weoryx_save_testimonial_role', 'weoryx_testimonial_role_nonce');
    $value = get_post_meta($post->ID, 'author_role', true);
    $lang = get_post_meta($post->ID, '_post_language', true);

    echo '<p><label for="weoryx_author_role">' . __('Author Role (e.g., CEO, NexaSystems):', 'weoryx') . '</label></p>';
    echo '<input type="text" id="weoryx_author_role" name="weoryx_author_role" value="' . esc_attr($value) . '" class="widefat">';

    echo '<p><label for="weoryx_post_language"><strong>' . __('Language:', 'weoryx') . '</strong></label></p>';
    echo '<select id="weoryx_post_language" name="weoryx_post_language" class="widefat">';
    echo '<option value="ar"' . selected($lang, 'ar', false) . '>Arabic</option>';
    echo '<option value="en"' . selected($lang, 'en', false) . '>English</option>';
    echo '</select>';
}

/**
 * Render Reels Meta Box
 */
function weoryx_render_reels_meta_box($post)
{
    wp_nonce_field('weoryx_save_reels_meta', 'weoryx_reels_nonce');
    $video_url = get_post_meta($post->ID, '_reel_video_url', true);
    $category = get_post_meta($post->ID, '_reel_category', true);
    $lang = get_post_meta($post->ID, '_post_language', true);

    echo '<p><label for="weoryx_reel_video_url"><strong>' . __('Video URL (Direct MP4 link):', 'weoryx') . '</strong></label></p>';
    echo '<input type="text" id="weoryx_reel_video_url" name="weoryx_reel_video_url" value="' . esc_attr($video_url) . '" class="widefat" placeholder="https://example.com/video.mp4">';

    echo '<p><label for="weoryx_reel_category"><strong>' . __('Category (e.g., Marketing, Tech):', 'weoryx') . '</strong></label></p>';
    echo '<input type="text" id="weoryx_reel_category" name="weoryx_reel_category" value="' . esc_attr($category) . '" class="widefat" placeholder="Marketing">';

    echo '<p><label for="weoryx_post_language"><strong>' . __('Language:', 'weoryx') . '</strong></label></p>';
    echo '<select id="weoryx_post_language" name="weoryx_post_language" class="widefat">';
    echo '<option value="ar"' . selected($lang, 'ar', false) . '>Arabic</option>';
    echo '<option value="en"' . selected($lang, 'en', false) . '>English</option>';
    echo '</select>';
}


/**
 * Render Related Portfolio Block (Dynamic)
 */
function weoryx_render_related_portfolio_block($attributes)
{
    // On editor, show placeholder or preview if possible
    if (is_admin()) {
        return '<div class="related-portfolio-admin-placeholder" style="padding: 40px; border: 2px dashed #ccc; text-align: center; background: #f9f9f9; border-radius: 12px; font-family: sans-serif;">' .
            '<strong>' . __('Related Portfolio', 'weoryx') . '</strong><br>' .
            '[' . __('Displays projects based on selected category in Metabox', 'weoryx') . ']' .
            '</div>';
    }

    $categoryId = isset($attributes['categoryId']) ? $attributes['categoryId'] : '';

    if (empty($categoryId)) {
        return '';
    }

    $title = isset($attributes['title']) ? weoryx_translate($attributes['title']) : weoryx_translate('Previous | Work');
    $tag = isset($attributes['tag']) ? weoryx_translate($attributes['tag']) : weoryx_translate('Portfolio');
    $count = isset($attributes['count']) ? $attributes['count'] : 3;

    $portfolio_query = new WP_Query(array(
        'post_type' => 'portfolio',
        'posts_per_page' => $count,
        'lang' => weoryx_get_current_lang(), // Filter by current language
        'tax_query' => array(
            array(
                'taxonomy' => 'portfolio_category',
                'field' => 'term_id',
                'terms' => $categoryId,
            ),
        ),
    ));

    if (!$portfolio_query->have_posts()) {
        return '';
    }

    ob_start();
?>
    <section class="related-portfolio-block container" style="margin-top: 60px; margin-bottom: 80px;">
        <div class="section-header" data-aos="fade-up" style="margin-bottom: 50px;">
            <span class="section-tag"><?php echo esc_html($tag); ?></span>
            <h2 class="section-title">
                <?php echo weoryx_format_title($title); ?>
            </h2>
            <div class="header-line" style="width: 60px; height: 3px; background: var(--accent-orange, #E85A3C); margin-top: 15px;"></div>
        </div>

        <div class="service-portfolio-grid">
            <?php
            while ($portfolio_query->have_posts()) : $portfolio_query->the_post();
            ?>
                <div class="service-portfolio-item" data-aos="fade-up">
                    <div class="service-portfolio-content">
                        <div class="service-portfolio-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large'); ?>
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/portfolio-placeholder.jpg" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <div class="sp-overlay-badge"><?php
                                                            $cats = get_the_terms(get_the_ID(), 'portfolio_category');
                                                            echo $cats ? esc_html($cats[0]->name) : weoryx_translate('Project');
                                                            ?></div>
                        </div>
                        <div class="service-portfolio-info">
                            <h4 class="sp-title"><?php the_title(); ?></h4>
                            <a href="<?php the_permalink(); ?>" class="sp-link">
                                <?php echo esc_html(weoryx_translate('View Case Study')); ?> <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </section>

    <style>
        .service-portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .service-portfolio-item {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.03);
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .service-portfolio-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(26, 95, 122, 0.12);
            border-color: rgba(232, 90, 60, 0.2);
        }

        .service-portfolio-image {
            height: 240px;
            overflow: hidden;
            position: relative;
        }

        .service-portfolio-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }

        .service-portfolio-item:hover .service-portfolio-image img {
            transform: scale(1.1);
        }

        .sp-overlay-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--primary-blue, #1A5F7A);
            text-transform: uppercase;
            letter-spacing: 1px;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .service-portfolio-info {
            padding: 30px;
        }

        .sp-title {
            font-size: 1.4rem;
            margin-bottom: 20px;
            color: var(--primary-blue, #1A5F7A);
            font-weight: 800;
            line-height: 1.3;
        }

        .sp-link {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--accent-orange, #E85A3C);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sp-link i {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .sp-link:hover {
            gap: 15px;
            color: var(--primary-blue, #1A5F7A);
        }

        /* RTL Support */
        [dir="rtl"] .sp-overlay-badge {
            left: auto;
            right: 20px;
        }

        [dir="rtl"] .sp-link i {
            transform: scaleX(-1);
        }

        [dir="rtl"] .sp-link:hover i {
            transform: scaleX(-1) translateX(5px);
        }

        [dir="rtl"] .section-header {
            text-align: right;
        }

        [dir="rtl"] .service-portfolio-info {
            text-align: right;
        }

        [dir="rtl"] .header-line {
            margin-left: auto;
            margin-right: 0;
        }

        @media (max-width: 991px) {
            .service-portfolio-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .service-portfolio-grid {
                grid-template-columns: 1fr;
            }

            .sp-title {
                font-size: 1.25rem;
            }
        }
    </style>
<?php
    return ob_get_clean();
}




/**
 * Save Meta Box Data
 */
function weoryx_save_service_meta_data($post_id)
{
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }


    // Save Testimonial Role
    if (isset($_POST['weoryx_testimonial_role_nonce'])) {
        if (wp_verify_nonce($_POST['weoryx_testimonial_role_nonce'], 'weoryx_save_testimonial_role')) {
            if (isset($_POST['weoryx_author_role'])) {
                update_post_meta($post_id, 'author_role', sanitize_text_field($_POST['weoryx_author_role']));
            }
        }
    }

    // Save Reels Meta
    if (isset($_POST['weoryx_reels_nonce'])) {
        if (wp_verify_nonce($_POST['weoryx_reels_nonce'], 'weoryx_save_reels_meta')) {
            if (isset($_POST['weoryx_reel_video_url'])) {
                update_post_meta($post_id, '_reel_video_url', esc_url_raw($_POST['weoryx_reel_video_url']));
            }
            if (isset($_POST['weoryx_reel_category'])) {
                update_post_meta($post_id, '_reel_category', sanitize_text_field($_POST['weoryx_reel_category']));
            }
            if (isset($_POST['weoryx_post_language'])) {
                update_post_meta($post_id, '_post_language', sanitize_text_field($_POST['weoryx_post_language']));
            }
        }
    }


    // General Language Save for other CPTs
    if (in_array(get_post_type($post_id), ['services', 'service', 'testimonial'])) {
        if (isset($_POST['weoryx_post_language'])) {
            update_post_meta($post_id, '_post_language', sanitize_text_field($_POST['weoryx_post_language']));
        }
    }
}
add_action('save_post', 'weoryx_save_service_meta_data');

/**
 * Setup English Homepage Content (One-time setup)
 */
function weoryx_setup_english_content()
{
    if (isset($_GET['setup_english']) && $_GET['setup_english'] === 'true' && current_user_can('manage_options')) {

        $page_slug = 'home-english';
        $page_title = 'Home English';

        $page = get_page_by_path($page_slug);

        // Block Content Construction
        $content = '';

        // 1. Hero Block
        $content .= '<!-- wp:weoryx/hero {"slides":[{"title":"DIGITAL MARKETING SOLUTION<br>FOR YOUR BUSINESS","tag":"Digital Marketing Agency","description":"We help you grow your business through data-driven marketing strategies and world-class software solutions.","imageUrl":"' . get_template_directory_uri() . '/images/hero-person-1.png","buttonText":"Contact Us","buttonUrl":"/contact?lang=en"},{"title":"TRANSFORM YOUR ONLINE<br>PRESENCE TODAY","tag":"Web Development","description":"Build stunning, responsive websites that convert visitors into customers with our expert development team.","imageUrl":"' . get_template_directory_uri() . '/images/hero-person-2.png","buttonText":"Our Services","buttonUrl":"/services?lang=en"},{"title":"GROW YOUR BUSINESS<br>WITH DATA-DRIVEN RESULTS","tag":"SEO & Analytics","description":"Boost your search rankings and maximize ROI with our proven SEO strategies and analytics solutions.","imageUrl":"' . get_template_directory_uri() . '/images/hero-person-3.png","buttonText":"View Portfolio","buttonUrl":"/portfolio?lang=en"}]} /-->';

        // 2. Video Block
        $content .= '<!-- wp:weoryx/video {"tag":"Creative Marketing","title":"Get Your Message Across | Via Video","description":"We believe video is the most powerful way to convey your message. Let us help you create visual content that captivates minds and hearts.","videoUrl":"https://www.youtube.com/embed/dQw4w9WgXcQ","thumbnail":"' . get_template_directory_uri() . '/images/video-thumb.png"} /-->';

        // 3. Reels Block
        $content .= '<!-- wp:weoryx/reels {"tag":"Quick Glimpses","title":"Watch Our | Success Stories","reels":[{"img":"video-thumb.png","video":"https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4","title":"Design Creativity","category":"Design"},{"img":"portfolio-1.jpg","video":"https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4","title":"Attention to Detail","category":"Video"},{"img":"portfolio-3.jpg","video":"https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4","title":"Business Future","category":"Tech"},{"img":"blog-1.jpg","video":"https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4","title":"Digital Productivity","category":"Work"},{"img":"portfolio-2.jpg","video":"https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4","title":"Execution Speed","category":"Dev"},{"img":"blog-2.jpg","video":"https://storage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4","title":"Inspiring Moments","category":"Creative"}]} /-->';

        // 4. About Block
        $content .= '<!-- wp:weoryx/about {"tag":"About WeOryx","title":"Turning Your Ideas Into | Digital Reality","description":"WeOryx is a specialized digital marketing agency offering creative and innovative solutions for companies and organizations. We believe every brand deserves a unique digital presence.","features":[{"icon":"fas fa-rocket","title":"Fast Results","description":"We achieve tangible results in record time."},{"icon":"fas fa-users","title":"Expert Team","description":"Specialized experts in digital marketing fields."}],"buttonText":"Discover More","buttonUrl":"/about?lang=en","imageUrl":"' . get_template_directory_uri() . '/images/about-team.jpg"} /-->';

        // 5. Choose Us Block
        $content .= '<!-- wp:weoryx/choose-us {"tag":"Why Us?","title":"Why Choose | WeOryx","description":"We combine creativity with technical expertise to deliver tangible results.","items":[{"icon":"fas fa-users","title":"Expert Team","desc":"Our team consists of certified professionals with years of successful experience."},{"icon":"fas fa-rocket","title":"Proven Results","desc":"We focus on data-driven strategies to achieve real ROI."},{"icon":"fas fa-headset","title":"24/7 Support","desc":"Always by your side to solve any problem and ensure business continuity."}]} /-->';

        // 6. Services Block
        $content .= '<!-- wp:weoryx/services {"tag":"Our Services","title":"What Do We | Offer You?","description":"We offer a comprehensive range of digital services designed to meet your business needs.","services":[{"icon":"fas fa-code","title":"Web Development","desc":"Custom websites with latest technologies, optimized for performance and conversions."},{"icon":"fas fa-search","title":"SEO Optimization","desc":"Boost your search ranking and drive organic traffic with proven SEO strategies."},{"icon":"fas fa-bullhorn","title":"Digital Marketing","desc":"Comprehensive marketing strategies to increase engagement and maximize ROI."},{"icon":"fas fa-paint-brush","title":"Brand Identity","desc":"Create a unique identity that sticks in the minds of your target audience."},{"icon":"fas fa-share-alt","title":"Social Media","desc":"Engage with your audience and build brand awareness across all platforms."},{"icon":"fas fa-mobile-alt","title":"App Development","desc":"Professional mobile applications for iOS and Android platforms."}]} /-->';

        // 7. Portfolio Block
        $content .= '<!-- wp:weoryx/portfolio {"tag":"Our Work","title":"Recent | Projects","subtitle":"Samples of our work that helped our clients achieve their goals.","buttonText":"See All Work","buttonUrl":"/portfolio?lang=en","items":[{"title":"E-commerce Platform","category":"Web Development","image":"' . get_template_directory_uri() . '/images/portfolio-1.jpg","link":"#"},{"title":"Corporate Branding","category":"Graphic Design","image":"' . get_template_directory_uri() . '/images/portfolio-2.jpg","link":"#"},{"title":"Marketing Campaign","category":"Marketing","image":"' . get_template_directory_uri() . '/images/portfolio-3.jpg","link":"#"},{"title":"Mobile App","category":"App Development","image":"' . get_template_directory_uri() . '/images/portfolio-4.jpg","link":"#"},{"title":"SEO Strategy","category":"SEO","image":"' . get_template_directory_uri() . '/images/portfolio-2.jpg","link":"#"}]} /-->';

        // 8. Stats Block
        $content .= '<!-- wp:weoryx/stats {"stats":[{"icon":"fa-check-circle","number":"150","label":"Projects Done"},{"icon":"fa-smile","number":"120","label":"Happy Clients"},{"icon":"fa-calendar-alt","number":"8","label":"Years Experience"},{"icon":"fa-user-tie","number":"25","label":"Expert Members"}]} /-->';

        // 9. Clients Block
        $content .= '<!-- wp:weoryx/clients {"tag":"Our Clients","title":"Partners of | Success","clients":["client-1.svg","client-2.svg","client-3.svg","client-4.svg","client-5.svg","client-6.svg"]} /-->';

        // 10. Reviews Block
        $content .= '<!-- wp:weoryx/reviews {"tag":"Testimonials","title":"What Our | Clients Say","subtitle":"We are proud of our clients\' trust and our partnership in their digital success journey.","items":[{"content":"WeOryx completely transformed our digital presence. Their strategic approach helped us increase sales by 300%.","name":"Ahmed Hassan","role":"CEO, TechStart","image":"' . get_template_directory_uri() . '/images/testimonial-1.jpg","rating":5},{"content":"Exceptional team. They delivered a stunning website that exceeded our expectations. Their attention to detail is unmatched.","name":"Sarah Mohamed","role":"Marketing Manager, Global Brand","image":"' . get_template_directory_uri() . '/images/testimonial-2.jpg","rating":5},{"content":"Working with WeOryx was a turning point for our business. Their SEO services got us to the first page of Google.","name":"Mohamed Ali","role":"Founder, EcoStore","image":"' . get_template_directory_uri() . '/images/testimonial-3.jpg","rating":5}]} /-->';

        // 11. Offer Block
        $content .= '<!-- wp:weoryx/offer {"price":"1500 SAR","tag":"Limited Time Offer","title":"Own Your Website Starting From 1500 SAR","features":[{"text":"Responsive Design"},{"text":"SEO Friendly"},{"text":"Fast Loading"},{"text":"Secure Hosting"}],"buttonText":"Claim Offer Now","buttonUrl":"/contact?lang=en"} /-->';

        // 12. Blog Block
        $content .= '<!-- wp:weoryx/blog {"tag":"Our Blog","title":"Latest | Articles","subtitle":"We share our knowledge and expertise in the digital marketing world.","buttonText":"View All","buttonUrl":"/blog?lang=en","posts":[{"title":"Top Digital Marketing Trends for 2024","category":"Marketing","date":"Jan 15, 2024","excerpt":"Discover the latest trends shaping the digital marketing landscape and how to leverage them.","image":"' . get_template_directory_uri() . '/images/blog-1.jpg","link":"#"},{"title":"SEO Best Practices","category":"SEO","date":"Jan 10, 2024","excerpt":"Learn essential SEO strategies to boost your site visibility in search results.","image":"' . get_template_directory_uri() . '/images/blog-2.jpg","link":"#"},{"title":"Building Effective Social Media Strategy","category":"Social Media","date":"Jan 05, 2024","excerpt":"Create a winning social media strategy that increases engagement with your audience.","image":"' . get_template_directory_uri() . '/images/blog-3.jpg","link":"#"}]} /-->';

        // Create or Update Page
        $page_data = array(
            'post_title'    => $page_title,
            'post_name'     => $page_slug,
            'post_content'  => $content,
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_author'   => get_current_user_id(),
        );

        if ($page) {
            $page_data['ID'] = $page->ID;
            wp_update_post($page_data);
            echo "<h1>English Page Updated!</h1>";
        } else {
            $page_id = wp_insert_post($page_data);
            if (!is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', 'template-home.php');
                echo "<h1>English Page Created!</h1>";
            }
        }
        exit;
    }
}
add_action('init', 'weoryx_setup_english_content');


/**
 * Theme Settings Page
 */
function weoryx_add_theme_menu()
{
    add_menu_page(
        'Theme Settings',
        'Theme Settings',
        'manage_options',
        'weoryx-settings',
        'weoryx_theme_settings_page',
        'dashicons-admin-generic',
        60
    );
}
add_action('admin_menu', 'weoryx_add_theme_menu');

function weoryx_register_settings()
{
    register_setting('weoryx-settings-group', 'weoryx_footer_description');
    register_setting('weoryx-settings-group', 'weoryx_footer_description_ar');
    register_setting('weoryx-settings-group', 'weoryx_footer_description_en');
    register_setting('weoryx-settings-group', 'weoryx_footer_address');
    register_setting('weoryx-settings-group', 'weoryx_footer_address_ar');
    register_setting('weoryx-settings-group', 'weoryx_footer_address_en');
    register_setting('weoryx-settings-group', 'weoryx_footer_phone');
    register_setting('weoryx-settings-group', 'weoryx_footer_email');
    register_setting('weoryx-settings-group', 'weoryx_facebook_url');
    register_setting('weoryx-settings-group', 'weoryx_twitter_url');
    register_setting('weoryx-settings-group', 'weoryx_instagram_url');
    register_setting('weoryx-settings-group', 'weoryx_linkedin_url');
    register_setting('weoryx-settings-group', 'weoryx_whatsapp_number');
    register_setting('weoryx-settings-group', 'weoryx_copyright_ar');
    register_setting('weoryx-settings-group', 'weoryx_copyright_en');
    register_setting('weoryx-settings-group', 'weoryx_newsletter_title_ar');
    register_setting('weoryx-settings-group', 'weoryx_newsletter_title_en');
    register_setting('weoryx-settings-group', 'weoryx_newsletter_description_ar');
    register_setting('weoryx-settings-group', 'weoryx_newsletter_description_en');
    register_setting('weoryx-settings-group', 'weoryx_newsletter_cf7_shortcode');
    register_setting('weoryx-settings-group', 'weoryx_contact_form_shortcode');
    register_setting('weoryx-settings-group', 'weoryx_contact_form_ar_shortcode');
    register_setting('weoryx-settings-group', 'weoryx_contact_form_en_shortcode');
}
add_action('admin_init', 'weoryx_register_settings');

/**
 * Ensure specified Contact Form 7 forms exist, creating them if necessary.
 */
function weoryx_ensure_contact_forms()
{
    if (!class_exists('WPCF7_ContactForm')) return array();

    $forms_to_create = array(
        'Newsletter Form' => array(
            'form' => '<div class="newsletter-input-group">' . "\n" . '    [email* your-email placeholder "Your Email Address"]' . "\n" . '    <button type="submit" aria-label="Subscribe"><i class="fas fa-paper-plane"></i></button>' . "\n" . '</div>',
            'mail' => array(
                'subject' => 'Newsletter Subscription from [your-email]',
                'sender' => '[your-email] <' . get_option('admin_email') . '>',
                'body' => "You have a new newsletter subscription from: [your-email]\n\n--\nThis e-mail was sent from a contact form on " . get_bloginfo('name') . " (" . home_url() . ")",
                'recipient' => get_option('admin_email'),
                'additional_headers' => 'Reply-To: [your-email]',
            ),
            'messages' => array(
                'mail_sent_ok' => 'Thank you for your message. It has been sent.',
                'mail_sent_ng' => 'There was an error trying to send your message. Please try again later.',
                'validation_error' => 'One or more fields have an error. Please check and try again.',
            )
        ),
        'Contact Form EN' => array(
            'form' => '<div class="form-row">' . "\n" . '    <div class="form-group">[text* your-name placeholder "Your Name"]</div>' . "\n" . '    <div class="form-group">[email* your-email placeholder "Your Email"]</div>' . "\n" . '</div>' . "\n" . '<div class="form-row">' . "\n" . '    <div class="form-group">[tel your-phone placeholder "Your Phone"]</div>' . "\n" . '    <div class="form-group">[text your-subject placeholder "Subject"]</div>' . "\n" . '</div>' . "\n" . '<div class="form-group">' . "\n" . '    [textarea your-message placeholder "Your Message"]' . "\n" . '</div>' . "\n" . '<div class="submit-group">' . "\n" . '    [submit class:btn class:btn-primary class:btn-lg "Send Message"]' . "\n" . '</div>',
            'mail' => array(
                'subject' => 'New Message from [your-name]',
                'sender' => '[your-name] <' . get_option('admin_email') . '>',
                'body' => "From: [your-name] <[your-email]>\nSubject: [your-subject]\n\nMessage Body:\n[your-message]\n\n--\nThis e-mail was sent from a contact form on " . get_bloginfo('name') . " (" . home_url() . ")",
                'recipient' => get_option('admin_email'),
                'additional_headers' => 'Reply-To: [your-email]',
            ),
            'messages' => array(
                'mail_sent_ok' => 'Thank you for your message. It has been sent.',
                'mail_sent_ng' => 'There was an error trying to send your message. Please try again later.',
                'validation_error' => 'One or more fields have an error. Please check and try again.',
            )
        ),
        'Contact Form AR' => array(
            'form' => '<div class="form-row">' . "\n" . '    <div class="form-group">[text* your-name placeholder "الأسم بالكامل"]</div>' . "\n" . '    <div class="form-group">[email* your-email placeholder "البريد الإلكتروني"]</div>' . "\n" . '</div>' . "\n" . '<div class="form-row">' . "\n" . '    <div class="form-group">[tel your-phone placeholder "رقم الهاتف"]</div>' . "\n" . '    <div class="form-group">[text your-subject placeholder "الموضوع"]</div>' . "\n" . '</div>' . "\n" . '<div class="form-group">' . "\n" . '    [textarea your-message placeholder "رسالتك"]' . "\n" . '</div>' . "\n" . '<div class="submit-group">' . "\n" . '    [submit class:btn class:btn-primary class:btn-lg "إرسال الرسالة"]' . "\n" . '</div>',
            'mail' => array(
                'subject' => 'رسالة جديدة من [your-name]',
                'sender' => '[your-name] <' . get_option('admin_email') . '>',
                'body' => "من: [your-name] <[your-email]>\nالموضوع: [your-subject]\n\nنص الرسالة:\n[your-message]\n\n--\nتم إرسال هذه الرسالة من نموذج الاتصال في " . get_bloginfo('name') . " (" . home_url() . ")",
                'recipient' => get_option('admin_email'),
                'additional_headers' => 'Reply-To: [your-email]',
            ),
            'messages' => array(
                'mail_sent_ok' => 'شكراً لك. تم إرسال رسالتك بنجاح.',
                'mail_sent_ng' => 'عذراً، حدث خطأ أثناء محاولة إرسال رسالتك. يرجى المحاولة مرة أخرى لاحقاً.',
                'validation_error' => 'واحد أو أكثر من الحقول يحتوي على خطأ. يرجى التحقق والمحاولة مرة أخرى.',
            )
        )
    );

    $results = array();

    foreach ($forms_to_create as $title => $data) {
        $existing = get_page_by_title($title, OBJECT, 'wpcf7_contact_form');

        if ($existing) {
            $form_id = $existing->ID;
        } else {
            $form_id = wp_insert_post(array(
                'post_title' => $title,
                'post_type' => 'wpcf7_contact_form',
                'post_status' => 'publish'
            ));
        }

        if ($form_id && !is_wp_error($form_id)) {
            update_post_meta($form_id, '_form', $data['form']);
            update_post_meta($form_id, '_mail', $data['mail']);
            update_post_meta($form_id, '_messages', $data['messages']);
            $results[$title] = $form_id;
        }
    }

    return $results;
}

function weoryx_theme_settings_page()
{
?>
    <style>
        .weoryx-settings-wrap {
            max-width: 1000px;
            margin: 20px auto;
            font-family: 'Outfit', sans-serif;
        }

        .weoryx-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid #eee;
        }

        .weoryx-card-header {
            margin-bottom: 25px;
            border-bottom: 1px solid #f5f5f5;
            padding-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .weoryx-card-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .weoryx-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .weoryx-field-group {
            margin-bottom: 20px;
        }

        .weoryx-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .weoryx-input,
        .weoryx-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .weoryx-input:focus,
        .weoryx-textarea:focus {
            border-color: #c5a059;
            box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.1);
            outline: none;
        }

        .lang-badge {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .lang-ar {
            background: #e3f2fd;
            color: #1976d2;
        }

        .lang-en {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .weoryx-submit-bar {
            position: sticky;
            bottom: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-radius: 12px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            border: 1px solid #eee;
        }

        .btn-demo {
            background: #f5f5f5;
            color: #666;
            border: 1px solid #ddd;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-demo:hover {
            background: #eee;
            color: #333;
        }

        .btn-save {
            background: #c5a059;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background: #b08e4d;
            box-shadow: 0 4px 12px rgba(197, 160, 89, 0.3);
        }

        .notice-success {
            background: #edffef;
            border-left: 4px solid #46b450;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #2e7d32;
        }
    </style>

    <div class="weoryx-settings-wrap">
        <div class="weoryx-card-header" style="border:none; margin-bottom: 10px;">
            <h1 style="font-weight: 800; font-size: 28px; margin: 0;">WeOryx <span style="color: #c5a059;">Theme Settings</span></h1>
            <?php if (isset($_GET['settings-updated'])) : ?>
                <div class="notice-success" style="margin:0;">Settings saved successfully!</div>
            <?php endif; ?>
        </div>

        <form method="post" action="options.php">
            <?php settings_fields('weoryx-settings-group'); ?>

            <!-- Footer Section -->
            <div class="weoryx-card">
                <div class="weoryx-card-header">
                    <h2 class="weoryx-card-title"><span class="dashicons dashicons-layout"></span> Footer Information</h2>
                </div>

                <div class="weoryx-grid">
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Footer Description <span class="lang-badge lang-ar">Arabic</span></label>
                        <textarea name="weoryx_footer_description_ar" class="weoryx-textarea" rows="4"><?php echo esc_textarea(get_option('weoryx_footer_description_ar')); ?></textarea>
                    </div>
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Footer Description <span class="lang-badge lang-en">English</span></label>
                        <textarea name="weoryx_footer_description_en" class="weoryx-textarea" rows="4"><?php echo esc_textarea(get_option('weoryx_footer_description_en')); ?></textarea>
                    </div>
                </div>

                <div class="weoryx-grid">
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Office Address <span class="lang-badge lang-ar">Arabic</span></label>
                        <input type="text" name="weoryx_footer_address_ar" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_footer_address_ar')); ?>" />
                    </div>
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Office Address <span class="lang-badge lang-en">English</span></label>
                        <input type="text" name="weoryx_footer_address_en" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_footer_address_en')); ?>" />
                    </div>
                </div>

                <div class="weoryx-grid">
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Contact Phone</label>
                        <input type="text" name="weoryx_footer_phone" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_footer_phone')); ?>" />
                    </div>
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Contact Email</label>
                        <input type="text" name="weoryx_footer_email" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_footer_email')); ?>" />
                    </div>
                </div>
            </div>

            <!-- Social Media Section -->
            <div class="weoryx-card">
                <div class="weoryx-card-header">
                    <h2 class="weoryx-card-title"><span class="dashicons dashicons-share"></span> Social Media Links</h2>
                </div>
                <div class="weoryx-grid">
                    <div class="weoryx-field-group">
                        <label class="weoryx-label"><span class="dashicons dashicons-facebook"></span> Facebook URL</label>
                        <input type="text" name="weoryx_facebook_url" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_facebook_url')); ?>" />
                    </div>
                    <div class="weoryx-field-group">
                        <label class="weoryx-label"><span class="dashicons dashicons-twitter"></span> Twitter URL</label>
                        <input type="text" name="weoryx_twitter_url" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_twitter_url')); ?>" />
                    </div>
                    <div class="weoryx-field-group">
                        <label class="weoryx-label"><span class="dashicons dashicons-instagram"></span> Instagram URL</label>
                        <input type="text" name="weoryx_instagram_url" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_instagram_url')); ?>" />
                    </div>
                    <div class="weoryx-field-group">
                        <label class="weoryx-label"><span class="dashicons dashicons-linkedin"></span> LinkedIn URL</label>
                        <input type="text" name="weoryx_linkedin_url" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_linkedin_url')); ?>" />
                    </div>
                    <div class="weoryx-field-group">
                        <label class="weoryx-label"><span class="dashicons dashicons-whatsapp"></span> WhatsApp Number (e.g. +96650...)</label>
                        <input type="text" name="weoryx_whatsapp_number" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_whatsapp_number')); ?>" placeholder="+966501234567" />
                    </div>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="weoryx-card">
                <div class="weoryx-card-header">
                    <h2 class="weoryx-card-title"><span class="dashicons dashicons-email-alt"></span> Newsletter Settings</h2>
                </div>
                <div class="weoryx-grid">
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Newsletter Title <span class="lang-badge lang-ar">Arabic</span></label>
                        <input type="text" name="weoryx_newsletter_title_ar" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_newsletter_title_ar')); ?>" />
                    </div>
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Newsletter Title <span class="lang-badge lang-en">English</span></label>
                        <input type="text" name="weoryx_newsletter_title_en" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_newsletter_title_en')); ?>" />
                    </div>
                </div>
                <div class="weoryx-grid">
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Newsletter Description <span class="lang-badge lang-ar">Arabic</span></label>
                        <textarea name="weoryx_newsletter_description_ar" class="weoryx-textarea" rows="3"><?php echo esc_textarea(get_option('weoryx_newsletter_description_ar')); ?></textarea>
                    </div>
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Newsletter Description <span class="lang-badge lang-en">English</span></label>
                        <textarea name="weoryx_newsletter_description_en" class="weoryx-textarea" rows="3"><?php echo esc_textarea(get_option('weoryx_newsletter_description_en')); ?></textarea>
                    </div>
                </div>
                <div class="weoryx-field-group">
                    <label class="weoryx-label">Contact Form 7 Shortcode (Newsletter)</label>
                    <input type="text" name="weoryx_newsletter_cf7_shortcode" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_newsletter_cf7_shortcode')); ?>" placeholder='[contact-form-7 id="123" title="Newsletter"]' />
                </div>
                <div class="weoryx-field-group">
                    <label class="weoryx-label">Contact Form 7 Shortcode (Contact Page)</label>
                    <input type="text" name="weoryx_contact_form_shortcode" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_contact_form_shortcode')); ?>" placeholder='[contact-form-7 id="123" title="Contact form 1"]' />
                </div>
            </div>

            <!-- Copyright Section -->
            <div class="weoryx-card">
                <div class="weoryx-card-header">
                    <h2 class="weoryx-card-title"><span class="dashicons dashicons-lock"></span> Footer Bottom & Copyright</h2>
                </div>
                <div class="weoryx-grid">
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Copyright Text <span class="lang-badge lang-ar">Arabic</span></label>
                        <input type="text" name="weoryx_copyright_ar" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_copyright_ar')); ?>" />
                    </div>
                    <div class="weoryx-field-group">
                        <label class="weoryx-label">Copyright Text <span class="lang-badge lang-en">English</span></label>
                        <input type="text" name="weoryx_copyright_en" class="weoryx-input" value="<?php echo esc_attr(get_option('weoryx_copyright_en')); ?>" />
                    </div>
                </div>
            </div>

            <div class="weoryx-submit-bar">
                
                <button type="submit" class="btn-save">Save All Settings</button>
            </div>
        </form>
    </div>
<?php
}

// Remove Contact Form 7 automatic paragraph tags
add_filter('wpcf7_autop_or_not', '__return_false');

// ============================================
// Service Icon Meta Box
// ============================================

/**
 * Add meta box for service icon
 */
function weoryx_add_service_icon_meta_box()
{
    add_meta_box(
        'weoryx_service_icon',
        __('Service Icon', 'weoryx'),
        'weoryx_service_icon_callback',
        'services',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'weoryx_add_service_icon_meta_box');

/**
 * Display the service icon meta box
 */
function weoryx_service_icon_callback($post)
{
    wp_nonce_field('weoryx_save_service_icon', 'weoryx_service_icon_nonce');
    $icon_class = get_post_meta($post->ID, '_service_icon', true);
?>
    <p>
        <label for="service_icon"><?php _e('Font Awesome Icon Class:', 'weoryx'); ?></label><br>
        <input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr($icon_class); ?>" style="width: 100%;" placeholder="fas fa-mobile-alt">
    </p>
    <p class="description">
        <?php _e('Enter Font Awesome icon class (e.g., "fas fa-mobile-alt", "fas fa-laptop", "fas fa-video")', 'weoryx'); ?><br>
        <a href="https://fontawesome.com/icons" target="_blank"><?php _e('Browse Font Awesome Icons', 'weoryx'); ?></a>
    </p>
<?php
}

/**
 * Save the service icon meta box data
 */
function weoryx_save_service_icon($post_id)
{
    // Check nonce
    if (!isset($_POST['weoryx_service_icon_nonce']) || !wp_verify_nonce($_POST['weoryx_service_icon_nonce'], 'weoryx_save_service_icon')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save the icon class
    if (isset($_POST['service_icon'])) {
        update_post_meta($post_id, '_service_icon', sanitize_text_field($_POST['service_icon']));
    }
}
add_action('save_post_services', 'weoryx_save_service_icon');


/**
 * Custom Meta Box for Portfolio Details
 */
function weoryx_portfolio_meta_box()
{
    add_meta_box(
        'weoryx_portfolio_details',
        __('Project Details', 'weoryx'),
        'weoryx_render_portfolio_meta_box',
        'portfolio',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'weoryx_portfolio_meta_box');

function weoryx_render_portfolio_meta_box($post)
{
    wp_nonce_field('weoryx_portfolio_nonce', 'weoryx_portfolio_nonce_field');

    $client = get_post_meta($post->ID, '_weoryx_client_name', true);
    $year = get_post_meta($post->ID, '_weoryx_project_year', true);
    $url = get_post_meta($post->ID, '_weoryx_project_url', true);
    $video_url = get_post_meta($post->ID, '_weoryx_project_video_url', true);
    $gallery_1 = get_post_meta($post->ID, '_weoryx_gallery_image_1', true);
    $gallery_2 = get_post_meta($post->ID, '_weoryx_gallery_image_2', true);
    $gallery_3 = get_post_meta($post->ID, '_weoryx_gallery_image_3', true);

?>
    <p>
        <label for="weoryx_client_name" style="font-weight:bold;"><?php _e('Client Name', 'weoryx'); ?></label>
        <input type="text" id="weoryx_client_name" name="weoryx_client_name" value="<?php echo esc_attr($client); ?>" style="width:100%; margin-top:5px;">
    </p>
    <p>
        <label for="weoryx_project_year" style="font-weight:bold;"><?php _e('Project Year', 'weoryx'); ?></label>
        <input type="text" id="weoryx_project_year" name="weoryx_project_year" value="<?php echo esc_attr($year); ?>" style="width:100%; margin-top:5px;">
    </p>
    <p>
        <label for="weoryx_project_url" style="font-weight:bold;"><?php _e('Project URL', 'weoryx'); ?></label>
        <input type="url" id="weoryx_project_url" name="weoryx_project_url" value="<?php echo esc_attr($url); ?>" style="width:100%; margin-top:5px;">
    </p>
    <p>
        <label for="weoryx_project_video_url" style="font-weight:bold;"><?php _e('Video URL (YouTube/Vimeo)', 'weoryx'); ?></label>
        <input type="url" id="weoryx_project_video_url" name="weoryx_project_video_url" value="<?php echo esc_attr($video_url); ?>" style="width:100%; margin-top:5px;" placeholder="https://www.youtube.com/watch?v=...">
        <small style="display:block; margin-top:5px; color:#666;"><?php _e('Enter YouTube or Vimeo URL to embed video in the gallery', 'weoryx'); ?></small>
    </p>

    <hr>
    <p><strong><?php _e('Gallery Images', 'weoryx'); ?></strong></p>

    <?php
    $gallery_fields = ['1' => $gallery_1, '2' => $gallery_2, '3' => $gallery_3];
    foreach ($gallery_fields as $i => $img_url) :
    ?>
        <div style="margin-bottom: 15px; border: 1px solid #ddd; padding: 10px; background: #f9f9f9;">
            <label style="font-weight:bold; display:block; margin-bottom:5px;"><?php echo __('Gallery Image ', 'weoryx') . $i; ?></label>
            <input type="hidden" id="weoryx_gallery_image_<?php echo $i; ?>" name="weoryx_gallery_image_<?php echo $i; ?>" value="<?php echo esc_attr($img_url); ?>">
            <div id="weoryx_gallery_image_<?php echo $i; ?>_preview" style="margin-bottom:10px;">
                <?php if ($img_url): ?>
                    <img src="<?php echo esc_url($img_url); ?>" style="max-width:100%; height:auto; border-radius:4px;">
                <?php endif; ?>
            </div>
            <button type="button" class="button weoryx_upload_gallery_button" data-target="weoryx_gallery_image_<?php echo $i; ?>"><?php _e('Select Image', 'weoryx'); ?></button>
            <?php if ($img_url): ?>
                <button type="button" class="button weoryx_remove_gallery_button" style="margin-left:5px; color: #a00;"><?php _e('Remove', 'weoryx'); ?></button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <script>
        jQuery(document).ready(function($) {
            // Upload Handler - Create new instance for each click
            $('.weoryx_upload_gallery_button').on('click', function(e) {
                e.preventDefault();
                var button = $(this);
                var targetFieldId = button.data('target');
                var targetField = $('#' + targetFieldId);
                var targetPreview = $('#' + targetFieldId + '_preview');

                // Create a NEW media uploader instance for each click
                var mediaUploader = wp.media({
                    title: '<?php _e('Select Image', 'weoryx'); ?>',
                    button: {
                        text: '<?php _e('Use this image', 'weoryx'); ?>'
                    },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    targetField.val(attachment.url);
                    targetPreview.html('<img src="' + attachment.url + '" style="max-width:100%; height:auto; border-radius:4px;">');

                    // Add remove button if not exists
                    if (button.next('.weoryx_remove_gallery_button').length === 0) {
                        button.after('<button type="button" class="button weoryx_remove_gallery_button" style="margin-left:5px; color: #a00;"><?php _e('Remove', 'weoryx'); ?></button>');
                    }
                });

                mediaUploader.open();
            });

            // Remove Handler
            $(document).on('click', '.weoryx_remove_gallery_button', function(e) {
                e.preventDefault();
                var container = $(this).parent();
                container.find('input[type="hidden"]').val('');
                container.find('div[id$="_preview"]').html('');
                $(this).remove();
            });
        });
    </script>
<?php
}

function weoryx_save_portfolio_meta($post_id)
{
    if (!isset($_POST['weoryx_portfolio_nonce_field']) || !wp_verify_nonce($_POST['weoryx_portfolio_nonce_field'], 'weoryx_portfolio_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Save basic fields
    if (isset($_POST['weoryx_client_name'])) update_post_meta($post_id, '_weoryx_client_name', sanitize_text_field($_POST['weoryx_client_name']));
    if (isset($_POST['weoryx_project_year'])) update_post_meta($post_id, '_weoryx_project_year', sanitize_text_field($_POST['weoryx_project_year']));
    if (isset($_POST['weoryx_project_url'])) update_post_meta($post_id, '_weoryx_project_url', esc_url_raw($_POST['weoryx_project_url']));
    if (isset($_POST['weoryx_project_video_url'])) update_post_meta($post_id, '_weoryx_project_video_url', esc_url_raw($_POST['weoryx_project_video_url']));

    // Save gallery images
    if (isset($_POST['weoryx_gallery_image_1'])) update_post_meta($post_id, '_weoryx_gallery_image_1', esc_url_raw($_POST['weoryx_gallery_image_1']));
    if (isset($_POST['weoryx_gallery_image_2'])) update_post_meta($post_id, '_weoryx_gallery_image_2', esc_url_raw($_POST['weoryx_gallery_image_2']));
    if (isset($_POST['weoryx_gallery_image_3'])) update_post_meta($post_id, '_weoryx_gallery_image_3', esc_url_raw($_POST['weoryx_gallery_image_3']));
}
add_action('save_post_portfolio', 'weoryx_save_portfolio_meta');
add_filter('big_image_size_threshold', '__return_false');
