<?php
/**
 * Front Page
 * @package NeonLightHK
 */
get_header();
?>

<!-- Hero Section -->
<section class="nl-hero">
	<div class="nl-hero__bg" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero_full.jpg' ); ?>');"></div>
	<div class="nl-hero__content">
		<p class="nl-hero__subtitle">霓虹燈設計及製作</p>
		<h1 class="nl-hero__title">NEON SIGNS</h1>
		<p class="nl-hero__desc">DESIGN & PRODUCTION</p>
		<a href="<?php echo esc_url( home_url( '/order/' ) ); ?>" class="nl-hero__cta">LEARN MORE</a>
	</div>
</section>

<!-- Service Grid -->
<section class="nl-services">
	<!-- Purchase -->
	<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="nl-service-card">
		<div class="nl-service-card__bg" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_8e5ba06c4c22450088b769ad00eb19a4_mv2_d_4288_2848_s_4_2.jpg' ); ?>');"></div>
		<div class="nl-service-card__content">
			<span class="nl-service-card__cn">購買現貨</span>
			<span class="nl-service-card__en">PURCHASE</span>
			<span class="nl-service-card__btn">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
			</span>
		</div>
	</a>

	<!-- Customise -->
	<a href="<?php echo esc_url( home_url( '/order/' ) ); ?>" class="nl-service-card">
		<div class="nl-service-card__bg" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_865d370f958e45109989ed43618209cb_mv2_d_5760_3840_s_4_2.jpg' ); ?>');"></div>
		<div class="nl-service-card__content">
			<span class="nl-service-card__cn">訂製設計</span>
			<span class="nl-service-card__en">CUSTOMISE</span>
			<span class="nl-service-card__btn">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
			</span>
		</div>
	</a>

	<!-- Workshop -->
	<a href="<?php echo esc_url( home_url( '/workshop/' ) ); ?>" class="nl-service-card">
		<div class="nl-service-card__bg" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_5380f14a383c4d968f48a14dac1767a8_mv2_d_4288_2848_s_4_2.jpg' ); ?>');"></div>
		<div class="nl-service-card__content">
			<span class="nl-service-card__cn">工作坊</span>
			<span class="nl-service-card__en">WORKSHOP</span>
			<span class="nl-service-card__btn">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
			</span>
		</div>
	</a>

	<!-- Rental -->
	<a href="<?php echo esc_url( home_url( '/rental/' ) ); ?>" class="nl-service-card">
		<div class="nl-service-card__bg" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_972ceaa0a4374a72bf1b9a8c1949e554_mv2.jpg' ); ?>');"></div>
		<div class="nl-service-card__content">
			<span class="nl-service-card__cn">租借服務</span>
			<span class="nl-service-card__en">RENTAL</span>
			<span class="nl-service-card__btn">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
			</span>
		</div>
	</a>
</section>

<!-- Lookbook / Instagram Section -->
<section class="nl-lookbook">
	<div class="nl-lookbook__inner">
		<p class="nl-lookbook__cn">作品參考</p>
		<h2 class="nl-lookbook__en">OUR WORKS</h2>
		<p class="nl-lookbook__handle">
			INSTAGRAM @ <a href="https://www.instagram.com/neonlighthk/" target="_blank" rel="noopener">NEONLIGHTHK</a>
		</p>
		<div class="nl-instagram-feed">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_0f73af18a9b04f789c8318e4e062fe54_mv2.jpg' ); ?>" alt="Neon work 1" loading="lazy">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_dd7497cbaac14a1fb1971bd53add3100_mv2.jpg' ); ?>" alt="Neon work 2" loading="lazy">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/1c4225b912e84630908533cb9bddd62c.jpg' ); ?>" alt="Neon work 3" loading="lazy">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_30794dc7125c4d0c8faf8952efb7207b_7Emv2.png' ); ?>" alt="Neon work 4" loading="lazy">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_8e5ba06c4c22450088b769ad00eb19a4_mv2_d_4288_2848_s_4_2.jpg' ); ?>" alt="Neon work 5" loading="lazy">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_865d370f958e45109989ed43618209cb_mv2_d_5760_3840_s_4_2.jpg' ); ?>" alt="Neon work 6" loading="lazy">
		</div>
		<a href="https://www.instagram.com/neonlighthk/" target="_blank" rel="noopener" class="nl-lookbook__more">MORE</a>
	</div>
</section>

<!-- Visit Us Section -->
<section class="nl-visit">
	<div class="nl-visit__left">
		<h2 class="nl-visit__title">VISIT US <span class="nl-visit__at">at</span> <strong>CENTRAL PMQ</strong></h2>
		<div class="nl-visit__divider"></div>
		<address class="nl-visit__address">
			HG19, Ground Floor, Block B, PMQ<br>
			35 Aberdeen St, Central, Hong Kong
		</address>
		<div class="nl-visit__map">
			<iframe
				width="100%"
				height="280"
				style="border:0; filter: grayscale(100%) contrast(1.1) opacity(0.9);"
				loading="lazy"
				allowfullscreen
				referrerpolicy="no-referrer-when-downgrade"
				src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3691.824!2d114.152!3d22.283!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x34040064a2f0d6af%3A0x4c6e6f6e6c696768!2sPMQ%20Hong%20Kong!5e0!3m2!1sen!2shk!4v1700000000000!5m2!1sen!2shk">
			</iframe>
		</div>
	</div>
	<div class="nl-visit__right" style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/images/client_v1-01.jpg' ); ?>');">
		<a href="https://wa.me/85282246494" target="_blank" rel="noopener" class="nl-visit__hello">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
			HELLO!
		</a>
	</div>
</section>

<!-- Clients Section -->
<section class="nl-clients">
	<div class="nl-clients__inner">
		<p class="nl-clients__cn">客戶支持</p>
		<h2 class="nl-clients__en">OUR BELOVED CLIENTS</h2>
		<div class="nl-clients__logos">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/client_v1-01.jpg' ); ?>" alt="Client logos" loading="lazy">
		</div>
	</div>
</section>

<!-- Contact Section -->
<section class="nl-contact">
	<div class="nl-contact__inner">
		<h2 class="nl-contact__title">CONTACT US</h2>
		<form class="nl-contact__form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
			<div class="nl-contact__field">
				<input type="text" name="name" placeholder="Name *" required>
			</div>
			<div class="nl-contact__field">
				<input type="email" name="email" placeholder="Email *" required>
			</div>
			<div class="nl-contact__field">
				<input type="tel" name="phone" placeholder="Phone No.">
			</div>
			<div class="nl-contact__field">
				<input type="text" name="subject" placeholder="Subject">
			</div>
			<div class="nl-contact__field">
				<textarea name="message" rows="4" placeholder="Message"></textarea>
			</div>
			<button type="submit" class="nl-contact__submit">Send</button>
		</form>
	</div>
</section>

<?php get_footer(); ?>
