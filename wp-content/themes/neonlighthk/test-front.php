<?php
require_once '/var/www/html/wp-load.php';

echo "REQUEST_URI: " . \$_SERVER['REQUEST_URI'] . "\n";
echo "QUERY_STRING: " . \$_SERVER['QUERY_STRING'] . "\n";
echo "is_front_page: " . (is_front_page() ? 'true' : 'false') . "\n";
echo "is_home: " . (is_home() ? 'true' : 'false') . "\n";
echo "show_on_front: " . get_option('show_on_front') . "\n";
echo "page_on_front: " . get_option('page_on_front') . "\n";
echo "template_include: " . apply_filters('template_include', get_template_directory() . '/index.php') . "\n";
echo "query_var lang: " . get_query_var('lang') . "\n";
echo "GET lang: " . (\$_GET['lang'] ?? 'NOT SET') . "\n";
