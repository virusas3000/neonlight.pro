<?php
/**
 * Template Name: Projects
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page">
	<h1 class="nl-page__title">活動 · PROJECTS</h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<div style="text-align:center; padding:40px 0;">
			<p><?php _e( '活動及項目即將更新。', 'neonlighthk' ); ?></p>
			<p>Events and projects coming soon.</p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
