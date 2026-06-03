<?php
/**
 * Custom Post Types for Neon Light HK
 *
 * @package NeonLightHK
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Custom Post Types
 */
function nl_register_custom_post_types() {

	// Workshop
	register_post_type(
		'nl_workshop',
		array(
			'labels'            => array(
				'name'               => __( 'Workshops', 'neonlighthk' ),
				'singular_name'      => __( 'Workshop', 'neonlighthk' ),
				'add_new'            => __( 'Add New', 'neonlighthk' ),
				'add_new_item'       => __( 'Add New Workshop', 'neonlighthk' ),
				'edit_item'          => __( 'Edit Workshop', 'neonlighthk' ),
				'new_item'           => __( 'New Workshop', 'neonlighthk' ),
				'view_item'          => __( 'View Workshop', 'neonlighthk' ),
				'search_items'       => __( 'Search Workshops', 'neonlighthk' ),
				'not_found'          => __( 'No workshops found', 'neonlighthk' ),
				'not_found_in_trash' => __( 'No workshops found in trash', 'neonlighthk' ),
				'all_items'          => __( 'All Workshops', 'neonlighthk' ),
				'menu_name'          => __( 'Workshops', 'neonlighthk' ),
			),
			'public'            => true,
			'has_archive'       => true,
			'show_in_rest'      => true,
			'supports'          => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'menu_icon'         => 'dashicons-admin-customizer',
			'rewrite'           => array( 'slug' => 'workshop-item' ),
			'capability_type'   => 'post',
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
		)
	);

	// Rental
	register_post_type(
		'nl_rental',
		array(
			'labels'            => array(
				'name'               => __( 'Rentals', 'neonlighthk' ),
				'singular_name'      => __( 'Rental', 'neonlighthk' ),
				'add_new'            => __( 'Add New', 'neonlighthk' ),
				'add_new_item'       => __( 'Add New Rental', 'neonlighthk' ),
				'edit_item'          => __( 'Edit Rental', 'neonlighthk' ),
				'new_item'           => __( 'New Rental', 'neonlighthk' ),
				'view_item'          => __( 'View Rental', 'neonlighthk' ),
				'search_items'       => __( 'Search Rentals', 'neonlighthk' ),
				'not_found'          => __( 'No rentals found', 'neonlighthk' ),
				'not_found_in_trash' => __( 'No rentals found in trash', 'neonlighthk' ),
				'all_items'          => __( 'All Rentals', 'neonlighthk' ),
				'menu_name'          => __( 'Rentals', 'neonlighthk' ),
			),
			'public'            => true,
			'has_archive'       => true,
			'show_in_rest'      => true,
			'supports'          => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'menu_icon'         => 'dashicons-store',
			'rewrite'           => array( 'slug' => 'rental-item' ),
			'capability_type'   => 'post',
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
		)
	);

	// Custom Order
	register_post_type(
		'nl_custom_order',
		array(
			'labels'            => array(
				'name'               => __( 'Custom Orders', 'neonlighthk' ),
				'singular_name'      => __( 'Custom Order', 'neonlighthk' ),
				'add_new'            => __( 'Add New', 'neonlighthk' ),
				'add_new_item'       => __( 'Add New Custom Order', 'neonlighthk' ),
				'edit_item'          => __( 'Edit Custom Order', 'neonlighthk' ),
				'new_item'           => __( 'New Custom Order', 'neonlighthk' ),
				'view_item'          => __( 'View Custom Order', 'neonlighthk' ),
				'search_items'       => __( 'Search Custom Orders', 'neonlighthk' ),
				'not_found'          => __( 'No custom orders found', 'neonlighthk' ),
				'not_found_in_trash' => __( 'No custom orders found in trash', 'neonlighthk' ),
				'all_items'          => __( 'All Custom Orders', 'neonlighthk' ),
				'menu_name'          => __( 'Custom Orders', 'neonlighthk' ),
			),
			'public'            => true,
			'has_archive'       => true,
			'show_in_rest'      => true,
			'supports'          => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'menu_icon'         => 'dashicons-art',
			'rewrite'           => array( 'slug' => 'custom-order-item' ),
			'capability_type'   => 'post',
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
		)
	);

	// Project
	register_post_type(
		'nl_project',
		array(
			'labels'            => array(
				'name'               => __( 'Projects', 'neonlighthk' ),
				'singular_name'      => __( 'Project', 'neonlighthk' ),
				'add_new'            => __( 'Add New', 'neonlighthk' ),
				'add_new_item'       => __( 'Add New Project', 'neonlighthk' ),
				'edit_item'          => __( 'Edit Project', 'neonlighthk' ),
				'new_item'           => __( 'New Project', 'neonlighthk' ),
				'view_item'          => __( 'View Project', 'neonlighthk' ),
				'search_items'       => __( 'Search Projects', 'neonlighthk' ),
				'not_found'          => __( 'No projects found', 'neonlighthk' ),
				'not_found_in_trash' => __( 'No projects found in trash', 'neonlighthk' ),
				'all_items'          => __( 'All Projects', 'neonlighthk' ),
				'menu_name'          => __( 'Projects', 'neonlighthk' ),
			),
			'public'            => true,
			'has_archive'       => true,
			'show_in_rest'      => true,
			'supports'          => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'menu_icon'         => 'dashicons-format-gallery',
			'rewrite'           => array( 'slug' => 'project-item' ),
			'capability_type'   => 'post',
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
		)
	);

	// Lookbook
	register_post_type(
		'nl_lookbook',
		array(
			'labels'            => array(
				'name'               => __( 'Lookbooks', 'neonlighthk' ),
				'singular_name'      => __( 'Lookbook', 'neonlighthk' ),
				'add_new'            => __( 'Add New', 'neonlighthk' ),
				'add_new_item'       => __( 'Add New Lookbook', 'neonlighthk' ),
				'edit_item'          => __( 'Edit Lookbook', 'neonlighthk' ),
				'new_item'           => __( 'New Lookbook', 'neonlighthk' ),
				'view_item'          => __( 'View Lookbook', 'neonlighthk' ),
				'search_items'       => __( 'Search Lookbooks', 'neonlighthk' ),
				'not_found'          => __( 'No lookbooks found', 'neonlighthk' ),
				'not_found_in_trash' => __( 'No lookbooks found in trash', 'neonlighthk' ),
				'all_items'          => __( 'All Lookbooks', 'neonlighthk' ),
				'menu_name'          => __( 'Lookbook', 'neonlighthk' ),
			),
			'public'            => true,
			'has_archive'       => true,
			'show_in_rest'      => true,
			'supports'          => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'menu_icon'         => 'dashicons-visibility',
			'rewrite'           => array( 'slug' => 'lookbook-item' ),
			'capability_type'   => 'post',
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
		)
	);

	// Hanfu (漢服)
	register_post_type(
		'nl_hanfu',
		array(
			'labels'            => array(
				'name'               => __( 'Hanfu', 'neonlighthk' ),
				'singular_name'      => __( 'Hanfu Item', 'neonlighthk' ),
				'add_new'            => __( 'Add New', 'neonlighthk' ),
				'add_new_item'       => __( 'Add New Hanfu', 'neonlighthk' ),
				'edit_item'          => __( 'Edit Hanfu', 'neonlighthk' ),
				'new_item'           => __( 'New Hanfu', 'neonlighthk' ),
				'view_item'          => __( 'View Hanfu', 'neonlighthk' ),
				'search_items'       => __( 'Search Hanfu', 'neonlighthk' ),
				'not_found'          => __( 'No hanfu items found', 'neonlighthk' ),
				'not_found_in_trash' => __( 'No hanfu items found in trash', 'neonlighthk' ),
				'all_items'          => __( 'All Hanfu', 'neonlighthk' ),
				'menu_name'          => __( 'Hanfu', 'neonlighthk' ),
			),
			'public'            => true,
			'has_archive'       => true,
			'show_in_rest'      => true,
			'supports'          => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'menu_icon'         => 'dashicons-translation',
			'rewrite'           => array( 'slug' => 'hanfu' ),
			'capability_type'   => 'post',
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
		)
	);

	// Balloon & Magic (氣球 & 魔術)
	register_post_type(
		'nl_balloon',
		array(
			'labels'            => array(
				'name'               => __( 'Balloon & Magic', 'neonlighthk' ),
				'singular_name'      => __( 'Balloon & Magic Item', 'neonlighthk' ),
				'add_new'            => __( 'Add New', 'neonlighthk' ),
				'add_new_item'       => __( 'Add New Balloon/Magic', 'neonlighthk' ),
				'edit_item'          => __( 'Edit Balloon/Magic', 'neonlighthk' ),
				'new_item'           => __( 'New Balloon/Magic', 'neonlighthk' ),
				'view_item'          => __( 'View Balloon/Magic', 'neonlighthk' ),
				'search_items'       => __( 'Search Balloon & Magic', 'neonlighthk' ),
				'not_found'          => __( 'No items found', 'neonlighthk' ),
				'not_found_in_trash' => __( 'No items found in trash', 'neonlighthk' ),
				'all_items'          => __( 'All Balloon & Magic', 'neonlighthk' ),
				'menu_name'          => __( 'Balloon & Magic', 'neonlighthk' ),
			),
			'public'            => true,
			'has_archive'       => true,
			'show_in_rest'      => true,
			'supports'          => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'menu_icon'         => 'dashicons-smiley',
			'rewrite'           => array( 'slug' => 'balloon-magic' ),
			'capability_type'   => 'post',
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
		)
	);
}
add_action( 'init', 'nl_register_custom_post_types' );
