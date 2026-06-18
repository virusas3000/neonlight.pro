<?php
/**
 * Template Name: Workshop Detail
 * @package NeonLightHK
 */
$raw_id = wp_unslash($_GET['id'] ?? '');
// WhatsApp sends decoded UTF-8; WP DB stores post_name as lowercase %-encoded slug.
// sanitize_text_field() strips %XX sequences — use sanitize_title() + strtolower instead.
$workshop_id = strtolower(sanitize_title($raw_id));
$lang = nl_lang();

/* ---------- Try to load from nl_workshop CPT first ---------- */
$workshop_post = null;
if ($workshop_id) {
    global $wpdb;
    // $wpdb->prepare() AND esc_sql() both treat % as printf placeholders —
    // they destroy %-encoded slugs (e.g. %e9 → hash). sanitize_title() never
    // outputs quotes, so direct string concat is safe here.
    $safe_id = str_replace("'", "''", $workshop_id);
    $pid = $wpdb->get_var(
        "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'nl_workshop' AND post_status = 'publish' AND LOWER(post_name) = LOWER('" . $safe_id . "') LIMIT 1"
    );
    if ($pid) {
        $workshop_post = get_post($pid);
    }
}

/* ---------- Build $ws array from CPT or hardcoded fallback ---------- */
if ($workshop_post) {
    $pid = $workshop_post->ID;
    $title     = $lang==='en' ? get_the_title($pid) : get_the_title($pid);
    $title_en  = get_post_meta($pid, '_nl_workshop_title_en', true) ?: get_the_title($pid);
    $title_zh  = get_the_title($pid);

    $gallery_ids = get_post_meta($pid, '_nl_workshop_gallery', true);
    $gallery_ids = is_array($gallery_ids) ? $gallery_ids : [];
    if (empty($gallery_ids) && has_post_thumbnail($pid)) {
        $gallery_ids = [get_post_thumbnail_id($pid)];
    }
    $gallery_urls = array_map(function($aid){ return wp_get_attachment_image_url($aid, 'large'); }, $gallery_ids);
    $gallery_urls = array_filter($gallery_urls);

    $ws = [
        'id'            => $workshop_post->post_name,
        'title'         => $title,
        'title_en'      => $title_en,
        'title_zh'      => $title_zh,
        'price'         => 0,
        'price_display' => '',
        'gallery'       => array_values($gallery_urls),
        'desc_en'       => get_post_meta($pid, '_nl_workshop_desc_en', true),
        'desc_zh'       => get_post_meta($pid, '_nl_workshop_desc_zh', true),
        'desc_cn'       => get_post_meta($pid, '_nl_workshop_desc_cn', true),
        'min_group'     => get_post_meta($pid, '_nl_workshop_min_group', true),
        'max_group'     => get_post_meta($pid, '_nl_workshop_max_group', true),
        'booking_url'   => get_post_meta($pid, '_nl_workshop_booking_url', true),
        'items'         => get_post_meta($pid, '_nl_workshop_items', true),
    ];
} else {
    /* ---------- Hardcoded fallback ---------- */
    $workshops = [];
    $ws = null;
}

if (!$ws) {
    wp_redirect(home_url('/workshop/'));
    exit;
}

/* ---------- Sharing meta (document title + Open Graph) ---------- */
$GLOBALS['nl_workshop_detail'] = $ws;
$GLOBALS['nl_workshop_detail_title'] = $ws['title'];

add_filter('document_title_parts', function($parts) {
    if (!empty($GLOBALS['nl_workshop_detail_title'])) {
        $parts['title'] = $GLOBALS['nl_workshop_detail_title'] . ' - ' . get_bloginfo('name');
    }
    return $parts;
});

add_action('wp_head', function() {
    $ws  = $GLOBALS['nl_workshop_detail'] ?? [];
    $lang = nl_lang();
    if (empty($ws)) return;

    $title   = $ws['title'] ?? '';
    $cover   = str_replace('http://', 'https://', $ws['gallery'][0] ?? '');
    $desc    = '';
    if ($lang === 'en') {
        $desc = $ws['desc_en'] ?: 'Join us for a hands-on neon light art workshop.';
    } elseif ($lang === 'zh') {
        $desc = $ws['desc_zh'] ?: '參加我們的霓虹燈藝術工作坊，親手製作屬於你的霓虹燈作品。';
    } else {
        $desc = $ws['desc_cn'] ?: '参加我们的霓虹灯艺术工作坊，亲手制作属于你的霓虹灯作品。';
    }
    $desc = str_replace(["\r\n", "\r", "\n"], ' ', $desc);
    $url = home_url(add_query_arg([]));

    echo "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($desc) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($cover) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($desc) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($cover) . '">' . "\n";
}, 1);

get_header();

$title = $ws['title'];
$locations = [
    ['name'=>'中環8號碼頭','name_en'=>'Central Pier 8','address'=>'香港中環8號碼頭U層','address_en'=>'U/F,Central Pier 8,Hong Kong'],
    ['name'=>'尖沙咀東匯大廈','name_en'=>'Tsim Sha Tsui','address'=>'尖沙咀寶勒巷27號東匯大廈14樓全層','address_en'=>'14/F, Tung Wui Commercial Building, 27 Prat Avenue, Tsim Sha Tsui, Kowloon, HK'],
    ['name'=>'馬灣公園','name_en'=>'Ma Wan','address'=>'馬灣1868馬灣後街8號39號屋地下','address_en'=>'G39, House 39, No.8 Ma Wan Back Street, Ma Wan Park Phase II, Ma Wan NT'],
    ['name'=>'赤柱大街','name_en'=>'Stanley','address'=>'香港赤柱大街78-79號Solo地下10號舖','address_en'=>'Unit 10, Solo, G/F, 78-79 Stanley Main Street, Stanley, Hong Kong'],
];

$gallery = $ws['gallery'] ?? [];
$has_gallery = !empty($gallery) && is_array($gallery);
$hero_img = $has_gallery ? $gallery[0] : (get_template_directory_uri().'/assets/images/hero-workshop.jpg');
?>

<style>
/* ---------- Hero & Gallery ---------- */
.nl-detail-hero{position:relative;width:100%;height:340px;overflow:hidden}
.nl-detail-hero img,.nl-detail-hero__bg{width:100%;height:100%;object-fit:cover;display:block}
.nl-detail-hero__overlay{position:absolute;inset:0;background:linear-gradient(transparent 40%,rgba(0,0,0,.65) 100%);pointer-events:none}
.nl-detail-hero__info{position:absolute;bottom:0;left:0;right:0;padding:28px 20px 20px;color:#fff}
.nl-detail-hero__info h1{font-size:1.9rem;font-weight:700;margin-bottom:6px;letter-spacing:1px;line-height:1.2}
.nl-detail-hero__info p{font-size:1rem;opacity:.9}
.nl-detail-hero__view{position:absolute;bottom:16px;right:16px;background:rgba(0,0,0,.55);color:#fff;border:none;border-radius:20px;padding:8px 16px;font-size:.85rem;cursor:pointer;display:flex;align-items:center;gap:6px;backdrop-filter:blur(4px);z-index:2}
.nl-detail-hero__view svg{width:16px;height:16px}

/* Thumbnail strip */
.nl-detail-thumbs{display:flex;gap:12px;padding:16px 20px;margin-top:4px;overflow-x:auto;background:#f8f8f8;-webkit-overflow-scrolling:touch}
.nl-detail-thumbs::-webkit-scrollbar{display:none}
.nl-detail-thumbs img{flex-shrink:0;width:96px;height:96px;object-fit:cover;border-radius:10px;cursor:pointer;border:2px solid transparent;transition:border-color .2s}
.nl-detail-thumbs img.active{border-color:#00d4b0}

/* Lightbox */
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
.nl-lightbox__dots span{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.4)}
.nl-lightbox__dots span.active{background:#fff}

/* Body */
.nl-detail-body{max-width:800px;margin:0 auto;padding:24px 16px 40px}
.nl-detail-meta{display:flex;gap:16px;flex-wrap:wrap;justify-content:center;margin-bottom:32px}
.nl-detail-meta span{background:#f5f5f5;padding:10px 20px;border-radius:999px;font-size:.9rem}
.nl-detail-card{background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:24px;margin-bottom:24px}
.nl-detail-price{font-size:1.8rem;font-weight:700;color:#00d4b0;margin-bottom:8px}
.nl-detail-book{display:inline-block;padding:14px 40px;background:#00d4b0;color:#fff;border-radius:30px;font-weight:600;font-size:1rem;text-decoration:none;cursor:pointer;border:none}
.nl-detail-book:hover{background:#00bfa0}
.nl-detail-back{display:inline-block;margin-top:32px;color:#666;text-decoration:none;font-size:.9rem}
.nl-detail-back:hover{color:#00d4b0}
.nl-detail-section{margin-bottom:36px}
.nl-detail-section h2{font-size:1.25rem;margin-bottom:12px;font-weight:600}
.nl-detail-section p,.nl-detail-section ul{color:#555;line-height:1.7}
.nl-detail-section ul{padding-left:18px}
.nl-detail-section li{margin-bottom:6px}

/* Item grid */
.nl-item-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px}
.nl-item-card{border:2px solid #eee;border-radius:10px;padding:18px;cursor:pointer;transition:all .2s}
.nl-item-card:hover{border-color:#00d4b0}
.nl-item-card.selected{border-color:#00d4b0;background:#f0fffb}
.nl-item-card__name{font-weight:600;font-size:1.05rem;margin-bottom:6px}
.nl-item-card__price{color:#00d4b0;font-weight:700;font-size:1.1rem}

/* Modal */
.nl-booking-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9998}
.nl-booking-modal{display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;width:92%;max-width:640px;max-height:92vh;overflow-y:auto;border-radius:20px;z-index:9999;padding:44px 40px}
.nl-booking-modal.active,.nl-booking-overlay.active{display:block}
.nl-booking-modal__close{position:absolute;top:18px;right:24px;font-size:32px;cursor:pointer;background:none;border:none}
.nl-booking-modal__title{font-size:1.8rem;margin-bottom:10px}
.nl-booking-modal__price{color:#00d4b0;font-weight:bold;font-size:1.35rem;margin-bottom:28px}
.nl-booking-step{display:none}
.nl-booking-step.active{display:block}
.nl-booking-step__title{font-size:1.4rem;margin-bottom:22px}
.nl-location-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px}
.nl-location-card{border:2px solid #eee;border-radius:10px;padding:18px;cursor:pointer;transition:all .2s}
.nl-location-card:hover{border-color:#00d4b0}
.nl-location-card.selected{border-color:#00d4b0;background:#f0fffb}
.nl-location-card__name{font-weight:bold;margin-bottom:6px;font-size:1.05rem}
.nl-location-card__addr{font-size:.9rem;color:#666;line-height:1.4}
.nl-booking-field{margin-bottom:20px}
.nl-booking-field label{display:block;margin-bottom:8px;font-size:1rem;font-weight:500}
.nl-booking-field input,.nl-booking-field select,.nl-booking-field textarea{width:100%;padding:14px;border:1px solid #ddd;border-radius:8px;font-size:16px;box-sizing:border-box}
.nl-booking-actions{display:flex;justify-content:space-between;margin-top:32px}
.nl-booking-actions button{padding:14px 32px;border-radius:30px;border:none;cursor:pointer;font-size:16px}
.nl-btn-primary{background:#00d4b0;color:#fff}
.nl-btn-primary:disabled{background:#ccc;cursor:not-allowed}
.nl-btn-secondary{background:#f0f0f0;color:#333}

@media(max-width:640px){
    .nl-detail-hero{height:280px}
    .nl-detail-hero__info h1{font-size:1.6rem}
    .nl-detail-hero__info{padding:20px 16px 16px}
    .nl-detail-thumbs img{width:72px;height:72px}
    .nl-lightbox__arrow{width:36px;height:36px}
    .nl-detail-body{padding:20px 12px 32px}
    .nl-booking-modal{width:98%;max-width:none;padding:40px 24px 32px;border-radius:20px}
    .nl-booking-modal__close{font-size:36px;top:14px;right:18px}
    .nl-booking-modal__title{font-size:1.9rem;margin-bottom:10px}
    .nl-booking-modal__price{font-size:1.4rem;margin-bottom:32px}
    .nl-booking-step__title{font-size:1.5rem;margin-bottom:24px}
    .nl-booking-field{margin-bottom:24px}
    .nl-booking-field label{font-size:1.15rem;margin-bottom:10px}
    .nl-booking-field input,.nl-booking-field select,.nl-booking-field textarea{padding:16px;font-size:17px;border-radius:8px}
    .nl-location-card{padding:22px;border-radius:12px}
    .nl-location-card__name{font-size:1.25rem;margin-bottom:8px}
    .nl-location-card__addr{font-size:1.05rem;line-height:1.5}
    .nl-booking-actions{margin-top:32px}
    .nl-booking-actions button{padding:16px 36px;font-size:17px;border-radius:30px}
}
</style>

<!-- Hero + Gallery -->
<section class="nl-detail-hero">
    <img src="<?php echo esc_url($hero_img); ?>" alt="<?php echo esc_attr($title); ?>" class="nl-detail-hero__bg" />
    <div class="nl-detail-hero__overlay"></div>
    <div class="nl-detail-hero__info">
        <h1><?php echo esc_html($title); ?></h1>
    </div>
    <?php if ($has_gallery && count($gallery) > 1): ?>
    <button class="nl-detail-hero__view" onclick="openLightbox(0)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        <?php echo $lang==='en' ? 'View images' : '查看圖片'; ?>
    </button>
    <?php endif; ?>
</section>

<?php if ($has_gallery && count($gallery) > 1): ?>
<div class="nl-detail-thumbs">
    <?php foreach ($gallery as $i => $url): ?>
    <img src="<?php echo esc_url($url); ?>" alt="" class="<?php echo $i===0 ? 'active' : ''; ?>" onclick="openLightbox(<?php echo $i; ?>)" />
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Lightbox -->
<div class="nl-lightbox" id="nlLightbox">
    <div class="nl-lightbox__top">
        <span class="nl-lightbox__counter" id="lbCounter">1 / 1</span>
        <button class="nl-lightbox__close" onclick="closeLightbox()">&times;</button>
    </div>
    <div class="nl-lightbox__stage">
        <button class="nl-lightbox__arrow prev" onclick="lbPrev()">&#10094;</button>
        <img src="" alt="" id="lbImage" />
        <button class="nl-lightbox__arrow next" onclick="lbNext()">&#10095;</button>
    </div>
    <div class="nl-lightbox__dots" id="lbDots"></div>
</div>

<div class="nl-detail-body">

    <?php
    $items = $ws['items'] ?? [];
    $items = is_array($items) ? $items : [];
    $has_items = !empty($items);
    ?>

    <?php if ($has_items): ?>
    <div class="nl-detail-section" id="nl-items-section">
        <h2><?php echo $lang==='en' ? 'Select Package' : '選擇套餐'; ?></h2>
        <div class="nl-item-grid">
            <?php foreach ($items as $idx => $item):
                $item_name = $lang==='en' ? ($item['name'] ?? '') : ($lang==='zh' ? ($item['name_zh'] ?? '') : ($item['name_cn'] ?? ''));
                $item_price = floatval($item['price'] ?? 0);
                $item_display = 'HK$' . number_format($item_price) . ' / person';
            ?>
            <div class="nl-item-card <?php echo $idx===0 ? 'selected' : ''; ?>"
                 data-price="<?php echo esc_attr($item_price); ?>"
                 data-price-display="<?php echo esc_attr($item_display); ?>"
                 data-name="<?php echo esc_attr($item_name); ?>"
                 onclick="selectItem(this)">
                <div class="nl-item-card__name"><?php echo esc_html($item_name); ?></div>
                <div class="nl-item-card__price"><?php echo esc_html($item_display); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="nl-detail-card">
        <?php
        $first_item_price = 0;
        $first_item_display = '';
        if ($has_items) {
            $first_item_price = floatval($items[0]['price'] ?? 0);
            $first_item_display = 'HK$' . number_format($first_item_price) . ' / person';
        }
        ?>
        <?php if ($has_items): ?>
        <div class="nl-detail-price" id="displayPrice"><?php echo esc_html($first_item_display); ?></div>
        <?php else: ?>
        <div class="nl-detail-price" id="displayPrice"><?php echo esc_html($ws['price_display']); ?></div>
        <?php endif; ?>

        <?php if (!empty($ws['min_group'])): ?>
        <p style="color:#666;margin-bottom:24px">
            <?php echo $lang==='en' ? 'Minimum ' . esc_html($ws['min_group']) . ' person' : '最小 ' . esc_html($ws['min_group']) . ' 人'; ?>
        </p>
        <?php endif; ?>

        <?php if (!empty($ws['booking_url'])): ?>
        <a href="<?php echo esc_url($ws['booking_url']); ?>" class="nl-detail-book" target="_blank">
            <?php echo nl_t('ws_book'); ?>
        </a>
        <?php else: ?>
        <button class="nl-detail-book js-open-booking"
                data-workshop-id="<?php echo esc_attr($ws['id']); ?>"
                data-title="<?php echo esc_attr($title); ?>"
                data-price="<?php echo esc_attr($first_item_price); ?>"
                data-price-display="<?php echo esc_attr($first_item_display); ?>">
            <?php echo nl_t('ws_book'); ?>
        </button>
        <?php endif; ?>
    </div>

    <div class="nl-detail-section">
        <h2><?php echo $lang==='en' ? 'About This Workshop' : '關於此工作坊'; ?></h2>
        <p>
            <?php
            if ($lang === 'en') {
                $desc = $ws['desc_en'] ?: 'Join us for a hands-on EL wire art workshop where you\'ll create your own neon light masterpiece. All materials and tools are provided. No prior experience needed.';
            } elseif ($lang === 'zh') {
                $desc = $ws['desc_zh'] ?: '參加我們的冷光線藝術工作坊，親手製作屬於你的霓虹燈作品。我們提供所有材料及工具，無需任何經驗。';
            } else { // cn
                $desc = $ws['desc_cn'] ?: '参加我们的冷光线艺术工作坊，亲手制作属于你的霓虹灯作品。我们提供所有材料及工具，无需任何经验。';
            }
            echo nl2br(esc_html($desc));
            ?>
        </p>
    </div>

    <a href="<?php echo home_url('/workshop/'); ?>" class="nl-detail-back">← <?php echo $lang==='en' ? 'Back to Workshops' : '返回工作坊列表'; ?></a>
</div>

<!-- Booking Modal (same as before) -->
<div class="nl-booking-overlay" id="bookingOverlay"></div>
<div class="nl-booking-modal" id="bookingModal">
    <button class="nl-booking-modal__close" id="bookingClose">×</button>
    <h3 class="nl-booking-modal__title" id="modalWorkshopTitle">Workshop</h3>
    <p class="nl-booking-modal__price" id="modalWorkshopPrice">$0</p>

    <form id="bookingForm" method="post" action="">
        <?php wp_nonce_field('nl_booking_form','nl_booking_nonce'); ?>
        <input type="hidden" name="nl_booking_workshop_id" id="bookingWorkshopId">
        <input type="hidden" name="nl_booking_workshop_title" id="bookingWorkshopTitleInput">
        <input type="hidden" name="nl_booking_price" id="bookingPrice">
        <input type="hidden" name="selected_location" id="selectedLocation">

        <!-- Step 1: Location -->
        <div class="nl-booking-step active" id="step1">
            <h4 class="nl-booking-step__title">1. <?php echo nl_t('ws_step1'); ?></h4>
            <p style="font-size:.85rem;color:#666;margin-bottom:12px"><strong><?php echo nl_t('ws_advance'); ?></strong></p>
            <div class="nl-location-grid">
                <?php foreach ($locations as $idx=>$loc): ?>
                <div class="nl-location-card" data-location="<?php echo $idx; ?>">
                    <div class="nl-location-card__name"><?php echo nl_lang()==='en' ? esc_html($loc['name_en']) : esc_html($loc['name']); ?></div>
                    <div class="nl-location-card__addr"><?php echo nl_lang()==='en' ? esc_html($loc['address_en']) : esc_html($loc['address']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="nl-booking-actions">
                <span></span>
                <button type="button" class="nl-btn-primary" id="btnStep1Next" disabled><?php echo nl_t('ws_next'); ?> →</button>
            </div>
        </div>

        <!-- Step 2: Date & Time -->
        <div class="nl-booking-step" id="step2">
            <h4 class="nl-booking-step__title">2. <?php echo nl_t('ws_step2'); ?></h4>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_date'); ?></label>
                <input type="date" name="booking_date" id="bookingDate" required min="">
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_time'); ?></label>
                <select name="booking_time" id="bookingTime" required>
                    <option value=""><?php echo nl_t('ws_time_sel'); ?></option>
                    <option value="12:00">12:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                </select>
            </div>
            <div class="nl-booking-actions">
                <button type="button" class="nl-btn-secondary" id="btnStep2Back">← <?php echo nl_t('ws_back'); ?></button>
                <button type="button" class="nl-btn-primary" id="btnStep2Next" disabled><?php echo nl_t('ws_next'); ?> →</button>
            </div>
        </div>

        <!-- Step 3: Booking Form -->
        <div class="nl-booking-step" id="step3">
            <h4 class="nl-booking-step__title">3. <?php echo nl_t('ws_step3'); ?></h4>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_name'); ?></label>
                <input type="text" name="customer_name" required placeholder="<?php echo esc_attr(nl_t('ws_name_ph')); ?>">
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_email_ph'); ?></label>
                <input type="email" name="customer_email" required placeholder="<?php echo esc_attr(nl_t('ws_email_ph2')); ?>">
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_phone_ph'); ?></label>
                <input type="tel" name="customer_phone" required placeholder="<?php echo esc_attr(nl_t('ws_phone_ph2')); ?>">
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_group_size'); ?></label>
                <select name="group_size" id="groupSize" required>
                    <?php
                    $min_g = intval($ws['min_group'] ?? 1);
                    $max_g = intval($ws['max_group'] ?? 10);
                    for ($g = $min_g; $g <= $max_g; $g++) {
                        echo '<option value="' . esc_attr($g) . '"' . ($g === $min_g ? ' selected' : '') . '>' . esc_html($g) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_remarks'); ?></label>
                <textarea name="remarks" rows="3" placeholder="<?php echo esc_attr(nl_t('ws_remarks_ph')); ?>"></textarea>
            </div>
            <div class="nl-booking-actions">
                <button type="button" class="nl-btn-secondary" id="btnStep3Back">← <?php echo nl_t('ws_back'); ?></button>
                <button type="button" class="nl-btn-primary" id="btnStep3Next"><?php echo nl_t('ws_next'); ?> →</button>
            </div>
        </div>

        <!-- Step 4: Payment -->
        <div class="nl-booking-step" id="step4">
            <h4 class="nl-booking-step__title">4. <?php echo nl_t('ws_step4'); ?></h4>
            <div style="background:#f8f8f8;padding:16px;border-radius:8px;margin-bottom:20px">
                <p><strong><?php echo nl_t('ws_title'); ?>:</strong> <span id="confirmWorkshop"></span></p>
                <p><strong><?php echo nl_t('ws_step1'); ?>:</strong> <span id="confirmLocation"></span></p>
                <p><strong><?php echo nl_t('ws_step2'); ?>:</strong> <span id="confirmDateTime"></span></p>
                <p><strong><?php echo nl_t('ws_name'); ?>:</strong> <span id="confirmName"></span></p>
                <p><strong><?php echo nl_t('ws_group_size'); ?>:</strong> <span id="confirmGroup"></span></p>
                <hr style="margin:12px 0;border:none;border-top:1px solid #ddd">
                <p style="font-size:1.2rem;color:#00d4b0"><strong><?php echo nl_t('ws_price'); ?>: <span id="confirmTotal"></span></strong></p>
            </div>
            <p style="font-size:.85rem;color:#666;margin-bottom:16px">
                <?php echo nl_t('ws_confirm'); ?>
            </p>
            <div class="nl-booking-actions">
                <button type="button" class="nl-btn-secondary" id="btnStep4Back">← <?php echo nl_t('ws_back'); ?></button>
                <button type="submit" class="nl-btn-primary" name="nl_booking_submit" value="1"><?php echo nl_t('ws_pay_now'); ?></button>
            </div>
        </div>
    </form>
</div>

<script>
/* ---------- Lightbox ---------- */
const galleryImages = <?php echo json_encode(array_values($gallery)); ?>;
let lbIndex = 0;

function openLightbox(idx) {
    if (!galleryImages.length) return;
    lbIndex = idx;
    updateLightbox();
    document.getElementById('nlLightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('nlLightbox').classList.remove('active');
    document.body.style.overflow = '';
}
function updateLightbox() {
    document.getElementById('lbImage').src = galleryImages[lbIndex];
    document.getElementById('lbCounter').textContent = (lbIndex + 1) + ' / ' + galleryImages.length;
    // dots
    const dotsContainer = document.getElementById('lbDots');
    dotsContainer.innerHTML = '';
    galleryImages.forEach((_, i) => {
        const span = document.createElement('span');
        if (i === lbIndex) span.classList.add('active');
        dotsContainer.appendChild(span);
    });
}
function lbNext() {
    lbIndex = (lbIndex + 1) % galleryImages.length;
    updateLightbox();
}
function lbPrev() {
    lbIndex = (lbIndex - 1 + galleryImages.length) % galleryImages.length;
    updateLightbox();
}

// Swipe
let touchStartX = 0;
document.getElementById('nlLightbox').addEventListener('touchstart', e => {
    touchStartX = e.changedTouches[0].screenX;
}, {passive:true});
document.getElementById('nlLightbox').addEventListener('touchend', e => {
    const diff = e.changedTouches[0].screenX - touchStartX;
    if (diff < -40) lbNext();
    else if (diff > 40) lbPrev();
}, {passive:true});

// Keyboard
document.addEventListener('keydown', e => {
    const lb = document.getElementById('nlLightbox');
    if (!lb.classList.contains('active')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') lbNext();
    if (e.key === 'ArrowLeft') lbPrev();
});

/* ---------- Item selector ---------- */
let selectedItemPrice = null;
let selectedItemPriceDisplay = null;
let selectedItemName = null;
function selectItem(el) {
    document.querySelectorAll('.nl-item-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    selectedItemPrice = el.dataset.price;
    selectedItemPriceDisplay = el.dataset.priceDisplay;
    selectedItemName = el.dataset.name;
    const displayPrice = document.getElementById('displayPrice');
    if (displayPrice) displayPrice.textContent = selectedItemPriceDisplay;
    const btn = document.querySelector('.js-open-booking');
    if (btn) {
        btn.dataset.price = selectedItemPrice;
        btn.dataset.priceDisplay = selectedItemPriceDisplay;
    }
}

/* ---------- Booking Modal ---------- */
document.addEventListener('DOMContentLoaded',function(){
    const modal=document.getElementById('bookingModal');
    const overlay=document.getElementById('bookingOverlay');
    const closeBtn=document.getElementById('bookingClose');
    let selectedLocation=null;
    let currentStep=1;

    document.querySelectorAll('.js-open-booking').forEach(btn=>{
        btn.addEventListener('click',function(){
            document.getElementById('modalWorkshopTitle').textContent=this.dataset.title;
            document.getElementById('modalWorkshopPrice').textContent=this.dataset.priceDisplay;
            document.getElementById('bookingWorkshopId').value=this.dataset.workshopId;
            document.getElementById('bookingWorkshopTitleInput').value=this.dataset.title;
            document.getElementById('bookingPrice').value=this.dataset.price;
            modal.classList.add('active');
            overlay.classList.add('active');
            resetSteps();
        });
    });

    closeBtn.addEventListener('click',closeModal);
    overlay.addEventListener('click',closeModal);
    function closeModal(){modal.classList.remove('active');overlay.classList.remove('active');}

    const dateInput=document.getElementById('bookingDate');
    const minDate=new Date();
    minDate.setDate(minDate.getDate()+2);
    dateInput.min=minDate.toISOString().split('T')[0];

    let selectedLocationIdx=null;
    document.querySelectorAll('.nl-location-card').forEach(card=>{
        card.addEventListener('click',function(){
            document.querySelectorAll('.nl-location-card').forEach(c=>c.classList.remove('selected'));
            this.classList.add('selected');
            selectedLocation=parseInt(this.dataset.location);
            selectedLocationIdx=selectedLocation;
            document.getElementById('selectedLocation').value=selectedLocation;
            document.getElementById('btnStep1Next').disabled=false;
            validateStep2();
        });
    });

    document.getElementById('btnStep1Next').addEventListener('click',()=>goToStep(2));
    document.getElementById('btnStep2Back').addEventListener('click',()=>goToStep(1));
    document.getElementById('btnStep2Next').addEventListener('click',()=>goToStep(3));
    document.getElementById('btnStep3Back').addEventListener('click',()=>goToStep(2));
    document.getElementById('btnStep3Next').addEventListener('click',()=>{
        const workshop=document.getElementById('bookingWorkshopTitleInput').value;
        const locCards=document.querySelectorAll('.nl-location-card');
        const locName=locCards[selectedLocation]?.querySelector('.nl-location-card__name')?.textContent||'';
        const date=document.getElementById('bookingDate').value;
        const time=document.getElementById('bookingTime').value;
        const name=document.querySelector('input[name="customer_name"]').value;
        const group=document.getElementById('groupSize').value;
        const price=parseFloat(document.getElementById('bookingPrice').value)||0;

        document.getElementById('confirmWorkshop').textContent=workshop;
        document.getElementById('confirmLocation').textContent=locName;
        document.getElementById('confirmDateTime').textContent=date+' '+time;
        document.getElementById('confirmName').textContent=name;
        document.getElementById('confirmGroup').textContent=group+' 人 / pax';
        const total=price>0?'HK$'+(price*parseInt(group)):'Price negotiable';
        document.getElementById('confirmTotal').textContent=total;
        goToStep(4);
    });
    document.getElementById('btnStep4Back').addEventListener('click',()=>goToStep(3));

    function goToStep(n){
        document.querySelectorAll('.nl-booking-step').forEach(s=>s.classList.remove('active'));
        document.getElementById('step'+n).classList.add('active');
        currentStep=n;
    }
    function resetSteps(){
        selectedLocation=null;
        selectedLocationIdx=null;
        document.querySelectorAll('.nl-location-card').forEach(c=>c.classList.remove('selected'));
        document.getElementById('bookingForm').reset();
        document.getElementById('btnStep1Next').disabled=true;
        document.getElementById('btnStep2Next').disabled=true;
        document.getElementById('bookingDate').min=minDate.toISOString().split('T')[0];
        goToStep(1);
    }

    dateInput.addEventListener('change',validateStep2);
    document.getElementById('bookingTime').addEventListener('change',validateStep2);
    function validateStep2(){
        const dateVal=dateInput.value;
        const timeVal=document.getElementById('bookingTime').value;
        let err='';
        if(dateVal){
            const sel=new Date(dateVal);
            const min=new Date(minDate.toISOString().split('T')[0]);
            if(sel<min){ err='Please book at least 2 days in advance.'; }
        }
        if(dateVal && selectedLocationIdx===0){
            const dow=new Date(dateVal).getDay();
            if(dow===3){ err='Central Pier 8 is closed on Wednesdays. Please choose another date.'; }
        }
        document.getElementById('btnStep2Next').disabled=!(dateVal&&timeVal&&!err);
    }
});
</script>

<?php get_footer(); ?>
