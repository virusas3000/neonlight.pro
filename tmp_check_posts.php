<?php
require "/var/www/html/wp-load.php";
$posts = get_posts(['post_type' => 'post', 'posts_per_page' => -1]);
foreach ($posts as $p) {
    echo "ID={$p->ID} lang=" . get_post_meta($p->ID, '_nl_post_lang', true) . " title={$p->post_title}\n";
}
