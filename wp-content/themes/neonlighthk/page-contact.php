<?php
/**
 * Template Name: Contact
 * @package NeonLightHK
 */

// ===== INTEREST FORM HANDLING =====
$interest_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nl_interest_nonce'])) {
    if (wp_verify_nonce($_POST['nl_interest_nonce'], 'nl_interest_form') && isset($_POST['first_name'])) {
        $first_name  = sanitize_text_field($_POST['first_name'] ?? '');
        $last_name   = sanitize_text_field($_POST['last_name'] ?? '');
        $email       = sanitize_email($_POST['email'] ?? '');
        $phone       = sanitize_text_field($_POST['phone'] ?? '');
        $age_range   = sanitize_text_field($_POST['age_range'] ?? '');
        $interested_theme = sanitize_textarea_field($_POST['interested_theme'] ?? '');

        $interest_id = wp_insert_post([
            'post_type'   => 'nl_booking',
            'post_title'  => 'Enquiry — ' . $first_name . ' ' . $last_name . ' — ' . $email,
            'post_status' => 'pending',
            'post_author' => 1,
        ]);

        if ($interest_id && !is_wp_error($interest_id)) {
            update_post_meta($interest_id, '_nl_first_name',  $first_name);
            update_post_meta($interest_id, '_nl_last_name',   $last_name);
            update_post_meta($interest_id, '_nl_email',       $email);
            update_post_meta($interest_id, '_nl_phone',       $phone);
            update_post_meta($interest_id, '_nl_age_range',   $age_range);
            update_post_meta($interest_id, '_nl_interested_theme', $interested_theme);

            wp_mail('www.neonlight.pro@gmail.com',
                'New Enquiry - ' . $first_name . ' ' . $last_name,
                "Name: $first_name $last_name\nEmail: $email\nPhone: $phone\nAge: $age_range\nInterested theme: $interested_theme");

            $interest_message = 'saved';
        } else {
            $interest_message = 'error';
        }
    }
}

get_header();
?>

<div class="nl-page">
	<h1 class="nl-page__title"><?php echo nl_t('contact_title'); ?></h1>

    <?php if ($interest_message === 'saved') : ?>
        <div class="nl-notice nl-notice--success">
            <strong><?php echo nl_t('ws_booking_saved'); ?></strong>
        </div>
    <?php elseif ($interest_message === 'error') : ?>
        <div class="nl-notice nl-notice--error">
            <strong><?php echo nl_t('ws_booking_error'); ?></strong>
        </div>
    <?php endif; ?>

    <!-- Contact Info -->
    <div style="max-width:600px; margin:0 auto 40px; text-align:center;">
        <p style="margin-bottom:8px;"><strong>NEON LIGHT HK</strong></p>
        <p style="margin-bottom:24px; opacity:0.7;"><?php echo nl_t('hero_label'); ?></p>
        <p style="margin-bottom:8px;">📞 <a href="https://wa.me/85261319328">61319328</a></p>
        <p style="margin-bottom:8px;">✉️ <a href="mailto:www.neonlight.pro@gmail.com">www.neonlight.pro@gmail.com</a></p>
        <p style="margin-bottom:8px;">📍 <?php echo nl_t('visit_addr1'); ?></p>
        <p style="margin-top:24px;">
            <a href="https://www.instagram.com/neonlighthk/" target="_blank" rel="noopener">Instagram: @neonlighthk</a>
        </p>
    </div>

    <!-- Contact Bar -->
    <div class="nl-contact-bar">
        <strong><?php echo nl_t('ws_contact'); ?></strong><br>
        <a href="https://wa.me/85261319328" target="_blank">WhatsApp 6131 9328</a> |
        <a href="mailto:www.neonlight.pro@gmail.com"><?php echo nl_t('ws_email'); ?></a> |
        IG: <a href="https://instagram.com/irregularthk" target="_blank">@irregularthk</a>
        <a href="https://instagram.com/justbe.mawan" target="_blank">@justbe.mawan</a>
        <a href="https://instagram.com/neonlight.pro" target="_blank">@neonlight.pro</a>
    </div>

    <!-- Enquiry Form -->
    <div class="nl-interest-form">
        <h2 class="nl-interest-form__title"><?php echo nl_t('ws_apply_title'); ?></h2>
        <form method="post" action="">
            <?php wp_nonce_field('nl_interest_form','nl_interest_nonce'); ?>
            <div class="nl-interest-form__grid">
                <input type="text" name="first_name" placeholder="<?php echo nl_t('ws_first_name'); ?>" required>
                <input type="text" name="last_name" placeholder="<?php echo nl_t('ws_last_name'); ?>" required>
                <input type="email" name="email" placeholder="<?php echo nl_t('ws_email_ph'); ?>" required>
                <input type="tel" name="phone" placeholder="<?php echo nl_t('ws_phone_ph'); ?>" required>
                <select name="age_range" class="nl-field--full">
                    <option value=""><?php echo nl_t('ws_age'); ?></option>
                    <option value="under18"><?php echo nl_t('ws_age_u18'); ?></option>
                    <option value="18-25"><?php echo nl_t('ws_age_18_25'); ?></option>
                    <option value="26-35"><?php echo nl_t('ws_age_26_35'); ?></option>
                    <option value="36-50"><?php echo nl_t('ws_age_36_50'); ?></option>
                    <option value="50+"><?php echo nl_t('ws_age_50'); ?></option>
                </select>
                <textarea name="interested_theme" class="nl-field--full" placeholder="<?php echo nl_t('ws_interested_theme'); ?>"></textarea>
            </div>
            <button type="submit" class="nl-interest-form__submit"><?php echo nl_t('ws_submit'); ?></button>
        </form>
    </div>

	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
