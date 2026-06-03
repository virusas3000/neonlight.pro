<?php
/**
 * Theme Footer
 * @package NeonLightHK
 */
?>

<footer class="nl-footer">
<div class="nl-footer__inner">
	<div class="nl-footer__brand">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nl-logo">
			<span class="nl-logo__neon">NE
				<span class="nl-logo__o">
				</span>N
			</span>
			<span class="nl-logo__sub">LIGHT · HK
			</span>
		</a>
	</div>

	<nav class="nl-footer__nav" aria-label="Footer">
		<ul id="menu-footer-menu" class="menu">
			<li class="menu-item">
				<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">購買·SHOP</a>
			</li>
			<li class="menu-item">
				<a href="<?php echo esc_url( home_url( '/rent/' ) ); ?>">租借·RENT</a>
			</li>
			<li class="menu-item">
				<a href="<?php echo esc_url( home_url( '/order/' ) ); ?>">訂製·ORDER</a>
			</li>
			<li class="menu-item">
				<a href="<?php echo esc_url( home_url( '/workshop/' ) ); ?>">工作坊·WORKSHOP</a>
			</li>
			<li class="menu-item">
				<a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">活動·PROJECTS</a>
			</li>
			<li class="menu-item">
				<a href="<?php echo esc_url( home_url( '/lookbook/' ) ); ?>">範例·LOOKBOOK</a>
			</li>
			<li class="menu-item">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">聯絡·CONTACT</a>
			</li>
		</ul>
	</nav>

	<div class="nl-footer__contact">
		<span class="nl-footer__label">Business Inquiries</span>
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
</body>
</html>