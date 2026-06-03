<?php
/**
 * Template Name: Shop
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page">
	<h1 class="nl-page__title">現貨 · SHOP</h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php _e( 'Coming soon — our neon sign shop is under construction.', 'neonlighthk' ); ?></p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
