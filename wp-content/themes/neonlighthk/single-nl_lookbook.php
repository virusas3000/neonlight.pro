<?php get_header(); ?>

<main id="primary" class="site-main">

<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('nl-single nl-lookbook'); ?>>
    <div class="nl-container">
        <div class="nl-hero">
            <div class="nl-hero-content">
                <h1 class="nl-title"><?php the_title(); ?></h1>
                <?php
                $cat = get_post_meta( get_the_ID(), 'category', true );
                if ( $cat ) : ?>
                <span class="nl-badge nl-category"><?php echo esc_html( ucfirst( str_replace( '_', ' ', $cat ) ) ); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <?php
        $gallery = get_post_meta( get_the_ID(), 'gallery', true );
        if ( $gallery ) :
            $images = maybe_unserialize( $gallery );
            if ( is_array( $images ) && ! empty( $images ) ) : ?>
        <div class="nl-gallery nl-gallery-masonry">
            <?php foreach ( $images as $img_id ) :
                $img_url = wp_get_attachment_image_url( $img_id, 'large' );
                $img_full = wp_get_attachment_image_url( $img_id, 'full' );
                if ( $img_url ) : ?>
                <a href="<?php echo esc_url( $img_full ); ?>" class="nl-gallery-item" data-lightbox="lookbook">
                    <img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy" />
                </a>
                <?php endif;
            endforeach; ?>
        </div>
        <?php endif; endif; ?>

        <div class="nl-content">
            <?php the_content(); ?>
        </div>
    </div>
</article>
<?php endwhile; ?>

</main>

<?php get_footer(); ?>
