<?php
/**
 * ACF Field Groups for Neon Light HK
 *
 * @package NeonLightHK
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

/**
 * Register ACF field groups for Custom Post Types
 */
function nl_register_acf_field_groups() {

	// Workshop Fields
	acf_add_local_field_group(
		array(
			'key'      => 'group_nl_workshop',
			'title'    => __( 'Workshop Details', 'neonlighthk' ),
			'fields'   => array(
				array(
					'key'           => 'field_workshop_duration',
					'label'         => __( 'Duration', 'neonlighthk' ),
					'name'          => 'duration',
					'type'          => 'text',
					'instructions'  => __( 'e.g. 2 hours, 3 hours', 'neonlighthk' ),
					'required'      => 1,
				),
				array(
					'key'           => 'field_workshop_price',
					'label'         => __( 'Price (HKD)', 'neonlighthk' ),
					'name'          => 'price',
					'type'          => 'number',
					'instructions'  => __( 'Workshop fee in HKD', 'neonlighthk' ),
					'required'      => 1,
					'min'           => 0,
					'step'          => 1,
				),
				array(
					'key'           => 'field_workshop_max_participants',
					'label'         => __( 'Max Participants', 'neonlighthk' ),
					'name'          => 'max_participants',
					'type'          => 'number',
					'instructions'  => __( 'Maximum number of participants per session', 'neonlighthk' ),
					'required'      => 1,
					'min'           => 1,
					'step'          => 1,
				),
				array(
					'key'           => 'field_workshop_schedule',
					'label'         => __( 'Schedule', 'neonlighthk' ),
					'name'          => 'schedule',
					'type'          => 'textarea',
					'instructions'  => __( 'Available dates and times', 'neonlighthk' ),
					'required'      => 0,
					'rows'          => 4,
				),
				array(
					'key'           => 'field_workshop_location',
					'label'         => __( 'Location', 'neonlighthk' ),
					'name'          => 'location',
					'type'          => 'text',
					'instructions'  => __( 'Workshop venue address', 'neonlighthk' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_workshop_instructor',
					'label'         => __( 'Instructor', 'neonlighthk' ),
					'name'          => 'instructor',
					'type'          => 'text',
					'instructions'  => __( 'Name of the instructor', 'neonlighthk' ),
					'required'      => 0,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'nl_workshop',
					),
				),
			),
		)
	);

	// Rental Fields
	acf_add_local_field_group(
		array(
			'key'      => 'group_nl_rental',
			'title'    => __( 'Rental Details', 'neonlighthk' ),
			'fields'   => array(
				array(
					'key'           => 'field_rental_daily_price',
					'label'         => __( 'Daily Price (HKD)', 'neonlighthk' ),
					'name'          => 'daily_price',
					'type'          => 'number',
					'instructions'  => __( 'Rental price per day', 'neonlighthk' ),
					'required'      => 1,
					'min'           => 0,
					'step'          => 1,
				),
				array(
					'key'           => 'field_rental_weekly_price',
					'label'         => __( 'Weekly Price (HKD)', 'neonlighthk' ),
					'name'          => 'weekly_price',
					'type'          => 'number',
					'instructions'  => __( 'Rental price per week', 'neonlighthk' ),
					'required'      => 0,
					'min'           => 0,
					'step'          => 1,
				),
				array(
					'key'           => 'field_rental_deposit',
					'label'         => __( 'Deposit Amount (HKD)', 'neonlighthk' ),
					'name'          => 'deposit_amount',
					'type'          => 'number',
					'instructions'  => __( 'Security deposit required', 'neonlighthk' ),
					'required'      => 0,
					'min'           => 0,
					'step'          => 1,
				),
				array(
					'key'           => 'field_rental_dimensions',
					'label'         => __( 'Dimensions', 'neonlighthk' ),
					'name'          => 'dimensions',
					'type'          => 'text',
					'instructions'  => __( 'e.g. 120cm x 60cm', 'neonlighthk' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_rental_material',
					'label'         => __( 'Material', 'neonlighthk' ),
					'name'          => 'material',
					'type'          => 'text',
					'instructions'  => __( 'Neon tube material or type', 'neonlighthk' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_rental_availability',
					'label'         => __( 'Available', 'neonlighthk' ),
					'name'          => 'availability',
					'type'          => 'true_false',
					'instructions'  => __( 'Is this item currently available for rent?', 'neonlighthk' ),
					'required'      => 0,
					'ui'            => 1,
					'ui_on_text'    => __( 'Yes', 'neonlighthk' ),
					'ui_off_text'   => __( 'No', 'neonlighthk' ),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'nl_rental',
					),
				),
			),
		)
	);

	// Custom Order Fields
	acf_add_local_field_group(
		array(
			'key'      => 'group_nl_custom_order',
			'title'    => __( 'Custom Order Details', 'neonlighthk' ),
			'fields'   => array(
				array(
					'key'           => 'field_custom_estimated_price',
					'label'         => __( 'Estimated Price (HKD)', 'neonlighthk' ),
					'name'          => 'estimated_price',
					'type'          => 'number',
					'instructions'  => __( 'Estimated total price', 'neonlighthk' ),
					'required'      => 0,
					'min'           => 0,
					'step'          => 1,
				),
				array(
					'key'           => 'field_custom_turnaround',
					'label'         => __( 'Turnaround Days', 'neonlighthk' ),
					'name'          => 'turnaround_days',
					'type'          => 'number',
					'instructions'  => __( 'Estimated production time in days', 'neonlighthk' ),
					'required'      => 0,
					'min'           => 0,
					'step'          => 1,
				),
				array(
					'key'           => 'field_custom_design_options',
					'label'         => __( 'Design Options', 'neonlighthk' ),
					'name'          => 'design_options',
					'type'          => 'textarea',
					'instructions'  => __( 'Available design choices', 'neonlighthk' ),
					'required'      => 0,
					'rows'          => 4,
				),
				array(
					'key'           => 'field_custom_size_options',
					'label'         => __( 'Size Options', 'neonlighthk' ),
					'name'          => 'size_options',
					'type'          => 'textarea',
					'instructions'  => __( 'Available size choices', 'neonlighthk' ),
					'required'      => 0,
					'rows'          => 4,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'nl_custom_order',
					),
				),
			),
		)
	);

	// Project Fields
	acf_add_local_field_group(
		array(
			'key'      => 'group_nl_project',
			'title'    => __( 'Project Details', 'neonlighthk' ),
			'fields'   => array(
				array(
					'key'           => 'field_project_client',
					'label'         => __( 'Client Name', 'neonlighthk' ),
					'name'          => 'client_name',
					'type'          => 'text',
					'instructions'  => __( 'Name of the client or brand', 'neonlighthk' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_project_date',
					'label'         => __( 'Project Date', 'neonlighthk' ),
					'name'          => 'project_date',
					'type'          => 'date_picker',
					'instructions'  => __( 'Date of the project/event', 'neonlighthk' ),
					'required'      => 0,
					'display_format'=> 'd/m/Y',
					'return_format' => 'Y-m-d',
					'first_day'     => 1,
				),
				array(
					'key'           => 'field_project_location',
					'label'         => __( 'Location', 'neonlighthk' ),
					'name'          => 'location',
					'type'          => 'text',
					'instructions'  => __( 'Venue or location of the project', 'neonlighthk' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_project_budget',
					'label'         => __( 'Budget Range', 'neonlighthk' ),
					'name'          => 'budget_range',
					'type'          => 'text',
					'instructions'  => __( 'e.g. HKD 5,000 - 10,000', 'neonlighthk' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_project_gallery',
					'label'         => __( 'Gallery', 'neonlighthk' ),
					'name'          => 'gallery',
					'type'          => 'gallery',
					'instructions'  => __( 'Project images', 'neonlighthk' ),
					'required'      => 0,
					'min'           => '',
					'max'           => '',
					'insert'        => 'append',
					'library'       => 'all',
					'min_width'     => '',
					'min_height'    => '',
					'max_width'     => '',
					'max_height'    => '',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'nl_project',
					),
				),
			),
		)
	);

	// Lookbook Fields
	acf_add_local_field_group(
		array(
			'key'      => 'group_nl_lookbook',
			'title'    => __( 'Lookbook Details', 'neonlighthk' ),
			'fields'   => array(
				array(
					'key'           => 'field_lookbook_gallery',
					'label'         => __( 'Gallery', 'neonlighthk' ),
					'name'          => 'gallery',
					'type'          => 'gallery',
					'instructions'  => __( 'Lookbook images', 'neonlighthk' ),
					'required'      => 0,
					'min'           => '',
					'max'           => '',
					'insert'        => 'append',
					'library'       => 'all',
					'min_width'     => '',
					'min_height'    => '',
					'max_width'     => '',
					'max_height'    => '',
				),
				array(
					'key'           => 'field_lookbook_category',
					'label'         => __( 'Category', 'neonlighthk' ),
					'name'          => 'category',
					'type'          => 'select',
					'instructions'  => __( 'Select the lookbook category', 'neonlighthk' ),
					'required'      => 0,
					'choices'       => array(
						'neon_signs' => __( 'Neon Signs', 'neonlighthk' ),
						'workshop'   => __( 'Workshop', 'neonlighthk' ),
						'events'     => __( 'Events', 'neonlighthk' ),
						'products'   => __( 'Products', 'neonlighthk' ),
					),
					'default_value' => 'neon_signs',
					'allow_null'    => 0,
					'multiple'      => 0,
					'ui'            => 0,
					'return_format' => 'value',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'nl_lookbook',
					),
				),
			),
		)
	);

	// Hanfu (漢服) Fields
	acf_add_local_field_group(
		array(
			'key'      => 'group_nl_hanfu',
			'title'    => __( 'Hanfu Details', 'neonlighthk' ),
			'fields'   => array(
				array(
					'key'           => 'field_hanfu_price',
					'label'         => __( 'Rental Price (HKD)', 'neonlighthk' ),
					'name'          => 'rental_price',
					'type'          => 'number',
					'instructions'  => __( 'Price per rental session (e.g. 4 hours)', 'neonlighthk' ),
					'required'      => 1,
					'min'           => 0,
					'step'          => 1,
				),
				array(
					'key'           => 'field_hanfu_size',
					'label'         => __( 'Available Sizes', 'neonlighthk' ),
					'name'          => 'available_sizes',
					'type'          => 'text',
					'instructions'  => __( 'e.g. S, M, L, XL', 'neonlighthk' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_hanfu_color',
					'label'         => __( 'Colors', 'neonlighthk' ),
					'name'          => 'colors',
					'type'          => 'text',
					'instructions'  => __( 'e.g. Red, Blue, White', 'neonlighthk' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_hanfu_era',
					'label'         => __( 'Dynasty / Era', 'neonlighthk' ),
					'name'          => 'dynasty_era',
					'type'          => 'text',
					'instructions'  => __( 'e.g. Tang, Song, Ming', 'neonlighthk' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_hanfu_gallery',
					'label'         => __( 'Gallery', 'neonlighthk' ),
					'name'          => 'gallery',
					'type'          => 'gallery',
					'instructions'  => __( 'Hanfu product images', 'neonlighthk' ),
					'required'      => 0,
					'min'           => '',
					'max'           => '',
					'insert'        => 'append',
					'library'       => 'all',
				),
				array(
					'key'           => 'field_hanfu_includes',
					'label'         => __( 'What\'s Included', 'neonlighthk' ),
					'name'          => 'includes',
					'type'          => 'textarea',
					'instructions'  => __( 'Items included in the rental package', 'neonlighthk' ),
					'required'      => 0,
					'rows'          => 3,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'nl_hanfu',
					),
				),
			),
		)
	);

	// Balloon & Magic Fields
	acf_add_local_field_group(
		array(
			'key'      => 'group_nl_balloon',
			'title'    => __( 'Balloon & Magic Details', 'neonlighthk' ),
			'fields'   => array(
				array(
					'key'           => 'field_balloon_price',
					'label'         => __( 'Price (HKD)', 'neonlighthk' ),
					'name'          => 'price',
					'type'          => 'number',
					'instructions'  => __( 'Base price for this service/item', 'neonlighthk' ),
					'required'      => 1,
					'min'           => 0,
					'step'          => 1,
				),
				array(
					'key'           => 'field_balloon_category',
					'label'         => __( 'Category', 'neonlighthk' ),
					'name'          => 'category',
					'type'          => 'select',
					'instructions'  => __( 'Select the service category', 'neonlighthk' ),
					'required'      => 1,
					'choices'       => array(
						'balloon_decor'   => __( 'Balloon Decoration', 'neonlighthk' ),
						'balloon_tasting' => __( 'Balloon Tasting Session', 'neonlighthk' ),
						'magic_show'      => __( 'Magic Show', 'neonlighthk' ),
						'workshop'        => __( 'Workshop', 'neonlighthk' ),
					),
					'default_value' => 'balloon_decor',
					'allow_null'    => 0,
					'multiple'      => 0,
					'ui'            => 0,
					'return_format' => 'value',
				),
				array(
					'key'           => 'field_balloon_duration',
					'label'         => __( 'Duration', 'neonlighthk' ),
					'name'          => 'duration',
					'type'          => 'text',
					'instructions'  => __( 'e.g. 30 min, 1 hour, 2 hours', 'neonlighthk' ),
					'required'      => 0,
				),
				array(
					'key'           => 'field_balloon_gallery',
					'label'         => __( 'Gallery', 'neonlighthk' ),
					'name'          => 'gallery',
					'type'          => 'gallery',
					'instructions'  => __( 'Service/product images', 'neonlighthk' ),
					'required'      => 0,
					'min'           => '',
					'max'           => '',
					'insert'        => 'append',
					'library'       => 'all',
				),
				array(
					'key'           => 'field_balloon_includes',
					'label'         => __( 'What\'s Included', 'neonlighthk' ),
					'name'          => 'includes',
					'type'          => 'textarea',
					'instructions'  => __( 'Items/services included', 'neonlighthk' ),
					'required'      => 0,
					'rows'          => 3,
				),
				array(
					'key'           => 'field_balloon_booking_required',
					'label'         => __( 'Requires Booking', 'neonlighthk' ),
					'name'          => 'requires_booking',
					'type'          => 'true_false',
					'instructions'  => __( 'Does this service require advance booking?', 'neonlighthk' ),
					'required'      => 0,
					'ui'            => 1,
					'ui_on_text'    => __( 'Yes', 'neonlighthk' ),
					'ui_off_text'   => __( 'No', 'neonlighthk' ),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'nl_balloon',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'nl_register_acf_field_groups' );
