<?php get_header(); ?>

<main id="primary" class="site-main">

<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('nl-single nl-balloon'); ?>>
    <div class="nl-container">
        <div class="nl-hero">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="nl-hero-image">
                    <?php the_post_thumbnail( 'full', array( 'alt' => get_the_title() ) ); ?>
                </div>
            <?php endif; ?>
            <div class="nl-hero-content">
                <h1 class="nl-title"><?php the_title(); ?></h1>
                <div class="nl-meta">
                    <?php
                    $price      = get_post_meta( get_the_ID(), 'price', true );
                    $category   = get_post_meta( get_the_ID(), 'category', true );
                    $duration   = get_post_meta( get_the_ID(), 'duration', true );
                    $booking_req = get_post_meta( get_the_ID(), 'requires_booking', true );
                    ?>
                    <?php if ( $price ) : ?><span class="nl-badge nl-price">HKD <?php echo esc_html( number_format( $price ) ); ?></span><?php endif; ?>
                    <?php if ( $category ) : ?><span class="nl-badge nl-category"><?php echo esc_html( ucfirst( str_replace( '_', ' ', $category ) ) ); ?></span><?php endif; ?>
                    <?php if ( $duration ) : ?><span class="nl-badge"><?php echo esc_html( $duration ); ?></span><?php endif; ?>
                    <?php if ( $booking_req ) : ?><span class="nl-badge nl-booking"><?php _e( 'Booking Required', 'neonlighthk' ); ?></span><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="nl-content">
            <?php the_content(); ?>
            <?php
            $includes = get_post_meta( get_the_ID(), 'includes', true );
            if ( $includes ) : ?>
            <div class="nl-includes">
                <h3><?php _e( "What's Included", 'neonlighthk' ); ?></h3>
                <p><?php echo nl2br( esc_html( $includes ) ); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Product Details -->
        <div class="nl-balloon-details">
            <h2 class="nl-balloon-details__title"><?php echo nl_t('product_details_info'); ?></h2>
            <table class="nl-balloon-details__table">
                <tbody>
                    <?php if ( $price ) : ?>
                    <tr><th><?php _e('Price', 'neonlighthk'); ?></th><td>HKD <?php echo esc_html( number_format( $price ) ); ?></td></tr>
                    <?php endif; ?>
                    <?php if ( $category ) : ?>
                    <tr><th><?php _e('Category', 'neonlighthk'); ?></th><td><?php echo esc_html( ucfirst( str_replace( '_', ' ', $category ) ) ); ?></td></tr>
                    <?php endif; ?>
                    <?php if ( $duration ) : ?>
                    <tr><th><?php _e('Duration', 'neonlighthk'); ?></th><td><?php echo esc_html( $duration ); ?></td></tr>
                    <?php endif; ?>
                    <?php if ( $booking_req ) : ?>
                    <tr><th><?php _e('Booking', 'neonlighthk'); ?></th><td><?php _e('Booking Required', 'neonlighthk'); ?></td></tr>
                    <?php endif; ?>
                    <?php if ( $includes ) : ?>
                    <tr><th><?php _e('Includes', 'neonlighthk'); ?></th><td><?php echo nl2br( esc_html( $includes ) ); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php
        $gallery = get_post_meta( get_the_ID(), 'gallery', true );
        if ( $gallery ) :
            $images = maybe_unserialize( $gallery );
            if ( is_array( $images ) && ! empty( $images ) ) : ?>
        <div class="nl-gallery">
            <div class="nl-gallery-grid">
                <?php foreach ( $images as $img_id ) :
                    $img_url = wp_get_attachment_image_url( $img_id, 'medium' );
                    if ( $img_url ) : ?>
                    <img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy" />
                    <?php endif;
                endforeach; ?>
            </div>
        </div>
        <?php endif; endif; ?>

        <div class="nl-cta">
            <a href="/contact/?subject=Balloon%20%26%20Magic%20Booking" class="nl-button nl-button-primary">
                <?php _e( 'Book Now', 'neonlighthk' ); ?>
            </a>
        </div>
    </div>
</article>
<?php endwhile; ?>

</main>

<style>
/* Balloon & Magic Detail Page */
.nl-balloon-details { margin-top: 40px; }
.nl-balloon-details__title {
	font-size: 1.25rem; font-weight: 700; margin: 0 0 16px; color: #111;
	border-bottom: 2px solid #00d4b0; padding-bottom: 8px; display: inline-block;
}
.nl-balloon-details__table {
	width: 100%; border-collapse: collapse; font-size: .95rem;
}
.nl-balloon-details__table th,
.nl-balloon-details__table td {
	padding: 12px 16px; text-align: left; border-bottom: 1px solid #eee;
}
.nl-balloon-details__table th {
	width: 30%; font-weight: 600; color: #333; background: #f9f9f9;
}
.nl-balloon-details__table td { color: #555; }
</style>

<?php get_footer(); ?>
