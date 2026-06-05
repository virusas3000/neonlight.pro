<?php
require "/var/www/html/wp-load.php";

// Get all posts without language meta
$posts = get_posts(['post_type' => 'post', 'posts_per_page' => -1, 'post_status' => 'publish']);
$updated = 0;

foreach ($posts as $p) {
    $lang = get_post_meta($p->ID, '_nl_post_lang', true);
    if (empty($lang)) {
        // Default old posts to zh
        update_post_meta($p->ID, '_nl_post_lang', 'zh');
        $updated++;
        echo "Updated ID {$p->ID} => zh ({$p->post_title})\n";
    }
}

echo "\nTotal updated: $updated\n";
