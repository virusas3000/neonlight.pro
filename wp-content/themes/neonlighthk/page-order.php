<?php
/**
 * Template Name: Custom Order
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page">
	<h1 class="nl-page__title"><?php echo nl_t('nav_order'); ?></h1>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<div style="max-width:600px; margin:0 auto;">
			<p style="text-align:center; margin-bottom:30px;">
				<?php echo nl_t('order_desc'); ?>
			</p>
			<form style="display:grid; gap:16px;" method="post" action="">
				<input type="text" name="order_name" placeholder="<?php echo nl_t('contact_name'); ?>" style="padding:12px; border:1px solid #ddd; font-family:inherit;" required>
				<input type="email" name="order_email" placeholder="<?php echo nl_t('contact_email'); ?>" style="padding:12px; border:1px solid #ddd; font-family:inherit;" required>
				<input type="tel" name="order_phone" placeholder="<?php echo nl_t('contact_phone'); ?>" style="padding:12px; border:1px solid #ddd; font-family:inherit;">
				<textarea name="order_concept" placeholder="<?php echo nl_t('order_concept'); ?>" rows="4" style="padding:12px; border:1px solid #ddd; font-family:inherit; resize:vertical;"></textarea>
				<button type="submit" style="padding:14px; background:var(--nl-cyan); color:var(--nl-black); border:none; font-family:inherit; font-weight:600; letter-spacing:0.15em; text-transform:uppercase; cursor:pointer;"><?php echo nl_t('ws_submit'); ?></button>
			</form>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
