<?php
/**
 * Template Name: Contact
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page">
	<h1 class="nl-page__title"><?php echo nl_t('contact_title'); ?></h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<div style="max-width:600px; margin:0 auto; text-align:center;">
			<p style="margin-bottom:8px;"><strong>NEON LIGHT HK</strong></p>
			<p style="margin-bottom:24px; opacity:0.7;"><?php echo nl_t('hero_label'); ?></p>
			<p style="margin-bottom:8px;">📞 61319328</p>
			<p style="margin-bottom:8px;">✉️ <a href="mailto:cantopopforyou@gmail.com">cantopopforyou@gmail.com</a></p>
			<p style="margin-bottom:8px;">📍 <?php echo nl_t('visit_addr1'); ?></p>
			<p style="margin-top:24px;">
				<a href="https://www.instagram.com/neonlighthk/" target="_blank" rel="noopener">Instagram: @neonlighthk</a>
			</p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
