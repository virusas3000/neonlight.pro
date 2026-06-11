<?php get_header(); ?>

<main id="primary" class="site-main">

<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('nl-single nl-rental'); ?>>
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
                    $day_price  = get_post_meta( get_the_ID(), 'daily_price', true );
                    $week_price = get_post_meta( get_the_ID(), 'weekly_price', true );
                    $deposit    = get_post_meta( get_the_ID(), 'deposit_amount', true );
                    $dimensions = get_post_meta( get_the_ID(), 'dimensions', true );
                    $material   = get_post_meta( get_the_ID(), 'material', true );
                    $available  = get_post_meta( get_the_ID(), 'availability', true );
                    ?>
                    <?php if ( $day_price ) : ?><span class="nl-badge nl-price">HKD <?php echo esc_html( number_format( $day_price ) ); ?>/day</span><?php endif; ?>
                    <?php if ( $week_price ) : ?><span class="nl-badge">HKD <?php echo esc_html( number_format( $week_price ) ); ?>/week</span><?php endif; ?>
                    <?php if ( $deposit ) : ?><span class="nl-badge">Deposit: HKD <?php echo esc_html( number_format( $deposit ) ); ?></span><?php endif; ?>
                    <?php if ( $dimensions ) : ?><span class="nl-badge"><?php echo esc_html( $dimensions ); ?></span><?php endif; ?>
                    <?php if ( $material ) : ?><span class="nl-badge"><?php echo esc_html( $material ); ?></span><?php endif; ?>
                    <span class="nl-badge nl-status <?php echo $available ? 'nl-available' : 'nl-unavailable'; ?>">
                        <?php echo $available ? __( 'Available', 'neonlighthk' ) : __( 'Unavailable', 'neonlighthk' ); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="nl-content">
            <?php the_content(); ?>
        </div>

        <?php if ( $available ) : ?>
        <div class="nl-cta">
            <a href="/shop/?add-to-cart=rental-<?php the_ID(); ?>" class="nl-button nl-button-primary">
                <?php _e( 'Rent Now', 'neonlighthk' ); ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</article>
<?php endwhile; ?>

</main>

<?php get_footer(); ?>
