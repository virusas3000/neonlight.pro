<?php
/**
 * Template Name: Neon Services
 * Cloned from https://www.neonlighthk.com/c-u-s-t-o-m-i-s-e
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page nl-neon-services">

	<!-- Page Title -->
	<h1 class="nl-page__title" style="text-align:center;margin:40px 0 20px;"><?php echo nl_t('nav_neon'); ?></h1>

	<!-- Hero Section -->
	<section class="nl-neon-hero" style="background-image:url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/neon-services-hero.jpg');">
		<div class="nl-neon-hero__overlay"></div>
		<div class="nl-neon-hero__content">
			<h4 class="nl-neon-hero__zh"><?php echo nl_t('neon_services_hero_zh'); ?></h4>
			<h2 class="nl-neon-hero__en"><?php echo nl_t('neon_services_hero_en'); ?></h2>
			<h4 class="nl-neon-hero__sub"><?php echo nl_t('neon_services_hero_sub'); ?></h4>
		</div>
	</section>

	<!-- Custom Design Section -->
	<section class="nl-neon-section">
		<div class="nl-neon-section__text">
			<h4 class="nl-neon-section__zh"><?php echo nl_t('neon_custom_design'); ?></h4>
			<h4 class="nl-neon-section__en"><?php echo nl_t('neon_custom_design_en'); ?></h4>
			<div class="nl-neon-section__desc">
				<p><?php echo nl_t('neon_custom_desc_zh'); ?></p>
				<p><?php echo nl_t('neon_custom_desc_en'); ?></p>
			</div>
			<div class="nl-neon-section__contact">
<p><a href="https://wa.me/85261319328">61319328</a></p>
				<p><a href="mailto:www.neonlight.pro@gmail.com">www.neonlight.pro@gmail.com</a></p>
			</div>
		</div>
		<div class="nl-neon-section__image">
			<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/neon-custom-design.jpg" alt="Custom Neon Design">
		</div>
		<a href="mailto:www.neonlight.pro@gmail.com?subject=Neon%20Quote%20Request" class="nl-neon-quote-btn">
			<?php echo nl_t('neon_quote_btn'); ?>
		</a>
	</section>

	<!-- Design Service Section -->
	<section class="nl-neon-section nl-neon-section--reverse">
		<div class="nl-neon-section__text">
			<h4 class="nl-neon-section__zh"><?php echo nl_t('neon_design_service'); ?></h4>
			<h4 class="nl-neon-section__en"><?php echo nl_t('neon_design_service_en'); ?></h4>
			<div class="nl-neon-section__desc">
				<p><?php echo nl_t('neon_design_desc_zh'); ?></p>
				<p><?php echo nl_t('neon_design_desc_en'); ?></p>
			</div>
			<div class="nl-neon-section__contact">
<p><a href="https://wa.me/85261319328">61319328</a></p>
				<p><a href="mailto:www.neonlight.pro@gmail.com">www.neonlight.pro@gmail.com</a></p>
			</div>
		</div>
		<div class="nl-neon-section__image">
			<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/neon-design-service.jpg" alt="Neon Design Service" style="transform:scaleX(-1);">
		</div>
		<a href="mailto:www.neonlight.pro@gmail.com?subject=Neon%20Design%20Service%20Quote" class="nl-neon-quote-btn">
			<?php echo nl_t('neon_quote_btn'); ?>
		</a>
	</section>

	<!-- Neon Signs Gallery -->
	<section class="nl-neon-gallery">
		<h4 class="nl-neon-gallery__zh">霓虹燈作品展示</h4>
		<h4 class="nl-neon-gallery__en">Neon Signs Gallery</h4>
		<div class="nl-neon-gallery__grid">
			<div class="nl-neon-gallery__item">
				<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/neon-services-gallery.jpg" alt="Neon Signs Collection" loading="lazy">
			</div>
			<div class="nl-neon-gallery__item">
				<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/neon-services-workshop.jpg" alt="Neon Workshop" loading="lazy">
			</div>
		</div>
	</section>

</div>

<style>
.nl-neon-services {
	color: #fff;
	background: #000;
}
.nl-neon-hero {
	position: relative;
	width: 100%;
	height: 60vh;
	min-height: 400px;
	background-size: cover;
	background-position: center;
	display: flex;
	align-items: center;
	justify-content: center;
	text-align: center;
}
.nl-neon-hero__overlay {
	position: absolute;
	inset: 0;
	background: rgba(0,0,0,0.4);
}
.nl-neon-hero__content {
	position: relative;
	z-index: 1;
}
.nl-neon-hero__zh {
	font-size: 1.2rem;
	letter-spacing: 4px;
	margin-bottom: 8px;
	font-weight: 400;
}
.nl-neon-hero__en {
	font-size: 3.5rem;
	font-weight: 700;
	letter-spacing: 6px;
	margin: 0 0 12px;
	text-transform: uppercase;
}
.nl-neon-hero__sub {
	font-size: 0.9rem;
	letter-spacing: 3px;
	font-weight: 400;
	opacity: 0.9;
}
.nl-neon-section {
	position: relative;
	padding: 60px 20px;
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 40px;
	max-width: 1100px;
	margin: 0 auto;
	align-items: center;
}
.nl-neon-section__text {
	padding: 20px;
}
.nl-neon-section__zh {
	font-size: 1.3rem;
	letter-spacing: 4px;
	margin-bottom: 4px;
	font-weight: 500;
}
.nl-neon-section__en {
	font-size: 1.1rem;
	letter-spacing: 3px;
	margin-bottom: 20px;
	opacity: 0.8;
	font-weight: 400;
}
.nl-neon-section__desc p {
	font-size: 0.9rem;
	line-height: 1.7;
	margin-bottom: 12px;
	white-space: pre-line;
}
.nl-neon-section__contact {
	margin-top: 20px;
}
.nl-neon-section__contact a {
	color: #fff;
	text-decoration: none;
	font-size: 0.9rem;
}
.nl-neon-section__contact a:hover {
	text-decoration: underline;
}
.nl-neon-section__image img {
	width: 100%;
	height: auto;
	display: block;
}
.nl-neon-quote-btn {
	position: absolute;
	bottom: 20px;
	left: 50%;
	transform: translateX(-50%);
	background: transparent;
	color: #fff;
	border: 1px solid #fff;
	padding: 12px 40px;
	font-size: 0.85rem;
	letter-spacing: 4px;
	text-decoration: none;
	transition: 0.3s;
}
.nl-neon-quote-btn:hover {
	background: #fff;
	color: #000;
}
.nl-neon-section--reverse {
	direction: rtl;
}
.nl-neon-section--reverse > * {
	direction: ltr;
}

/* Neon Signs Gallery */
.nl-neon-gallery {
	padding: 60px 20px;
	max-width: 1100px;
	margin: 0 auto;
	text-align: center;
}
.nl-neon-gallery__zh {
	font-size: 1.3rem;
	letter-spacing: 4px;
	margin-bottom: 4px;
	font-weight: 500;
}
.nl-neon-gallery__en {
	font-size: 1.1rem;
	letter-spacing: 3px;
	margin-bottom: 30px;
	opacity: 0.8;
	font-weight: 400;
}
.nl-neon-gallery__grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 24px;
}
.nl-neon-gallery__item {
	border-radius: 8px;
	overflow: hidden;
}
.nl-neon-gallery__item img {
	width: 100%;
	height: auto;
	display: block;
	border-radius: 8px;
}
@media (max-width: 768px) {
	.nl-neon-gallery__grid {
		grid-template-columns: 1fr;
		gap: 16px;
	}
}

@media (max-width: 768px) {
	.nl-neon-hero__en { font-size: 2.2rem; }
	.nl-neon-section {
		grid-template-columns: 1fr;
		gap: 20px;
		padding: 40px 20px;
	}
	.nl-neon-quote-btn {
		position: static;
		transform: none;
		display: inline-block;
		margin-top: 20px;
	}
}
</style>

<?php get_footer(); ?>
