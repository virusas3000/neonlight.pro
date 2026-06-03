<?php
/**
 * Template Name: Rental
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page">
	<h1 class="nl-page__title">租借 · RENTAL</h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<div style="text-align:center; padding:40px 0;">
			<p><?php _e( '霓虹燈租借服務即將推出。請聯絡我們查詢詳情。', 'neonlighthk' ); ?></p>
			<p>Neon sign rental service coming soon. Please contact us for enquiries.</p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
