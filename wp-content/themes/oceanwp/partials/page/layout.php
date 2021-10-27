<?php
/**
 * Outputs correct page layout
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<?php if (!is_front_page()): ?>
	<article class="single-page-article clr">
<?php endif ?>


	<?php
	// Get page entry
	get_template_part( 'partials/page/article' );

	// Display comments
	comments_template(); ?>

<?php if (!is_front_page()): ?>
	</article>
<?php endif ?>
