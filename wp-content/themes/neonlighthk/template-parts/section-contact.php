<?php
/**
 * Contact Section
 * @package NeonLightHK
 */

$nl_contact_notice = '';
if ( isset( $_GET['contact_submitted'] ) ) {
	$nl_contact_notice = '<div class="nl-contact__notice nl-contact__notice--success">' . esc_html( nl_t( 'contact_success' ) ) . '</div>';
} elseif ( isset( $_GET['contact_error'] ) ) {
	$nl_contact_notice = '<div class="nl-contact__notice nl-contact__notice--error">' . esc_html( nl_t( 'contact_error' ) ) . '</div>';
}
?>
<section class="nl-contact" id="contact">
	<div class="nl-contact__inner">
		<h2 class="nl-contact__title"><?php echo nl_t('contact_title'); ?></h2>
		<?php echo $nl_contact_notice; ?>
		<form class="nl-contact__form" method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>?lang=<?php echo nl_lang(); ?>#contact">
			<?php wp_nonce_field( 'nl_contact_action', 'nl_contact_nonce' ); ?>
			<div class="nl-contact__field"><input type="text" name="name" placeholder="<?php echo nl_t('contact_name'); ?> *" required></div>
			<div class="nl-contact__field"><input type="email" name="email" placeholder="<?php echo nl_t('contact_email'); ?> *" required></div>
			<div class="nl-contact__field"><input type="tel" name="phone" placeholder="<?php echo nl_t('contact_phone'); ?>"></div>
			<div class="nl-contact__field"><input type="text" name="subject" placeholder="<?php echo nl_t('contact_subject'); ?>"></div>
			<div class="nl-contact__field"><textarea name="message" placeholder="<?php echo nl_t('contact_message'); ?>" required></textarea></div>
			<button type="submit" name="nl_contact_submit" value="1" class="nl-contact__submit"><?php echo nl_t('contact_send'); ?></button>
		</form>
	</div>
</section>
