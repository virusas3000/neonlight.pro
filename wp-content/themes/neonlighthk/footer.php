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
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>?page_id=24&lang=<?php echo nl_lang(); ?>"><?php echo nl_t('nav_shop'); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>?page_id=26&lang=<?php echo nl_lang(); ?>"><?php echo nl_t('nav_rent'); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>?page_id=28&lang=<?php echo nl_lang(); ?>"><?php echo nl_t('nav_order'); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>?page_id=45&lang=<?php echo nl_lang(); ?>"><?php echo nl_t('nav_workshop'); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>?page_id=31&lang=<?php echo nl_lang(); ?>"><?php echo nl_t('nav_projects'); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>?page_id=33&lang=<?php echo nl_lang(); ?>"><?php echo nl_t('nav_lookbook'); ?></a></li>
		</ul>
	</nav>
	<div class="nl-footer__contact">
		<a href="mailto:hkneonlight@gmail.com" class="nl-footer__email">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
				<polyline points="22,6 12,13 2,6" />
			</svg>
			hkneonlight@gmail.com
		</a>
	</div>
</div>
</footer>

<?php wp_footer(); ?>

<!-- Floating Chat Widget -->
<a href="https://wa.me/85262246494" target="_blank" class="nl-hello-widget">
	<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
		<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
	</svg>
	HELLO!
</a>

</body>
</html>
