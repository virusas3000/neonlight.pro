<?php
/**
 * Front Page — NeonLightHK
 * @package NeonLightHK
 */
get_header();
?>

<!-- Hero Section -->
<?php get_template_part( 'template-parts/section', 'hero' ); ?>

<!-- Services Grid (2x2 Cards) -->
<?php get_template_part( 'template-parts/section', 'shop' ); ?>

<!-- Lookbook / Portfolio -->
<?php get_template_part( 'template-parts/section', 'lookbook' ); ?>

<!-- Visit Us -->
<?php get_template_part( 'template-parts/section', 'visit' ); ?>

<!-- Clients -->
<?php get_template_part( 'template-parts/section', 'clients' ); ?>

<!-- Contact -->
<?php get_template_part( 'template-parts/section', 'contact' ); ?>

<?php get_footer(); ?>
