<?php
/**
 * Admin Language Picker for all posts, CPTs, and WooCommerce products
 * @package NeonLightHK
 */

if (!defined('ABSPATH')) exit;

/* ===== LANGUAGE META BOX ===== */
add_action('add_meta_boxes', 'nl_register_lang_meta_boxes', 10, 2);
function nl_register_lang_meta_boxes($post_type, $post) {
    $types = ['post', 'page', 'nl_workshop', 'nl_balloon', 'nl_hanfu', 'nl_rental', 'nl_custom_order', 'nl_lookbook', 'nl_project', 'product'];
    if (!in_array($post_type, $types)) return;
    add_meta_box(
        'nl_lang_meta',
        __('Language', 'neonlighthk'),
        'nl_render_lang_meta_box',
        $post_type,
        'side',
        'high'
    );
}

function nl_render_lang_meta_box($post) {
    wp_nonce_field('nl_save_lang', 'nl_lang_nonce');
    $val = get_post_meta($post->ID, '_nl_post_lang', true) ?: 'zh';
    ?>
    <style>
    .nl-lang-options{display:flex;gap:4px;margin-top:8px}
    .nl-lang-options label{cursor:pointer;display:flex;align-items:center;gap:4px;padding:6px 10px;border-radius:6px;border:1px solid #ddd;font-size:13px;background:#fff;transition:.15s}
    .nl-lang-options label:hover{background:#f5f5f5}
    .nl-lang-options input[type="radio"]{margin:0}
    .nl-lang-options input:checked + label{background:#00a896;border-color:#00a896;color:#fff}
    .nl-lang-options label.selected{background:#00a896;border-color:#00a896;color:#fff}
    </style>
    <p style="margin:0;color:#666;font-size:12px"><?php _e('Select the language for this content. Visitors will see it only when that language is active.', 'neonlighthk'); ?></p>
    <div class="nl-lang-options">
        <label class="<?php echo $val==='en' ? 'selected' : ''; ?>">
            <input type="radio" name="nl_post_lang" value="en" <?php checked($val, 'en'); ?>> EN
        </label>
        <label class="<?php echo $val==='zh' ? 'selected' : ''; ?>">
            <input type="radio" name="nl_post_lang" value="zh" <?php checked($val, 'zh'); ?>> 繁中
        </label>
        <label class="<?php echo $val==='cn' ? 'selected' : ''; ?>">
            <input type="radio" name="nl_post_lang" value="cn" <?php checked($val, 'cn'); ?>> 简中
        </label>
    </div>
    <script>
    jQuery(function($){
        $('.nl-lang-options input').on('change', function(){
            $(this).closest('.nl-lang-options').find('label').removeClass('selected');
            $(this).closest('label').addClass('selected');
        });
    });
    </script>
    <?php
}

/* ===== SAVE META ===== */
add_action('save_post', function ($post_id) {
    if (
        !isset($_POST['nl_lang_nonce']) ||
        !wp_verify_nonce($_POST['nl_lang_nonce'], 'nl_save_lang') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }
    if (isset($_POST['nl_post_lang']) && in_array($_POST['nl_post_lang'], ['en', 'zh', 'cn'])) {
        update_post_meta($post_id, '_nl_post_lang', sanitize_text_field($_POST['nl_post_lang']));
    }
});

/* ===== ADMIN LIST COLUMNS ===== */
function nl_add_lang_column($columns) {
    $new = [];
    foreach ($columns as $k => $v) {
        $new[$k] = $v;
        if ($k === 'title') {
            $new['nl_lang'] = __('Lang', 'neonlighthk');
        }
    }
    return $new;
}

function nl_render_lang_column($column, $post_id) {
    if ($column !== 'nl_lang') return;
    $lang = get_post_meta($post_id, '_nl_post_lang', true) ?: 'zh';
    $labels = ['en' => 'EN', 'zh' => '繁中', 'cn' => '简中'];
    $colors = ['en' => '#0073aa', 'zh' => '#00a896', 'cn' => '#d54e21'];
    echo '<span style="display:inline-block;padding:2px 8px;border-radius:4px;background:' . esc_attr($colors[$lang]) . ';color:#fff;font-size:11px;font-weight:600;">' . esc_html($labels[$lang]) . '</span>';
}

// Apply to all relevant post types
foreach (['post', 'page', 'nl_workshop', 'nl_balloon', 'nl_hanfu', 'nl_rental', 'nl_custom_order', 'nl_lookbook', 'nl_project'] as $type) {
    add_filter("manage_{$type}_posts_columns", 'nl_add_lang_column');
    add_action("manage_{$type}_posts_custom_column", 'nl_render_lang_column', 10, 2);
}

// Products (WooCommerce) — different hook
add_filter('manage_edit-product_columns', 'nl_add_lang_column');
add_action('manage_product_posts_custom_column', 'nl_render_lang_column', 10, 2);

/* ===== QUICK EDIT ===== */
add_action('quick_edit_custom_box', function ($column_name, $post_type) {
    if ($column_name !== 'nl_lang') return;
    $types = ['post', 'page', 'nl_workshop', 'nl_balloon', 'nl_hanfu', 'nl_rental', 'nl_custom_order', 'nl_lookbook', 'nl_project', 'product'];
    if (!in_array($post_type, $types)) return;
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="alignleft">
                <span class="title"><?php _e('Language', 'neonlighthk'); ?></span>
                <span class="input-text-wrap">
                    <select name="nl_post_lang" id="nl_inline_lang">
                        <option value="en">EN</option>
                        <option value="zh">繁中</option>
                        <option value="cn">简中</option>
                    </select>
                </span>
            </label>
        </div>
    </fieldset>
    <?php
}, 10, 2);

add_action('save_post', function ($post_id) {
    if (
        !isset($_POST['nl_lang_nonce']) ||
        !wp_verify_nonce($_POST['nl_lang_nonce'], 'nl_save_lang') ||
        (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }
    if (isset($_POST['nl_post_lang']) && in_array($_POST['nl_post_lang'], ['en', 'zh', 'cn'])) {
        update_post_meta($post_id, '_nl_post_lang', sanitize_text_field($_POST['nl_post_lang']));
    }
});

/* ===== INLINE SCRIPT FOR QUICK EDIT ===== */
add_action('admin_footer', function () {
    $screen = get_current_screen();
    if (!$screen || !in_array($screen->id, ['edit-post', 'edit-page', 'edit-nl_workshop', 'edit-nl_balloon', 'edit-nl_hanfu', 'edit-nl_rental', 'edit-nl_custom_order', 'edit-nl_lookbook', 'edit-nl_project', 'edit-product'])) return;
    ?>
    <script>
    jQuery(function($){
        var $wp_inline_edit = inlineEditPost.edit;
        inlineEditPost.edit = function(id){
            $wp_inline_edit.apply(this, arguments);
            var post_id = 0;
            if (typeof(id)==='object') post_id = parseInt(this.getId(id));
            if (post_id===0) return;
            var $row = $('#post-'+post_id);
            var lang = $row.find('.nl_lang .nl-lang-badge').data('lang') || 'zh';
            $('#nl_inline_lang').val(lang);
        };
    });
    </script>
    <?php
});

/* ===== BULK EDIT ===== */
add_action('bulk_edit_custom_box', function ($column_name, $post_type) {
    if ($column_name !== 'nl_lang') return;
    $types = ['post', 'page', 'nl_workshop', 'nl_balloon', 'nl_hanfu', 'nl_rental', 'nl_custom_order', 'nl_lookbook', 'nl_project', 'product'];
    if (!in_array($post_type, $types)) return;
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="alignleft">
                <span class="title"><?php _e('Language', 'neonlighthk'); ?></span>
                <span class="input-text-wrap">
                    <select name="nl_post_lang">
                        <option value="">— No Change —</option>
                        <option value="en">EN</option>
                        <option value="zh">繁中</option>
                        <option value="cn">简中</option>
                    </select>
                </span>
            </label>
        </div>
    </fieldset>
    <?php
}, 10, 2);

/* ===== HANDLE BULK EDIT SAVE ===== */
add_action('save_post', function ($post_id) {
    if (!isset($_REQUEST['bulk_edit'])) return;
    if (isset($_REQUEST['nl_post_lang']) && in_array($_REQUEST['nl_post_lang'], ['en', 'zh', 'cn'])) {
        update_post_meta($post_id, '_nl_post_lang', sanitize_text_field($_REQUEST['nl_post_lang']));
    }
});
