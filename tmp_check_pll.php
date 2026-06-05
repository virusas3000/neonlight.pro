<?php
require "/var/www/html/wp-load.php";

echo "Polylang functions:\n";
echo "pll_get_post_language: " . (function_exists('pll_get_post_language') ? 'YES' : 'NO') . "\n";
echo "pll_set_post_language: " . (function_exists('pll_set_post_language') ? 'YES' : 'NO') . "\n";
echo "PLL: " . (defined('POLYLANG_VERSION') ? POLYLANG_VERSION : 'not loaded') . "\n";

// Check active plugins
$active = get_option('active_plugins');
foreach ($active as $p) {
    if (strpos($p, 'poly') !== false) {
        echo "Plugin: $p\n";
    }
}
