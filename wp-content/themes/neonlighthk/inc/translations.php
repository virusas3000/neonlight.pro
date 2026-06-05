<?php
/**
 * Trilingual Translation System — EN / 繁中(zh) / 简中(cn)
 * @package NeonLightHK
 */

if (!defined('ABSPATH')) exit;

function nl_lang() {
    $supported = ['en', 'zh', 'cn'];
    if (isset($_GET['lang']) && in_array($_GET['lang'], $supported)) {
        $lang = $_GET['lang'];
        setcookie('nl_lang', $lang, time()+86400*30, '/', '', false, true);
        return $lang;
    }
    if (isset($_COOKIE['nl_lang']) && in_array($_COOKIE['nl_lang'], $supported)) {
        return $_COOKIE['nl_lang'];
    }
    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $accept = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
        $full   = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        if ($accept === 'zh') {
            if (strpos($full, 'zh-cn') !== false || strpos($full, 'zh-hans') !== false || strpos($full, 'zh-sg') !== false) {
                return 'cn';
            }
            return 'zh';
        }
        if ($accept === 'en') return 'en';
    }
    return 'zh';
}

function nl_t($key) {
    $lang = nl_lang();
    $dict = nl_dictionary();
    return $dict[$key][$lang] ?? $dict[$key]['zh'] ?? $dict[$key]['en'] ?? $key;
}

function nl_dictionary() {
    return [
        // Navigation
        'nav_shop'      => ['en'=>'SHOP',       'zh'=>'現貨',       'cn'=>'现货'],
        'nav_shop_bi'   => ['en'=>'SHOP',       'zh'=>'現貨·SHOP',  'cn'=>'现货·SHOP'],
        'nav_rent'      => ['en'=>'RENT',       'zh'=>'租借',       'cn'=>'租借'],
        'nav_rent_bi'   => ['en'=>'RENT',       'zh'=>'租借·RENT',  'cn'=>'租借·RENT'],
        'nav_order'     => ['en'=>'ORDER',      'zh'=>'訂製',       'cn'=>'订制'],
        'nav_order_bi'  => ['en'=>'ORDER',      'zh'=>'訂製·ORDER', 'cn'=>'订制·ORDER'],
        'nav_workshop'  => ['en'=>'WORKSHOP',   'zh'=>'工作坊',     'cn'=>'工作坊'],
        'nav_workshop_bi'=>['en'=>'WORKSHOP',   'zh'=>'工作坊·WORKSHOP','cn'=>'工作坊·WORKSHOP'],
        'nav_projects'  => ['en'=>'PROJECTS',   'zh'=>'活動',       'cn'=>'活动'],
        'nav_projects_bi'=>['en'=>'PROJECTS',   'zh'=>'活動·PROJECTS','cn'=>'活动·PROJECTS'],
        'nav_lookbook'  => ['en'=>'LOOKBOOK',   'zh'=>'範例',       'cn'=>'范例'],
        'nav_lookbook_bi'=>['en'=>'LOOKBOOK',   'zh'=>'範例·LOOKBOOK','cn'=>'范例·LOOKBOOK'],
        'nav_about'     => ['en'=>'ABOUT',      'zh'=>'關於',       'cn'=>'关于'],
        'nav_about_bi'  => ['en'=>'ABOUT',      'zh'=>'關於·ABOUT', 'cn'=>'关于·ABOUT'],
        'nav_products'  => ['en'=>'PRODUCTS',   'zh'=>'產品',       'cn'=>'产品'],
        'nav_products_bi'=>['en'=>'PRODUCTS',   'zh'=>'產品·PRODUCTS','cn'=>'产品·PRODUCTS'],
        'nav_hanfu'     => ['en'=>'HANFU',      'zh'=>'漢服',       'cn'=>'汉服'],
        'nav_hanfu_bi'  => ['en'=>'HANFU',      'zh'=>'漢服·HANFU', 'cn'=>'汉服·HANFU'],
        'nav_balloon'   => ['en'=>'BALLOON & MAGIC', 'zh'=>'氣球 & 魔術', 'cn'=>'气球 & 魔术'],
        'nav_balloon_bi'=> ['en'=>'BALLOON & MAGIC', 'zh'=>'氣球 & 魔術·BALLOON & MAGIC', 'cn'=>'气球 & 魔术·BALLOON & MAGIC'],
        'nav_neon'      => ['en'=>'Neon Services', 'zh'=>'霓虹燈服務', 'cn'=>'霓虹灯服务'],
        'nav_neon_bi'   => ['en'=>'Neon Services', 'zh'=>'現貨·租借·訂製·Neon', 'cn'=>'现货·租借·订制·Neon'],
        'nav_lookbook_about' => ['en'=>'Lookbook & About', 'zh'=>'範例·關於', 'cn'=>'范例·关于'],
        'nav_lookbook_about_bi' => ['en'=>'Lookbook & About', 'zh'=>'範例·LOOKBOOK & 關於·ABOUT', 'cn'=>'范例·LOOKBOOK & 关于·ABOUT'],
        'nav_about_lookbook' => ['en'=>'About Us · Lookbook', 'zh'=>'關於我們 · 範例', 'cn'=>'关于我们 · 范例'],
        'nav_about_lookbook_bi' => ['en'=>'About Us · Lookbook', 'zh'=>'關於我們·ABOUT & 範例·LOOKBOOK', 'cn'=>'关于我们·ABOUT & 范例·LOOKBOOK'],

        // Lookbook & About page
        'lookbook_about_title' => ['en'=>'Lookbook & About Us', 'zh'=>'範例·關於我們', 'cn'=>'范例·关于我们'],
        'about_heading' => ['en'=>'About Us', 'zh'=>'關於我們', 'cn'=>'关于我们'],
        'about_story' => ['en'=>'Neon Light HK is a creative studio specializing in neon sign design and production. We bring your ideas to life with custom neon creations for businesses, events, and personal spaces.', 'zh'=>'Neon Light HK 是一家專注於霓虹燈設計及製作的創意工作室。我們為商業、活動及個人空間打造獨特的霓虹燈作品。', 'cn'=>'Neon Light HK 是一家专注于霓虹灯设计及制作的创意工作室。我们为商业、活动及个人空间打造独特的霓虹灯作品。'],
        'about_services_heading' => ['en'=>'Our Services', 'zh'=>'服務範圍', 'cn'=>'服务范围'],
        'service_neon' => ['en'=>'Neon Sign Design & Production', 'zh'=>'霓虹燈設計及製作', 'cn'=>'霓虹灯设计及制作'],
        'service_rental' => ['en'=>'Neon Sign Rental', 'zh'=>'霓虹燈租借', 'cn'=>'霓虹灯租借'],
        'service_workshop' => ['en'=>'Neon Sign Workshop', 'zh'=>'霓虹燈工作坊', 'cn'=>'霓虹灯工作坊'],
        'service_products' => ['en'=>'Neon Products', 'zh'=>'霓虹燈產品', 'cn'=>'霓虹灯产品'],
        'service_balloon' => ['en'=>'Balloon & Magic', 'zh'=>'氣球 & 魔術', 'cn'=>'气球 & 魔术'],
        'service_hanfu' => ['en'=>'Hanfu (Traditional Chinese Clothing)', 'zh'=>'漢服', 'cn'=>'汉服'],
        'lookbook_heading' => ['en'=>'Our Works', 'zh'=>'作品參考', 'cn'=>'作品参考'],

        // Neon Services page
        'neon_services_title' => ['en'=>'Neon Services', 'zh'=>'霓虹燈服務', 'cn'=>'霓虹灯服务'],
        'neon_services_hero_zh' => ['en'=>'Design & Customise Neon', 'zh'=>'設計·訂製霓虹燈', 'cn'=>'设计·订制霓虹灯'],
        'neon_services_hero_en' => ['en'=>'CUSTOMISE', 'zh'=>'CUSTOMISE', 'cn'=>'CUSTOMISE'],
        'neon_services_hero_sub' => ['en'=>'ORDER · DESIGN · PRODUCTION', 'zh'=>'訂單·設計·製作', 'cn'=>'订单·设计·制作'],
        'neon_custom_design' => ['en'=>'Custom Design', 'zh'=>'來圖訂製', 'cn'=>'来图订制'],
        'neon_custom_design_en' => ['en'=>'SEND US YOUR DESIGN', 'zh'=>'SEND US YOUR DESIGN', 'cn'=>'SEND US YOUR DESIGN'],
        'neon_custom_desc_zh' => ['en'=>'Welcome to send us your designs and we will proceed to LED neon light production according to your artwork', 'zh'=>'歡迎將設計圖發給我們 我們會跟據你的圖檔製作霓虹燈\n(檔案模式：.ai/.ps/.jpg)', 'cn'=>'欢迎将设计图发给我们 我们会根据你的图档制作霓虹灯\n(档案模式：.ai/.ps/.jpg)'],
        'neon_custom_desc_en' => ['en'=>'Welcome to send us your designs and we will proceed to LED neon light production according to your artwork', 'zh'=>'Welcome to send us your designs and we will proceed to LED neon light production according to your artwork', 'cn'=>'Welcome to send us your designs and we will proceed to LED neon light production according to your artwork'],
        'neon_design_service' => ['en'=>'Design Service', 'zh'=>'設計服務', 'cn'=>'设计服务'],
        'neon_design_service_en' => ['en'=>'DESIGN SERVICE', 'zh'=>'DESIGN SERVICE', 'cn'=>'DESIGN SERVICE'],
        'neon_design_desc_zh' => ['en'=>'Welcome to send us references or let us know your concept idea, we will custom design the neon light for you', 'zh'=>'歡迎跟我們說出你的概念或想法 亦可以將參考圖發給我們\n我們的設計團隊會為你度身製作', 'cn'=>'欢迎跟我们说出你的概念或想法 亦可以将参考图发给我们\n我们的设计团队会为你度身制作'],
        'neon_design_desc_en' => ['en'=>'Welcome to send us references or let us know your concept idea, we will custom design the neon light for you', 'zh'=>'Welcome to send us references or let us know your concept idea, we will custom design the neon light for you', 'cn'=>'Welcome to send us references or let us know your concept idea, we will custom design the neon light for you'],
        'neon_quote_btn' => ['en'=>'QUOTE', 'zh'=>'報價', 'cn'=>'报价'],

        // Hero
        'hero_label'    => ['en'=>'Neon Sign Design & Production', 'zh'=>'霓虹燈設計及製作', 'cn'=>'霓虹灯设计及制作'],
        'hero_title'    => ['en'=>'NEON SIGNS', 'zh'=>'霓虹燈招牌', 'cn'=>'霓虹灯招牌'],
        'hero_subtitle' => ['en'=>'DESIGN & PRODUCTION', 'zh'=>'設計及製作', 'cn'=>'设计及制作'],
        'hero_cta'      => ['en'=>'LEARN MORE', 'zh'=>'了解更多',   'cn'=>'了解更多'],

        // Service Cards
        'card_purchase' => ['en'=>'PURCHASE',   'zh'=>'購買現貨',   'cn'=>'购买现货'],
        'card_customise'=> ['en'=>'CUSTOMISE',  'zh'=>'訂製設計',   'cn'=>'订制设计'],
        'card_workshop' => ['en'=>'WORKSHOP',   'zh'=>'工作坊',     'cn'=>'工作坊'],
        'card_rental'   => ['en'=>'RENTAL',     'zh'=>'租借服務',   'cn'=>'租借服务'],

        // Gallery
        'gallery_title' => ['en'=>'OUR WORKS',  'zh'=>'作品參考',   'cn'=>'作品参考'],
        'gallery_ig'    => ['en'=>'INSTAGRAM @ NEONLIGHTHK', 'zh'=>'INSTAGRAM @ NEONLIGHTHK', 'cn'=>'INSTAGRAM @ NEONLIGHTHK'],
        'gallery_more'  => ['en'=>'MORE',       'zh'=>'更多',       'cn'=>'更多'],

        // Visit
        'visit_title'   => ['en'=>'VISIT US',    'zh'=>'來訪我們',   'cn'=>'来访我们'],
        'visit_at'      => ['en'=>'at',          'zh'=>'於',         'cn'=>'于'],
        'visit_addr1'   => ['en'=>'U/F,Central Pier 8,Hong Kong · 香港中環8號碼頭U層', 'zh'=>'香港中環8號碼頭U層 · U/F,Central Pier 8,Hong Kong', 'cn'=>'香港中环8号码头U层 · U/F,Central Pier 8,Hong Kong'],
        'visit_hours'   => ['en'=>'Mon – Sat: 12:00 – 19:00<br>Sun & Public Holidays: Closed', 'zh'=>'週一至週六：12:00 – 19:00<br>週日及公眾假期：休息', 'cn'=>'周一至周六：12:00 – 19:00<br>周日及公众假期：休息'],

        // Clients
        'clients_title' => ['en'=>'OUR BELOVED CLIENTS', 'zh'=>'客戶支持', 'cn'=>'客户支持'],

        // Contact
        'contact_title' => ['en'=>'CONTACT US',  'zh'=>'聯絡我們',   'cn'=>'联络我们'],
        'contact_name'  => ['en'=>'Name',        'zh'=>'姓名',       'cn'=>'姓名'],
        'contact_email' => ['en'=>'Email',       'zh'=>'電郵',       'cn'=>'电邮'],
        'contact_phone' => ['en'=>'Phone No.',   'zh'=>'電話',       'cn'=>'电话'],
        'contact_subject'=>['en'=>'Subject',     'zh'=>'主旨',       'cn'=>'主旨'],
        'contact_message'=>['en'=>'Message / Budget', 'zh'=>'訊息 / 預算', 'cn'=>'讯息 / 预算'],
        'contact_send'  => ['en'=>'SEND',        'zh'=>'發送',       'cn'=>'发送'],

        // Workshop
        'ws_title'      => ['en'=>'WORKSHOP',    'zh'=>'工作坊',     'cn'=>'工作坊'],
        'ws_contact'    => ['en'=>'Contact Us',  'zh'=>'聯絡我們',   'cn'=>'联络我们'],
        'ws_whatsapp'   => ['en'=>'WhatsApp',    'zh'=>'WhatsApp',   'cn'=>'WhatsApp'],
        'ws_email'      => ['en'=>'Email',       'zh'=>'電郵',       'cn'=>'电邮'],
        'ws_apply_title'=> ['en'=>'Mini Neon Workshop Application', 'zh'=>'小型霓虹燈 · 工作坊報名', 'cn'=>'小型霓虹灯 · 工作坊报名'],
        'ws_apply_desc' => ['en'=>'If you are interested in joining our future neon workshops and events, please fill in the following information!', 'zh'=>'如欲參與我們未來舉辦的霓虹工作坊及趣味活動，歡迎填寫以下資料。', 'cn'=>'如欲参与我们未来举办的霓虹工作坊及趣味活动，欢迎填写以下资料。'],
        'ws_first_name' => ['en'=>'First name',  'zh'=>'姓氏',       'cn'=>'姓氏'],
        'ws_last_name'  => ['en'=>'Last name',   'zh'=>'名字',       'cn'=>'名字'],
        'ws_email_ph'   => ['en'=>'Email',       'zh'=>'電郵',       'cn'=>'电邮'],
        'ws_phone_ph'   => ['en'=>'Phone',       'zh'=>'電話',       'cn'=>'电话'],
        'ws_age'        => ['en'=>'Age Range',   'zh'=>'年齡',       'cn'=>'年龄'],
        'ws_age_u18'    => ['en'=>'Under 18',    'zh'=>'18歲以下',   'cn'=>'18岁以下'],
        'ws_age_18_25'  => ['en'=>'18-25',       'zh'=>'18-25',     'cn'=>'18-25'],
        'ws_age_26_35'  => ['en'=>'26-35',       'zh'=>'26-35',     'cn'=>'26-35'],
        'ws_age_36_50'  => ['en'=>'36-50',       'zh'=>'36-50',     'cn'=>'36-50'],
        'ws_age_50'     => ['en'=>'50+',         'zh'=>'50歲以上',   'cn'=>'50岁以上'],
        'ws_theme'      => ['en'=>'Workshop theme that you would be interested', 'zh'=>'有興趣的工作坊主題', 'cn'=>'有兴趣的工作坊主题'],
        'ws_theme_trad' => ['en'=>'Traditional HK', 'zh'=>'傳統香港', 'cn'=>'传统香港'],
        'ws_theme_love' => ['en'=>'Love / Couple', 'zh'=>'情侶款',   'cn'=>'情侣款'],
        'ws_theme_fest' => ['en'=>'Festive',     'zh'=>'節日',       'cn'=>'节日'],
        'ws_theme_name' => ['en'=>'Name / Initials', 'zh'=>'名字 / 字母', 'cn'=>'名字 / 字母'],
        'ws_theme_pets' => ['en'=>'Pets',        'zh'=>'寵物',       'cn'=>'宠物'],
        'ws_theme_kids' => ['en'=>'Kids',        'zh'=>'小童',       'cn'=>'小童'],
        'ws_theme_other'=> ['en'=>'Other',       'zh'=>'其他',       'cn'=>'其他'],
        'ws_time'       => ['en'=>'Best Time for You', 'zh'=>'最佳時段', 'cn'=>'最佳时段'],
        'ws_time_weekday'=>['en'=>'Weekday',    'zh'=>'平日',       'cn'=>'平日'],
        'ws_time_weekend'=>['en'=>'Weekend',    'zh'=>'週末',       'cn'=>'周末'],
        'ws_group'      => ['en'=>'Group Size Preferred', 'zh'=>'理想人數', 'cn'=>'理想人数'],
        'ws_group_solo' => ['en'=>'Me only',     'zh'=>'自己',       'cn'=>'自己'],
        'ws_group_friend'=>['en'=>'I will bring a friend!', 'zh'=>'攜眷', 'cn'=>'携眷'],
        'ws_group_join' => ['en'=>'I\'d love to meet new people!', 'zh'=>'加入別組', 'cn'=>'加入别组'],
        'ws_group_any'  => ['en'=>'I\'m good with any group size', 'zh'=>'任何', 'cn'=>'任何'],
        'ws_submit'     => ['en'=>'Submit',      'zh'=>'提交',       'cn'=>'提交'],
        'ws_diy_title'  => ['en'=>'DIY Neon',    'zh'=>'中環PMQ霓虹燈工作坊', 'cn'=>'中环PMQ霓虹灯工作坊'],
        'ws_diy_sub'    => ['en'=>'PMQ Workshop (1 person+)', 'zh'=>'PMQ工作坊（1人起）', 'cn'=>'PMQ工作坊（1人起）'],
        'ws_onsite_title'=>['en'=>'On-site Workshop', 'zh'=>'霓虹燈工作坊 到會', 'cn'=>'霓虹灯工作坊 到会'],
        'ws_onsite_sub' => ['en'=>'Welcome groups of 10+', 'zh'=>'歡迎10人或以上活動', 'cn'=>'欢迎10人或以上活动'],
        'ws_themed_title'=>['en'=>'Themed Workshop', 'zh'=>'主題霓虹燈工作坊', 'cn'=>'主题霓虹灯工作坊'],
        'ws_themed_sub' => ['en'=>'Welcome groups of 8+', 'zh'=>'歡迎8人或以上活動', 'cn'=>'欢迎8人或以上活动'],
        'ws_duration'   => ['en'=>'Duration',    'zh'=>'時長',       'cn'=>'时长'],
        'ws_price'      => ['en'=>'Price',       'zh'=>'價格',       'cn'=>'价格'],
        'ws_book'       => ['en'=>'Request to Book', 'zh'=>'預約報名', 'cn'=>'预约报名'],
        'ws_step1'      => ['en'=>'Select Location', 'zh'=>'選擇地點', 'cn'=>'选择地点'],
        'ws_step2'      => ['en'=>'Select Date & Time', 'zh'=>'選擇日期及時間', 'cn'=>'选择日期及时间'],
        'ws_step3'      => ['en'=>'Fill Booking Form', 'zh'=>'填寫資料', 'cn'=>'填写资料'],
        'ws_step4'      => ['en'=>'Payment',     'zh'=>'付款',       'cn'=>'付款'],
        'ws_date'       => ['en'=>'Date',        'zh'=>'日期',       'cn'=>'日期'],
        'ws_time_sel'   => ['en'=>'Select time', 'zh'=>'選擇時間',   'cn'=>'选择时间'],
        'ws_name'       => ['en'=>'Name',        'zh'=>'姓名',       'cn'=>'姓名'],
        'ws_group_size' => ['en'=>'Number of People', 'zh'=>'人數', 'cn'=>'人数'],
        'ws_remarks'    => ['en'=>'Remarks',     'zh'=>'備註',       'cn'=>'备注'],
        'ws_next'       => ['en'=>'Next',        'zh'=>'下一步',     'cn'=>'下一步'],
        'ws_back'       => ['en'=>'Back',        'zh'=>'上一步',     'cn'=>'上一步'],
        'ws_pay_now'    => ['en'=>'Pay Now',     'zh'=>'立即付款',   'cn'=>'立即付款'],
        'ws_booking_saved'=>['en'=>'Booking submitted! We will confirm via WhatsApp or email shortly.', 'zh'=>'預約已提交！我們會盡快透過WhatsApp或電郵與您確認。', 'cn'=>'预约已提交！我们会尽快透过WhatsApp或电邮与您确认。'],
        'ws_booking_error'=>['en'=>'Something went wrong. Please WhatsApp 6131 9328 for enquiries.', 'zh'=>'提交失敗。請致電/WhatsApp 6131 9328 查詢。', 'cn'=>'提交失败。请致电/WhatsApp 6131 9328 查询。'],
        'ws_advance'    => ['en'=>'Must book 2 days in advance', 'zh'=>'課程需要兩日前預約', 'cn'=>'课程需要两日前预约'],
        'ws_confirm'    => ['en'=>'Payment confirms your booking. To reschedule, notify us 48 hours in advance.', 'zh'=>'付款後即確認預約。如需更改日期，請於48小時前通知。', 'cn'=>'付款后即确认预约。如需更改日期，请于48小时前通知。'],

        // Footer
        'footer_about'  => ['en'=>'ABOUT',       'zh'=>'關於',       'cn'=>'关于'],
        'footer_services'=>['en'=>'SERVICES',    'zh'=>'服務',       'cn'=>'服务'],
        'footer_legal'  => ['en'=>'LEGAL',       'zh'=>'條款',       'cn'=>'条款'],
        'footer_copyright'=>['en'=>'Cheezo Group Limited. All Rights Reserved.', 'zh'=>'Cheezo Group Limited. 版權所有。', 'cn'=>'Cheezo Group Limited. 版权所有。'],

        // Misc
        'shop_coming'   => ['en'=>'Coming soon — our neon sign shop is under construction.', 'zh'=>'即將開業 — 我們的霓虹燈商店正在籌備中。', 'cn'=>'即将开业 — 我们的霓虹灯商店正在筹备中。'],
        'shop_no_image' => ['en'=>'No image',      'zh'=>'暫無圖片',    'cn'=>'暂无图片'],
        'shop_breadcrumb_home' => ['en'=>'Home',     'zh'=>'主頁',       'cn'=>'主页'],
        'shop_title'    => ['en'=>'Neon Retail',   'zh'=>'霓虹燈零售',   'cn'=>'霓虹灯零售'],
        'shop_showing'  => ['en'=>'Showing',       'zh'=>'顯示',       'cn'=>'显示'],
        'shop_of'       => ['en'=>'of',            'zh'=>'共',         'cn'=>'共'],
        'shop_results'  => ['en'=>'results',       'zh'=>'件結果',     'cn'=>'件结果'],
        'shop_sort_default'=> ['en'=>'Default sorting', 'zh'=>'預設排序', 'cn'=>'默认排序'],
        'shop_sale'     => ['en'=>'Sale!',         'zh'=>'特價！',      'cn'=>'特价！'],
        'wc_add_to_cart'=> ['en'=>'Add to Cart',   'zh'=>'加入購物車',  'cn'=>'加入购物车'],
        'wc_quantity'   => ['en'=>'Quantity:',     'zh'=>'數量：',     'cn'=>'数量：'],
        'wc_related_products'=> ['en'=>'Related Products', 'zh'=>'相關產品', 'cn'=>'相关产品'],
        'rental_title'  => ['en'=>'Rental',        'zh'=>'霓虹燈租借',   'cn'=>'霓虹灯租借'],
        'balloon_title' => ['en'=>'Balloon & Magic', 'zh'=>'氣球 & 魔術', 'cn'=>'气球 & 魔术'],
        'balloon_coming'=> ['en'=>'Balloon & Magic rental coming soon — please contact us for enquiries.', 'zh'=>'氣球及魔術租借服務即將推出，請聯絡我們查詢詳情。', 'cn'=>'气球及魔术租借服务即将推出，请联络我们查询详情。'],
        'products_title'=> ['en'=>'Products', 'zh'=>'產品', 'cn'=>'产品'],
        'products_coming'=>['en'=>'Products coming soon — please contact us for enquiries.', 'zh'=>'產品即將推出，請聯絡我們查詢詳情。', 'cn'=>'产品即将推出，请联络我们查询详情。'],
        'neon_products_title'=> ['en'=>'Neon Products', 'zh'=>'霓虹燈產品', 'cn'=>'霓虹灯产品'],
        'neon_products_coming'=>['en'=>'Neon products coming soon — please contact us for enquiries.', 'zh'=>'霓虹燈產品即將推出，請聯絡我們查詢詳情。', 'cn'=>'霓虹灯产品即将推出，请联络我们查询详情。'],
        'rental_coming' => ['en'=>'Rental service coming soon — please contact us for enquiries.', 'zh'=>'霓虹燈租借服務即將推出，請聯絡我們查詢詳情。', 'cn'=>'霓虹灯租借服务即将推出，请联络我们查询详情。'],
        'order_desc'    => ['en'=>'Please fill in the form below and we will contact you soon.', 'zh'=>'請填寫以下表格，我們會盡快與您聯絡。', 'cn'=>'请填写以下表格，我们会尽快与您联络。'],
        'order_concept' => ['en'=>'Design concept', 'zh'=>'設計理念', 'cn'=>'设计理念'],
        'lang_en'       => ['en'=>'English',     'zh'=>'English',    'cn'=>'English'],
        'lang_zh'       => ['en'=>'繁體中文',     'zh'=>'繁體中文',   'cn'=>'繁体中文'],
        'lang_cn'       => ['en'=>'简体中文',     'zh'=>'简体中文',   'cn'=>'简体中文'],
    ];
}
