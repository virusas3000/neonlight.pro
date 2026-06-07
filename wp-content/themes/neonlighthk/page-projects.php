<?php
/**
 * Template Name: Projects
 * @package NeonLightHK
 */
get_header();

$lang = nl_lang();
?>

<div class="nl-page nl-projects">

	<div class="nl-page-header">
		<h1 class="nl-page-title"><?php
			if ($lang === 'en') { echo 'PROJECTS'; }
			elseif ($lang === 'cn') { echo '活动'; }
			else { echo '活動'; }
		?></h1>
	</div>

	<!-- Page title from WordPress backend -->
	<?php while (have_posts()) : the_post(); ?>
		<div class="nl-page-content nl-projects-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>

	<!-- Blog Posts Grid -->
	<section class="nl-projects-section">
		<?php
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => 12,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => '_nl_post_lang',
					'value'   => $lang,
					'compare' => '=',
				),
			),
			'orderby' => 'date',
			'order'   => 'DESC',
		);

		$blog_posts = new WP_Query($args);

		if ($blog_posts->have_posts()) :
	?>
			<div class="nl-blog-grid__inner">
				<?php while ($blog_posts->have_posts()) : $blog_posts->the_post(); ?>
					<article class="nl-blog-card">
						<a href="<?php the_permalink(); ?>?lang=<?php echo nl_lang(); ?>" class="nl-blog-card__link">
							<?php if (has_post_thumbnail()) : ?>
								<div class="nl-blog-card__img">
									<?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
								</div>
							<?php else : ?>
								<div class="nl-blog-card__img nl-blog-card__img--placeholder">
									<span><?php echo nl_t('gallery_title'); ?></span>
								</div>
							<?php endif; ?>
							<div class="nl-blog-card__content">
								<h2 class="nl-blog-card__title"><?php the_title(); ?></h2>
								<p class="nl-blog-card__date"><?php echo get_the_date('M j, Y'); ?></p>
								<p class="nl-blog-card__excerpt"><?php echo wp_trim_words(get_the_content(), 30, '...'); ?></p>
							</div>
						</a>
					</article>
				<?php endwhile; ?>
			</div>
		<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p class="nl-blog-grid__empty"><?php echo nl_t('gallery_title'); ?> — <?php echo nl_t('nav_projects'); ?> <?php echo ($lang === 'en') ? 'coming soon' : '即將推出'; ?></p>
		<?php endif; ?>
	</section>
</div>

<style>
.nl-page.nl-projects {
	max-width: 100%;
	padding: 0;
}
.nl-projects-section {
	max-width: 1200px;
	margin: 0 auto;
	padding: 0 24px 60px;
}
.nl-page-header {
	text-align: center;
	padding: 40px 20px 24px;
}
.nl-page-title {
	font-size: 1.8rem;
	font-weight: 700;
	color: #111;
	margin: 0;
	letter-spacing: .05em;
}
@media (max-width: 768px) {
	.nl-page-header { padding: 24px 16px 16px; }
	.nl-page-title { font-size: 1.4rem; }
}
.nl-blog-grid__inner {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 24px;
}
.nl-blog-card {
	background: #fff;
	border-radius: 12px;
	overflow: hidden;
	box-shadow: 0 2px 8px rgba(0,0,0,0.08);
	transition: transform 0.2s, box-shadow 0.2s;
}
.nl-blog-card:hover {
	transform: translateY(-4px);
	box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.nl-blog-card__link {
	text-decoration: none;
	color: inherit;
	display: block;
}
.nl-blog-card__img {
	width: 100%;
	aspect-ratio: 21/9;
	overflow: hidden;
	background: #111;
}
.nl-blog-card__img img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.3s;
}
.nl-blog-card:hover .nl-blog-card__img img {
	transform: scale(1.05);
}
.nl-blog-card__img--placeholder {
	display: flex;
	align-items: center;
	justify-content: center;
	background: linear-gradient(135deg, #111 0%, #222 100%);
	color: #00a896;
	font-size: 1.2rem;
	font-weight: 600;
}
.nl-blog-card__content {
	padding: 20px;
}
.nl-blog-card__title {
	font-size: 1.25rem;
	font-weight: 600;
	color: #111;
	margin: 0 0 8px;
	line-height: 1.3;
}
.nl-blog-card__date {
	font-size: 0.85rem;
	color: #888;
	margin: 0 0 12px;
}
.nl-blog-card__excerpt {
	font-size: 0.95rem;
	line-height: 1.6;
	color: #555;
	margin: 0;
	display: -webkit-box;
	-webkit-line-clamp: 3;
	-webkit-box-orient: vertical;
	overflow: hidden;
}
.nl-blog-grid__empty {
	text-align: center;
	padding: 60px 0;
	color: #888;
	font-size: 1.1rem;
}
</style>

<?php get_footer(); ?>
