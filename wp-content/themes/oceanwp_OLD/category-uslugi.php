<?php

get_header(); ?>

<?php do_action( 'ocean_before_content_wrap' ); ?>

<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/assets/css/category.css">

<div id="content-wrap" class="container clr">

	<?php do_action( 'ocean_before_primary' ); ?>

	<div id="primary" class="clr">

		<?php do_action( 'ocean_before_content' ); ?>

		<div id="content" class="site-content documentation clr">

			<?php do_action( 'ocean_before_content_inner' ); ?>

			<?php if ( have_posts() ) : ?>
				<ul>
					<?php 
					$oceanwp_count = 0;
					while ( have_posts() ) : the_post();
						$oceanwp_count++; ?>
						<li><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php endwhile; ?>
				</ul>
			<?php endif; ?>

			<?php do_action( 'ocean_after_content_inner' ); ?>

		</div><!-- #content -->

		<?php do_action( 'ocean_after_content' ); ?>

	</div><!-- #primary -->

	<?php do_action( 'ocean_after_primary' ); ?>

</div><!-- #content-wrap -->

<?php do_action( 'ocean_after_content_wrap' ); ?>

<?php get_footer(); ?>