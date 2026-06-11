<?php get_header(); ?>

<main id="primary" class="site-main">

<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('nl-single nl-workshop'); ?>>
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
                    $duration = get_post_meta( get_the_ID(), 'duration', true );
                    $price    = get_post_meta( get_the_ID(), 'price', true );
                    $max_pax  = get_post_meta( get_the_ID(), 'max_participants', true );
                    $location = get_post_meta( get_the_ID(), 'location', true );
                    $instructor = get_post_meta( get_the_ID(), 'instructor', true );
                    ?>
                    <?php if ( $price ) : ?><span class="nl-badge nl-price">HKD <?php echo esc_html( number_format( $price ) ); ?></span><?php endif; ?>
                    <?php if ( $duration ) : ?><span class="nl-badge"><?php echo esc_html( $duration ); ?></span><?php endif; ?>
                    <?php if ( $max_pax ) : ?><span class="nl-badge">Max <?php echo esc_html( $max_pax ); ?> pax</span><?php endif; ?>
                    <?php if ( $location ) : ?><span class="nl-badge"><i class="dashicons dashicons-location"></i> <?php echo esc_html( $location ); ?></span><?php endif; ?>
                    <?php if ( $instructor ) : ?><span class="nl-badge">Instructor: <?php echo esc_html( $instructor ); ?></span><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="nl-content">
            <div class="nl-description">
                <?php the_content(); ?>
            </div>
            <?php
            $schedule = get_post_meta( get_the_ID(), 'schedule', true );
            if ( $schedule ) : ?>
            <div class="nl-schedule">
                <h3><?php _e( 'Available Schedule', 'neonlighthk' ); ?></h3>
                <p><?php echo nl2br( esc_html( $schedule ) ); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="nl-cta">
            <a href="/shop/?add-to-cart=workshop-<?php the_ID(); ?>" class="nl-button nl-button-primary">
                <?php _e( 'Book Now', 'neonlighthk' ); ?>
            </a>
        </div>
    </div>
</article>
<?php endwhile; ?>

</main>

<?php get_footer(); ?>
