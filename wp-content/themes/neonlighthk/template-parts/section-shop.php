<?php
/**
 * Services Section — matching Wix 2×2 grid with arrow buttons
 * @package NeonLightHK
 */
?>
<section class="nl-services">
	<a href="<?php echo esc_url( home_url( '/neon-products/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/hello.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h3><?php echo nl_t('card_purchase'); ?></h3>
			<span class="nl-service-card__arrow">→</span>
		</div>
	</a>
	<a href="<?php echo esc_url( home_url( '/hanfu/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/hanfu-rental.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h3><?php echo nl_t('card_customise'); ?></h3>
			<span class="nl-service-card__arrow">→</span>
		</div>
	</a>
	<a href="<?php echo esc_url( home_url( '/workshop/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/workshop-tiedye.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h3><?php echo nl_t('card_workshop'); ?></h3>
			<span class="nl-service-card__arrow">→</span>
		</div>
	</a>
	<a href="<?php echo esc_url( home_url( '/balloon/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/balloon-birthday.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h3><?php echo nl_t('card_balloon'); ?></h3>
			<span class="nl-service-card__arrow">→</span>
		</div>
	</a>
</section>
