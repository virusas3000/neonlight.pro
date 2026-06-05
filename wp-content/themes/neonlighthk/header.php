<?php
/**
 * Theme Header — Trilingual (EN / 繁中 / 简中)
 * @package NeonLightHK
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<style>
	.nl-lang-switcher{display:inline-flex;gap:8px;align-items:center}
	.nl-lang-switcher a{display:inline-block;padding:6px 14px;border-radius:20px;font-size:13px;text-decoration:none;color:#fff;background:rgba(255,255,255,.2);transition:.2s}
	.nl-lang-switcher a.active{background:#fff;color:#00a896;font-weight:700}
	.nl-lang-switcher a:hover{background:rgba(255,255,255,.4)}
	@media (min-width:769px) {
		.nl-lang-switcher a{padding:8px 18px;font-size:15px;border-radius:24px}
	}
	</style>
</head>
<body <?php body_class(); ?>>

	<!-- Top Contact Bar -->
	<div class="nl-topbar">
		<div class="nl-topbar__inner">
			<a href="tel:+85261319328">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
				</svg>
				61319328
			</a>
			<a href="mailto:cantopopforyou@gmail.com">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
					<polyline points="22,6 12,13 2,6" />
				</svg>
				cantopopforyou@gmail.com
			</a>
			<span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
					<circle cx="12" cy="10" r="3" />
				</svg>
				<?php echo nl_t('visit_addr1'); ?>
			</span>
			<!-- Language Switcher -->
			<div class="nl-lang-switcher">
				<?php $curlang = nl_lang(); ?>
				<?php $current_uri = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
				<?php foreach (['en'=>'EN', 'zh'=>'繁', 'cn'=>'简'] as $code=>$label) : ?>
					<a href="<?php echo esc_url(add_query_arg('lang', $code, $current_uri)); ?>" class="<?php echo $curlang===$code ? 'active' : ''; ?>">
					<?php echo $label; ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<!-- Header -->
	<header class="nl-header">
		<div class="nl-header__inner">
			<button class="nl-mobile-toggle" aria-label="Open Menu" onclick="document.querySelector('.nl-mobile-menu').classList.add('is-open')">
				<span></span>
				<span></span>
				<span></span>
			</button>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-logo">
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/neon-sign-logo.jpg" alt="Neon Light HK" style="max-height:70px; max-width:200px; width:auto; display:block;">
			</a>

			<nav class="nl-nav" aria-label="Primary">
				<ul id="menu-primary-menu" class="menu">
					<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(96)) ); ?>"><?php echo nl_t('nav_about_lookbook'); ?></a></li>
					<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(45)) ); ?>"><?php echo nl_t('nav_workshop'); ?></a></li>
					<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(95)) ); ?>"><?php echo nl_t('nav_neon'); ?></a></li>
					<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(31)) ); ?>"><?php echo nl_t('nav_projects'); ?></a></li>
					<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), home_url('/neon-products/')) ); ?>"><?php echo nl_t('nav_products'); ?></a></li>
					<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(12)) ); ?>"><?php echo nl_t('nav_balloon'); ?></a></li>
					<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(138)) ); ?>"><?php echo nl_t('nav_hanfu'); ?></a></li>
				</ul>
			</nav>

			<div class="nl-header__actions">
				<?php if ( function_exists( 'wc_get_cart_url' ) ) { ?>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="nl-bag-btn" aria-label="Cart">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
						<path d="M6 6h15l-1.5 9h-12z" />
						<circle cx="9" cy="20" r="1.5" />
						<circle cx="18" cy="20" r="1.5" />
						<path d="M6 6L5 3H2" />
					</svg>
				</a>
				<?php } ?>
			</div>
		</div>
	</header>

	<!-- Mobile Menu -->
	<div class="nl-mobile-menu">
		<button class="nl-mobile-menu__close" aria-label="Close Menu" onclick="document.querySelector('.nl-mobile-menu').classList.remove('is-open')">✕</button>
		<ul id="menu-primary-menu" class="menu">
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(96)) ); ?>"><?php echo nl_t('nav_about_lookbook'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(45)) ); ?>"><?php echo nl_t('nav_workshop'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(95)) ); ?>"><?php echo nl_t('nav_neon'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(31)) ); ?>"><?php echo nl_t('nav_projects'); ?></a></li>
		<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), home_url('/neon-products/')) ); ?>"><?php echo nl_t('nav_products'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(12)) ); ?>"><?php echo nl_t('nav_balloon'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(138)) ); ?>"><?php echo nl_t('nav_hanfu'); ?></a></li>
		</ul>
	</div>
