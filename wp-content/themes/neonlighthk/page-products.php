<?php
/**
 * Template Name: Products
 * @package NeonLightHK
 */
if (!defined('ABSPATH')) exit;
get_header('shop');
$lang = nl_lang();

// Query WooCommerce products filtered by language
$base_args = [
	'post_type'      => 'product',
	'posts_per_page' => 50,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'tax_query'      => [
		[
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => 'products',
		],
	],
];
$products = new WP_Query($base_args);
$total = $products->found_posts;
$showing = min(12, $total);
?>
<div class="nl-page nl-shop-page">
	<div class="nl-shop-header">
		<nav class="nl-breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo nl_t('shop_breadcrumb_home'); ?></a> / <span><?php echo nl_t('products_title'); ?></span></nav>
		<h1 class="nl-shop-title"><?php echo nl_t('products_title'); ?></h1>
		<div class="nl-shop-toolbar">
			<span class="nl-results-count"><?php echo nl_t('shop_showing'); ?> 1–<?php echo $showing; ?> <?php echo nl_t('shop_of'); ?> <?php echo $total; ?> <?php echo nl_t('shop_results'); ?></span>
			<div class="nl-sort-dropdown"><select><option><?php echo nl_t('shop_sort_default'); ?></option></select></div>
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
	003cdiv class="nl-product-card__image"><?php the_post_thumbnail('medium'); ?></div>
						<?php else : ?>
							<div class="nl-product-card__image nl-product-card__image--placeholder"><span><?php echo nl_t('shop_no_image'); ?></span></div>
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
		<div style="text-align:center; padding:60px 20px;"><p><?php echo nl_t('products_coming'); ?></p></div>
	<?php endif; ?>
</div>
<style>
.nl-shop-page { max-width:1200px; margin:0 auto; padding:40px 20px; }
.nl-shop-header { margin-bottom: 32px; }
.nl-breadcrumb { font-size:.85rem; color:#777; margin-bottom:12px; }
.nl-breadcrumb a { color:#777; text-decoration:none; }
.nl-shop-title { font-size:1.8rem; font-weight:700; margin:0 0 16px; }
.nl-shop-toolbar { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:24px; }
.nl-results-count { font-size:.9rem; color:#666; }
.nl-sort-dropdown select { padding:8px 12px; border:1px solid #ddd; border-radius:8px; background:#fff; }
.nl-product-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:24px; }
.nl-product-card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); transition:transform .2s, box-shadow .2s; position:relative; }
.nl-product-card:hover { transform:translateY(-4px); box-shadow:0 8px 24px rgba(0,0,0,.12); }
.nl-product-card__link { display:block; text-decoration:none; color:inherit; }
.nl-product-card__image { aspect-ratio:1; overflow:hidden; background:#f5f5f5; }
.nl-product-card__image img { width:100%; height:100%; object-fit:cover; display:block; }
.nl-product-card__image--placeholder { display:flex; align-items:center; justify-content:center; font-size:14px; color:#999; }
.nl-product-card__title { font-size:1rem; margin:12px 12px 4px; line-height:1.3; font-weight:600; }
.nl-product-card__price { padding:0 12px 12px; font-weight:600; }
.nl-product-card__price-regular { text-decoration:line-through; color:#999; margin-right:8px; font-size:.9rem; }
.nl-product-card__price-sale { color:#e53935; }
.nl-product-card__price-current { color:#00d4b0; }
.nl-sale-badge { position:absolute; top:12px; left:12px; background:#e53935; color:#fff; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; z-index:2; }
.nl-product-card__cart { padding:0 12px 12px; }
.nl-product-card__cart button { width:100%; padding:10px; border:none; border-radius:30px; background:#00d4b0; color:#fff; font-size:14px; cursor:pointer; }
.nl-product-card__cart button:hover { background:#00bfa0; }
@media(max-width:768px){
	.nl-product-grid { grid-template-columns: repeat(2, 1fr); gap:12px; }
	.nl-shop-title { font-size:1.4rem; }
	.nl-shop-toolbar { flex-direction:column; align-items:flex-start; }
}
</style>
<?php get_footer('shop'); ?>
