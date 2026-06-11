<?php
/**
 * Template Name: Customise / Order
 * @package NeonLightHK
 */

get_header();
$lang = nl_lang();
?>

<div class="nl-page nl-page-customise">

    <!-- Hero Section -->
    <section class="nl-hero" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/images/hero-customise.jpg');">
        <div class="nl-hero-overlay"></div>
        <div class="nl-hero-content">
            <p class="nl-hero-label"><?php echo nl_t('neon_services_hero_zh'); ?></p>
            <h1 class="nl-hero-title"><?php echo nl_t('neon_services_hero_en'); ?></h1>
            <p class="nl-hero-subtitle"><?php echo nl_t('neon_services_hero_sub'); ?></p>
        </div>
    </section>

    <!-- Two Service Cards -->
    <section class="nl-services">
        <div class="nl-service-card">
            <div class="nl-service-image">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/send-design.jpg" alt="<?php echo esc_attr(nl_t('neon_custom_design')); ?>" loading="lazy" />
            </div>
            <div class="nl-service-body">
                <div class="nl-service-titles">
                    <h3 class="nl-service-title-zh"><?php echo nl_t('neon_custom_design'); ?></h3>
                    <h3 class="nl-service-title-en"><?php echo nl_t('neon_custom_design_en'); ?></h3>
                </div>

                <div class="nl-service-desc">
                    <p class="nl-desc-zh"><?php echo nl_t('neon_custom_desc_zh'); ?></p>
                    <p class="nl-desc-en"><?php echo nl_t('neon_custom_desc_en'); ?></p>
                </div>

                <div class="nl-service-contact">
                    <p><a href="https://wa.me/85261319328">61319328</a></p>
                    <p><a href="mailto:www.neonlight.pro@gmail.com">www.neonlight.pro@gmail.com</a></p>
                </div>

                <a href="mailto:www.neonlight.pro@gmail.com?subject=Quote%20Request%20-%20Send%20Design">
                    <button class="nl-btn-primary nl-btn-wide"><?php echo nl_t('neon_quote_btn'); ?></button>
                </a>
            </div>
        </div>

        <div class="nl-service-card">
            <div class="nl-service-image">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/design-service.jpg" alt="<?php echo esc_attr(nl_t('neon_design_service')); ?>" loading="lazy" />
            </div>

            <div class="nl-service-body">
                <div class="nl-service-titles">
                    <h3 class="nl-service-title-zh"><?php echo nl_t('neon_design_service'); ?></h3>
                    <h3 class="nl-service-title-en"><?php echo nl_t('neon_design_service_en'); ?></h3>
                </div>

                <div class="nl-service-desc">
                    <p class="nl-desc-zh"><?php echo nl_t('neon_design_desc_zh'); ?></p>
                    <p class="nl-desc-en"><?php echo nl_t('neon_design_desc_en'); ?></p>
                </div>

                <div class="nl-service-contact">
                    <p><a href="https://wa.me/85261319328">61319328</a></p>
                    <p><a href="mailto:www.neonlight.pro@gmail.com">www.neonlight.pro@gmail.com</a></p>
                </div>

                <a href="mailto:www.neonlight.pro@gmail.com?subject=Quote%20Request%20-%20Design%20Service">
                    <button class="nl-btn-primary nl-btn-wide"><?php echo nl_t('neon_quote_btn'); ?></button>
                </a>
            </div>
        </div>
    </section>

</div>

<style>
.nl-page-customise {
    --nl-cyan: #00d4b0;
    --nl-pink: #ff2d95;
    --nl-dark: #0a0a0f;
}

/* Hero */
.nl-hero {
    position: relative;
    width: 100%;
    height: 50vh;
    min-height: 400px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
}
.nl-hero-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.45);
}
.nl-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: #fff;
    max-width: 900px;
    padding: 20px;
}
.nl-hero-label {
    font-size: 1.1rem;
    letter-spacing: 4px;
    margin-bottom: 12px;
    opacity: .9;
}
.nl-hero-title {
    font-size: clamp(3rem, 8vw, 6rem);
    font-weight: 700;
    letter-spacing: 8px;
    margin: 0 0 16px;
    line-height: 1;
}
.nl-hero-subtitle {
    font-size: 1.2rem;
    letter-spacing: 6px;
    opacity: .85;
}

/* Services */
.nl-services {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    max-width: 1200px;
    margin: 60px auto;
    padding: 0 20px;
}
.nl-service-card {
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
    margin: 0 12px;
}
.nl-service-image {
    width: 100%;
    aspect-ratio: 3/2;
    overflow: hidden;
}
.nl-service-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.nl-service-body {
    padding: 28px 24px 32px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.nl-service-titles {
    margin-bottom: 16px;
}
.nl-service-title-zh {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0 0 4px;
    color: var(--nl-dark);
}
.nl-service-title-en {
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
    color: #666;
    letter-spacing: 1px;
}
.nl-service-desc {
    flex: 1;
    margin-bottom: 20px;
}
.nl-service-desc p {
    margin: 0 0 8px;
    line-height: 1.6;
    color: #444;
}
.nl-desc-zh {
    font-size: .95rem;
}
.nl-desc-zh-note {
    font-size: .9rem;
    color: #888;
}
.nl-desc-en {
    font-size: .9rem;
    color: #666;
    margin-top: 12px;
}
.nl-service-contact {
    margin-bottom: 20px;
}
.nl-service-contact p {
    margin: 0 0 4px;
    font-size: .9rem;
    color: #555;
}
.nl-service-contact a {
    color: var(--nl-cyan);
    text-decoration: none;
}
.nl-service-contact a:hover {
    text-decoration: underline;
}
.nl-btn-wide {
    width: 100%;
    padding: 14px;
    font-size: 1rem;
    letter-spacing: 4px;
    border-radius: 30px;
}

@media (max-width: 768px) {
    .nl-services {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    .nl-service-card {
        margin: 0;
    }
    .nl-hero {
        height: 40vh;
        min-height: 300px;
    }
    .nl-hero-title {
        font-size: 2.5rem;
        letter-spacing: 4px;
    }
}
</style>

<?php get_footer(); ?>
