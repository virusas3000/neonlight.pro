<?php
require "/var/www/html/wp-load.php";

// Check Polylang model
$model = new PLL_Model(new PLL_Links_Default());
$languages = $model->get_languages_list();
echo "Languages: " . count($languages) . "\n";
foreach ($languages as $lang) {
    echo "  {$lang->slug} / {$lang->name} / {$lang->locale}\n";
}

// Check if Polylang tables exist
global $wpdb;
$tables = $wpdb->get_results("SHOW TABLES LIKE '%polylang%'", ARRAY_N);
echo "\nPolylang tables:\n";
foreach ($tables as $t) {
    echo "  " . $t[0] . "\n";
}

// Check terms for languages
$terms = get_terms(['taxonomy' => 'language', 'hide_empty' => false]);
echo "\nLanguage terms:\n";
foreach ($terms as $t) {
    echo "  {$t->slug} / {$t->name} / {$t->term_id}\n";
}
