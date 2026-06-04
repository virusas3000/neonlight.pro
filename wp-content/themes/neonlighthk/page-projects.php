<?php
/**
 * Template Name: Projects
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page nl-projects">
	<!-- Page content from WordPress backend -->
	<?php while (have_posts()) : the_post(); ?>
		<div class="nl-page__title">
			<?php the_title(); ?>
		</div>
		<div class="nl-page-content nl-projects-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>
</div>

<style>
.nl-projects-content {
	max-width: 1100px;
	margin: 0 auto;
	padding: 20px 24px 60px;
}
.nl-projects-content p {
	font-size: 1.05rem;
	line-height: 1.7;
	margin-bottom: 16px;
}
.nl-projects-content h2,
.nl-projects-content h3 {
	font-weight: 600;
	color: #111;
	margin: 32px 0 16px;
}
.nl-projects-content h2 {
	font-size: 1.5rem;
}
.nl-projects-content h3 {
	font-size: 1.2rem;
}
.nl-projects-content img {
	border-radius: 12px;
	width: 100%;
	height: auto;
	margin: 12px 0;
}
.nl-projects-content .wp-block-gallery,
.nl-projects-content .wp-block-columns {
	gap: 16px;
}
</style>

<?php get_footer(); ?>
