<?php
/**
 * Theme Footer — Trilingual
 * @package NeonLightHK
 */
?>

<footer class="nl-footer">
<div class="nl-footer__inner">
	<div class="nl-footer__brand">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>?lang=<?php echo nl_lang(); ?>" class="nl-logo">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/neon-sign-logo.jpg" alt="Neon Light HK" style="height:70px; width:auto; display:block;">
		</a>
	</div>

	<nav class="nl-footer__nav" aria-label="Footer">
		<ul id="menu-footer-menu" class="menu">
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(96)) ); ?>"><?php echo nl_t('nav_about_lookbook'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(45)) ); ?>"><?php echo nl_t('nav_workshop'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(95)) ); ?>"><?php echo nl_t('nav_neon'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(31)) ); ?>"><?php echo nl_t('nav_projects'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), home_url('/neon-products/')) ); ?>"><?php echo nl_t('nav_products'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(12)) ); ?>"><?php echo nl_t('nav_balloon'); ?></a></li>
			<li><a href="<?php echo esc_url( add_query_arg('lang', nl_lang(), get_permalink(138)) ); ?>"><?php echo nl_t('nav_hanfu'); ?></a></li>
		</ul>
	</nav>
	<div class="nl-footer__contact">
		<a href="https://wa.me/85261319328" class="nl-footer__phone">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
			</svg>
			61319328
		</a>
		<a href="mailto:www.neonlight.pro@gmail.com" class="nl-footer__email">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
				<polyline points="22,6 12,13 2,6" />
			</svg>
			www.neonlight.pro@gmail.com
		</a>
		<span class="nl-footer__addr">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
				<circle cx="12" cy="10" r="3" />
			</svg>
			<?php echo nl_t('visit_addr1'); ?>
		</span>
	</div>
</div>
</footer>

<?php wp_footer(); ?>

<!-- Floating Chat Widget -->
<a href="https://wa.me/85261319328" target="_blank" class="nl-hello-widget">
	<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
		<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
	</svg>
	HELLO!
</a>

</body>
</html>
