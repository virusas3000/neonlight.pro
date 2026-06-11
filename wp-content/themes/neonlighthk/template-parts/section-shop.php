<?php
/**
 * Services Section — matching Wix 2×2 grid with arrow buttons
 * @package NeonLightHK
 */
?>
<section class="nl-services">
	<a href="<?php echo esc_url( home_url( '/neon-services/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-4.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h3><?php echo nl_t('card_purchase'); ?></h3>
			<span class="nl-service-card__arrow">→</span>
		</div>
	</a>
	<a href="<?php echo esc_url( home_url( '/neon-services/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-3.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h3><?php echo nl_t('card_customise'); ?></h3>
			<span class="nl-service-card__arrow">→</span>
		</div>
	</a>
	<a href="<?php echo esc_url( home_url( '/workshop/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-2.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h3><?php echo nl_t('card_workshop'); ?></h3>
			<span class="nl-service-card__arrow">→</span>
		</div>
	</a>
	<a href="<?php echo esc_url( home_url( '/balloon/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-6.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h3><?php echo nl_t('card_balloon'); ?></h3>
			<span class="nl-service-card__arrow">→</span>
		</div>
	</a>
</section>
