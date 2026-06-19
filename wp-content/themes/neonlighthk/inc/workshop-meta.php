<?php
/**
 * Workshop Meta Boxes — Admin fields for nl_workshop CPT
 *
 * @package NeonLightHK
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- Register meta box ---------- */
add_action( 'add_meta_boxes', function () {
	add_meta_box(
		'nl_workshop_details',
		__( 'Workshop Details', 'neonlighthk' ),
		'nl_workshop_meta_box_callback',
		'nl_workshop',
		'normal',
		'high'
	);
} );

/* ---------- Hide default title input (CSS fallback so Gutenberg save button stays enabled) ---------- */
add_action( 'admin_head', function () {
	global $post_type;
	if ( $post_type !== 'nl_workshop' ) return;
	echo '<style>.editor-post-title { display:none !important; }</style>';
} );

/* ---------- Render meta box ---------- */
function nl_workshop_meta_box_callback( $post ) {
	wp_nonce_field( 'nl_workshop_meta_save', 'nl_workshop_meta_nonce' );

    $fields = [
        '_nl_workshop_title_en'         => get_post_meta( $post->ID, '_nl_workshop_title_en', true ),
        '_nl_workshop_title_zh'         => get_post_meta( $post->ID, '_nl_workshop_title_zh', true ),
        '_nl_workshop_title_cn'         => get_post_meta( $post->ID, '_nl_workshop_title_cn', true ),
        '_nl_workshop_desc_en'          => get_post_meta( $post->ID, '_nl_workshop_desc_en', true ),
        '_nl_workshop_desc_zh'          => get_post_meta( $post->ID, '_nl_workshop_desc_zh', true ),
        '_nl_workshop_desc_cn'          => get_post_meta( $post->ID, '_nl_workshop_desc_cn', true ),
        '_nl_workshop_gallery'          => get_post_meta( $post->ID, '_nl_workshop_gallery', true ),
        '_nl_workshop_max_group'        => get_post_meta( $post->ID, '_nl_workshop_max_group', true ),
        '_nl_workshop_min_group'        => get_post_meta( $post->ID, '_nl_workshop_min_group', true ),
        '_nl_workshop_booking_url'      => get_post_meta( $post->ID, '_nl_workshop_booking_url', true ),
        '_nl_workshop_items'            => get_post_meta( $post->ID, '_nl_workshop_items', true ),
    ];

	$gallery_ids = is_array( $fields['_nl_workshop_gallery'] ) ? $fields['_nl_workshop_gallery'] : [];

	$cover_id    = get_post_meta( $post->ID, '_nl_workshop_cover', true );
	$cover_url   = $cover_id ? wp_get_attachment_image_url( $cover_id, 'medium' ) : '';
	?>
	<style>
		.nl-meta-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-top:12px; }
		.nl-meta-grid .nl-meta-field { display:flex; flex-direction:column; }
		.nl-meta-grid .nl-meta-field.full { grid-column:1 / -1; }
		.nl-meta-grid label { font-weight:600; margin-bottom:4px; }
		.nl-meta-grid input[type="text"],
		.nl-meta-grid input[type="number"],
		.nl-meta-grid input[type="url"],
		.nl-meta-grid textarea { width:100%; }
		.nl-meta-grid textarea { min-height:80px; }
		.nl-meta-gallery { display:flex; flex-wrap:wrap; gap:8px; margin-top:8px; }
		.nl-meta-gallery .thumb { position:relative; width:100px; height:100px; }
		.nl-meta-gallery .thumb img { width:100%; height:100%; object-fit:cover; border-radius:4px; }
		.nl-meta-gallery .thumb .remove { position:absolute; top:2px; right:2px; background:#d63638; color:#fff; border:none; border-radius:50%; width:20px; height:20px; cursor:pointer; font-size:12px; line-height:20px; text-align:center; }
		.nl-meta-gallery-placeholder { color:#757575; font-style:italic; }
	</style>

	<div class="nl-meta-grid">
        <div class="nl-meta-field full">
            <label><?php _e( 'Title (English)', 'neonlighthk' ); ?></label>
            <input type="text" name="_nl_workshop_title_en" value="<?php echo esc_attr( $fields['_nl_workshop_title_en'] ); ?>" placeholder="Workshop Title" />
        </div>
        <div class="nl-meta-field full">
            <label><?php _e( 'Title (繁體中文)', 'neonlighthk' ); ?></label>
            <input type="text" name="_nl_workshop_title_zh" value="<?php echo esc_attr( $fields['_nl_workshop_title_zh'] ); ?>" placeholder="工作坊名稱" />
        </div>
        <div class="nl-meta-field full">
            <label><?php _e( 'Title (简体中文)', 'neonlighthk' ); ?></label>
            <input type="text" name="_nl_workshop_title_cn" value="<?php echo esc_attr( $fields['_nl_workshop_title_cn'] ); ?>" placeholder="工作坊名称" />
        </div>
        <div class="nl-meta-field">
            <label><?php _e( 'Min Group Size', 'neonlighthk' ); ?></label>
            <input type="number" name="_nl_workshop_min_group" value="<?php echo esc_attr( $fields['_nl_workshop_min_group'] ); ?>" />
        </div>
        <div class="nl-meta-field">
            <label><?php _e( 'Max Group Size', 'neonlighthk' ); ?></label>
            <input type="number" name="_nl_workshop_max_group" value="<?php echo esc_attr( $fields['_nl_workshop_max_group'] ); ?>" />
        </div>
        <div class="nl-meta-field full">
            <label><?php _e( 'Description (English)', 'neonlighthk' ); ?></label>
            <textarea name="_nl_workshop_desc_en"><?php echo esc_textarea( $fields['_nl_workshop_desc_en'] ); ?></textarea>
        </div>
        <div class="nl-meta-field full">
            <label><?php _e( 'Description (繁體中文)', 'neonlighthk' ); ?></label>
            <textarea name="_nl_workshop_desc_zh"><?php echo esc_textarea( $fields['_nl_workshop_desc_zh'] ); ?></textarea>
        </div>
        <div class="nl-meta-field full">
            <label><?php _e( 'Description (简体中文)', 'neonlighthk' ); ?></label>
            <textarea name="_nl_workshop_desc_cn"><?php echo esc_textarea( $fields['_nl_workshop_desc_cn'] ); ?></textarea>
        </div>
        <div class="nl-meta-field full">
            <label><?php _e( 'Items / Packages — name + price per person', 'neonlighthk' ); ?></label>
            <div id="nl-items-repeater">
                <?php
                $items = $fields['_nl_workshop_items'];
                $items = is_array($items) ? $items : [];
                if (empty($items)) { $items = [['name'=>'','name_zh'=>'','name_cn'=>'','price'=>'']]; }
                foreach ($items as $i => $item):
                ?>
                <div class="nl-item-row" style="display:grid;grid-template-columns:1.5fr 1.5fr 1.5fr 1fr auto;gap:8px;margin-bottom:8px;align-items:center;">
                    <input type="text" name="_nl_workshop_items[<?php echo $i; ?>][name]" value="<?php echo esc_attr($item['name'] ?? ''); ?>" placeholder="Name (EN)" />
                    <input type="text" name="_nl_workshop_items[<?php echo $i; ?>][name_zh]" value="<?php echo esc_attr($item['name_zh'] ?? ''); ?>" placeholder="名稱 (繁體)" />
                    <input type="text" name="_nl_workshop_items[<?php echo $i; ?>][name_cn]" value="<?php echo esc_attr($item['name_cn'] ?? ''); ?>" placeholder="名称 (简体)" />
                    <input type="number" name="_nl_workshop_items[<?php echo $i; ?>][price]" value="<?php echo esc_attr($item['price'] ?? ''); ?>" placeholder="HK$" />
                    <button type="button" class="button nl-item-remove">&times;</button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button" id="nl-item-add">+ <?php _e('Add Item','neonlighthk'); ?></button>
            <script>
            (function(){
                var container = document.getElementById('nl-items-repeater');
                var addBtn    = document.getElementById('nl-item-add');
                function reindex() {
                    container.querySelectorAll('.nl-item-row').forEach(function(row, idx){
                        row.querySelectorAll('input').forEach(function(inp){
                            var name = inp.getAttribute('name');
                            if (name) inp.setAttribute('name', name.replace(/\[\d+\]/, '['+idx+']'));
                        });
                    });
                }
                addBtn.addEventListener('click', function(){
                    var rows = container.querySelectorAll('.nl-item-row');
                    var idx  = rows.length;
                    var tpl  = rows[0] ? rows[0].outerHTML : '';
                    if (!tpl) return;
                    var div  = document.createElement('div');
                    div.innerHTML = tpl;
                    var newRow = div.firstElementChild;
                    newRow.querySelectorAll('input').forEach(function(inp){ inp.value = ''; });
                    newRow.querySelectorAll('input').forEach(function(inp){
                        var name = inp.getAttribute('name');
                        if (name) inp.setAttribute('name', name.replace(/\[\d+\]/, '['+idx+']'));
                    });
                    container.appendChild(newRow);
                });
                container.addEventListener('click', function(e){
                    if (e.target.classList.contains('nl-item-remove')) {
                        var row = e.target.closest('.nl-item-row');
                        if (container.querySelectorAll('.nl-item-row').length > 1) {
                            row.remove();
                            reindex();
                        } else {
                            row.querySelectorAll('input').forEach(function(inp){ inp.value = ''; });
                        }
                    }
                });
            })();
            </script>
        </div>
        <div class="nl-meta-field full">
            <label><?php _e( 'External Booking URL (optional)', 'neonlighthk' ); ?></label>
			<input type="url" name="_nl_workshop_booking_url" value="<?php echo esc_attr( $fields['_nl_workshop_booking_url'] ); ?>" placeholder="https://..." />
		</div>
		<div class="nl-meta-field full">
			<label><?php _e( 'Cover Photo', 'neonlighthk' ); ?></label>
			<input type="hidden" name="_nl_workshop_cover" id="nl_workshop_cover_input" value="<?php echo esc_attr( $cover_id ); ?>" />
			<button type="button" class="button" id="nl_workshop_add_cover"><?php _e( 'Select Cover Photo', 'neonlighthk' ); ?></button>
			<div class="nl-meta-gallery" id="nl_workshop_cover_preview">
				<?php if ( $cover_url ) : ?>
					<div class="thumb" data-id="<?php echo esc_attr( $cover_id ); ?>">
						<img src="<?php echo esc_url( $cover_url ); ?>" alt="" />
						<button type="button" class="remove">&times;</button>
					</div>
				<?php else : ?>
					<span class="nl-meta-gallery-placeholder"><?php _e( 'No cover photo selected.', 'neonlighthk' ); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<div class="nl-meta-field full">
			<label><?php _e( 'Photo Gallery', 'neonlighthk' ); ?></label>
			<input type="hidden" name="_nl_workshop_gallery" id="nl_workshop_gallery_input" value="<?php echo esc_attr( implode( ',', $gallery_ids ) ); ?>" />
			<button type="button" class="button" id="nl_workshop_add_images"><?php _e( 'Add Images', 'neonlighthk' ); ?></button>
			<div class="nl-meta-gallery" id="nl_workshop_gallery_preview">
				<?php if ( empty( $gallery_ids ) ) : ?>
					<span class="nl-meta-gallery-placeholder"><?php _e( 'No images uploaded yet.', 'neonlighthk' ); ?></span>
				<?php else : ?>
					<?php foreach ( $gallery_ids as $aid ) : ?>
						<div class="thumb" data-id="<?php echo esc_attr( $aid ); ?>">
							<?php echo wp_get_attachment_image( $aid, 'thumbnail' ); ?>
							<button type="button" class="remove">&times;</button>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<script>
	(function($){
		var frame;
		$('#nl_workshop_add_images').on('click', function(e){
			e.preventDefault();
			if (frame) { frame.open(); return; }
			frame = wp.media({
				title: '<?php echo esc_js( __( "Select Workshop Photos", "neonlighthk" ) ); ?>',
				button: { text: '<?php echo esc_js( __( "Add to Gallery", "neonlighthk" ) ); ?>' },
				multiple: true,
				library: { type: 'image' }
			});
			frame.on('select', function(){
				var attachments = frame.state().get('selection').map(function(att){ return att.toJSON(); });
				var $preview = $('#nl_workshop_gallery_preview');
				var ids = [];
				$preview.find('.nl-meta-gallery-placeholder').remove();
				attachments.forEach(function(att){
					if (!$preview.find('.thumb[data-id="'+att.id+'"]').length) {
						$preview.append('<div class="thumb" data-id="'+att.id+'"><img src="'+att.sizes.thumbnail.url+'" /><button type="button" class="remove">&times;</button></div>');
					}
					ids.push(att.id);
				});
				// merge with existing
				$preview.find('.thumb').each(function(){ ids.push($(this).data('id')); });
				$('#nl_workshop_gallery_input').val(ids.join(','));
			});
			frame.open();
		});
		$('#nl_workshop_gallery_preview').on('click', '.remove', function(e){
			e.preventDefault();
			$(this).closest('.thumb').remove();
			var ids = [];
			$('#nl_workshop_gallery_preview .thumb').each(function(){ ids.push($(this).data('id')); });
			$('#nl_workshop_gallery_input').val(ids.join(','));
		});

		// Cover photo — single select
		var coverFrame;
		$('#nl_workshop_add_cover').on('click', function(e){
			e.preventDefault();
			if (coverFrame) { coverFrame.open(); return; }
			coverFrame = wp.media({
				title: '<?php echo esc_js( __( "Select Cover Photo", "neonlighthk" ) ); ?>',
				button: { text: '<?php echo esc_js( __( "Set as Cover", "neonlighthk" ) ); ?>' },
				multiple: false,
				library: { type: 'image' }
			});
			coverFrame.on('select', function(){
				var att = coverFrame.state().get('selection').first().toJSON();
				var $preview = $('#nl_workshop_cover_preview');
				$preview.find('.nl-meta-gallery-placeholder').remove();
				$preview.html('<div class="thumb" data-id="'+att.id+'"><img src="'+att.sizes.thumbnail.url+'" /><button type="button" class="remove">&times;</button></div>');
				$('#nl_workshop_cover_input').val(att.id);
			});
			coverFrame.open();
		});
		$('#nl_workshop_cover_preview').on('click', '.remove', function(e){
			e.preventDefault();
			$(this).closest('.thumb').remove();
			$('#nl_workshop_cover_preview').html('<span class="nl-meta-gallery-placeholder"><?php echo esc_js( __( 'No cover photo selected.', 'neonlighthk' ) ); ?></span>');
			$('#nl_workshop_cover_input').val('');
		});
	})(jQuery);
	</script>
	<?php
}

/* ---------- Save meta box ---------- */
add_action( 'save_post_nl_workshop', function ( $post_id ) {
	if ( ! isset( $_POST['nl_workshop_meta_nonce'] ) || ! wp_verify_nonce( $_POST['nl_workshop_meta_nonce'], 'nl_workshop_meta_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

    $text_fields = [
        '_nl_workshop_title_en',
        '_nl_workshop_title_zh',
        '_nl_workshop_title_cn',
        '_nl_workshop_desc_en',
        '_nl_workshop_desc_zh',
        '_nl_workshop_desc_cn',
        '_nl_workshop_booking_url',
    ];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, wp_kses_post( $_POST[ $key ] ) );
		}
	}

    // Sync post_title from trilingual title fields (fallback chain)
    $new_title = '';
    if ( ! empty( $_POST['_nl_workshop_title_zh'] ) ) {
        $new_title = sanitize_text_field( $_POST['_nl_workshop_title_zh'] );
    } elseif ( ! empty( $_POST['_nl_workshop_title_en'] ) ) {
        $new_title = sanitize_text_field( $_POST['_nl_workshop_title_en'] );
    } elseif ( ! empty( $_POST['_nl_workshop_title_cn'] ) ) {
        $new_title = sanitize_text_field( $_POST['_nl_workshop_title_cn'] );
    }
    if ( $new_title ) {
        wp_update_post( [
            'ID'         => $post_id,
            'post_title' => $new_title,
        ] );
    }

    $num_fields = [ '_nl_workshop_min_group', '_nl_workshop_max_group' ];
	foreach ( $num_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, floatval( $_POST[ $key ] ) );
		}
	}

    // Gallery — array of attachment IDs
    if ( isset( $_POST['_nl_workshop_gallery'] ) ) {
        $raw = sanitize_text_field( $_POST['_nl_workshop_gallery'] );
        $ids = array_filter( array_map( 'intval', explode( ',', $raw ) ) );
        update_post_meta( $post_id, '_nl_workshop_gallery', $ids );
    } else {
        update_post_meta( $post_id, '_nl_workshop_gallery', [] );
    }

    // Items / Packages repeater
    if ( isset( $_POST['_nl_workshop_items'] ) && is_array( $_POST['_nl_workshop_items'] ) ) {
        $items = [];
        foreach ( $_POST['_nl_workshop_items'] as $item ) {
            $name    = isset( $item['name'] )    ? sanitize_text_field( $item['name'] ) : '';
            $name_zh = isset( $item['name_zh'] ) ? sanitize_text_field( $item['name_zh'] ) : '';
            $name_cn = isset( $item['name_cn'] ) ? sanitize_text_field( $item['name_cn'] ) : '';
            $price   = isset( $item['price'] )   ? floatval( $item['price'] ) : 0;
            if ( $name || $name_zh || $name_cn ) {
                $items[] = [
                    'name'    => $name,
                    'name_zh' => $name_zh,
                    'name_cn' => $name_cn,
                    'price'   => $price,
                ];
            }
        }
        update_post_meta( $post_id, '_nl_workshop_items', $items );
    }

    // Cover photo — single attachment ID
    if ( isset( $_POST['_nl_workshop_cover'] ) ) {
		$cover_id = intval( $_POST['_nl_workshop_cover'] );
		if ( $cover_id > 0 ) {
			update_post_meta( $post_id, '_nl_workshop_cover', $cover_id );
		} else {
			delete_post_meta( $post_id, '_nl_workshop_cover' );
		}
	}
} );

/* ---------- Admin list columns ---------- */
add_filter( 'manage_nl_workshop_posts_columns', function ( $columns ) {
	return $columns;
} );

/* ---------- Frontend trilingual title helper ---------- */
if ( ! function_exists( 'nl_get_workshop_trilingual_title' ) ) {
	function nl_get_workshop_trilingual_title( $post_id = 0 ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		$lang    = nl_lang();
		$meta_keys = [
			'en' => '_nl_workshop_title_en',
			'zh' => '_nl_workshop_title_zh',
			'cn' => '_nl_workshop_title_cn',
		];
		$key = $meta_keys[ $lang ] ?? '_nl_workshop_title_zh';
		$val = get_post_meta( $post_id, $key, true );
		if ( $val ) {
			return $val;
		}
		// Fallback chain: zh -> en -> cn -> post_title
		foreach ( [ '_nl_workshop_title_zh', '_nl_workshop_title_en', '_nl_workshop_title_cn' ] as $fallback ) {
			$val = get_post_meta( $post_id, $fallback, true );
			if ( $val ) return $val;
		}
		return get_the_title( $post_id );
	}
}
