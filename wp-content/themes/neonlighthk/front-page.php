<?php
/**
 * Front Page — NeonLightHK
 * @package NeonLightHK
 */

/* ---------- Contact form handler (must run before any output) ---------- */
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && ! empty( $_POST['nl_contact_submit'] ) ) {
	if ( isset( $_POST['nl_contact_nonce'] ) && wp_verify_nonce( $_POST['nl_contact_nonce'], 'nl_contact_action' ) ) {
		$name    = sanitize_text_field( $_POST['name'] ?? '' );
		$email   = sanitize_email( $_POST['email'] ?? '' );
		$phone   = sanitize_text_field( $_POST['phone'] ?? '' );
		$subject = sanitize_text_field( $_POST['subject'] ?? '' );
		$message = sanitize_textarea_field( $_POST['message'] ?? '' );

		if ( $name && $email && $message ) {
			$enquiry_id = wp_insert_post( array(
				'post_type'    => 'nl_enquiry',
				'post_title'   => $name . ' — ' . $subject,
				'post_content' => $message,
				'post_status'  => 'publish',
				'meta_input'   => array(
					'_nl_enquiry_email'   => $email,
					'_nl_enquiry_phone'   => $phone,
					'_nl_enquiry_subject' => $subject,
				),
			) );
			if ( $enquiry_id && ! is_wp_error( $enquiry_id ) ) {
				$redirect = add_query_arg( array(
					'contact_submitted' => '1',
					'lang'              => nl_lang(),
				), home_url( '/' ) ) . '#contact';
				wp_safe_redirect( $redirect );
				exit;
			}
		}
	}
	// On failure, redirect back with error flag
	$redirect = add_query_arg( array(
		'contact_error' => '1',
		'lang'          => nl_lang(),
	), home_url( '/' ) ) . '#contact';
	wp_safe_redirect( $redirect );
	exit;
}
/* ------------------------------------------------------------------------ */

get_header();
?>

<!-- Hero Section -->
<?php get_template_part( 'template-parts/section', 'hero' ); ?>

<!-- Services Grid (2x2 Cards) -->
<?php get_template_part( 'template-parts/section', 'shop' ); ?>

<!-- Lookbook / Portfolio -->
<?php get_template_part( 'template-parts/section', 'lookbook' ); ?>

<!-- Visit Us -->
<?php get_template_part( 'template-parts/section', 'visit' ); ?>

<!-- Contact -->
<?php get_template_part( 'template-parts/section', 'contact' ); ?>

<?php get_footer(); ?>
