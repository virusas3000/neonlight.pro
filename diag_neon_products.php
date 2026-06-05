<?php
require '/var/www/html/wp-load.php';

// Temporarily enable error display
ini_set('display_errors', 1);
error_reporting(E_ALL);

$lang = nl_lang();

$args = [
	'post_type'      => 'product',
	'posts_per_page' => 50,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'tax_query'      => [
		[
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => 'products',
		],
	],
];
$products = new WP_Query($args);
$total = $products->found_posts;
echo "Total products: $total\n";

if ($products->have_posts()) {
	while ($products->have_posts()) {
		$products->the_post();
		$product = wc_get_product(get_the_ID());
		if (!$product || is_wp_error($product)) {
			echo "SKIP: " . get_the_title() . " (invalid product)\n";
			continue;
		}
		echo "OK: " . get_the_title() . " ID=" . $product->get_id() . " Price=" . $product->get_regular_price() . "\n";
	}
	wp_reset_postdata();
}
echo "\nDone\n";
