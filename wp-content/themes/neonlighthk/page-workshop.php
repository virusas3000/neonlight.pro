<?php
/**
 * Template Name: Workshop
 * @package NeonLightHK
 */
get_header();

// ===== INTEREST FORM HANDLING =====
$interest_message = '';
$booking_message  = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nl_interest_nonce'])) {
    if (wp_verify_nonce($_POST['nl_interest_nonce'], 'nl_interest_form') && isset($_POST['first_name'])) {
        $first_name  = sanitize_text_field($_POST['first_name'] ?? '');
        $last_name   = sanitize_text_field($_POST['last_name'] ?? '');
        $email       = sanitize_email($_POST['email'] ?? '');
        $phone       = sanitize_text_field($_POST['phone'] ?? '');
        $age_range   = sanitize_text_field($_POST['age_range'] ?? '');
        $themes      = array_map('sanitize_text_field', $_POST['theme'] ?? []);
        $best_time   = sanitize_text_field($_POST['best_time'] ?? '');
        $group_prefs = array_map('sanitize_text_field', $_POST['group'] ?? []);

        $interest_id = wp_insert_post([
            'post_type'   => 'nl_booking',
            'post_title'  => 'Interest — ' . $first_name . ' ' . $last_name . ' — ' . $email,
            'post_status' => 'pending',
            'post_author' => 1,
        ]);

        if ($interest_id && !is_wp_error($interest_id)) {
            update_post_meta($interest_id, '_nl_first_name',  $first_name);
            update_post_meta($interest_id, '_nl_last_name',   $last_name);
            update_post_meta($interest_id, '_nl_email',       $email);
            update_post_meta($interest_id, '_nl_phone',       $phone);
            update_post_meta($interest_id, '_nl_age_range',   $age_range);
            update_post_meta($interest_id, '_nl_themes',      implode(', ', $themes));
            update_post_meta($interest_id, '_nl_best_time',   $best_time);
            update_post_meta($interest_id, '_nl_group_prefs', implode(', ', $group_prefs));

            wp_mail('www.neonlight.pro@gmail.com',
                'New Workshop Interest - ' . $first_name . ' ' . $last_name,
                "Name: $first_name $last_name
Email: $email
Phone: $phone
Age: $age_range
Interested themes: " . implode(', ', $themes) . "
Best time: $best_time
Group preference: " . implode(', ', $group_prefs));

            $interest_message = 'saved';
        } else {
            $interest_message = 'error';
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nl_booking_nonce'])) {
    if (wp_verify_nonce($_POST['nl_booking_nonce'], 'nl_booking_form') && isset($_POST['nl_booking_submit'])) {
        $workshop_id    = sanitize_text_field($_POST['nl_booking_workshop_id'] ?? '');
        $workshop_title = sanitize_text_field($_POST['nl_booking_workshop_title'] ?? '');
        $price          = floatval($_POST['nl_booking_price'] ?? 0);
        $location_idx   = intval($_POST['selected_location'] ?? 0);
        $booking_date   = sanitize_text_field($_POST['booking_date'] ?? '');
        $booking_time   = sanitize_text_field($_POST['booking_time'] ?? '');
        $customer_name  = sanitize_text_field($_POST['customer_name'] ?? '');
        $customer_email = sanitize_email($_POST['customer_email'] ?? '');
        $customer_phone = sanitize_text_field($_POST['customer_phone'] ?? '');
        $group_size     = intval($_POST['group_size'] ?? 1);
        $remarks        = sanitize_textarea_field($_POST['remarks'] ?? '');

        $locations = [
            ['name'=>'中環8號碼頭','name_en'=>'Central Pier 8','address'=>'香港中環8號碼頭U層','address_en'=>'U/F,Central Pier 8,Hong Kong'],
            ['name'=>'尖沙咀東匯大廈','name_en'=>'Tsim Sha Tsui','address'=>'尖沙咀寶勒巷27號東匯大廈14樓全層','address_en'=>'14/F, Tung Wui Commercial Building, 27 Prat Avenue, Tsim Sha Tsui, Kowloon, HK'],
            ['name'=>'馬灣公園','name_en'=>'Ma Wan','address'=>'馬灣1868馬灣後街8號39號屋地下','address_en'=>'G39, House 39, No.8 Ma Wan Back Street, Ma Wan Park Phase II, Ma Wan NT'],
            ['name'=>'赤柱大街','name_en'=>'Stanley','address'=>'香港赤柱大街78-79號Solo地下10號舖','address_en'=>'Unit 10, Solo, G/F, 78-79 Stanley Main Street, Stanley, Hong Kong'],
        ];
        $loc = $locations[$location_idx] ?? $locations[0];

        // Save to nl_booking CPT
        $booking_id = wp_insert_post([
            'post_type'   => 'nl_booking',
            'post_title'  => $workshop_title . ' — ' . $customer_name . ' — ' . $booking_date,
            'post_status' => 'pending',
            'post_author' => 1,
        ]);

        if ($booking_id && !is_wp_error($booking_id)) {
            update_post_meta($booking_id, '_nl_workshop_id',      $workshop_id);
            update_post_meta($booking_id, '_nl_workshop_title',  $workshop_title);
            update_post_meta($booking_id, '_nl_price',           $price);
            update_post_meta($booking_id, '_nl_location_name',   $loc['name'] . ' · ' . $loc['name_en']);
            update_post_meta($booking_id, '_nl_location_address',$loc['address'] . ' | ' . $loc['address_en']);
            update_post_meta($booking_id, '_nl_booking_date',    $booking_date);
            update_post_meta($booking_id, '_nl_booking_time',    $booking_time);
            update_post_meta($booking_id, '_nl_customer_name',   $customer_name);
            update_post_meta($booking_id, '_nl_customer_email',  $customer_email);
            update_post_meta($booking_id, '_nl_customer_phone',  $customer_phone);
            update_post_meta($booking_id, '_nl_group_size',      $group_size);
            update_post_meta($booking_id, '_nl_remarks',         $remarks);
            update_post_meta($booking_id, '_nl_total',           $price * $group_size);

            // Email admin
            wp_mail('www.neonlight.pro@gmail.com',
                'New Workshop Booking - ' . $customer_name,
                "Workshop: $workshop_title
Location: " . $loc['name'] . ' · ' . $loc['name_en'] . "
Date: $booking_date $booking_time
Name: $customer_name
Email: $customer_email
Phone: $customer_phone
Group: $group_size
Total: HK$" . ($price * $group_size) . "
Remarks: $remarks");

            // If priced, create WC product & go to checkout
            if ($price > 0 && class_exists('WC_Product_Simple')) {
                $existing = get_posts(['post_type'=>'product','meta_key'=>'_nl_workshop_product_id','meta_value'=>$workshop_id,'posts_per_page'=>1,'post_status'=>'any']);
                if (!empty($existing)) {
                    $product_id = $existing[0]->ID;
                } else {
                    $product = new WC_Product_Simple();
                    $product->set_name($workshop_title);
                    $product->set_regular_price($price);
                    $product->set_price($price);
                    $product->set_status('publish');
                    $product->set_catalog_visibility('hidden');
                    $product->set_virtual(true);
                    $product->set_sold_individually(false);
                    $product->update_meta_data('_nl_workshop_product_id', $workshop_id);
                    $product_id = $product->save();
                }
                if ($product_id && class_exists('WC_Cart')) {
                    WC()->cart->empty_cart();
                    WC()->cart->add_to_cart($product_id, $group_size);
                    WC()->session->set('nl_booking_id', $booking_id);
                    wp_redirect(wc_get_checkout_url());
                    exit;
                }
            }
            $booking_message = 'saved';
        } else {
            $booking_message = 'error';
        }
    }
}

$locations = [
    ['name'=>'中環8號碼頭','name_en'=>'Central Pier 8','address'=>'香港中環8號碼頭U層','address_en'=>'U/F,Central Pier 8,Hong Kong'],
    ['name'=>'尖沙咀東匯大廈','name_en'=>'Tsim Sha Tsui','address'=>'尖沙咀寶勒巷27號東匯大廈14樓全層','address_en'=>'14/F, Tung Wui Commercial Building, 27 Prat Avenue, Tsim Sha Tsui, Kowloon, HK'],
    ['name'=>'馬灣公園','name_en'=>'Ma Wan','address'=>'馬灣1868馬灣後街8號39號屋地下','address_en'=>'G39, House 39, No.8 Ma Wan Back Street, Ma Wan Park Phase II, Ma Wan NT'],
    ['name'=>'赤柱大街','name_en'=>'Stanley','address'=>'香港赤柱大街78-79號Solo地下10號舖','address_en'=>'Unit 10, Solo, G/F, 78-79 Stanley Main Street, Stanley, Hong Kong'],
];

/* Query CPT nl_workshop for list */
$ws_posts = get_posts([
    'post_type'      => 'nl_workshop',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
$workshops = [];
foreach ($ws_posts as $p) {
    $price = 0;
    $items = get_post_meta($p->ID, '_nl_workshop_items', true);
    if (!empty($items) && is_array($items)) {
        $price = floatval($items[0]['price'] ?? 0);
    }
    $workshops[] = [
        'id'            => $p->post_name,
        'title'         => $p->post_title,
        'title_en'      => get_post_meta($p->ID, '_nl_workshop_title_en', true) ?: $p->post_title,
        'price'         => $price,
        'price_display' => $price > 0 ? 'HK$' . number_format($price) : 'Contact us',
        'image'         => '',
    ];
}
?>

<style>
/* Language Switcher */
.nl-lang{display:flex;gap:6px;margin-left:12px;align-items:center}
.nl-lang a{font-size:11px;padding:2px 6px;border-radius:3px;color:#fff;text-decoration:none;opacity:.7;transition:.2s}
.nl-lang a:hover,.nl-lang a.active{opacity:1;background:rgba(255,255,255,.2)}

.nl-workshop-page{max-width:900px;margin:0 auto;padding:40px 20px}
.nl-workshop-page__title{text-align:center;font-size:2.4rem;margin-bottom:40px;letter-spacing:4px}
.nl-notice{padding:16px 20px;border-radius:8px;margin-bottom:24px;text-align:center}
.nl-notice--success{background:#e6f9f3;color:#0a7b5c}
.nl-notice--error{background:#ffe6e6;color:#b30c0c}

/* Contact bar */
.nl-contact-bar{background:#00d4b0;color:#fff;padding:20px;text-align:center;margin-bottom:40px;border-radius:8px}
.nl-contact-bar a{color:#fff;text-decoration:underline;margin:0 8px}

/* Interest Form */
.nl-interest-form{background:#f8f8f8;padding:40px 20px;margin-bottom:60px;border-radius:8px}
.nl-interest-form__title{text-align:center;margin-bottom:8px;font-size:1.8rem}
.nl-interest-form__subtitle{text-align:center;margin-bottom:30px;color:#666;font-size:1.1rem}
.nl-interest-form__grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.nl-interest-form__grid input,.nl-interest-form__grid select,.nl-interest-form__grid textarea{width:100%;padding:14px;border:1px solid #ddd;border-radius:4px;font-size:15px;box-sizing:border-box}
.nl-interest-form__grid textarea{grid-column:span 2;height:80px}
.nl-interest-form__grid .nl-field--full{grid-column:span 2}
.nl-interest-form__submit{display:block;width:200px;margin:24px auto 0;padding:12px;background:#00d4b0;color:#fff;border:none;border-radius:30px;cursor:pointer;font-size:14px}
.nl-interest-form__checkboxes{grid-column:span 2}
.nl-interest-form__checkboxes label{display:inline-block;margin-right:16px;margin-bottom:8px;font-size:14px}
.nl-interest-form__checkboxes input{width:auto;margin-right:6px}

/* Workshop Cards */
.nl-workshop-list{display:flex;flex-direction:column;gap:24px;margin-bottom:60px}
.nl-workshop-card{display:grid;grid-template-columns:200px 1fr;gap:20px;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.08)}
.nl-workshop-card__image{width:100%;height:100%;object-fit:cover;min-height:160px;display:block}
.nl-workshop-card__info{padding:20px}
.nl-workshop-card__title{font-size:1.2rem;margin-bottom:4px}
.nl-workshop-card__subtitle{color:#666;font-size:.9rem;margin-bottom:12px}
.nl-workshop-card__meta{display:flex;gap:16px;color:#333;font-size:.9rem;margin-bottom:16px}
.nl-workshop-card__btn{display:inline-block;padding:10px 24px;background:#00d4b0;color:#fff;border:none;border-radius:30px;cursor:pointer;font-size:14px;text-decoration:none}
.nl-workshop-card__btn:hover{background:#00bfa0}

/* Modal */
.nl-booking-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9998}
.nl-booking-modal{display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;width:92%;max-width:640px;max-height:92vh;overflow-y:auto;border-radius:20px;z-index:9999;padding:44px 40px}
.nl-booking-modal.active,.nl-booking-overlay.active{display:block}
.nl-booking-modal__close{position:absolute;top:18px;right:24px;font-size:32px;cursor:pointer;background:none;border:none}
.nl-booking-modal__title{font-size:1.8rem;margin-bottom:10px}
.nl-booking-modal__price{color:#00d4b0;font-weight:bold;font-size:1.35rem;margin-bottom:28px}

/* Steps */
.nl-booking-step{display:none}
.nl-booking-step.active{display:block}
.nl-booking-step__title{font-size:1.4rem;margin-bottom:22px}

/* Location */
.nl-location-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:24px}
.nl-location-card{border:2px solid #eee;border-radius:10px;padding:18px;cursor:pointer;transition:all .2s}
.nl-location-card:hover{border-color:#00d4b0}
.nl-location-card.selected{border-color:#00d4b0;background:#f0fffb}
.nl-location-card__name{font-weight:bold;margin-bottom:6px;font-size:1.05rem}
.nl-location-card__addr{font-size:.9rem;color:#666;line-height:1.4}

/* Fields */
.nl-booking-field{margin-bottom:20px}
.nl-booking-field label{display:block;margin-bottom:8px;font-size:1rem;font-weight:500}
.nl-booking-field input,.nl-booking-field select,.nl-booking-field textarea{width:100%;padding:14px;border:1px solid #ddd;border-radius:8px;font-size:16px;box-sizing:border-box}
input[type="date"]{min-width:0;width:100%}
.nl-booking-actions{display:flex;justify-content:space-between;margin-top:32px}
.nl-booking-actions button{padding:14px 32px;border-radius:30px;border:none;cursor:pointer;font-size:16px}
.nl-btn-primary{background:#00d4b0;color:#fff}
.nl-btn-primary:disabled{background:#ccc;cursor:not-allowed}
.nl-btn-secondary{background:#f0f0f0;color:#333}

@media(max-width:640px){
    .nl-workshop-card{grid-template-columns:120px 1fr;gap:12px}
    .nl-workshop-card__image{height:auto;min-height:0;align-self:stretch}
    .nl-workshop-card__info{padding:12px}
    .nl-interest-form__grid{grid-template-columns:1fr}
    .nl-interest-form__grid textarea,.nl-interest-form__grid .nl-field--full,.nl-interest-form__checkboxes{grid-column:span 1}
    .nl-location-grid{grid-template-columns:1fr}
    .nl-booking-modal{width:98%;max-width:none;padding:32px 16px 24px;border-radius:20px}
    .nl-booking-field input,.nl-booking-field select{padding:10px 12px;font-size:14px}
    input[type="date"]{font-size:14px;-webkit-appearance:none;appearance:none;text-align:left}
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

<div class="nl-workshop-page">

    <h1 class="nl-workshop-page__title"><?php echo nl_t('ws_title'); ?></h1>

    <?php if ($interest_message === 'saved') : ?>        <div class="nl-notice nl-notice--success">
            <strong><?php echo nl_t('ws_booking_saved'); ?></strong>
        </div>
    <?php elseif ($interest_message === 'error') : ?>        <div class="nl-notice nl-notice--error">
            <strong><?php echo nl_t('ws_booking_error'); ?></strong>
        </div>
    <?php elseif ($booking_message === 'saved') : ?>        <div class="nl-notice nl-notice--success">
            <strong><?php echo nl_t('ws_booking_saved'); ?></strong>
        </div>
    <?php elseif ($booking_message === 'error') : ?>        <div class="nl-notice nl-notice--error">
            <strong><?php echo nl_t('ws_booking_error'); ?></strong>
        </div>
    <?php endif; ?>

    <!-- Contact Bar -->
    <div class="nl-contact-bar">
        <strong><?php echo nl_t('ws_contact'); ?></strong><br>
        <a href="https://wa.me/85261319328" target="_blank">WhatsApp 6131 9328</a> |
        <a href="mailto:www.neonlight.pro@gmail.com"><?php echo nl_t('ws_email'); ?></a> |
        IG: <a href="https://instagram.com/irregularthk" target="_blank">@irregularthk</a>
        <a href="https://instagram.com/justbe.mawan" target="_blank">@justbe.mawan</a>
        <a href="https://instagram.com/neonlight.pro" target="_blank">@neonlight.pro</a>
    </div>

    <!-- Interest Form -->
    <div class="nl-interest-form">
        <h2 class="nl-interest-form__title"><?php echo nl_t('ws_apply_title'); ?></h2>
        <p class="nl-interest-form__subtitle"><?php echo nl_t('ws_apply_title'); ?></p>
        <p style="text-align:center;font-size:.9rem;color:#666;margin-bottom:24px"><?php echo nl_t('ws_apply_desc'); ?></p>
        <form method="post" action="">
            <?php wp_nonce_field('nl_interest_form','nl_interest_nonce'); ?>
            <div class="nl-interest-form__grid">
                <input type="text" name="first_name" placeholder="<?php echo nl_t('ws_first_name'); ?>" required>
                <input type="text" name="last_name" placeholder="<?php echo nl_t('ws_last_name'); ?>" required>
                <input type="email" name="email" placeholder="<?php echo nl_t('ws_email_ph'); ?>" required>
                <input type="tel" name="phone" placeholder="<?php echo nl_t('ws_phone_ph'); ?>" required>
                <select name="age_range" class="nl-field--full">
                    <option value=""><?php echo nl_t('ws_age'); ?></option>
                    <option value="under18"><?php echo nl_t('ws_age_u18'); ?></option>
                    <option value="18-25"><?php echo nl_t('ws_age_18_25'); ?></option>
                    <option value="26-35"><?php echo nl_t('ws_age_26_35'); ?></option>
                    <option value="36-50"><?php echo nl_t('ws_age_36_50'); ?></option>
                    <option value="50+"><?php echo nl_t('ws_age_50'); ?></option>
                </select>
                <div class="nl-interest-form__checkboxes">
                    <p><strong><?php echo nl_t('ws_theme'); ?></strong></p>
                    <label><input type="checkbox" name="theme[]" value="traditional"> <?php echo nl_t('ws_theme_trad'); ?></label>
                    <label><input type="checkbox" name="theme[]" value="couple"> <?php echo nl_t('ws_theme_love'); ?></label>
                    <label><input type="checkbox" name="theme[]" value="festive"> <?php echo nl_t('ws_theme_fest'); ?></label>
                    <label><input type="checkbox" name="theme[]" value="name"> <?php echo nl_t('ws_theme_name'); ?></label>
                    <label><input type="checkbox" name="theme[]" value="pets"> <?php echo nl_t('ws_theme_pets'); ?></label>
                    <label><input type="checkbox" name="theme[]" value="kids"> <?php echo nl_t('ws_theme_kids'); ?></label>
                    <label><input type="checkbox" name="theme[]" value="other"> <?php echo nl_t('ws_theme_other'); ?></label>
                </div>
                <select name="best_time" class="nl-field--full">
                    <option value=""><?php echo nl_t('ws_time'); ?></option>
                    <option value="weekday"><?php echo nl_t('ws_time_weekday'); ?></option>
                    <option value="weekend"><?php echo nl_t('ws_time_weekend'); ?></option>
                </select>
                <div class="nl-interest-form__checkboxes">
                    <p><strong><?php echo nl_t('ws_group'); ?></strong></p>
                    <label><input type="checkbox" name="group[]" value="solo"> <?php echo nl_t('ws_group_solo'); ?></label>
                    <label><input type="checkbox" name="group[]" value="friend"> <?php echo nl_t('ws_group_friend'); ?></label>
                    <label><input type="checkbox" name="group[]" value="join"> <?php echo nl_t('ws_group_join'); ?></label>
                    <label><input type="checkbox" name="group[]" value="any"> <?php echo nl_t('ws_group_any'); ?></label>
                </div>
            </div>
            <button type="submit" class="nl-interest-form__submit"><?php echo nl_t('ws_submit'); ?></button>
        </form>
    </div>

    <!-- Workshop Listings -->
    <h2 style="text-align:center;font-size:1.5rem;margin-bottom:30px;letter-spacing:3px">NEON DIY WORKSHOP</h2>

    <div class="nl-workshop-list">
        <?php foreach ($workshops as $ws) :
            // Try to find matching nl_workshop CPT for cover photo
            $cover_url = '';
            $ws_posts = get_posts([
                'post_type'      => 'nl_workshop',
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'name'           => $ws['id'],
            ]);
            if (!empty($ws_posts)) {
                $cover_id = get_post_meta($ws_posts[0]->ID, '_nl_workshop_cover', true);
                if ($cover_id) {
                    $cover_url = wp_get_attachment_image_url($cover_id, 'medium');
                }
            }
            if (!$cover_url) {
                $cover_url = get_template_directory_uri() . '/assets/images/' . $ws['image'];
            }
        ?>
        <div class="nl-workshop-card">
            <img src="<?php echo esc_url($cover_url); ?>"
                 alt="<?php echo esc_attr($ws['title']); ?>"
                 class="nl-workshop-card__image"
                 onerror="this.style.opacity='0';this.parentElement.style.gridTemplateColumns='1fr'">
            <div class="nl-workshop-card__info">
                <h3 class="nl-workshop-card__title">
                    <a href="<?php echo home_url('/workshop-detail/?id=' . esc_attr($ws['id'])); ?>" style="color:inherit;text-decoration:none;">
                        <?php echo nl_lang()==='en' ? esc_html($ws['title_en']) : esc_html($ws['title']); ?>
                    </a>
                </h3>
                <div class="nl-workshop-card__meta">
                    <span>💰 <?php echo esc_html($ws['price_display']); ?></span>
                </div>
                <button class="nl-workshop-card__btn js-open-booking"
                        data-workshop-id="<?php echo esc_attr($ws['id']); ?>"
                        data-title="<?php echo nl_lang()==='en' ? esc_attr($ws['title_en']) : esc_attr($ws['title']); ?>"
                        data-price="<?php echo esc_attr($ws['price']); ?>"
                        data-price-display="<?php echo esc_attr($ws['price_display']); ?>"><?php echo nl_t('ws_book'); ?></button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- Booking Modal -->
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
                <?php foreach ($locations as $idx=>$loc): ?>                <div class="nl-location-card" data-location="<?php echo $idx; ?>">
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

        <!-- Step 2: Date &amp; Time -->
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
                <input type="text" name="customer_name" required placeholder="Full name">
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_email_ph'); ?></label>
                <input type="email" name="customer_email" required>
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_phone_ph'); ?></label>
                <input type="tel" name="customer_phone" required placeholder="eg. 6123 4567">
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_group_size'); ?></label>
                <select name="group_size" id="groupSize" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6-10</option>
                    <option value="10">10+</option>
                </select>
            </div>
            <div class="nl-booking-field">
                <label><?php echo nl_t('ws_remarks'); ?></label>
                <textarea name="remarks" rows="3" placeholder="Any special requests..."></textarea>
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
document.addEventListener('DOMContentLoaded',function(){
    const modal=document.getElementById('bookingModal');
    const overlay=document.getElementById('bookingOverlay');
    const closeBtn=document.getElementById('bookingClose');
    let selectedLocation=null;
    let currentStep=1;

    // Open modal
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

    // 2-day advance restriction
    const dateInput=document.getElementById('bookingDate');
    const minDate=new Date();
    minDate.setDate(minDate.getDate()+2);
    dateInput.min=minDate.toISOString().split('T')[0];

    // Location selection
    let selectedLocationIdx=null;
    document.querySelectorAll('.nl-location-card').forEach(card=>{
        card.addEventListener('click',function(){
            document.querySelectorAll('.nl-location-card').forEach(c=>c.classList.remove('selected'));
            this.classList.add('selected');
            selectedLocation=parseInt(this.dataset.location);
            selectedLocationIdx=selectedLocation;
            document.getElementById('selectedLocation').value=selectedLocation;
            document.getElementById('btnStep1Next').disabled=false;
            // If Central (0) selected, re-validate date in case it's Wednesday
            validateStep2();
        });
    });

    // Step navigation
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

    // Date/time validation
    dateInput.addEventListener('change',validateStep2);
    document.getElementById('bookingTime').addEventListener('change',validateStep2);
    function validateStep2(){
        const dateVal=dateInput.value;
        const timeVal=document.getElementById('bookingTime').value;
        let err='';
        // 2-day advance
        if(dateVal){
            const sel=new Date(dateVal);
            const min=new Date(minDate.toISOString().split('T')[0]);
            if(sel<min){ err='Please book at least 2 days in advance.'; }
        }
        // Central closed on Wednesday (location 0)
        if(dateVal && selectedLocationIdx===0){
            const dow=new Date(dateVal).getDay();
            if(dow===3){ err='Central Pier 8 is closed on Wednesdays. Please choose another date.'; }
        }
        document.getElementById('btnStep2Next').disabled=!(dateVal&&timeVal&&!err);
        // Optional: show inline error if you want — for now just disable button
    }
});
</script>

<?php get_footer(); ?>
