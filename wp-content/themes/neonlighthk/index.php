<?php
/**
 * Index / Blog Archive
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page">
	<h1 class="nl-page__title"><?php single_post_title(); ?></h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article style="margin-bottom:40px; padding-bottom:40px; border-bottom:1px solid rgba(0,0,0,0.08);">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<p style="font-size:0.8rem; opacity:0.6; margin:8px 0 16px;"><?php echo get_the_date(); ?></p>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; ?>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php _e( 'No posts yet.', 'neonlighthk' ); ?></p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
