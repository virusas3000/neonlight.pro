<?php
/**
 * Template Name: About Us
 * @package NeonLightHK
 */
get_header();
?>

<main id="primary" class="site-main nl-about-page">

    <div class="nl-page-header">
        <span class="nl-page-label"><?php echo nl_lang()==='zh' ? '關於' : (nl_lang()==='cn' ? '关于' : 'ABOUT'); ?></span>
        <h1 class="nl-page-title"><?php echo nl_lang()==='en' ? 'LED NEON SIGNS' : 'LED霓虹燈'; ?></h1>
    </div>

    <section class="nl-section">
        <div class="nl-section__inner">
            <div class="nl-about-intro">
                <p class="nl-about-intro__cn">
                    我們採用LED光條製作霓虹燈，比傳統玻璃管製的霓虹燈
                    <br>更安全、更輕巧、更耐用、更容易安裝、更省電、更環保
                </p>
                <p class="nl-about-intro__en">
                    We are using LED Neon Flex instead of Traditional Glass Neon to create Neon Signs,
                    <br>safer, lighter in weight, longer lifespan, easier installation,
                    <br>greater benefits in energy savings and environmental protection.
                </p>
            </div>

            <div class="nl-features-grid">
                <div class="nl-feature-card">
                    <div class="nl-feature-card__images">
                        <img src="http://113.253.77.204:8080/wp-content/themes/neonlighthk/assets/images/about_1.jpg" alt="Safer" loading="lazy">
                        <img src="http://113.253.77.204:8080/wp-content/themes/neonlighthk/assets/images/about_2.png" alt="Lighter" loading="lazy">
                    </div>
                    <h3 class="nl-feature-card__title">
                        <span class="zh">更安全</span> · <span class="en">SAFER</span>
                    </h3>
                    <p class="nl-feature-card__desc">
                        LED光條外圍物料是塑膠，不像傳統霓虹燈以玻璃製成，不會容易破碎，也不會過熱。
                    </p>
                    <p class="nl-feature-card__desc-en">
                        LED neon flex is made by silicone, flexible and elastic, unlike traditional glass-tube-made neon signages, it will not be break easily, safe low voltage operation, low heat and cool to touch.
                    </p>
                </div>

                <div class="nl-feature-card">
                    <div class="nl-feature-card__images">
                        <img src="/wp-content/themes/neonlighthk/assets/images/about_3.jpg" alt="Lighter" loading="lazy">
                        <img src="/wp-content/themes/neonlighthk/assets/images/about_4.jpg" alt="Longer Lifespan" loading="lazy">
                    </div>
                    <h3 class="nl-feature-card__title">
                        <span class="zh">更輕巧</span> · <span class="en">LIGHTER</span>
                    </h3>
                    <p class="nl-feature-card__desc">
                        塑膠製的LED光條 vs 傳統玻璃條製霓虹燈。同一設計及尺寸的霓虹燈，重量輕至少一半。
                    </p>
                    <p class="nl-feature-card__desc-en">
                        Silicone LED neon flex can do same design and dimension as traditional neon signs, yet at least half the weight which makes it more handy and decoration friendly.
                    </p>
                </div>

                <div class="nl-feature-card">
                    <div class="nl-feature-card__images">
                        <img src="/wp-content/themes/neonlighthk/assets/images/about_5.jpg" alt="Longer Lifespan" loading="lazy">
                        <img src="/wp-content/themes/neonlighthk/assets/images/about_6.jpg" alt="Easy Install" loading="lazy">
                    </div>
                    <h3 class="nl-feature-card__title">
                        <span class="zh">更耐用</span> · <span class="en">LONGER LIFESPAN</span>
                    </h3>
                    <p class="nl-feature-card__desc">
                        新式LED比傳統霓虹燈3倍長壽。LED壽命約3萬小時，而傳統霓虹燈壽命只有1萬小時。
                    </p>
                    <p class="nl-feature-card__desc-en">
                        LED neon lights has 30,000 hours lifespan, where traditional neon gas filled signages has 10,000 hours lifespan only, 3 times longer.
                    </p>
                </div>

                <div class="nl-feature-card">
                    <h3 class="nl-feature-card__title">
                        <span class="zh">更易安裝</span> · <span class="en">EASY TO INSTALL</span>
                    </h3>
                    <p class="nl-feature-card__desc">
                        我們的LED霓虹燈有亞加力膠板承托，用普通螺絲安裝上牆便可。
                    </p>
                    <p class="nl-feature-card__desc-en">
                        Our LED neon lights are mounted on acrylic sheets, and can be easily installed with standard screws.
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.nl-about-page .nl-section{max-width:1100px;margin:0 auto;padding:40px 20px}
.nl-about-intro{text-align:center;margin-bottom:48px}
.nl-about-intro__cn{font-size:1.15rem;line-height:1.8;color:#222;margin-bottom:16px}
.nl-about-intro__en{font-size:0.95rem;line-height:1.7;color:#555}
.nl-features-grid{display:grid;grid-template-columns:1fr;gap:48px}
.nl-feature-card{background:#fff;border-radius:16px;padding:32px;box-shadow:0 2px 12px rgba(0,0,0,0.06)}
.nl-feature-card__images{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px}
.nl-feature-card__images img{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:12px}
.nl-feature-card__title{font-size:1.25rem;font-weight:600;margin:0 0 16px;color:#111}
.nl-feature-card__title .zh{color:#00a896}
.nl-feature-card__title .en{color:#777;font-size:0.85em}
.nl-feature-card__desc{font-size:1rem;line-height:1.7;color:#333;margin-bottom:8px}
.nl-feature-card__desc-en{font-size:0.88rem;line-height:1.6;color:#777}
@media (max-width:768px){
    .nl-feature-card{padding:20px}
    .nl-feature-card__images{grid-template-columns:1fr}
}
</style>

<?php get_footer(); ?>
