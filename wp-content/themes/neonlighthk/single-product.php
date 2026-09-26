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
				$image_ids = [];
				if (has_post_thumbnail()) {
					$image_ids[] = get_post_thumbnail_id();
				}
				foreach ($product->get_gallery_image_ids() as $gid) {
					if (!in_array($gid, $image_ids, true)) {
						$image_ids[] = $gid;
					}
				}
				if (!empty($image_ids)) : ?>
					<?php
					// Collect full-size URLs for the lightbox (use original, not resized, for max quality)
					$gallery_full_urls = array_map(function($id) { return wp_get_attachment_url($id); }, $image_ids);
					$gallery_full_urls = array_values(array_filter($gallery_full_urls));
					?>
					<?php foreach ($image_ids as $idx => $img_id) : ?>
						<div class="nl-product-gallery__img" data-lb-index="<?php echo esc_attr($idx); ?>" role="button" tabindex="0" aria-label="Enlarge image">
							<?php echo wp_get_attachment_image($img_id, 'large'); ?>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="nl-product-gallery__img nl-product-gallery__img--placeholder">
						<span><?php echo nl_t('shop_no_image'); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<!-- Product Summary -->
			<div class="nl-product-summary">
				<h1 class="nl-product-summary__title"><?php echo nl_get_product_trilingual_title(); ?></h1>
				<div class="nl-product-summary__price">
					<?php if ($product->is_on_sale() && $product->get_sale_price()) : ?>
						<span class="nl-product-summary__price-regular">HK$<?php echo number_format($product->get_regular_price()); ?></span>
						<span class="nl-product-summary__price-sale">HK$<?php echo number_format($product->get_sale_price()); ?></span>
					<?php else : ?>
						<span class="nl-product-summary__price-current">HK$<?php echo number_format($product->get_regular_price()); ?></span>
					<?php endif; ?>
				</div>
				<form class="cart nl-product-cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
					<?php do_action('woocommerce_before_add_to_cart_button'); ?>
					<div class="nl-product-cart__qty">
						<label for="quantity_<?php echo esc_attr($product->get_id()); ?>"><?php echo nl_t('wc_quantity'); ?></label>
						<input type="number" id="quantity_<?php echo esc_attr($product->get_id()); ?>" name="quantity" value="1" min="1" step="1">
					</div>
					<button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="nl-btn-primary single_add_to_cart_button"><?php echo nl_t('wc_add_to_cart'); ?></button>
					<?php do_action('woocommerce_after_add_to_cart_button'); ?>
				</form>
			</div>
		</div>

		<!-- Product Details -->
		<div class="nl-product-details">
			<?php
			$long_desc = nl_get_product_trilingual_desc();
			if (!empty($long_desc)) : ?>
				<div class="nl-product-details__section">
					<h2><?php echo nl_t('product_details_desc'); ?></h2>
					<div class="nl-product-details__content">
						<?php echo apply_filters('the_content', $long_desc); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<!-- Related Products -->
		<?php
		$related = wc_get_related_products($product->get_id(), 4);
		if (!empty($related)) : ?>
			<div class="nl-related-products">
				<h2><?php echo nl_t('wc_related_products'); ?></h2>
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
.nl-product-gallery {
	display:grid;
	gap: 12px;
	grid-template-columns: repeat(2, 1fr);
}
.nl-product-gallery__img {
	border-radius: 16px;
	overflow:hidden;
	background:#f5f5f5;
	aspect-ratio: 1;
	cursor: zoom-in;
	position: relative;
}
.nl-product-gallery__img img {
	transition: transform .3s ease;
}
.nl-product-gallery__img:hover img {
	transform: scale(1.05);
}
.nl-product-gallery__img:first-child:nth-last-child(1) { grid-column: 1 / -1; }
.nl-product-gallery__img:first-child:nth-last-child(2),
.nl-product-gallery__img:first-child:nth-last-child(2) ~ .nl-product-gallery__img { grid-column: span 1; }
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

/* Product Details */
.nl-product-details { margin-top: 48px; }
.nl-product-details__section { margin-bottom: 40px; }
.nl-product-details__section h2 {
	font-size:1.25rem; font-weight:700; margin:0 0 16px; color:#111;
	border-bottom:2px solid #00d4b0; padding-bottom:8px; display:inline-block;
}
.nl-product-details__content { font-size:1rem; line-height:1.7; color:#444; }
.nl-product-details__content p { margin-bottom:12px; }
.nl-product-details__table {
	width:100%; border-collapse:collapse; font-size:.95rem;
}
.nl-product-details__table th,
.nl-product-details__table td {
	padding:12px 16px; text-align:left; border-bottom:1px solid #eee;
}
.nl-product-details__table th {
	width:30%; font-weight:600; color:#333; background:#f9f9f9;
}
.nl-product-details__table td { color:#555; }

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

/* Product image lightbox */
.nl-lightbox{display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:10000;flex-direction:column}
.nl-lightbox.active{display:flex}
.nl-lightbox__top{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;color:#fff}
.nl-lightbox__counter{font-size:.9rem;opacity:.8}
.nl-lightbox__close{background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;padding:0 8px;line-height:1}
.nl-lightbox__stage{flex:1;display:flex;align-items:center;justify-content:center;position:relative;padding:0 56px;touch-action:pan-y}
.nl-lightbox__stage img{max-width:100%;max-height:78vh;object-fit:contain;border-radius:6px;user-select:none}
.nl-lightbox__arrow{position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.15);border:none;color:#fff;width:44px;height:44px;border-radius:50%;cursor:pointer;font-size:1.2rem;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
.nl-lightbox__arrow.prev{left:12px}
.nl-lightbox__arrow.next{right:12px}
.nl-lightbox__dots{display:flex;justify-content:center;gap:8px;padding:16px}
.nl-lightbox__dots span{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.4);cursor:pointer}
.nl-lightbox__dots span.active{background:#fff}
@media(max-width:640px){
	.nl-lightbox__arrow{width:36px;height:36px}
	.nl-lightbox__stage{padding:0 44px}
}
</style>

<!-- Product gallery lightbox -->
<?php if (!empty($gallery_full_urls ?? [])) : ?>
<div class="nl-lightbox" id="nlProductLightbox">
	<div class="nl-lightbox__top">
		<span class="nl-lightbox__counter" id="nlLbCounter">1 / <?php echo count($gallery_full_urls); ?></span>
		<button class="nl-lightbox__close" onclick="nlCloseLightbox()" aria-label="Close">&times;</button>
	</div>
	<div class="nl-lightbox__stage">
		<button class="nl-lightbox__arrow prev" onclick="nlLbPrev()" aria-label="Previous">&#10094;</button>
		<img src="" alt="" id="nlLbImage" />
		<button class="nl-lightbox__arrow next" onclick="nlLbNext()" aria-label="Next">&#10095;</button>
	</div>
	<div class="nl-lightbox__dots" id="nlLbDots"></div>
</div>

<script>
(function(){
	const nlGalleryImages = <?php echo wp_json_encode(array_values($gallery_full_urls)); ?>;
	if (!nlGalleryImages.length) return;
	let nlLbIndex = 0;
	const lb = document.getElementById('nlProductLightbox');
	const lbImage = document.getElementById('nlLbImage');
	const lbCounter = document.getElementById('nlLbCounter');
	const lbDots = document.getElementById('nlLbDots');

	function nlUpdateLightbox() {
		lbImage.src = nlGalleryImages[nlLbIndex];
		lbCounter.textContent = (nlLbIndex + 1) + ' / ' + nlGalleryImages.length;
		lbDots.innerHTML = '';
		nlGalleryImages.forEach((_, i) => {
			const s = document.createElement('span');
			if (i === nlLbIndex) s.classList.add('active');
			s.addEventListener('click', () => { nlLbIndex = i; nlUpdateLightbox(); });
			lbDots.appendChild(s);
		});
	}
	window.nlOpenLightbox = function(idx) {
		nlLbIndex = Math.max(0, Math.min(idx, nlGalleryImages.length - 1));
		nlUpdateLightbox();
		lb.classList.add('active');
		document.body.style.overflow = 'hidden';
	};
	window.nlCloseLightbox = function() {
		lb.classList.remove('active');
		document.body.style.overflow = '';
	};
	window.nlLbNext = function() {
		nlLbIndex = (nlLbIndex + 1) % nlGalleryImages.length;
		nlUpdateLightbox();
	};
	window.nlLbPrev = function() {
		nlLbIndex = (nlLbIndex - 1 + nlGalleryImages.length) % nlGalleryImages.length;
		nlUpdateLightbox();
	};

	// Bind each gallery card to open the lightbox
	document.querySelectorAll('.nl-product-gallery__img[data-lb-index]').forEach(el => {
		const idx = parseInt(el.getAttribute('data-lb-index'), 10) || 0;
		el.addEventListener('click', () => nlOpenLightbox(idx));
		el.addEventListener('keydown', e => {
			if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); nlOpenLightbox(idx); }
		});
	});

	// Click outside image closes
	lb.addEventListener('click', e => {
		if (e.target === lb || e.target.classList.contains('nl-lightbox__stage')) nlCloseLightbox();
	});

	// Keyboard navigation
	document.addEventListener('keydown', e => {
		if (!lb.classList.contains('active')) return;
		if (e.key === 'Escape') nlCloseLightbox();
		if (e.key === 'ArrowRight') nlLbNext();
		if (e.key === 'ArrowLeft') nlLbPrev();
	});

	// Touch swipe
	let touchStartX = 0;
	lb.addEventListener('touchstart', e => {
		touchStartX = e.changedTouches[0].screenX;
	}, {passive:true});
	lb.addEventListener('touchend', e => {
		const diff = e.changedTouches[0].screenX - touchStartX;
		if (diff < -40) nlLbNext();
		else if (diff > 40) nlLbPrev();
	}, {passive:true});
})();
</script>
<?php endif; ?>

<?php get_footer('shop'); ?>
