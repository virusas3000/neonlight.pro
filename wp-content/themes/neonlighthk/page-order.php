<?php
/**
 * Template Name: Custom Order
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page">
	<h1 class="nl-page__title">訂製 · CUSTOM ORDER</h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<div style="max-width:600px; margin:0 auto;">
			<p style="text-align:center; margin-bottom:30px;">
				<?php _e( '請填寫以下表格，我們會盡快與您聯絡。', 'neonlighthk' ); ?><br>
				Please fill in the form below and we will contact you soon.
			</p>
			<form style="display:grid; gap:16px;">
				<input type="text" placeholder="姓名 / Name" style="padding:12px; border:1px solid #ddd; font-family:inherit;">
				<input type="email" placeholder="電郵 / Email" style="padding:12px; border:1px solid #ddd; font-family:inherit;">
				<input type="tel" placeholder="電話 / Phone" style="padding:12px; border:1px solid #ddd; font-family:inherit;">
				<textarea placeholder="設計理念 / Design concept" rows="4" style="padding:12px; border:1px solid #ddd; font-family:inherit; resize:vertical;"></textarea>
				<button type="submit" style="padding:14px; background:var(--nl-cyan); color:var(--nl-black); border:none; font-family:inherit; font-weight:600; letter-spacing:0.15em; text-transform:uppercase; cursor:pointer;">提交 / SUBMIT</button>
			</form>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
