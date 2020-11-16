<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<?php /*echo do_shortcode('[elementor-template id="115"]');*/ ?>
<header id="site-header" class="<?php echo esc_attr( oceanwp_header_classes() ); ?>" style="min-height: 100%" <?php oceanwp_schema_markup( 'header' ); ?> role="banner">

	<?php get_template_part( 'partials/header/style/custom-header' ); ?>

</header>

<?php do_action( 'ocean_after_header' ); ?>