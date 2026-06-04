<?php
/**
 * Template Name: Lookbook & About Us
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page nl-about">
	<h1 class="nl-page__title">
		<?php echo nl_t('lookbook_about_title'); ?>
	</h1>

	<?php while (have_posts()) : the_post(); ?>
		<div class="nl-page-content nl-about-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>

	<!-- Lookbook / Gallery Section -->
	<section class="nl-about-section">
		<h2><?php echo nl_t('lookbook_heading'); ?></h2>
		<p class="nl-about-ig">
			<a href="https://instagram.com/neonlighthk" target="_blank">INSTAGRAM @ NEONLIGHTHK</a>
		</p>
		<div class="nl-about-gallery">
			<p style="text-align:center; opacity:0.6; padding:40px 0;">Gallery coming soon</p>
		</div>
	</section>

	<!-- Contact Section -->
	<section class="nl-about-section">
		<h2><?php echo nl_t('contact_title'); ?></h2>
		<div class="nl-about-contact">
			<p><strong>WhatsApp:</strong> <a href="https://wa.me/85261319328">(852) 6131-9328</a></p>
			<p><strong>Email:</strong> <a href="mailto:www.neonlight.pro@gmail.com">www.neonlight.pro@gmail.com</a></p>
			<p><strong>Address:</strong> <?php echo nl_t('visit_addr1'); ?></p>
		</div>
	</section>
</div>

<style>
.nl-about-content .nl-intro-en,
.nl-about-content .nl-intro-zh,
.nl-about-content .nl-intro-cn,
.nl-about-content .nl-desc-en,
.nl-about-content .nl-desc-zh,
.nl-about-content .nl-desc-cn,
.nl-about-content .nl-upcycle-en,
.nl-about-content .nl-upcycle-zh,
.nl-about-content .nl-upcycle-cn {
	font-size: 1.05rem;
	line-height: 1.7;
	margin-bottom: 16px;
}
.nl-about-content .nl-heading-en,
.nl-about-content .nl-heading-zh,
.nl-about-content .nl-heading-cn {
	font-size: 1.4rem;
	font-weight: 600;
	margin: 32px 0 16px;
	color: #111;
}
.nl-about-content .nl-programs-en,
.nl-about-content .nl-programs-zh,
.nl-about-content .nl-programs-cn {
	margin: 0 0 24px 20px;
	padding: 0;
	list-style: disc;
}
.nl-about-content .nl-programs-en li,
.nl-about-content .nl-programs-zh li,
.nl-about-content .nl-programs-cn li {
	margin-bottom: 6px;
	font-size: 1rem;
}
.nl-about-content .nl-cta-en,
.nl-about-content .nl-cta-zh,
.nl-about-content .nl-cta-cn {
	margin: 24px 0 32px;
}
.nl-about-content .wp-block-button__link {
	display: inline-block;
	padding: 12px 28px;
	background: #00a896;
	color: #fff;
	border-radius: 999px;
	font-weight: 500;
	text-decoration: none;
	font-size: 1rem;
}
.nl-about-content .wp-block-button__link:hover {
	background: #008c7a;
}
</style>

<?php get_footer(); ?>
