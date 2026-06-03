<?php
/**
 * Template Name: Contact
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page">
	<h1 class="nl-page__title">聯絡 · CONTACT</h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<div style="max-width:600px; margin:0 auto; text-align:center;">
			<p style="margin-bottom:8px;"><strong>NEON LIGHT HK</strong></p>
			<p style="margin-bottom:24px; opacity:0.7;">霓虹燈設計及製作</p>
			<p style="margin-bottom:8px;">📞 (852) 8224-6494</p>
			<p style="margin-bottom:8px;">✉️ <a href="mailto:hkneonlight@gmail.com">hkneonlight@gmail.com</a></p>
			<p style="margin-bottom:8px;">📍 HG19, Ground Floor, Block B, PMQ,<br>35 Aberdeen St, Central, Hong Kong</p>
			<p style="margin-top:24px;">
				<a href="https://www.instagram.com/neonlighthk/" target="_blank" rel="noopener">Instagram: @neonlighthk</a>
			</p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
