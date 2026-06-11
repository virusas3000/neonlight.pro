<?php get_header(); ?>

<main id="primary" class="site-main">

<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('nl-single nl-custom-order'); ?>>
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
                    $est_price = get_post_meta( get_the_ID(), 'estimated_price', true );
                    $turnaround = get_post_meta( get_the_ID(), 'turnaround_days', true );
                    ?>
                    <?php if ( $est_price ) : ?><span class="nl-badge nl-price">Est. HKD <?php echo esc_html( number_format( $est_price ) ); ?></span><?php endif; ?>
                    <?php if ( $turnaround ) : ?><span class="nl-badge"><?php echo esc_html( $turnaround ); ?> days turnaround</span><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="nl-content">
            <?php the_content(); ?>
            <?php
            $design_opts = get_post_meta( get_the_ID(), 'design_options', true );
            $size_opts   = get_post_meta( get_the_ID(), 'size_options', true );
            if ( $design_opts ) : ?>
            <div class="nl-options">
                <h3><?php _e( 'Design Options', 'neonlighthk' ); ?></h3>
                <p><?php echo nl2br( esc_html( $design_opts ) ); ?></p>
            </div>
            <?php endif; ?>
            <?php if ( $size_opts ) : ?>
            <div class="nl-options">
                <h3><?php _e( 'Size Options', 'neonlighthk' ); ?></h3>
                <p><?php echo nl2br( esc_html( $size_opts ) ); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="nl-cta">
            <a href="/contact/?subject=Custom%20Order%20Request" class="nl-button nl-button-primary">
                <?php _e( 'Request Quote', 'neonlighthk' ); ?>
            </a>
        </div>
    </div>
</article>
<?php endwhile; ?>

</main>

<?php get_footer(); ?>
