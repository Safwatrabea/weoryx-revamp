<?php

/**
 * Render for Clients Block
 */
$tag = isset($attributes['tag']) ? $attributes['tag'] : weoryx_translate('Our Partners');
$title = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('Trusted By | Leading Brands');
$clients = isset($attributes['clients']) && !empty($attributes['clients']) ? $attributes['clients'] : array(
    'client-1.svg',
    'client-2.svg',
    'client-3.svg',
    'client-4.svg',
    'client-5.svg',
    'client-6.svg'
);
?>
<section class="clients">
    <div class="container">
        <div class="clients-header" data-aos="fade-up">
            <span class="section-tag section-tag-center"><?php echo esc_html($tag); ?></span>
            <h2 class="section-title section-title-center">
                <?php echo weoryx_format_title(isset($attributes['title']) ? $attributes['title'] : 'Trusted By | Leading Brands'); ?>
            </h2>
        </div>
        <div class="weoryx-marquee">
            <div class="marquee-content">
                <?php
                // Output twice for seamless CSS loop
                for ($i = 0; $i < 2; $i++) :
                ?>
                    <div class="marquee-track">
                        <?php
                        foreach ($clients as $client) :
                            $client_logo = is_array($client) ? (isset($client['img']) ? $client['img'] : '') : $client;
                            if (empty($client_logo)) continue;
                            $logo_url = (strpos($client_logo, 'http') === 0) ? $client_logo : get_template_directory_uri() . '/images/' . $client_logo;
                        ?>
                            <div class="marquee-item">
                                <img src="<?php echo esc_url($logo_url); ?>" alt="Partner Logo">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>