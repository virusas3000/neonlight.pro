<?php
/**
 * Hero Section
 * @package NeonLightHK
 */
?>
<section class="nl-hero">
	<div class="nl-hero__bg" style="background-image:url('/wp-content/themes/<?php echo get_stylesheet(); ?>/assets/images/hero-main.jpg');"></div>
	<div class="nl-hero__content">
		<h4 class="nl-hero__subtitle"><?php echo nl_t('hero_label'); ?></h4>
		<h1 class="nl-hero__headline"><?php echo nl_t('hero_title'); ?></h1>
		<h2 class="nl-hero__title"><?php echo nl_t('hero_subtitle'); ?></h2>
		<a href="<?php echo esc_url( home_url( '/neon-services/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-hero__btn"><?php echo nl_t('hero_cta'); ?></a>
	</div>
</section>
