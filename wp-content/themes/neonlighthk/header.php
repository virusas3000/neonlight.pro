<?php
/**
 * Theme Header
 * @package NeonLightHK
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

	<!-- Top Contact Bar -->
	<div class="nl-topbar">
		<div class="nl-topbar__inner">
			<a href="tel:+85282246494">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
				</svg>
				(852) 8224-6494
			</a>
			<a href="mailto:hkneonlight@gmail.com">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
					<polyline points="22,6 12,13 2,6" />
				</svg>
				hkneonlight@gmail.com
			</a>
			<span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
					<circle cx="12" cy="10" r="3" />
				</svg>
				HG19, Ground Floor, Block B, PMQ, 35 Aberdeen St, Central, Hong Kong
			</span>
		</div>
	</div>

	<!-- Header -->
	<header class="nl-header">
		<div class="nl-header__inner">
			<button class="nl-mobile-toggle" aria-label="Open Menu" onclick="document.querySelector('.nl-mobile-menu').classList.add('is-open')">
				<span>
				</span>
				<span>
				</span>
				<span>
				</span>
			</button>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nl-logo">
				<span class="nl-logo__neon">NE
					<span class="nl-logo__o">
					</span>N
				</span>
				<span class="nl-logo__sub">LIGHT · HK
				</span>
			</a>

			<nav class="nl-nav" aria-label="Primary">
				<ul id="menu-primary-menu" class="menu">
					<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-25">
						<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">購買·SHOP</a>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-27">
						<a href="<?php echo esc_url( home_url( '/rent/' ) ); ?>">租借·RENT</a>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-29">
						<a href="<?php echo esc_url( home_url( '/order/' ) ); ?>">訂製·ORDER</a>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-30">
						<a href="<?php echo esc_url( home_url( '/workshop/' ) ); ?>">工作坊·WORKSHOP</a>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-32">
						<a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">活動·PROJECTS</a>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-34">
						<a href="<?php echo esc_url( home_url( '/lookbook/' ) ); ?>">範例·LOOKBOOK</a>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-35">
						<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">聯絡·CONTACT</a>
					</li>
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
			<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-25">
				<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">購買·SHOP</a>
			</li>
			<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-27">
				<a href="<?php echo esc_url( home_url( '/rent/' ) ); ?>">租借·RENT</a>
			</li>
			<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-29">
				<a href="<?php echo esc_url( home_url( '/order/' ) ); ?>">訂製·ORDER</a>
			</li>
			<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-30">
				<a href="<?php echo esc_url( home_url( '/workshop/' ) ); ?>">工作坊·WORKSHOP</a>
			</li>
			<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-32">
				<a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">活動·PROJECTS</a>
			</li>
			<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-34">
				<a href="<?php echo esc_url( home_url( '/lookbook/' ) ); ?>">範例·LOOKBOOK</a>
			</li>
			<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-35">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">聯絡·CONTACT</a>
			</li>
		</ul>
	</div>