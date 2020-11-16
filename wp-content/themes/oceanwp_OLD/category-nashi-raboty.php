<?php

get_header(); ?>

	<?php do_action( 'ocean_before_content_wrap' ); ?>

	<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/assets/css/category.css">

	<div id="content-wrap" class="container clr">

		<?php do_action( 'ocean_before_primary' ); ?>

		<div id="primary" class="clr">

			<?php do_action( 'ocean_before_content' ); ?>

			<div id="content" class="site-content clr">

				<?php do_action( 'ocean_before_content_inner' ); ?>

				<?php
				// Check if posts exist
				if ( have_posts() ) :

					// Elementor `archive` location
					if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {

						// Add Support For EDD Archive Pages
						if ( is_post_type_archive( 'download' ) || is_tax( array( 'download_category', 'download_tag' ) ) ) { 

							do_action( 'ocean_before_archive_download' ); ?>

							<div class="oceanwp-row <?php echo esc_attr( oceanwp_edd_loop_classes() ); ?>">
								<?php
								// Archive Post Count for clearing float
								$oceanwp_count = 0;
								while ( have_posts() ) : the_post();
									$oceanwp_count++;
									get_template_part( 'partials/edd/archive' );
									if ( oceanwp_edd_entry_columns() == $oceanwp_count ) {
										$oceanwp_count=0;
									}
								endwhile; ?>
							</div>

							<?php
							do_action( 'ocean_after_archive_download' );
						}  else { ?>
						<div id="blog-entries" class="<?php oceanwp_blog_wrap_classes(); ?> our-works-entries">

							<?php
							// Define counter for clearing floats
							$oceanwp_count = 0; ?>

							<?php
							// Loop through posts
							while ( have_posts() ) : the_post(); ?>

								<?php
								// Add to counter
								$oceanwp_count++; ?>

								<?php
								// Get post entry content
								get_template_part( 'partials/entry/layout-our-works', get_post_type() ); ?>

								<?php
								// Reset counter to clear floats
								if ( oceanwp_blog_entry_columns() == $oceanwp_count ) {
									$oceanwp_count=0;
								} ?>

							<?php endwhile; ?>

						</div><!-- #blog-entries -->

						<?php
						// Display post pagination
						oceanwp_blog_pagination();
						}
					} ?>

				<?php
				// No posts found
				else : ?>

					<?php
					// Display no post found notice
					get_template_part( 'partials/none' ); ?>

				<?php endif; ?>

				<?php do_action( 'ocean_after_content_inner' ); ?>

			</div><!-- #content -->

			<?php do_action( 'ocean_after_content' ); ?>

		</div><!-- #primary -->

		<?php do_action( 'ocean_after_primary' ); ?>

	</div><!-- #content-wrap -->

	<?php do_action( 'ocean_after_content_wrap' ); ?>

<?php get_footer(); ?>