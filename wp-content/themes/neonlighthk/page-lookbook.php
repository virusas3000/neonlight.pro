<?php
/**
 * Template Name: Lookbook
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page">
	<h1 class="nl-page__title">範例 · LOOKBOOK</h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<div class="nl-lookbook">
			<div class="nl-lookbook__inner">
				<p class="nl-lookbook__cn">作品參考</p>
				<h2 class="nl-lookbook__en">OUR WORKS</h2>
				<p class="nl-lookbook__handle">
					INSTAGRAM @ <a href="https://www.instagram.com/neonlighthk/" target="_blank" rel="noopener">NEONLIGHTHK</a>
				</p>
				<div class="nl-instagram-feed">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_0f73af18a9b04f789c8318e4e062fe54_mv2.jpg' ); ?>" alt="Neon work 1">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/27ffb1_dd7497cbaac14a1fb1971bd53add3100_mv2.jpg' ); ?>" alt="Neon work 2">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/1c4225b912e84630908533cb9bddd62c.jpg' ); ?>" alt="Neon work 3">
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
