<?php
/**
 * Theme Functions
 * @package NeonLightHK
 */

// Load translations (must be global)
require_once get_template_directory() . '/inc/translations.php';

// Load custom post types
require_once get_template_directory() . '/inc/cpt.php';

// Admin language picker for all posts, CPTs, and WooCommerce products
require_once get_template_directory() . '/inc/admin-lang-picker.php';

// Workshop admin meta boxes (price, duration, description, gallery, etc.)
require_once get_template_directory() . '/inc/workshop-meta.php';

// Register ?lang= query var so WordPress doesn't 404 on ?lang=zh / ?lang=en / ?lang=cn
add_filter('query_vars', function($vars) {
    $vars[] = 'lang';
    $vars[] = 'contact_submitted';
    $vars[] = 'contact_error';
    return $vars;
});

// Fix front-page routing when query vars (e.g. ?lang=en) cause WordPress to 404
add_action('template_redirect', function() {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if ($uri === '/' || $uri === '' || $uri === '/index.php') {
        // Force 200 OK and front-page flags regardless of what WP thinks
        status_header(200);
        global $wp_query;
        $wp_query->is_404 = false;
        $wp_query->is_home = false;
        $wp_query->is_front_page = true;
    }
}, 1);

// Fix workshop-detail routing when query vars cause WordPress to 404
add_action('template_redirect', function() {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $path = rtrim($path, '/');
    if ($path === '/workshop-detail') {
        // Force 200 OK — virtual route handled by template_include
        status_header(200);
        global $wp_query;
        $wp_query->is_404 = false;
        $wp_query->is_page = true;
    }
}, 1);

// Force custom templates with high priority to override WooCommerce
add_filter('template_include', function($template) {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $path = rtrim($path, '/');
    
    // Front page
    if ($path === '' || $path === '/' || $path === '/index.php') {
        $front = get_template_directory() . '/front-page.php';
        if (file_exists($front)) return $front;
    }
    
    // Page templates by URI path (bypasses CPT archive conflicts)
    $map = [
        '/about-lookbook'       => 'page-about-lookbook.php',
        '/workshop'       => 'page-workshop.php',
        '/workshop-detail' => 'page-workshop-detail.php',
        '/neon-services'  => 'page-neon-services.php',
        '/projects'       => 'page-projects.php',
        '/neon-products'  => 'page-neon-products.php',
        '/balloon'        => 'page-balloon.php',
        '/hanfu'          => 'page-hanfu.php',
    ];
    foreach ($map as $route => $tpl) {
        if ($path === $route) {
            $full = get_template_directory() . '/' . $tpl;
            if (file_exists($full)) return $full;
        }
    }
    
    // Fallback to page IDs for edge cases
    if (is_page(96) || is_page('about-lookbook')) {
        $t = get_template_directory() . '/page-about.php';
        if (file_exists($t)) return $t;
    }
    if (is_page(45) || is_page('workshop')) {
        $t = get_template_directory() . '/page-workshop.php';
        if (file_exists($t)) return $t;
    }
    if (is_page(95) || is_page('neon-services')) {
        $t = get_template_directory() . '/page-neon-services.php';
        if (file_exists($t)) return $t;
    }
    if (is_page(31) || is_page('projects')) {
        $t = get_template_directory() . '/page-projects.php';
        if (file_exists($t)) return $t;
    }
    if (is_page(171) || is_page('neon-products')) {
        $t = get_template_directory() . '/page-neon-products.php';
        if (file_exists($t)) return $t;
    }
    if (is_page(12) || is_page('balloon')) {
        $t = get_template_directory() . '/page-balloon.php';
        if (file_exists($t)) return $t;
    }
    if (is_page(138) || is_page('hanfu')) {
        $t = get_template_directory() . '/page-hanfu.php';
        if (file_exists($t)) return $t;
    }
    return $template;
}, 999);

// Disable canonical redirect for plain page_id permalinks so pages load correctly
remove_filter('template_redirect', 'redirect_canonical');

// Fix site URL to match the request host (localhost vs WAN IP)
add_filter('option_home', 'nl_dynamic_home_url', 99);
add_filter('option_siteurl', 'nl_dynamic_home_url', 99);
function nl_dynamic_home_url($url) {
    if (!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== 'localhost') {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        // HTTP_HOST may already include the port — only add port if it doesn't
        $host = $_SERVER['HTTP_HOST'];
        $has_port = strpos($host, ':') !== false;
        $port = (!$has_port && !empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
        $new_url = $proto . '://' . $host . $port;
        // Only replace the domain part, preserve the path
        return preg_replace('#^https?://[^/]+#', $new_url, $url);
    }
    return $url;
}

// Also fix stylesheet URI
add_filter('stylesheet_uri', function($uri) {
    if (!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== 'localhost') {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $has_port = strpos($host, ':') !== false;
        $port = (!$has_port && !empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
        $new_base = $proto . '://' . $host . $port;
        return preg_replace('#^https?://[^/]+#', $new_base, $uri);
    }
    return $uri;
}, 99);

// Fix template directory URI
add_filter('template_directory_uri', function($uri) {
    if (!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== 'localhost') {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $has_port = strpos($host, ':') !== false;
        $port = (!$has_port && !empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
        $new_base = $proto . '://' . $host . $port;
        return preg_replace('#^https?://[^/]+#', $new_base, $uri);
    }
    return $uri;
}, 99);

// Fix any content URLs in the page output (images, links, etc)
add_action('template_redirect', function() {
    if (!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== 'localhost' && !is_admin()) {
        ob_start(function($buffer) {
            $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $has_port = strpos($host, ':') !== false;
            $port = (!$has_port && !empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? ':' . $_SERVER['SERVER_PORT'] : '';
            $new_base = $proto . '://' . $host . $port;
			// Replace localhost:8080 with actual host — preserve original quote char
			$buffer = preg_replace('#(href|src)=(["\'])https?://localhost:8080/#', '$1=$2' . $new_base . '/', $buffer);
			$buffer = preg_replace('#(href|src)=(["\'])https?://localhost/#', '$1=$2' . $new_base . '/', $buffer);
            return $buffer;
        });
    }
});

/* ===== RENAME "POSTS" TO "PROJECTS" IN WP ADMIN ===== */
add_action('admin_menu', function() {
    global $menu;
    global $submenu;
    foreach ($menu as &$item) {
        if (isset($item[0]) && $item[0] === 'Posts') {
            $item[0] = 'Projects';
            $item[6] = 'dashicons-format-gallery';
        }
    }
    if (isset($submenu['edit.php'])) {
        foreach ($submenu['edit.php'] as &$sub) {
            $sub[0] = str_replace(['Posts', 'Post'], ['Projects', 'Project'], $sub[0]);
        }
    }
}, 999);

add_action('init', function() {
    global $wp_post_types;
    if (isset($wp_post_types['post'])) {
        $labels = &$wp_post_types['post']->labels;
        $labels->name               = 'Projects';
        $labels->singular_name      = 'Project';
        $labels->add_new            = 'Add New';
        $labels->add_new_item       = 'Add New Project';
        $labels->edit_item          = 'Edit Project';
        $labels->new_item           = 'New Project';
        $labels->view_item          = 'View Project';
        $labels->search_items       = 'Search Projects';
        $labels->not_found          = 'No projects found';
        $labels->not_found_in_trash = 'No projects found in Trash';
        $labels->all_items          = 'All Projects';
        $labels->menu_name          = 'Projects';
        $labels->name_admin_bar     = 'Project';
    }
}, 999);

// Theme setup
add_action( 'after_setup_theme', function() {
	// Title tag support
	add_theme_support( 'title-tag' );

	// Post thumbnails
	add_theme_support( 'post-thumbnails' );

	// HTML5 markup
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// WooCommerce support
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );

	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Register menus
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'neonlighthk' ),
		'footer'  => __( 'Footer Menu', 'neonlighthk' ),
	) );
} );

// Enqueue styles and scripts
add_action( 'wp_enqueue_scripts', function() {
	// Theme stylesheet
	wp_enqueue_style(
		'neonlighthk-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);

	// Google Fonts
	wp_enqueue_style(
		'neonlighthk-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Noto+Sans+TC:wght@300;400;500;700&display=swap',
		array(),
		null
	);
} );

// WooCommerce product per page
add_filter( 'loop_shop_per_page', function( $cols ) {
	return 12;
}, 20 );

// Remove default WooCommerce CSS
add_filter( 'woocommerce_enqueue_styles', function( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );
	return $enqueue_styles;
} );

// WooCommerce body class
add_filter( 'body_class', function( $classes ) {
	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		$classes[] = 'nl-woocommerce';
	}
	return $classes;
} );
// ===== BOOKING CUSTOM POST TYPE =====
add_action('init', function() {
	register_post_type('nl_booking', [
		'label'              => 'Workshop Bookings',
		'labels'             => [
			'name'          => 'Workshop Bookings',
			'singular_name' => 'Booking',
			'all_items'     => 'All Bookings',
			'edit_item'     => 'View Booking',
			'view_item'     => 'View Booking',
		],
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => ['title'],
		'menu_icon'          => 'dashicons-calendar-alt',
	]);
});

// ===== BOOKING ADMIN COLUMNS =====
add_filter('manage_nl_booking_posts_columns', function($columns) {
	unset($columns['date']);
	$columns['phone']       = 'Phone';
	$columns['themes']      = 'Themes';
	$columns['best_time']   = 'Time Slot';
	$columns['group_prefs'] = 'Group';
	$columns['status']      = 'Status';
	$columns['date']        = 'Date';
	return $columns;
});

add_action('manage_nl_booking_posts_custom_column', function($column, $post_id) {
	switch ($column) {
		case 'phone':
			echo esc_html(get_post_meta($post_id, '_nl_phone', true));
			break;
		case 'themes':
			echo esc_html(get_post_meta($post_id, '_nl_themes', true));
			break;
		case 'best_time':
			echo esc_html(get_post_meta($post_id, '_nl_best_time', true));
			break;
		case 'group_prefs':
			echo esc_html(get_post_meta($post_id, '_nl_group_prefs', true));
			break;
		case 'status':
			echo esc_html(get_post_meta($post_id, '_nl_booking_status', true) ?: get_post_meta($post_id, '_nl_status', true) ?: get_post_status($post_id));
			break;
	}
}, 10, 2);

// ===== BOOKING META BOX =====
add_action('add_meta_boxes', function() {
	add_meta_box(
		'nl_booking_details',
		'Booking Details',
		function($post) {
			$fields = [
				'First Name'  => '_nl_first_name',
				'Last Name'   => '_nl_last_name',
				'Email'       => '_nl_email',
				'Phone'       => '_nl_phone',
				'Age Range'   => '_nl_age_range',
				'Themes'      => '_nl_themes',
				'Best Time'   => '_nl_best_time',
				'Group Prefs' => '_nl_group_prefs',
				'Workshop'    => '_nl_workshop_title',
				'Price'       => '_nl_price',
				'Location'    => '_nl_location',
				'Booking Date'=> '_nl_booking_date',
				'Booking Time'=> '_nl_booking_time',
				'Group Size'  => '_nl_group_size',
				'Remarks'     => '_nl_remarks',
			];
			echo '<table class="form-table">';
			foreach ($fields as $label => $key) {
				$value = get_post_meta($post->ID, $key, true);
				if ($value === '') continue;
				echo '<tr><th>' . esc_html($label) . '</th><td>' . esc_html($value) . '</td></tr>';
			}
			echo '</table>';
		},
		'nl_booking',
		'normal',
		'high'
	);
});

// ===== LOOKBOOK GALLERY META BOX (WP Media Library repeater) =====
add_action('add_meta_boxes', function () {
	add_meta_box(
		'nl_lookbook_gallery',
		__('Lookbook Gallery', 'neonlighthk'),
		function ($post) {
			wp_nonce_field('nl_save_lookbook_gallery', 'nl_lookbook_gallery_nonce');
			$gallery = get_post_meta($post->ID, '_nl_lookbook_gallery', true);
			$images  = is_array($gallery) ? $gallery : [];
		?>
			<div id="nl-gallery-wrap">
				<ul id="nl-gallery-list" style="display:flex;flex-wrap:wrap;gap:10px;list-style:none;padding:0;margin:0;">
					<?php foreach ($images as $img_id) : ?>
						<li data-id="<?php echo esc_attr($img_id); ?>" style="position:relative;">
							<?php echo wp_get_attachment_image($img_id, 'thumbnail'); ?>
							<a href="#" class="nl-remove-img" style="position:absolute;top:2px;right:2px;background:#c00;color:#fff;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:12px;">×</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<input type="hidden" name="nl_lookbook_gallery" id="nl_lookbook_gallery_input" value="<?php echo esc_attr(implode(',', $images)); ?>">
				<button type="button" class="button" id="nl-add-gallery-img"><?php _e('Add Images', 'neonlighthk'); ?></button>
			</div>
			<style>
			#nl-gallery-list li { cursor: move; }
			#nl-gallery-list li img { border-radius: 4px; }
			</style>
			<script>
			jQuery(function($) {
				var frame;
				$('#nl-add-gallery-img').on('click', function(e) {
					e.preventDefault();
					if (frame) { frame.open(); return; }
					frame = wp.media({
						title: '<?php _e("Select Images", "neonlighthk"); ?>',
						button: { text: '<?php _e("Add to Gallery", "neonlighthk"); ?>' },
						multiple: true,
						library: { type: 'image' }
					});
					frame.on('select', function() {
						var attachments = frame.state().get('selection').map(function(a) { return a.toJSON(); });
						attachments.forEach(function(att) {
							$('#nl-gallery-list').append('<li data-id="' + att.id + '"><img src="' + att.sizes.thumbnail.url + '" width="150" height="150"><a href="#" class="nl-remove-img" style="position:absolute;top:2px;right:2px;background:#c00;color:#fff;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:12px;">×</a></li>');
						});
						nl_update_gallery_input();
					});
					frame.open();
				});
				$('#nl-gallery-list').on('click', '.nl-remove-img', function(e) {
					e.preventDefault();
					$(this).closest('li').remove();
					nl_update_gallery_input();
				});
				$('#nl-gallery-list').sortable({
					stop: function() { nl_update_gallery_input(); }
				});
				function nl_update_gallery_input() {
					var ids = [];
					$('#nl-gallery-list li').each(function() { ids.push($(this).data('id')); });
					$('#nl_lookbook_gallery_input').val(ids.join(','));
				}
			});
			</script>
		<?php
		},
		'page',
		'normal',
		'high'
	);
});

add_action('save_post', function ($post_id) {
	if (
		!isset($_POST['nl_lookbook_gallery_nonce']) ||
		!wp_verify_nonce($_POST['nl_lookbook_gallery_nonce'], 'nl_save_lookbook_gallery') ||
		defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||
		!current_user_can('edit_post', $post_id)
	) {
		return;
	}
	if (isset($_POST['nl_lookbook_gallery'])) {
		$raw = sanitize_text_field($_POST['nl_lookbook_gallery']);
		$ids = array_filter(array_map('intval', explode(',', $raw)));
		update_post_meta($post_id, '_nl_lookbook_gallery', $ids);
	} else {
		delete_post_meta($post_id, '_nl_lookbook_gallery');
	}
});

// ===== WORKSHOP PRODUCT HELPER =====
function nl_get_or_create_workshop_product($workshop_id, $title, $price) {
	$existing = get_posts([
		'post_type'      => 'product',
		'meta_key'       => '_nl_workshop_product_id',
		'meta_value'     => $workshop_id,
		'posts_per_page' => 1,
		'post_status'    => 'any',
	]);
	if (!empty($existing)) {
		return $existing[0]->ID;
	}
	$product = new WC_Product_Simple();
	$product->set_name($title);
	$product->set_regular_price($price);
	$product->set_price($price);
	$product->set_status('publish');
	$product->set_catalog_visibility('hidden');
	$product->set_virtual(true);
	$product->set_sold_individually(false);
	$product->update_meta_data('_nl_workshop_product_id', $workshop_id);
	return $product->save();
}


// ===== ABOUT PAGE LANG SWITCHING =====
add_action('wp_head', function() {
    if (!is_page()) return;
    $page = get_post();
    if (!$page || $page->post_name !== 'about') return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var lang = 'en';
        var m = location.search.match(/[?&]lang=(zh|cn|en)/);
        if (m) lang = m[1];
        var isZh = lang === 'zh' || lang === 'cn';
        // Show/hide EN content
        document.querySelectorAll('.nl-intro-en, .nl-heading-en, .nl-programs-en, .nl-desc-en, .nl-upcycle-en, .nl-cta-en').forEach(function(el) {
            el.style.display = isZh ? 'none' : '';
        });
        // Show/hide ZH content
        document.querySelectorAll('.nl-intro-zh, .nl-heading-zh, .nl-programs-zh, .nl-desc-zh, .nl-upcycle-zh, .nl-cta-zh').forEach(function(el) {
            el.style.display = lang === 'zh' ? '' : 'none';
        });
        // Show/hide CN content
        document.querySelectorAll('.nl-intro-cn, .nl-heading-cn, .nl-programs-cn, .nl-desc-cn, .nl-upcycle-cn, .nl-cta-cn').forEach(function(el) {
            el.style.display = lang === 'cn' ? '' : 'none';
        });
    });
    </script>
    <?php
});

// ===== DOCUMENT TITLE TRANSLATION =====
add_filter('document_title_parts', 'nl_translate_document_title');
function nl_translate_document_title($parts) {
    $lang = nl_lang();
    if (is_shop() || is_page_template('page-shop.php')) {
        $parts['title'] = nl_t('shop_title');
    }
    if (is_page_template('page-rental.php')) {
        $parts['title'] = nl_t('balloon_title');
    }
    if (is_product()) {
        $parts['title'] = get_the_title() . ' – ' . nl_t('shop_title');
    }
    if (is_page_template('page-about-lookbook.php')) {
        $parts['title'] = $lang === 'en' ? 'Lookbook & About Us' : ($lang === 'cn' ? '范例 & 关于我们' : '範例 & 關於我們');
    }
    if (is_page_template('page-about.php')) {
        $parts['title'] = $lang === 'en' ? 'About Us' : ($lang === 'cn' ? '关于我们' : '關於我們');
    }
    if (is_page_template('page-projects.php')) {
        $parts['title'] = $lang === 'en' ? 'Projects' : ($lang === 'cn' ? '活动' : '活動');
    }
    return $parts;
}
