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
			'show_in_menu'      => false,
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
			'show_in_menu'      => false,
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
			'show_in_menu'      => false,
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
			'show_in_menu'      => false,
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
			'show_in_menu'      => false,
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
			'show_in_menu'      => false,
			'show_in_nav_menus' => true,
		)
	);
}
add_action( 'init', 'nl_register_custom_post_types' );

/**
 * Register Enquiry CPT
 */
function nl_register_enquiry_post_type() {
	register_post_type(
		'nl_enquiry',
		array(
			'labels'            => array(
				'name'               => __( 'Enquiries', 'neonlighthk' ),
				'singular_name'      => __( 'Enquiry', 'neonlighthk' ),
				'add_new'            => __( 'Add New', 'neonlighthk' ),
				'add_new_item'       => __( 'Add New Enquiry', 'neonlighthk' ),
				'edit_item'          => __( 'Edit Enquiry', 'neonlighthk' ),
				'new_item'           => __( 'New Enquiry', 'neonlighthk' ),
				'view_item'          => __( 'View Enquiry', 'neonlighthk' ),
				'search_items'       => __( 'Search Enquiries', 'neonlighthk' ),
				'not_found'          => __( 'No enquiries found', 'neonlighthk' ),
				'not_found_in_trash' => __( 'No enquiries found in trash', 'neonlighthk' ),
				'all_items'          => __( 'All Enquiries', 'neonlighthk' ),
				'menu_name'          => __( 'Enquiries', 'neonlighthk' ),
			),
			'public'            => false,
			'publicly_queryable'  => false,
			'has_archive'       => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_rest'      => true,
			'supports'          => array( 'title', 'editor', 'custom-fields' ),
			'menu_icon'         => 'dashicons-email-alt',
			'capability_type'   => 'post',
		)
	);
}
add_action( 'init', 'nl_register_enquiry_post_type' );

/* ---------- Admin columns for Enquiries ---------- */
add_filter( 'manage_nl_enquiry_posts_columns', function ( $columns ) {
	$columns = array(
		'cb'        => '<input type="checkbox" />',
		'title'     => __( 'Name', 'neonlighthk' ),
		'email'     => __( 'Email', 'neonlighthk' ),
		'phone'     => __( 'Tel', 'neonlighthk' ),
		'subject'   => __( 'Subject', 'neonlighthk' ),
		'message'   => __( 'Message', 'neonlighthk' ),
		'date'      => __( 'Date', 'neonlighthk' ),
	);
	return $columns;
} );

add_action( 'manage_nl_enquiry_posts_custom_column', function ( $column, $post_id ) {
	switch ( $column ) {
		case 'email':
			echo esc_html( get_post_meta( $post_id, '_nl_enquiry_email', true ) );
			break;
		case 'phone':
			echo esc_html( get_post_meta( $post_id, '_nl_enquiry_phone', true ) );
			break;
		case 'subject':
			echo esc_html( get_post_meta( $post_id, '_nl_enquiry_subject', true ) );
			break;
		case 'message':
			$msg = get_post_meta( $post_id, '_nl_enquiry_message', true );
			echo esc_html( wp_trim_words( $msg, 15 ) );
			break;
	}
}, 10, 2 );
