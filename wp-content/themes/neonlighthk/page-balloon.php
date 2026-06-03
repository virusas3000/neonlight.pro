<?php
/**
 * Template Name: Balloon &amp; Magic
 *
 * @package NeonLightHK
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="nl-page-header">
        <span class="nl-page-label">氣球魔術</span>
        <h1 class="nl-page-title">BALLOON &amp; MAGIC</h1>
        <p class="nl-page-subtitle">Balloon decorations, tasting sessions &amp; magic shows</p>
    </div>

    <div class="nl-archive-grid">
        <?php
        $items = new WP_Query( array(
            'post_type'      => 'nl_balloon',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'ASC',
        ) );

        if ( $items->have_posts() ) :
            while ( $items->have_posts() ) : $items->the_post();
                $price      = get_post_meta( get_the_ID(), 'price', true );
                $category   = get_post_meta( get_the_ID(), 'category', true );
                $duration   = get_post_meta( get_the_ID(), 'duration', true );
                $booking_req = get_post_meta( get_the_ID(), 'requires_booking', true );
        ?>
        <article class="nl-card nl-card-balloon">
            <a href="<?php the_permalink(); ?>" class="nl-card-link">
                <div class="nl-card-image">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
                    <?php else : ?>
                        <div class="nl-card-placeholder"><?php _e( 'Balloon &amp; Magic', 'neonlighthk' ); ?></div>
                    <?php endif; ?>
                    <?php if ( $booking_req ) : ?>
                        <div class="nl-card-tag"><?php _e( 'Booking Required', 'neonlighthk' ); ?></div>
                    <?php endif; ?>
                </div>
                <div class="nl-card-body">
                    <h3 class="nl-card-title"><?php the_title(); ?></h3>
                    <div class="nl-card-meta">
                        <?php if ( $price ) : ?><span class="nl-badge nl-price">HKD <?php echo esc_html( number_format( $price ) ); ?></span><?php endif; ?>
                        <?php if ( $category ) : ?><span class="nl-badge nl-category"><?php echo esc_html( ucfirst( str_replace( '_', ' ', $category ) ) ); ?></span><?php endif; ?>
                        <?php if ( $duration ) : ?><span class="nl-badge"><?php echo esc_html( $duration ); ?></span><?php endif; ?>
                    </div>
                </div>
            </a>
        </article>
        <?php
            endwhile;
            wp_reset_postdata();
        else :
        ?>
        <p><?php _e( 'No services available at the moment.', 'neonlighthk' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
