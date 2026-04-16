<?php

/**
 * Render for Special Offer Block
 */
$tag = isset($attributes['tag']) && !empty($attributes['tag']) ? weoryx_translate($attributes['tag']) : weoryx_translate('Limited Time Offer');
$title = isset($attributes['title']) && !empty($attributes['title']) ? weoryx_translate($attributes['title']) : weoryx_translate('Own Your Website Starting From 1500 SAR');
$price = isset($attributes['price']) && !empty($attributes['price']) ? weoryx_translate($attributes['price']) : weoryx_translate('1500 SAR');
$pricePrefix = isset($attributes['pricePrefix']) && !empty($attributes['pricePrefix']) ? weoryx_translate($attributes['pricePrefix']) : weoryx_translate('Starting From');
$cardDescription = isset($attributes['cardDescription']) && !empty($attributes['cardDescription']) ? weoryx_translate($attributes['cardDescription']) : weoryx_translate('Get your professional website launched in record time.');
$cardNote = isset($attributes['cardNote']) && !empty($attributes['cardNote']) ? weoryx_translate($attributes['cardNote']) : weoryx_translate('Limited spots available for this month.');
$buttonText = isset($attributes['buttonText']) && !empty($attributes['buttonText']) ? weoryx_translate($attributes['buttonText']) : weoryx_translate('Claim Offer Now');
$buttonUrl = isset($attributes['buttonUrl']) && !empty($attributes['buttonUrl']) ? $attributes['buttonUrl'] : '/contact';
$features = isset($attributes['features']) && !empty($attributes['features']) ? $attributes['features'] : array(
    array('text' => 'Responsive Design'),
    array('text' => 'SEO Friendly'),
    array('text' => 'Fast Loading'),
    array('text' => 'Secure Hosting')
);
foreach ($features as &$feature) {
    if (is_array($feature)) {
        $feature['text'] = weoryx_translate($feature['text']);
    } else {
        $feature = weoryx_translate($feature);
    }
}
unset($feature);
?>
<section class="offer-section" id="offer">
    <!-- Background Decorations -->
    <div class="offer-decorations">
        <div class="offer-orb orb-1"></div>
        <div class="offer-orb orb-2"></div>
        <div class="offer-grid-overlay"></div>
    </div>

    <div class="container">
        <div class="offer-grid">
            <!-- Left Column: Content -->
            <div class="offer-content-col" data-aos="fade-right">
                <span class="offer-tag"><?php echo esc_html($tag); ?></span>
                <h2 class="offer-title">
                    <?php echo wp_kses_post($title); ?>
                </h2>
                <div class="offer-features">
                    <?php foreach ($features as $feature) :
                        $feature_text = is_array($feature) ? (isset($feature['text']) ? $feature['text'] : '') : $feature;
                    ?>
                        <div class="offer-feature-item">
                            <span class="feature-icon"><i class="fas fa-check"></i></span>
                            <span class="feature-text"><?php echo esc_html($feature_text); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Column: Pricing Card -->
            <div class="offer-card-col" data-aos="fade-left" data-aos-delay="200">
                <div class="offer-card">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="card-header">
                            <span class="card-subtitle"><?php echo esc_html($pricePrefix); ?></span>
                            <div class="card-price"><?php echo esc_html($price); ?></div>
                        </div>
                        <div class="card-body">
                            <p class="card-desc"><?php echo esc_html($cardDescription); ?></p>
                            <a href="<?php echo esc_url($buttonUrl); ?>" class="btn btn-primary btn-block">
                                <?php echo esc_html($buttonText); ?>
                            </a>
                            <p class="card-note"><?php echo esc_html($cardNote); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>