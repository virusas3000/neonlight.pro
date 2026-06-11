<?php
/**
 * Single Blog Post
 * @package NeonLightHK
 */
get_header();
?>

<article id="post-<?php the_ID(); ?>" class="nl-blog-single">
    <div class="nl-container">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="nl-blog-hero">
                <?php the_post_thumbnail('large', ['class' => 'nl-blog-image', 'alt' => get_the_title()]); ?>
            </div>
        <?php endif; ?>

        <div class="nl-blog-header">
            <h1 class="nl-blog-title"><?php the_title(); ?></h1>
            <div class="nl-blog-meta">
                <span class="nl-blog-date"><?php echo get_the_date(); ?></span>
            </div>
        </div>

        <div class="nl-blog-content">
            <?php the_content(); ?>
        </div>

        <div class="nl-blog-nav">
            <?php
            previous_post_link('%link', '<span class="nl-nav-prev">← ' . nl_lang('prev_post', 'Previous Post') . '</span>');
            next_post_link('%link', '<span class="nl-nav-next">' . nl_lang('next_post', 'Next Post') . ' →</span>');
            ?>
        </div>
    </div>
</article>

<?php get_footer(); ?>
