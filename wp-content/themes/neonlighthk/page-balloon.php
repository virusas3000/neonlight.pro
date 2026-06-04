<?php
/**
 * Template Name: Balloon & Magic
 *
 * @package NeonLightHK
 */

get_header();
$lang = nl_lang();
?>

<div class="nl-page">
	<h1 class="nl-page__title"><?php echo nl_t('nav_balloon'); ?></h1>
	<p style="text-align:center;color:#666;margin-top:-12px;margin-bottom:24px;"><?php _e('Balloon decorations, tasting sessions & magic shows', 'neonlighthk'); ?></p>

	<?php
	$args = [
		'post_type'      => 'nl_balloon',
		'posts_per_page' => 50,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	];
	$items = new WP_Query($args);

	if ($items->have_posts()) : ?>
		<div class="nl-product-grid">
			<?php while ($items->have_posts()) : $items->the_post(); ?>
				<?php
				$price       = get_post_meta(get_the_ID(), 'price', true);
				$category    = get_post_meta(get_the_ID(), 'category', true);
				$duration    = get_post_meta(get_the_ID(), 'duration', true);
				$booking_req = get_post_meta(get_the_ID(), 'requires_booking', true);
				?>
				<div class="nl-product-card">
					<a href="<?php echo esc_url(get_permalink() . '?lang=' . $lang); ?>" class="nl-product-card__link">
						<?php if (has_post_thumbnail()) : ?>
							<div class="nl-product-card__image">
								<?php the_post_thumbnail('medium'); ?>
							</div>
						<?php else : ?>
							<div class="nl-product-card__image nl-product-card__image--placeholder">
								<span><?php echo nl_t('shop_no_image'); ?></span>
							</div>
						<?php endif; ?>
						<h3 class="nl-product-card__title"><?php the_title(); ?></h3>
						<div class="nl-product-card__price">
							<span class="nl-product-card__price-current"><?php echo $price ? 'HK$' . number_format($price) : ''; ?></span>
						</div>
						<?php if ($category || $duration) : ?>
							<div style="padding:0 12px 8px;font-size:13px;color:#888;">
								<?php echo esc_html(ucfirst(str_replace('_', ' ', $category)) . ($category && $duration ? ' · ' : '') . ($duration ? $duration : '')); ?>
							</div>
						<?php endif; ?>
					</a>
					<div class="nl-product-card__cart">
						<a href="<?php echo esc_url(get_permalink() . '?lang=' . $lang); ?>" class="nl-btn-primary" style="display:inline-block;text-decoration:none;text-align:center;">
							<?php echo $booking_req ? nl_t('booking_now') : nl_t('details'); ?>
						</a>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	<?php else : ?>
		<p style="text-align:center;"><?php _e('No services available at the moment.', 'neonlighthk'); ?></p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
