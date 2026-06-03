<?php
/**
 * Contact Section
 * @package NeonLightHK
 */
?>
<section class="nl-contact">
	<div class="nl-contact__header">
		<h2>CONTACT US</h2>
	</div>
	<form class="nl-contact__form" method="post" action="">
		<input type="text" name="name" placeholder="Name *" required>
		<input type="email" name="email" placeholder="Email *" required>
		<input type="tel" name="phone" placeholder="Phone No.">
		<input type="text" name="subject" placeholder="Subject">
		<textarea name="message" placeholder="Message" required></textarea>
		<button type="submit">Send</button>
	</form>
</section>
