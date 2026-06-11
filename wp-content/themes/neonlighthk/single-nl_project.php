<?php get_header(); ?>

<main id="primary" class="site-main">

<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('nl-single nl-project'); ?>>
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
                    $client   = get_post_meta( get_the_ID(), 'client_name', true );
                    $proj_date = get_post_meta( get_the_ID(), 'project_date', true );
                    $location = get_post_meta( get_the_ID(), 'location', true );
                    $budget   = get_post_meta( get_the_ID(), 'budget_range', true );
                    ?>
                    <?php if ( $client ) : ?><span class="nl-badge">Client: <?php echo esc_html( $client ); ?></span><?php endif; ?>
                    <?php if ( $proj_date ) : ?><span class="nl-badge"><?php echo esc_html( $proj_date ); ?></span><?php endif; ?>
                    <?php if ( $location ) : ?><span class="nl-badge"><i class="dashicons dashicons-location"></i> <?php echo esc_html( $location ); ?></span><?php endif; ?>
                    <?php if ( $budget ) : ?><span class="nl-badge"><?php echo esc_html( $budget ); ?></span><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="nl-content">
            <?php the_content(); ?>
        </div>

        <?php
        $gallery = get_post_meta( get_the_ID(), 'gallery', true );
        if ( $gallery ) :
            $images = maybe_unserialize( $gallery );
            if ( is_array( $images ) && ! empty( $images ) ) : ?>
        <div class="nl-gallery">
            <h3><?php _e( 'Project Gallery', 'neonlighthk' ); ?></h3>
            <div class="nl-gallery-grid">
                <?php foreach ( $images as $img_id ) :
                    $img_url = wp_get_attachment_image_url( $img_id, 'medium' );
                    $img_full = wp_get_attachment_image_url( $img_id, 'full' );
                    if ( $img_url ) : ?>
                    <a href="<?php echo esc_url( $img_full ); ?>" class="nl-gallery-item" data-lightbox="project">
                        <img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy" />
                    </a>
                    <?php endif;
                endforeach; ?>
            </div>
        </div>
        <?php endif; endif; ?>
    </div>
</article>
<?php endwhile; ?>

</main>

<?php get_footer(); ?>
