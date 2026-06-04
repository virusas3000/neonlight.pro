<?php
/**
 * Contact Section
 * @package NeonLightHK
 */
?>
<section class="nl-contact">
	<div class="nl-contact__inner">
		<h2 class="nl-contact__title"><?php echo nl_t('contact_title'); ?></h2>
		<form class="nl-contact__form" method="post" action="">
			<div class="nl-contact__field"><input type="text" name="name" placeholder="<?php echo nl_t('contact_name'); ?> *" required></div>
			<div class="nl-contact__field"><input type="email" name="email" placeholder="<?php echo nl_t('contact_email'); ?> *" required></div>
			<div class="nl-contact__field"><input type="tel" name="phone" placeholder="<?php echo nl_t('contact_phone'); ?>"></div>
			<div class="nl-contact__field"><input type="text" name="subject" placeholder="<?php echo nl_t('contact_subject'); ?>"></div>
			<div class="nl-contact__field"><textarea name="message" placeholder="<?php echo nl_t('contact_message'); ?>" required></textarea></div>
			<button type="submit" class="nl-contact__submit"><?php echo nl_t('contact_send'); ?></button>
		</form>
	</div>
</section>
