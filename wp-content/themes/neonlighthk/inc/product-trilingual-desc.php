<?php
/**
 * Trilingual Product Description Meta Box (Admin only)
 * Adds EN / 繁中 / 简中 description fields to WooCommerce products.
 * Visible in WP Admin under the product editor. Stored as post meta.
 *
 * @package NeonLightHK
 */

if (!defined('ABSPATH')) exit;

/* ===== FRONTEND HELPER ===== */
function nl_get_product_trilingual_desc($product_id = null) {
	if (!$product_id) $product_id = get_the_ID();
	$lang = nl_lang();
	$map = ['en' => '_nl_desc_en', 'zh' => '_nl_desc_tc', 'cn' => '_nl_desc_sc'];
	$key = $map[$lang] ?? '_nl_desc_tc';
	return get_post_meta($product_id, $key, true);
}
add_action('add_meta_boxes', function () {
	add_meta_box(
		'nl_product_desc_meta',
		__('Product Description (Multi-language)', 'neonlighthk'),
		'nl_render_product_desc_meta_box',
		'product',
		'normal',
		'high'
	);
});

function nl_render_product_desc_meta_box($post) {
	wp_nonce_field('nl_save_product_desc', 'nl_product_desc_nonce');
	$desc_en = get_post_meta($post->ID, '_nl_desc_en', true) ?: '';
	$desc_tc = get_post_meta($post->ID, '_nl_desc_tc', true) ?: '';
	$desc_sc = get_post_meta($post->ID, '_nl_desc_sc', true) ?: '';
	?>
	<style>
	.nl-desc-tabs { display:flex; gap:4px; border-bottom:2px solid #eee; margin-bottom:12px; }
	.nl-desc-tab { cursor:pointer; padding:8px 16px; border:none; background:none; font-size:13px; color:#555; border-bottom:2px solid transparent; margin-bottom:-2px; }
	.nl-desc-tab.active { color:#00a896; border-bottom-color:#00a896; font-weight:600; }
	.nl-desc-panel { display:none; }
	.nl-desc-panel.active { display:block; }
	.nl-desc-field { margin-bottom:12px; }
	.nl-desc-field label { display:block; font-weight:600; margin-bottom:4px; font-size:13px; }
	.nl-desc-field textarea { width:100%; min-height:180px; font-family:monospace; font-size:13px; }
	</style>

	<div class="nl-desc-tabs">
		<button type="button" class="nl-desc-tab active" data-tab="desc-en">🇺🇸 EN</button>
		<button type="button" class="nl-desc-tab" data-tab="desc-tc">🇹🇼 繁中</button>
		<button type="button" class="nl-desc-tab" data-tab="desc-sc">🇨🇳 简中</button>
	</div>

	<div id="desc-en" class="nl-desc-panel active">
		<div class="nl-desc-field">
			<label for="nl_desc_en">English Description</label>
			<textarea id="nl_desc_en" name="nl_desc_en"><?php echo esc_textarea($desc_en); ?></textarea>
		</div>
	</div>
	<div id="desc-tc" class="nl-desc-panel">
		<div class="nl-desc-field">
			<label for="nl_desc_tc">繁體中文描述</label>
			<textarea id="nl_desc_tc" name="nl_desc_tc"><?php echo esc_textarea($desc_tc); ?></textarea>
		</div>
	</div>
	<div id="desc-sc" class="nl-desc-panel">
		<div class="nl-desc-field">
			<label for="nl_desc_sc">简体中文描述</label>
			<textarea id="nl_desc_sc" name="nl_desc_sc"><?php echo esc_textarea($desc_sc); ?></textarea>
		</div>
	</div>

	<script>
	jQuery(function($){
		$('.nl-desc-tab').on('click', function(){
			var tab = $(this).data('tab');
			$('.nl-desc-tab').removeClass('active');
			$(this).addClass('active');
			$('.nl-desc-panel').removeClass('active');
			$('#'+tab).addClass('active');
		});
	});
	</script>
	<?php
}

/* ===== SAVE META ===== */
add_action('save_post', function ($post_id) {
	if (
		!isset($_POST['nl_product_desc_nonce']) ||
		!wp_verify_nonce($_POST['nl_product_desc_nonce'], 'nl_save_product_desc') ||
		(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
		!current_user_can('edit_post', $post_id)
	) {
		return;
	}
	if (isset($_POST['nl_desc_en'])) update_post_meta($post_id, '_nl_desc_en', wp_kses_post($_POST['nl_desc_en']));
	if (isset($_POST['nl_desc_tc'])) update_post_meta($post_id, '_nl_desc_tc', wp_kses_post($_POST['nl_desc_tc']));
	if (isset($_POST['nl_desc_sc'])) update_post_meta($post_id, '_nl_desc_sc', wp_kses_post($_POST['nl_desc_sc']));
});
