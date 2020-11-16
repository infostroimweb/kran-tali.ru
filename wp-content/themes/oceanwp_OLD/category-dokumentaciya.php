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

			<?php 

			$subCats = get_categories( array( 'parent' => '18' ) );

			foreach ($subCats as $subCat) : ?>

				<h2><?php echo $subCat->name; ?></h2>
				
				<ul>
					<?php 
					$docPosts = get_posts(array('category' => $subCat->cat_ID));
					if ($docPosts) :
						foreach ($docPosts as $docPost) : the_post(); ?>
							<li><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>

			<?php endforeach;  ?>

			<?php do_action( 'ocean_after_content_inner' ); ?>

		</div><!-- #content -->

		<?php do_action( 'ocean_after_content' ); ?>

	</div><!-- #primary -->

	<?php do_action( 'ocean_after_primary' ); ?>

</div><!-- #content-wrap -->

<?php do_action( 'ocean_after_content_wrap' ); ?>

<?php get_footer(); ?>