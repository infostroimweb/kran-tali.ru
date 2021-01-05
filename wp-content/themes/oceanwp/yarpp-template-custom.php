<?php
/*
YARPP Template: Thumbnails
Description: Requires a theme which supports post thumbnails
Author: YARPP Team
*/ ?>

<!-- <?php if (have_posts()):?>
	<h3>Вас также может заинтересовать:</h3>
	<ul>
		<?php while (have_posts()) : the_post(); ?>
			<?php if (has_post_thumbnail()):?>
				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a></li>
			<?php endif; ?>
		<?php endwhile; ?>
	</ul>
<?php endif; ?> -->


<?php if (have_posts()):?>
	<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/assets/css/category.css">
	<h3>Вас также может заинтересовать:</h3>
	<div class="entries clr">
		<?php while (have_posts()) : the_post(); ?>
			<?php
			// Add to counter
			$oceanwp_count++; ?>

			<?php
			// Get post entry content
			get_template_part( 'partials/entry/layout', get_post_type() ); ?>

			<?php
			// Reset counter to clear floats
			if ( oceanwp_blog_entry_columns() == $oceanwp_count ) {
				$oceanwp_count=0;
			} ?>
		<?php endwhile; ?>
	</div>
<?php endif; ?>