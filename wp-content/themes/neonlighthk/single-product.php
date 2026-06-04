<?php
/**
 * The Template for displaying all single products
 *
 * @package NeonLightHK
 */

if (!defined('ABSPATH')) exit;

get_header('shop'); ?>

<div class="nl-page nl-product-page">

	<?php while (have_posts()) : the_post(); ?>
		<?php global $product; ?>

		<div class="nl-product-wrap">
			<!-- Product Gallery -->
			<div class="nl-product-gallery">
				<?php
				$thumb = get_the_post_thumbnail(get_the_ID(), 'large');
				if ($thumb) : ?>
					<div class="nl-product-gallery__img">
						<?php echo $thumb; ?>
					</div>
				<?php else : ?>
					<div class="nl-product-gallery__img nl-product-gallery__img--placeholder">
						<span><?php echo nl_t('shop_no_image'); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<!-- Product Summary -->
			<div class="nl-product-summary">
				<h1 class="nl-product-summary__title"><?php the_title(); ?></h1>

				<div class="nl-product-summary__price">
					<?php if ($product->is_on_sale() && $product->get_sale_price()) : ?>
						<span class="nl-product-summary__price-regular">HK$<?php echo number_format($product->get_regular_price()); ?></span>
						<span class="nl-product-summary__price-sale">HK$<?php echo number_format($product->get_sale_price()); ?></span>
					<?php else : ?>
						<span class="nl-product-summary__price-current">HK$<?php echo number_format($product->get_regular_price()); ?></span>
					<?php endif; ?>
				</div>

				<?php if ($product->get_short_description()) : ?>
					<div class="nl-product-summary__desc">
						<?php echo wp_kses_post($product->get_short_description()); ?>
					</div>
				<?php endif; ?>

				<form class="cart nl-product-cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
					<?php do_action('woocommerce_before_add_to_cart_button'); ?>
					<div class="nl-product-cart__qty">
						<label for="quantity_<?php echo esc_attr($product->get_id()); ?>"><?php esc_html_e('Quantity:', 'woocommerce'); ?></label>
						<input type="number" id="quantity_<?php echo esc_attr($product->get_id()); ?>" name="quantity" value="1" min="1" step="1">
					</div>
					<button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="nl-btn-primary single_add_to_cart_button"><?php echo nl_t('wc_add_to_cart'); ?></button>
					<?php do_action('woocommerce_after_add_to_cart_button'); ?>
				</form>

				<?php if (wc_get_stock_html($product)) : ?>
					<div class="nl-product-stock">
						<?php echo wc_get_stock_html($product); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Related Products -->
		<?php
		$related = wc_get_related_products($product->get_id(), 4);
		if (!empty($related)) : ?>
			<div class="nl-related-products">
				<h2><?php esc_html_e('Related Products', 'woocommerce'); ?></h2>
				<div class="nl-product-grid">
					<?php foreach ($related as $related_id) :
						$related_product = wc_get_product($related_id);
						if (!$related_product) continue;
					?>
						<div class="nl-product-card">
							<a href="<?php echo esc_url(get_permalink($related_id)); ?>?lang=<?php echo nl_lang(); ?>" class="nl-product-card__link">
								<?php if (has_post_thumbnail($related_id)) : ?>
									<div class="nl-product-card__image"><?php echo get_the_post_thumbnail($related_id, 'medium'); ?></div>
								<?php endif; ?>
								<h3 class="nl-product-card__title"><?php echo esc_html($related_product->get_name()); ?></h3>
								<div class="nl-product-card__price">
									<?php if ($related_product->is_on_sale() && $related_product->get_sale_price()) : ?>
										<span class="nl-product-card__price-regular">HK$<?php echo number_format($related_product->get_regular_price()); ?></span>
										<span class="nl-product-card__price-sale">HK$<?php echo number_format($related_product->get_sale_price()); ?></span>
									<?php else : ?>
										<span class="nl-product-card__price-current">HK$<?php echo number_format($related_product->get_regular_price()); ?></span>
									<?php endif; ?>
								</div>
							</a>
							<form class="nl-product-card__cart" method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>">
								<input type="hidden" name="add-to-cart" value="<?php echo esc_attr($related_id); ?>">
								<button type="submit" class="nl-btn-primary"><?php echo nl_t('wc_add_to_cart'); ?></button>
							</form>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

	<?php endwhile; ?>

</div>

<style>
/* Product Detail Page */
.nl-product-page { max-width:1200px; margin:0 auto; padding:40px 20px; }

/* Two-column wrap */
.nl-product-wrap {
	display:grid;
	grid-template-columns: 1fr 1fr;
	gap: 48px;
	align-items:start;
	margin-bottom: 60px;
}

/* Gallery */
.nl-product-gallery__img {
	border-radius: 16px;
	overflow:hidden;
	background:#f5f5f5;
	aspect-ratio: 1;
}
.nl-product-gallery__img img {
	width:100%; height:100%; object-fit:cover; display:block;
}
.nl-product-gallery__img--placeholder {
	display:flex; align-items:center; justify-content:center;
	font-size:14px; color:#999;
}

/* Summary */
.nl-product-summary { padding-top: 8px; }
.nl-product-summary__title {
	font-size: 2rem; font-weight:700; margin:0 0 16px; line-height:1.2;
}
.nl-product-summary__price {
	font-size: 1.4rem; font-weight:700; margin-bottom: 20px;
}
.nl-product-summary__price-regular {
	text-decoration:line-through; color:#999; margin-right:12px;
	font-size:1.1rem;
}
.nl-product-summary__price-sale { color:#e53935; }
.nl-product-summary__price-current { color:#00d4b0; }

.nl-product-summary__desc {
	font-size:1rem; line-height:1.6; color:#555; margin-bottom: 24px;
}

/* Cart form */
.nl-product-cart { margin-top: 24px; }
.nl-product-cart__qty {
	display:flex; align-items:center; gap:12px; margin-bottom: 16px;
}
.nl-product-cart__qty label { font-weight:600; font-size:.9rem; }
.nl-product-cart__qty input[type="number"] {
	width:80px; padding:10px 12px; border:1px solid #ddd;
	border-radius:8px; font-size:1rem; text-align:center;
}
.nl-product-cart .nl-btn-primary {
	width:100%; padding:14px 28px; border:none; border-radius:30px;
	background:#00d4b0; color:#fff; font-size:1rem; font-weight:600;
	cursor:pointer; transition:background .2s;
}
.nl-product-cart .nl-btn-primary:hover { background:#00bfa0; }

.nl-product-stock { margin-top:16px; font-size:.9rem; color:#777; }

/* Related products */
.nl-related-products { margin-top: 60px; }
.nl-related-products h2 {
	font-size:1.5rem; margin-bottom:24px; text-align:center;
}

/* Re-use shop grid styles */
.nl-product-grid {
	display:grid;
	grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
	gap:24px;
}
.nl-product-card {
	background:#fff; border-radius:12px; overflow:hidden;
	box-shadow:0 2px 8px rgba(0,0,0,.08); transition:transform .2s, box-shadow .2s;
}
.nl-product-card:hover {
	transform:translateY(-4px); box-shadow:0 8px 24px rgba(0,0,0,.12);
}
.nl-product-card__link { display:block; text-decoration:none; color:inherit; }
.nl-product-card__image { aspect-ratio:1; overflow:hidden; background:#f5f5f5; }
.nl-product-card__image img { width:100%; height:100%; object-fit:cover; display:block; }
.nl-product-card__title { font-size:1rem; margin:12px 12px 4px; line-height:1.3; }
.nl-product-card__price { padding:0 12px 12px; font-weight:600; }
.nl-product-card__price-regular { text-decoration:line-through; color:#999; margin-right:8px; font-size:.9rem; }
.nl-product-card__price-sale { color:#e53935; }
.nl-product-card__price-current { color:#00d4b0; }
.nl-product-card__cart { padding:0 12px 12px; }
.nl-product-card__cart button {
	width:100%; padding:10px; border:none; border-radius:30px;
	background:#00d4b0; color:#fff; font-size:14px; cursor:pointer;
}
.nl-product-card__cart button:hover { background:#00bfa0; }

/* Mobile */
@media(max-width: 768px){
	.nl-product-wrap {
		grid-template-columns: 1fr;
		gap: 24px;
	}
	.nl-product-gallery__img { aspect-ratio: 4/3; }
	.nl-product-summary__title { font-size: 1.5rem; }
	.nl-product-grid { grid-template-columns: repeat(2, 1fr); gap:12px; }
}
</style>

<?php get_footer('shop'); ?>
