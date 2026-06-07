<?php
/**
 * Template Name: Shop
 * @package NeonLightHK
 */
get_header();

$lang = nl_lang();

// Query WooCommerce products filtered by language
$base_args = [
	'post_type'      => 'product',
	'posts_per_page' => 50,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'meta_query'     => [
		[
			'key'     => '_nl_post_lang',
			'value'   => $lang,
			'compare' => '=',
		],
	],
];
$products = new WP_Query($base_args);

// Fallback: if no products match the language, show all
if (!$products->have_posts()) {
	$fallback_args = [
		'post_type'      => 'product',
		'posts_per_page' => 50,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	];
	$products = new WP_Query($fallback_args);
}
$total = $products->found_posts;
$showing = min(12, $total);
?>

<div class="nl-page nl-shop-page">
	<div class="nl-shop-header">
		<nav class="nl-breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo nl_t('shop_breadcrumb_home'); ?></a> / <span><?php echo nl_t('shop_title'); ?></span></nav>
		<h1 class="nl-shop-title"><?php echo nl_t('shop_title'); ?></h1>
		<div class="nl-shop-toolbar">
			<span class="nl-results-count"><?php echo nl_t('shop_showing'); ?> 1–<?php echo $showing; ?> <?php echo nl_t('shop_of'); ?> <?php echo $total; ?> <?php echo nl_t('shop_results'); ?></span>
			<div class="nl-sort-dropdown">
				<select>
					<option><?php echo nl_t('shop_sort_default'); ?></option>
				</select>
			</div>
		</div>
	</div>

	<?php if ($products->have_posts()) : ?>
		<div class="nl-product-grid">
			<?php while ($products->have_posts()) : $products->the_post(); ?>
				<?php global $product; if (!$product) continue; ?>
				<div class="nl-product-card">
					<?php if ($product->is_on_sale()) : ?><div class="nl-sale-badge"><?php echo nl_t('shop_sale'); ?></div><?php endif; ?>
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
							<?php if ($product->is_on_sale() && $product->get_sale_price()) : ?>
								<span class="nl-product-card__price-regular">$<?php echo number_format($product->get_regular_price(),2); ?></span>
								<span class="nl-product-card__price-sale">$<?php echo number_format($product->get_sale_price(),2); ?></span>
							<?php else : ?>
								<span class="nl-product-card__price-current">$<?php echo number_format($product->get_regular_price(),2); ?></span>
							<?php endif; ?>
						</div>
					</a>
					<form class="nl-product-card__cart" method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>">
						<?php wp_nonce_field('woocommerce-cart'); ?>
						<input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>">
						<button type="submit" class="nl-btn-primary"><?php echo nl_t('wc_add_to_cart'); ?></button>
					</form>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	<?php else : ?>
		<p><?php echo nl_t('shop_coming'); ?></p>
	<?php endif; ?>
</div>

<style>
/* === Shop Page === */
.nl-shop-page {
	padding: 0 0 40px;
	background: #fff;
}

/* Shop Header */
.nl-shop-header {
	padding: 20px 16px 12px;
	max-width: 680px;
	margin: 0 auto;
}
.nl-breadcrumb {
	font-size: 13px;
	color: #888;
	margin-bottom: 8px;
}
.nl-breadcrumb a {
	color: #888;
	text-decoration: none;
}
.nl-shop-title {
	font-size: 28px;
	font-weight: 700;
	color: #111;
	margin: 0 0 16px;
	letter-spacing: -0.5px;
}
.nl-shop-toolbar {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 20px;
}
.nl-results-count {
	font-size: 13px;
	color: #666;
}
.nl-sort-dropdown select {
	font-size: 13px;
	color: #666;
	border: 1px solid #ddd;
	border-radius: 6px;
	padding: 6px 28px 6px 10px;
	background: #fff;
	appearance: none;
	-webkit-appearance: none;
	background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
	background-repeat: no-repeat;
	background-position: right 8px center;
}

/* === Product Grid === */
.nl-product-grid {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 12px;
	padding: 0 12px;
	max-width: 680px;
	margin: 0 auto;
}
@media (min-width: 641px) {
	.nl-product-grid {
		grid-template-columns: repeat(3, 1fr);
		gap: 20px;
		padding: 0 20px;
	}
}

/* === Product Card === */
.nl-product-card {
	background: #fff;
	padding-bottom: 8px;
}

/* Sale Badge — plain centered text ABOVE image */
.nl-sale-badge {
	text-align: center;
	font-size: 12px;
	font-weight: 700;
	color: #111;
	margin-bottom: 2px;
	line-height: 1;
}

/* Image */
.nl-product-card__image {
	aspect-ratio: 1;
	overflow: hidden;
	background: #f5f5f5;
}
.nl-product-card__image img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
}

/* Title — centered bold uppercase, tight to image */
.nl-product-card__title {
	font-size: 13px;
	font-weight: 700;
	text-transform: uppercase;
	text-align: center;
	margin: 8px 0 2px;
	letter-spacing: 0.3px;
	color: #111;
	line-height: 1.2;
}

/* Price — centered teal, very tight to title */
.nl-product-card__price {
	text-align: center;
	font-size: 13px;
	font-weight: 600;
	margin-bottom: 8px;
	line-height: 1.3;
}
.nl-product-card__price-regular {
	text-decoration: line-through;
	color: #00a896;
	margin-right: 4px;
}
.nl-product-card__price-sale {
	color: #00a896;
}
.nl-product-card__price-current {
	color: #00a896;
}

/* Add to Cart — full-width teal pill, black text */
.nl-product-card__cart {
	padding: 0;
}
.nl-btn-primary {
	display: block;
	width: 100%;
	padding: 10px 12px;
	border: none;
	border-radius: 30px;
	background: #00a896;
	color: #111;
	font-size: 11px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.6px;
	cursor: pointer;
	transition: background .2s;
}
.nl-btn-primary:hover {
	background: #009883;
}

/* Placeholder */
.nl-product-card__image--placeholder {
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 14px;
	color: #999;
}
</style>

<?php get_footer(); ?>
