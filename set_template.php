<?php
require '/var/www/html/wp-load.php';

$id = 171;
update_post_meta($id, '_wp_page_template', 'page-neon-products.php');
echo "Template set for page $id\n";
