<?php
/**
 * Services Section
 * @package NeonLightHK
 */
?>
<section class="nl-services">
	<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-4.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h2><?php echo nl_t('card_purchase'); ?></h2>
		</div>
	</a>
	<a href="<?php echo esc_url( home_url( '/order/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-3.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h2><?php echo nl_t('card_customise'); ?></h2>
		</div>
	</a>
	<a href="<?php echo esc_url( home_url( '/workshop/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-2.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h2><?php echo nl_t('card_workshop'); ?></h2>
		</div>
	</a>
	<a href="<?php echo esc_url( home_url( '/rental/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-service-card">
		<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/work-6.jpg');"></div>
		<div class="nl-service-card__overlay"></div>
		<div class="nl-service-card__content">
			<h2><?php echo nl_t('card_rental'); ?></h2>
		</div>
	</a>
</section>
