<?php
require '/var/www/html/wp-load.php';

$id = wp_insert_post([
    'post_title'   => 'Neon Products',
    'post_name'    => 'neon-products',
    'post_status'  => 'publish',
    'post_type'    => 'page',
    'post_content' => '',
]);

if (is_wp_error($id)) {
    echo "ERROR: " . $id->get_error_message() . "\n";
} else {
    echo "CREATED: $id\n";
}
