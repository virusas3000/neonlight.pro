<?php
require_once '/Users/vickhung/Desktop/neonlighthk-wp/wp-load.php';
echo "REQUEST_URI: " . \$_SERVER['REQUEST_URI'] . "\n";
echo "QUERY_STRING: " . \$_SERVER['QUERY_STRING'] . "\n";
echo "is_front_page: " . (is_front_page() ? 'true' : 'false') . "\n";
echo "is_home: " . (is_home() ? 'true' : 'false') . "\n";
echo "template: " . get_template_directory() . "\n";
