<?php
require "/var/www/html/wp-load.php";

// Get admin user
$admin = get_users(['role' => 'administrator', 'number' => 1])[0];
$author_id = $admin ? $admin->ID : 1;

$img_base = '/var/www/html/wp-content/uploads/wix-import/';

// Import image function
function import_wix_image($src_path, $filename, $post_id = 0) {
    $upload_dir = wp_upload_dir();
    $year_month = date('Y/m');
    $dest_dir = $upload_dir['basedir'] . '/' . $year_month;
    
    if (!file_exists($dest_dir)) wp_mkdir_p($dest_dir);
    
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $basename = pathinfo($filename, PATHINFO_FILENAME);
    
    // Convert webp to jpg
    if ($ext === 'webp') {
        $dest_filename = $basename . '.jpg';
        $dest_path = $dest_dir . '/' . $dest_filename;
        
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
            copy($src_path, $dest_path);
        }
        $mime = 'image/jpeg';
    } else {
        $dest_filename = $filename;
        $dest_path = $dest_dir . '/' . $dest_filename;
        copy($src_path, $dest_path);
        $mime = 'image/jpeg';
    }
    
    $attachment = array(
        'post_mime_type' => $mime,
        'post_title'     => sanitize_file_name($basename),
        'post_content'   => '',
        'post_status'    => 'inherit',
        'guid'           => $upload_dir['baseurl'] . '/' . $year_month . '/' . $dest_filename
    );
    
    $attach_id = wp_insert_attachment($attachment, $dest_path, $post_id);
    
    if (!is_wp_error($attach_id)) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $dest_path);
        wp_update_attachment_metadata($attach_id, $attach_data);
    }
    
    return $attach_id;
}

// Define posts
$posts = [
    [
        'date' => '2021-02-25 12:00:00',
        'images' => [
            'hong-kong-love-story-1.webp',
            'hong-kong-love-story-2.webp',
            'hong-kong-love-story-3.webp',
            'hong-kong-love-story-4.webp',
            'hong-kong-love-story-5.webp',
            'hong-kong-love-story-6.webp',
        ],
        'zh' => ['title' => '香港愛情故事', 'content' => '《香港愛情故事》由羅天宇、龔嘉欣、王敏奕、謝東閔及菊梓喬領銜主演。暖男設計師陳子朗與理想主義女友邱凱琪，是香港典型因土地問題而被迫流離浪蕩的情侶。子朗兩位妹妹陳子欣和陳子婷性格極端，攝影師子欣沉淪於藝術家紀家希的失樂園；品學兼優的大學女神子婷，愛之初體驗以現實先行，兩姊妹各有戀事。子朗、凱琪為覓安樂窩打拼，新婚後居於百多呎的劏房，除了要面對空間不足的生活磨擦外，還要處理雙方父母的暮年感情危機！小兩口排除萬難，置業美夢卻一夜變噩夢，為了一層樓竟忘記如何好好戀愛……'],
        'en' => ['title' => 'Hong Kong Love Story', 'content' => '"Hong Kong Love Story" stars Joey Law, Kaki Leung, Venus Wong, Brian Tse, and HANA. The warm-hearted designer Chan Tsz-long and his idealistic girlfriend Yau Hoi-ki are a typical Hong Kong couple forced to drift due to land issues. Tsz-long\'s two younger sisters, Chan Tsz-yan and Chan Tsz-ting, have extreme personalities. Photographer Tsz-yan indulges in artist Kei Ka-hei\'s paradise; the academically excellent university goddess Tsz-ting experiences love with reality first. The couple works hard for a comfortable home, living in a subdivided flat after marriage, facing not only space constraints but also their parents\' late-life relationship crisis! The young couple overcomes all difficulties, but their dream of owning a home turns into a nightmare overnight, forgetting how to love properly for the sake of an apartment...'],
        'cn' => ['title' => '香港爱情故事', 'content' => '《香港爱情故事》由罗天宇、龚嘉欣、王敏奕、谢东闵及菊梓乔领衔主演。暖男设计师陈子朗与理想主义女友邱凯琪，是香港典型因土地问题而被迫流离浪荡的情侣。子朗两位妹妹陈子欣和陈子婷性格极端，摄影师子欣沉沦于艺术家纪家希的失乐园；品学兼优的大学女神子婷，爱之初体验以现实先行，两姊妹各有恋事。子朗、凯琪为觅安乐窝打拼，新婚后居于百多呎的劏房，除了要面对空间不足的生活磨擦外，还要处理双方父母的暮年感情危机！小两口排除万难，置业美梦却一夜变噩梦，为了一层楼竟忘记如何好好恋爱……'],
    ],
    [
        'date' => '2021-01-12 12:00:00',
        'images' => [],
        'zh' => ['title' => '鼎家喜筷 DING LEE', 'content' => "全新品牌「鼎家喜筷」終於登陸荃灣海之戀商場，鼎爺話今次新搞作將品牌年輕化，以年輕版的風格將傳統燒味活化，飲住花茶食燒味，為大眾帶來Chill食燒味新文化！\n\n地址：荃灣海之戀商場3012號舖"],
        'en' => ['title' => 'Ding Lee Chopsticks', 'content' => "The brand new \"Ding Lee Chopsticks\" has finally landed at the Tsuen Wan Ocean Pride Mall. Uncle Ding says this new venture will rejuvenate the brand with a youthful style, revitalizing traditional siu mei culture with a modern twist. Enjoying flower tea with siu mei brings a new Chill food culture to the public!\n\nAddress: Shop 3012, Ocean Pride Mall, Tsuen Wan"],
        'cn' => ['title' => '鼎家喜筷 DING LEE', 'content' => "全新品牌「鼎家喜筷」终于登陆荃湾海之恋商场，鼎爷话今次新搞作将品牌年轻化，以年轻版的风格将传统烧味活化，饮住花茶食烧味，为大众带来Chill食烧味新文化！\n\n地址：荃湾海之恋商场3012号舖"],
    ],
];

echo "Starting blog import...\n\n";

foreach ($posts as $idx => $post_data) {
    echo "=== Post group: {$post_data['zh']['title']} ===\n";
    
    // Import images (once per group)
    $image_ids = [];
    $image_urls = [];
    foreach ($post_data['images'] as $img_file) {
        $src = $img_base . $img_file;
        if (file_exists($src)) {
            $attach_id = import_wix_image($src, $img_file);
            if ($attach_id) {
                $image_ids[] = $attach_id;
                $url = wp_get_attachment_url($attach_id);
                $image_urls[] = $url;
                echo "  Image: $url\n";
            }
        } else {
            echo "  Image missing: $img_file\n";
        }
    }
    
    $featured_id = !empty($image_ids) ? $image_ids[0] : 0;
    
    // Create posts for each language
    foreach (['zh', 'en', 'cn'] as $lang) {
        $data = $post_data[$lang];
        
        // Build content with images
        $content = wpautop($data['content']);
        foreach ($image_urls as $url) {
            $content .= "\n<img src=\"$url\" alt=\"\" style=\"max-width:100%; height:auto; margin: 20px 0;\" />\n";
        }
        
        $post_id = wp_insert_post([
            'post_title'   => $data['title'],
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'post',
            'post_author'  => $author_id,
            'post_date'    => $post_data['date'],
        ]);
        
        if (is_wp_error($post_id)) {
            echo "  ERROR ($lang): " . $post_id->get_error_message() . "\n";
            continue;
        }
        
        // Store language meta
        update_post_meta($post_id, '_nl_post_lang', $lang);
        
        // Set featured image
        if ($featured_id) {
            set_post_thumbnail($post_id, $featured_id);
        }
        
        echo "  $lang post: ID $post_id\n";
    }
}

echo "\n=== Import complete ===\n";
