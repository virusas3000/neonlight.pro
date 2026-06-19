<?php
/**
 * Trilingual Product Title + Description (Admin)
 * 3 independent meta boxes each for title and description: EN / TC / SC
 * Removes default WP editor from products (descriptions live in meta only)
 * @package NeonLightHK
 */

if (!defined('ABSPATH')) exit;

/* ===== REMOVE DEFAULT EDITOR FROM PRODUCTS ===== */
add_action('init', function () {
    remove_post_type_support('product', 'editor');
});

/* ===== FRONTEND HELPERS ===== */
function nl_get_product_trilingual_title($product_id = null) {
    if (!$product_id) $product_id = get_the_ID();
    $lang = nl_lang();
    $map = ['en' => '_nl_title_en', 'zh' => '_nl_title_tc', 'cn' => '_nl_title_sc'];
    $key = $map[$lang] ?? '_nl_title_tc';
    $title = get_post_meta($product_id, $key, true);
    return $title ?: get_the_title($product_id);
}

function nl_get_product_trilingual_desc($product_id = null) {
    if (!$product_id) $product_id = get_the_ID();
    $lang = nl_lang();
    $map = ['en' => '_nl_desc_en', 'zh' => '_nl_desc_tc', 'cn' => '_nl_desc_sc'];
    $key = $map[$lang] ?? '_nl_desc_tc';
    return get_post_meta($product_id, $key, true);
}

/* ===== META BOXES ===== */
add_action('add_meta_boxes', function () {
    $boxes = [
        ['nl_title_en', 'Title (EN)', '_nl_title_en', true],
        ['nl_title_tc', 'Title (TC)', '_nl_title_tc', false],
        ['nl_title_sc', 'Title (SC)', '_nl_title_sc', false],
        ['nl_desc_en',  'Description (EN)',  '_nl_desc_en',  false],
        ['nl_desc_tc',  'Description (TC)',  '_nl_desc_tc',  false],
        ['nl_desc_sc',  'Description (SC)',  '_nl_desc_sc',  false],
    ];
    foreach ($boxes as [$id, $title, $key, $nonce]) {
        add_meta_box(
            $id,
            __($title, 'neonlighthk'),
            function ($post) use ($key, $nonce) {
                if ($nonce) wp_nonce_field('nl_save_product_desc', 'nl_product_desc_nonce');
                $val = get_post_meta($post->ID, $key, true) ?: '';
                ?><style>.nl-meta-field textarea,.nl-meta-field input{width:100%;font-family:monospace;font-size:13px}.nl-meta-field textarea{min-height:180px}.nl-meta-field input{height:36px;padding:6px 8px}</style>
                <div class="nl-meta-field"><?php if (strpos($key, '_title_') !== false) : ?><input type="text" name="<?php echo str_replace('_nl_', 'nl_', $key); ?>" value="<?php echo esc_attr($val); ?>"><?php else : ?><textarea name="<?php echo str_replace('_nl_', 'nl_', $key); ?>"><?php echo esc_textarea($val); ?></textarea><?php endif; ?></div><?php
            },
            'product',
            'normal',
            'high'
        );
    }
});

/* ===== SAVE ===== */
add_action('save_post', function ($post_id) {
    if (
        !isset($_POST['nl_product_desc_nonce']) ||
        !wp_verify_nonce($_POST['nl_product_desc_nonce'], 'nl_save_product_desc') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)
    ) return;
    foreach (['_nl_title_en','_nl_title_tc','_nl_title_sc','_nl_desc_en','_nl_desc_tc','_nl_desc_sc'] as $key) {
        $field = str_replace('_nl_', 'nl_', $key);
        if (!isset($_POST[$field])) continue;
        $val = (strpos($key, '_title_') !== false) ? sanitize_text_field($_POST[$field]) : wp_kses_post($_POST[$field]);
        if ($val === '') {
            delete_post_meta($post_id, $key);
        } else {
            update_post_meta($post_id, $key, $val);
        }
    }
});
