<?php
/**
 * Blog Archive / Index
 * @package NeonLightHK
 */
get_header();
?>

<div class="nl-page nl-projects">
    <h1 class="nl-page__title"><?php single_post_title(); ?></h1>

    <?php if ( have_posts() ) : ?>
        <div class="nl-blog-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="nl-blog-card">
                    <a href="<?php the_permalink(); ?>" class="nl-blog-card__link">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="nl-blog-card__image">
                                <?php the_post_thumbnail('medium_large', ['alt' => get_the_title(), 'loading' => 'lazy']); ?>
                            </div>
                        <?php endif; ?>
                        <div class="nl-blog-card__body">
                            <h2 class="nl-blog-card__title"><?php the_title(); ?></h2>
                            <p class="nl-blog-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' ); ?></p>
                        </div>
                    </a>
                </article>
            <?php endwhile; ?>
        </div>
        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <div style="text-align:center; padding:60px 0;">
            <p style="font-size:1.1rem; opacity:0.6;"><?php _e( 'No posts yet.', 'neonlighthk' ); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
