<?php
/**
 * Import Wix blog posts into WordPress
 * Run inside docker container: docker exec nlwp_wordpress php /tmp/import_blog.php
 */
require "/var/www/html/wp-load.php";

// Check if user is admin
if (!current_user_can('administrator') && !defined('WP_ADMIN')) {
    define('WP_ADMIN', true);
}

// Import images function
function import_image($src_path, $filename, $post_id = 0) {
    $upload_dir = wp_upload_dir();
    $year_month = date('Y/m');
    $dest_dir = $upload_dir['basedir'] . '/' . $year_month;
    
    if (!file_exists($dest_dir)) {
        wp_mkdir_p($dest_dir);
    }
    
    $dest_path = $dest_dir . '/' . $filename;
    
    // Convert webp to jpg if needed
    if (strpos($filename, '.webp') !== false) {
        $new_filename = str_replace('.webp', '.jpg', $filename);
        $dest_path = $dest_dir . '/' . $new_filename;
        
        // Use imagick or gd to convert
        if (extension_loaded('imagick')) {
            $img = new Imagick($src_path);
            $img->setImageFormat('jpeg');
            $img->writeImage($dest_path);
            $img->destroy();
        } elseif (function_exists('imagecreatefromwebp')) {
            $img = imagecreatefromwebp($src_path);
            imagejpeg($img, $dest_path, 90);
            imagedestroy($img);
        } else {
            // Just copy as-is if no conversion available
            copy($src_path, $dest_path);
        }
        $filename = $new_filename;
    } else {
        copy($src_path, $dest_path);
    }
    
    // Create attachment post
    $attachment = array(
        'post_mime_type' => 'image/jpeg',
        'post_title'     => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
        'post_content'   => '',
        'post_status'    => 'inherit',
        'guid'           => $upload_dir['baseurl'] . '/' . $year_month . '/' . $filename
    );
    
    $attach_id = wp_insert_attachment($attachment, $dest_path, $post_id);
    
    if (!is_wp_error($attach_id)) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $dest_path);
        wp_update_attachment_metadata($attach_id, $attach_data);
    }
    
    return $attach_id;
}

// Get admin user ID
$admin = get_users(['role' => 'administrator', 'number' => 1])[0];
$author_id = $admin ? $admin->ID : 1;

// Image base path
$img_base = '/var/www/html/wp-content/uploads/wix-import/';

// Define posts
$posts = [
    [
        'title_zh' => '香港愛情故事',
        'title_en' => 'Hong Kong Love Story',
        'title_cn' => '香港爱情故事',
        'date' => '2021-02-25 12:00:00',
        'images' => [
            'hong-kong-love-story-1.webp',
            'hong-kong-love-story-2.webp',
            'hong-kong-love-story-3.webp',
            'hong-kong-love-story-4.webp',
            'hong-kong-love-story-5.webp',
            'hong-kong-love-story-6.webp',
        ],
        'content_zh' => '《香港愛情故事》由羅天宇、龔嘉欣、王敏奕、謝東閔及菊梓喬領銜主演。暖男設計師陳子朗與理想主義女友邱凱琪，是香港典型因土地問題而被迫流離浪蕩的情侶。子朗兩位妹妹陳子欣和陳子婷性格極端，攝影師子欣沉淪於藝術家紀家希的失樂園；品學兼優的大學女神子婷，愛之初體驗以現實先行，兩姊妹各有戀事。子朗、凱琪為覓安樂窩打拼，新婚後居於百多呎的劏房，除了要面對空間不足的生活磨擦外，還要處理雙方父母的暮年感情危機！小兩口排除萬難，置業美夢卻一夜變噩夢，為了一層樓竟忘記如何好好戀愛……',
        'content_en' => '"Hong Kong Love Story" stars Joey Law, Kaki Leung, Venus Wong, Brian Tse, and HANA. The warm-hearted designer Chan Tsz-long and his idealistic girlfriend Yau Hoi-ki are a typical Hong Kong couple forced to drift due to land issues. Tsz-long\'s two younger sisters, Chan Tsz-yan and Chan Tsz-ting, have extreme personalities. Photographer Tsz-yan indulges in artist Kei Ka-hei\'s paradise; the academically excellent university goddess Tsz-ting experiences love with reality first. The couple works hard for a comfortable home, living in a subdivided flat after marriage, facing not only space constraints but also their parents\' late-life relationship crisis! The young couple overcomes all difficulties, but their dream of owning a home turns into a nightmare overnight, forgetting how to love properly for the sake of an apartment...',
        'content_cn' => '《香港爱情故事》由罗天宇、龚嘉欣、王敏奕、谢东闵及菊梓乔领衔主演。暖男设计师陈子朗与理想主义女友邱凯琪，是香港典型因土地问题而被迫流离浪荡的情侣。子朗两位妹妹陈子欣和陈子婷性格极端，摄影师子欣沉沦于艺术家纪家希的失乐园；品学兼优的大学女神子婷，爱之初体验以现实先行，两姊妹各有恋事。子朗、凯琪为觅安乐窝打拼，新婚后居于百多呎的劏房，除了要面对空间不足的生活磨擦外，还要处理双方父母的暮年感情危机！小两口排除万难，置业美梦却一夜变噩梦，为了一层楼竟忘记如何好好恋爱……',
    ],
    [
        'title_zh' => '鼎家喜筷 DING LEE',
        'title_en' => 'Ding Lee Chopsticks',
        'title_cn' => '鼎家喜筷 DING LEE',
        'date' => '2021-01-12 12:00:00',
        'images' => [], // We'll check what images are available
        'content_zh' => '全新品牌「鼎家喜筷」終於登陸荃灣海之戀商場，鼎爺話今次新搞作將品牌年輕化，以年輕版的風格將傳統燒味活化，飲住花茶食燒味，為大眾帶來Chill食燒味新文化！\n\n地址：荃灣海之戀商場3012號舖',
        'content_en' => 'The brand new "Ding Lee Chopsticks" has finally landed at the Tsuen Wan Ocean Pride Mall. Uncle Ding says this new venture will rejuvenate the brand with a youthful style, revitalizing traditional siu mei culture with a modern twist. Enjoying flower tea with siu mei brings a new Chill food culture to the public!\n\nAddress: Shop 3012, Ocean Pride Mall, Tsuen Wan',
        'content_cn' => '全新品牌「鼎家喜筷」终于登陆荃湾海之恋商场，鼎爷话今次新搞作将品牌年轻化，以年轻版的风格将传统烧味活化，饮住花茶食烧味，为大众带来Chill食烧味新文化！\n\n地址：荃湾海之恋商场3012号舖',
    ],
];

echo "Starting import...\n";

foreach ($posts as $idx => $post_data) {
    echo "\n=== Importing: {$post_data['title_zh']} ===\n";
    
    // Import images
    $image_ids = [];
    $image_urls = [];
    foreach ($post_data['images'] as $img_file) {
        $src = $img_base . $img_file;
        if (file_exists($src)) {
            $attach_id = import_image($src, $img_file);
            if ($attach_id) {
                $image_ids[] = $attach_id;
                $url = wp_get_attachment_url($attach_id);
                $image_urls[] = $url;
                echo "  Image imported: $url\n";
            }
        } else {
            echo "  Image not found: $src\n";
        }
    }
    
    // Build content with images
    $content_zh = wpautop($post_data['content_zh']);
    $content_en = wpautop($post_data['content_en']);
    $content_cn = wpautop($post_data['content_cn']);
    
    foreach ($image_urls as $url) {
        $img_html = "\n<img src=\"$url\" alt=\"\" style=\"max-width:100%; height:auto; margin: 20px 0;\" />\n";
        $content_zh .= $img_html;
        $content_en .= $img_html;
        $content_cn .= $img_html;
    }
    
    // Create ZH post
    $zh_id = wp_insert_post([
        'post_title'   => $post_data['title_zh'],
        'post_content' => $content_zh,
        'post_status'  => 'publish',
        'post_type'    => 'post',
        'post_author'  => $author_id,
        'post_date'    => $post_data['date'],
    ]);
    
    if (is_wp_error($zh_id)) {
        echo "ERROR creating ZH post: " . $zh_id->get_error_message() . "\n";
        continue;
    }
    
    echo "  ZH post created: ID $zh_id\n";
    
    // Set featured image
    if (!empty($image_ids)) {
        set_post_thumbnail($zh_id, $image_ids[0]);
    }
    
    // Create EN translation as separate post
    $en_id = wp_insert_post([
        'post_title'   => $post_data['title_en'],
        'post_content' => $content_en,
        'post_status'  => 'publish',
        'post_type'    => 'post',
        'post_author'  => $author_id,
        'post_date'    => $post_data['date'],
    ]);
    
    if (!is_wp_error($en_id)) {
        echo "  EN post created: ID $en_id\n";
        if (!empty($image_ids)) {
            set_post_thumbnail($en_id, $image_ids[0]);
        }
    }
    
    // Create CN translation as separate post
    $cn_id = wp_insert_post([
        'post_title'   => $post_data['title_cn'],
        'post_content' => $content_cn,
        'post_status'  => 'publish',
        'post_type'    => 'post',
        'post_author'  => $author_id,
        'post_date'    => $post_data['date'],
    ]);
    
    if (!is_wp_error($cn_id)) {
        echo "  CN post created: ID $cn_id\n";
        if (!empty($image_ids)) {
            set_post_thumbnail($cn_id, $image_ids[0]);
        }
    }
    
    // Store translation relationships in post meta (since Polylang not active in CLI)
    update_post_meta($zh_id, '_nl_translations', ['en' => $en_id, 'cn' => $cn_id]);
    update_post_meta($en_id, '_nl_translations', ['zh' => $zh_id, 'cn' => $cn_id]);
    update_post_meta($cn_id, '_nl_translations', ['zh' => $zh_id, 'en' => $en_id]);
}

echo "\n=== Import complete ===\n";
