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

/* ---------- Render meta box ---------- */
function nl_workshop_meta_box_callback( $post ) {
	wp_nonce_field( 'nl_workshop_meta_save', 'nl_workshop_meta_nonce' );

	$fields = [
		'_nl_workshop_price'           => get_post_meta( $post->ID, '_nl_workshop_price', true ),
		'_nl_workshop_duration'         => get_post_meta( $post->ID, '_nl_workshop_duration', true ),
		'_nl_workshop_size'             => get_post_meta( $post->ID, '_nl_workshop_size', true ),
		'_nl_workshop_size_en'          => get_post_meta( $post->ID, '_nl_workshop_size_en', true ),
		'_nl_workshop_desc_en'          => get_post_meta( $post->ID, '_nl_workshop_desc_en', true ),
		'_nl_workshop_desc_zh'          => get_post_meta( $post->ID, '_nl_workshop_desc_zh', true ),
		'_nl_workshop_desc_cn'          => get_post_meta( $post->ID, '_nl_workshop_desc_cn', true ),
		'_nl_workshop_gallery'          => get_post_meta( $post->ID, '_nl_workshop_gallery', true ),
		'_nl_workshop_includes_en'      => get_post_meta( $post->ID, '_nl_workshop_includes_en', true ),
		'_nl_workshop_includes_zh'      => get_post_meta( $post->ID, '_nl_workshop_includes_zh', true ),
		'_nl_workshop_highlights_en'    => get_post_meta( $post->ID, '_nl_workshop_highlights_en', true ),
		'_nl_workshop_highlights_zh'    => get_post_meta( $post->ID, '_nl_workshop_highlights_zh', true ),
		'_nl_workshop_location'         => get_post_meta( $post->ID, '_nl_workshop_location', true ),
		'_nl_workshop_location_en'      => get_post_meta( $post->ID, '_nl_workshop_location_en', true ),
		'_nl_workshop_meeting_point'    => get_post_meta( $post->ID, '_nl_workshop_meeting_point', true ),
		'_nl_workshop_meeting_point_en' => get_post_meta( $post->ID, '_nl_workshop_meeting_point_en', true ),
		'_nl_workshop_max_group'        => get_post_meta( $post->ID, '_nl_workshop_max_group', true ),
		'_nl_workshop_min_age'          => get_post_meta( $post->ID, '_nl_workshop_min_age', true ),
		'_nl_workshop_booking_url'      => get_post_meta( $post->ID, '_nl_workshop_booking_url', true ),
	];

	$gallery_ids = is_array( $fields['_nl_workshop_gallery'] ) ? $fields['_nl_workshop_gallery'] : [];
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
		<div class="nl-meta-field">
			<label><?php _e( 'Price (HK$)', 'neonlighthk' ); ?></label>
			<input type="number" name="_nl_workshop_price" value="<?php echo esc_attr( $fields['_nl_workshop_price'] ); ?>" />
		</div>
		<div class="nl-meta-field">
			<label><?php _e( 'Duration', 'neonlighthk' ); ?></label>
			<input type="text" name="_nl_workshop_duration" value="<?php echo esc_attr( $fields['_nl_workshop_duration'] ); ?>" placeholder="e.g. 2 hours" />
		</div>
		<div class="nl-meta-field">
			<label><?php _e( 'Size / Height (中文)', 'neonlighthk' ); ?></label>
			<input type="text" name="_nl_workshop_size" value="<?php echo esc_attr( $fields['_nl_workshop_size'] ); ?>" placeholder="e.g. 8cm height" />
		</div>
		<div class="nl-meta-field">
			<label><?php _e( 'Size / Height (EN)', 'neonlighthk' ); ?></label>
			<input type="text" name="_nl_workshop_size_en" value="<?php echo esc_attr( $fields['_nl_workshop_size_en'] ); ?>" placeholder="e.g. 8cm height" />
		</div>
		<div class="nl-meta-field">
			<label><?php _e( 'Max Group Size', 'neonlighthk' ); ?></label>
			<input type="number" name="_nl_workshop_max_group" value="<?php echo esc_attr( $fields['_nl_workshop_max_group'] ); ?>" />
		</div>
		<div class="nl-meta-field">
			<label><?php _e( 'Min Age', 'neonlighthk' ); ?></label>
			<input type="number" name="_nl_workshop_min_age" value="<?php echo esc_attr( $fields['_nl_workshop_min_age'] ); ?>" />
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
			<label><?php _e( 'Includes List (EN) — one per line', 'neonlighthk' ); ?></label>
			<textarea name="_nl_workshop_includes_en" placeholder="EL wire materials&#10;Battery pack&#10;Instruction booklet"><?php echo esc_textarea( $fields['_nl_workshop_includes_en'] ); ?></textarea>
		</div>
		<div class="nl-meta-field full">
			<label><?php _e( 'Includes List (中文) — one per line', 'neonlighthk' ); ?></label>
			<textarea name="_nl_workshop_includes_zh" placeholder="冷光線材料&#10;電池盒&#10;說明書"><?php echo esc_textarea( $fields['_nl_workshop_includes_zh'] ); ?></textarea>
		</div>
		<div class="nl-meta-field full">
			<label><?php _e( 'Highlights (EN) — one per line', 'neonlighthk' ); ?></label>
			<textarea name="_nl_workshop_highlights_en" placeholder="Hands-on EL wire bending&#10;Take home your own neon sign&#10;No experience needed"><?php echo esc_textarea( $fields['_nl_workshop_highlights_en'] ); ?></textarea>
		</div>
		<div class="nl-meta-field full">
			<label><?php _e( 'Highlights (中文) — one per line', 'neonlighthk' ); ?></label>
			<textarea name="_nl_workshop_highlights_zh" placeholder="親手彎曲冷光線&#10;帶走自製霓虹燈牌&#10;無需經驗"><?php echo esc_textarea( $fields['_nl_workshop_highlights_zh'] ); ?></textarea>
		</div>
		<div class="nl-meta-field">
			<label><?php _e( 'Location (中文)', 'neonlighthk' ); ?></label>
			<input type="text" name="_nl_workshop_location" value="<?php echo esc_attr( $fields['_nl_workshop_location'] ); ?>" />
		</div>
		<div class="nl-meta-field">
			<label><?php _e( 'Location (EN)', 'neonlighthk' ); ?></label>
			<input type="text" name="_nl_workshop_location_en" value="<?php echo esc_attr( $fields['_nl_workshop_location_en'] ); ?>" />
		</div>
		<div class="nl-meta-field full">
			<label><?php _e( 'Meeting Point (中文)', 'neonlighthk' ); ?></label>
			<input type="text" name="_nl_workshop_meeting_point" value="<?php echo esc_attr( $fields['_nl_workshop_meeting_point'] ); ?>" />
		</div>
		<div class="nl-meta-field full">
			<label><?php _e( 'Meeting Point (EN)', 'neonlighthk' ); ?></label>
			<input type="text" name="_nl_workshop_meeting_point_en" value="<?php echo esc_attr( $fields['_nl_workshop_meeting_point_en'] ); ?>" />
		</div>
		<div class="nl-meta-field full">
			<label><?php _e( 'External Booking URL (optional)', 'neonlighthk' ); ?></label>
			<input type="url" name="_nl_workshop_booking_url" value="<?php echo esc_attr( $fields['_nl_workshop_booking_url'] ); ?>" placeholder="https://..." />
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
		'_nl_workshop_duration',
		'_nl_workshop_size',
		'_nl_workshop_size_en',
		'_nl_workshop_desc_en',
		'_nl_workshop_desc_zh',
		'_nl_workshop_desc_cn',
		'_nl_workshop_includes_en',
		'_nl_workshop_includes_zh',
		'_nl_workshop_highlights_en',
		'_nl_workshop_highlights_zh',
		'_nl_workshop_location',
		'_nl_workshop_location_en',
		'_nl_workshop_meeting_point',
		'_nl_workshop_meeting_point_en',
		'_nl_workshop_booking_url',
	];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, wp_kses_post( $_POST[ $key ] ) );
		}
	}

	$num_fields = [ '_nl_workshop_price', '_nl_workshop_max_group', '_nl_workshop_min_age' ];
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
} );

/* ---------- Admin list columns ---------- */
add_filter( 'manage_nl_workshop_posts_columns', function ( $columns ) {
	$cols = [];
	foreach ( $columns as $key => $label ) {
		$cols[ $key ] = $label;
		if ( $key === 'title' ) {
			$cols['price']    = __( 'Price', 'neonlighthk' );
			$cols['duration'] = __( 'Duration', 'neonlighthk' );
		}
	}
	return $cols;
} );

add_action( 'manage_nl_workshop_posts_custom_column', function ( $column, $post_id ) {
	switch ( $column ) {
		case 'price':
			echo esc_html( get_post_meta( $post_id, '_nl_workshop_price', true ) );
			break;
		case 'duration':
			echo esc_html( get_post_meta( $post_id, '_nl_workshop_duration', true ) );
			break;
	}
}, 10, 2 );
