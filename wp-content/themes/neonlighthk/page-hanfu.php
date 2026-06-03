<?php
/**
 * Template Name: Hanfu
 *
 * @package NeonLightHK
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="nl-page-header">
        <span class="nl-page-label">漢服</span>
        <h1 class="nl-page-title">HANFU RENTAL</h1>
        <p class="nl-page-subtitle">Traditional Chinese clothing for photoshoots &amp; events</p>
    </div>

    <div class="nl-archive-grid">
        <?php
        $hanfu = new WP_Query( array(
            'post_type'      => 'nl_hanfu',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'ASC',
        ) );

        if ( $hanfu->have_posts() ) :
            while ( $hanfu->have_posts() ) : $hanfu->the_post();
                $price  = get_post_meta( get_the_ID(), 'rental_price', true );
                $sizes  = get_post_meta( get_the_ID(), 'available_sizes', true );
                $colors = get_post_meta( get_the_ID(), 'colors', true );
                $era    = get_post_meta( get_the_ID(), 'dynasty_era', true );
        ?>
        <article class="nl-card nl-card-hanfu">
            <a href="<?php the_permalink(); ?>" class="nl-card-link">
                <div class="nl-card-image">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
                    <?php else : ?>
                        <div class="nl-card-placeholder"><?php _e( 'Hanfu', 'neonlighthk' ); ?></div>
                    <?php endif; ?>
                </div>
                <div class="nl-card-body">
                    <h3 class="nl-card-title"><?php the_title(); ?></h3>
                    <div class="nl-card-meta">
                        <?php if ( $price ) : ?><span class="nl-badge nl-price">HKD <?php echo esc_html( number_format( $price ) ); ?></span><?php endif; ?>
                        <?php if ( $sizes ) : ?><span class="nl-badge"><?php echo esc_html( $sizes ); ?></span><?php endif; ?>
                        <?php if ( $era ) : ?><span class="nl-badge"><?php echo esc_html( $era ); ?></span><?php endif; ?>
                    </div>
                </div>
            </a>
        </article>
        <?php
            endwhile;
            wp_reset_postdata();
        else :
        ?>
        <p><?php _e( 'No hanfu items available at the moment.', 'neonlighthk' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
