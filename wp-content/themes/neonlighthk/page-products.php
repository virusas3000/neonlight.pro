<?php
/**
 * Template Name: Products
 *
 * @package NeonLightHK
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="nl-page-header">
        <span class="nl-page-label">零售</span>
        <h1 class="nl-page-title">NEON RETAIL</h1>
        <p class="nl-page-subtitle">Ready-made neon signs, accessories &amp; LED products</p>
    </div>

    <?php
    // WooCommerce product grid with dark theme wrapper
    if ( class_exists( 'WooCommerce' ) ) :
        // Get products
        $products = new WP_Query( array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ) );

        if ( $products->have_posts() ) :
    ?>
    <div class="nl-products-grid">
        <?php
        while ( $products->have_posts() ) : $products->the_post();
            global $product;
            $price_html = $product->get_price_html();
        ?>
        <article class="nl-product-card">
            <a href="<?php the_permalink(); ?>" class="nl-card-link">
                <div class="nl-product-img">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
                    <?php else : ?>
                        <div class="nl-card-placeholder">PRODUCT</div>
                    <?php endif; ?>
                </div>
                <div class="nl-product-info">
                    <h3 class="nl-product-title"><?php the_title(); ?></h3>
                    <?php if ( $price_html ) : ?>
                        <div class="nl-product-price"><?php echo $price_html; ?></div>
                    <?php endif; ?>
                    <span class="button">Add to Cart</span>
                </div>
            </a>
        </article>
        <?php
            endwhile;
            wp_reset_postdata();
        ?>
    </div>
    <?php
        else :
    ?>
    <div class="nl-archive-grid">
        <p>No products available yet. Check back soon for our neon sign collection!</p>
    </div>
    <?php
        endif;
    else :
    ?>
    <div class="nl-archive-grid">
        <p>WooCommerce is required to display products. Please install and activate WooCommerce.</p>
    </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
