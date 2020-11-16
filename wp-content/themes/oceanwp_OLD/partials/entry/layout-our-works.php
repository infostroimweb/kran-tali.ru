<?php
/**
 * Default post entry layout
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get post format
$format = get_post_format();

// Blog style
$style = get_theme_mod( 'ocean_blog_style', 'large-entry' );

// Quote format is completely different
if ( 'quote' == $format ) {

	// Get quote entry content
	get_template_part( 'partials/entry/quote' );

	return;

}

// If thumbnail style
if ( 'thumbnail-entry' == $style ) {
	get_template_part( 'partials/entry/thumbnail-style/layout' );
} else {

	// Add classes to the blog entry post class
	$classes = oceanwp_post_entry_classes(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

		<div class="blog-entry-inner clr">

			<?php
			// Get elements
			$elements = oceanwp_blog_entry_elements_positioning();

			get_template_part( 'partials/entry/header' );
			get_template_part( 'partials/entry/media/blog-entry', $format );

			 ?>

			 <a href="<?php echo get_permalink(); ?>" class="our-works-btn">Подробнее</a>


		</div><!-- .blog-entry-inner -->

	</article><!-- #post-## -->

<?php
} ?>