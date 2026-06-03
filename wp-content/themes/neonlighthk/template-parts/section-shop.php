<?php
/**
 * Services Section (2x2 Card Grid)
 * @package NeonLightHK
 */
?>
<section class="nl-services">
	<div class="nl-service-grid">
		<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="nl-service-card">
			<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/card-shop.jpg');"></div>
			<div class="nl-service-card__overlay"></div>
			<div class="nl-service-card__content">
				<h3>購買現貨</h3>
				<h2>PURCHASE</h2>
			</div>
		</a>
		<a href="<?php echo esc_url( home_url( '/order/' ) ); ?>" class="nl-service-card">
			<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/card-order.jpg');"></div>
			<div class="nl-service-card__overlay"></div>
			<div class="nl-service-card__content">
				<h3>訂製設計</h3>
				<h2>CUSTOMISE</h2>
			</div>
		</a>
		<a href="<?php echo esc_url( home_url( '/workshop/' ) ); ?>" class="nl-service-card">
			<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/card-workshop.jpg');"></div>
			<div class="nl-service-card__overlay"></div>
			<div class="nl-service-card__content">
				<h3>工作坊</h3>
				<h2>WORKSHOP</h2>
			</div>
		</a>
		<a href="<?php echo esc_url( home_url( '/rental/' ) ); ?>" class="nl-service-card">
			<div class="nl-service-card__img" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/card-rent.jpg');"></div>
			<div class="nl-service-card__overlay"></div>
			<div class="nl-service-card__content">
				<h3>租借服務</h3>
				<h2>RENTAL</h2>
			</div>
		</a>
	</div>
</section>
