<?php

/**
 * Render for Team Block
 */
$tag = isset($attributes['tag']) ? $attributes['tag'] : weoryx_translate('Our Team');
$title = isset($attributes['title']) ? $attributes['title'] : weoryx_translate('Meet Our Experts');
$members = isset($attributes['members']) && !empty($attributes['members']) ? $attributes['members'] : array(
    array(
        'name' => weoryx_translate('Ahmed Hassan'),
        'role' => weoryx_translate('CEO & Founder'),
        'img' => 'team-1.jpg'
    ),
    array(
        'name' => weoryx_translate('Sarah Johnson'),
        'role' => weoryx_translate('Marketing Director'),
        'img' => 'team-2.jpg'
    ),
    array(
        'name' => weoryx_translate('Mohammed Ali'),
        'role' => weoryx_translate('Lead Developer'),
        'img' => 'team-3.jpg'
    ),
    array(
        'name' => weoryx_translate('Fatima Ahmed'),
        'role' => weoryx_translate('Creative Director'),
        'img' => 'team-4.jpg'
    )
);
?>
<section class="team-section" style="overflow: hidden; padding: 100px 0;">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up" style="margin-bottom: 60px;">
            <span class="section-tag section-tag-center"><?php echo esc_html(weoryx_translate($tag)); ?></span>
            <h2 class="section-title" style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 800;"><?php echo weoryx_format_title($title); ?></h2>
        </div>

        <div class="swiper team-swiper" style="padding: 20px 20px 80px; margin: 0 -20px;">
            <div class="swiper-wrapper">
                <?php foreach ($members as $index => $member) :
                    $member_img = $member['img'];
                    $member_img_url = (strpos($member_img, 'http') === 0 || strpos($member_img, '/') === 0) ? $member_img : get_template_directory_uri() . '/images/' . $member_img;
                ?>
                    <div class="swiper-slide">
                        <div class="team-card editorial-style" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>" style="background: transparent; border-radius: 0; overflow: visible; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); position: relative; height: 100%;">
                            <div class="team-image-container" style="position: relative; overflow: hidden; height: 480px; background: #f8f8f8; border-radius: 20px;">
                                <img src="<?php echo esc_url($member_img_url); ?>"
                                    alt="<?php echo esc_attr($member['name']); ?>"
                                    class="team-photo"
                                    style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%); transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);">

                                <div class="team-role-overlay" style="position: absolute; top: 40px; inset-inline-start: -10px; font-size: 4.5rem; font-weight: 900; color: rgba(255,255,255,0.08); text-transform: uppercase; white-space: nowrap; pointer-events: none; transform: rotate(-90deg); transform-origin: top left; line-height: 1; z-index: 1; transition: all 0.5s ease;">
                                    <?php echo esc_html(weoryx_translate($member['role'])); ?>
                                </div>

                                <div class="team-social" style="position: absolute; top: 20px; inset-inline-end: 20px; display: flex; flex-direction: column; gap: 12px; transform: translateY(-20px); opacity: 0; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); z-index: 15;">
                                    <a href="#" style="width: 42px; height: 42px; background: var(--primary-blue, #1A5F7A); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: 0 10px 20px rgba(0,0,0,0.15); transition: all 0.3s ease;"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#" style="width: 42px; height: 42px; background: #fff; color: var(--primary-blue, #1A5F7A); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: 0 10px 20px rgba(0,0,0,0.15); transition: all 0.3s ease;"><i class="fab fa-twitter"></i></a>
                                </div>
                            </div>

                            <div class="team-info" style="position: absolute; bottom: -10px; inset-inline-start: 20px; inset-inline-end: 20px; background: rgba(255,255,255,0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); padding: 25px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); border: 1px solid rgba(255,255,255,0.5); z-index: 10; transition: all 0.5s ease;">
                                <h4 style="font-size: 1.4rem; color: var(--primary-blue, #1A5F7A); margin-bottom: 8px; font-weight: 800; letter-spacing: -0.5px;"><?php echo esc_html(weoryx_translate($member['name'])); ?></h4>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="display: block; width: 25px; height: 2px; background: var(--accent-orange, #E85A3C);"></span>
                                    <span style="font-size: 0.85rem; color: var(--accent-orange, #E85A3C); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;"><?php echo esc_html(weoryx_translate($member['role'])); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Custom Navigation -->
            <div class="team-nav-container" style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); display: flex; gap: 20px; z-index: 10;">
                <div class="swiper-button-prev team-nav-prev" style="position: relative; top: auto; left: auto; margin: 0; width: 50px; height: 50px; background: #fff; border-radius: 50%; color: var(--primary-blue, #1A5F7A); box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;"></div>
                <div class="swiper-button-next team-nav-next" style="position: relative; top: auto; right: auto; margin: 0; width: 50px; height: 50px; background: #fff; border-radius: 50%; color: var(--primary-blue, #1A5F7A); box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s ease;"></div>
            </div>

            <!-- Inline Custom CSS -->
            <style>
                .team-card:hover {
                    transform: translateY(-10px) !important;
                }

                .team-card:hover .team-photo {
                    filter: grayscale(0%) scale(1.1) !important;
                }

                .team-card:hover .team-social {
                    transform: translateY(0) !important;
                    opacity: 1 !important;
                }

                .team-card:hover .team-info {
                    background: #fff !important;
                    transform: translateY(-5px);
                    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12) !important;
                    border-color: var(--accent-orange, #E85A3C);
                }

                .team-card:hover .team-role-overlay {
                    color: rgba(26, 95, 122, 0.05) !important;
                    inset-inline-start: 0 !important;
                }

                .team-nav-prev:hover,
                .team-nav-next:hover {
                    background: var(--primary-blue, #1A5F7A) !important;
                    color: #fff !important;
                    transform: scale(1.1);
                }

                .team-nav-prev:after,
                .team-nav-next:after {
                    font-size: 18px !important;
                    font-weight: 900;
                }

                [dir="rtl"] .team-role-overlay {
                    transform: rotate(90deg);
                    transform-origin: top right;
                    inset-inline-start: auto;
                    inset-inline-end: -10px;
                    top: 40px;
                }

                [dir="rtl"] .team-card:hover .team-role-overlay {
                    inset-inline-end: 0 !important;
                }
            </style>
        </div>
    </div>
</section>