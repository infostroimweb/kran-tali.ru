<?php
/*
YARPP Template: Thumbnails
Description: Requires a theme which supports post thumbnails
Author: YARPP Team
*/ ?>
<?php if (have_posts()):?>
	<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/assets/css/category.css">
	<div class="form-service-wrap">
		<?php echo do_shortcode('[contact-form-7 id="1550"]'); ?>
	</div>
	<div class="container interes">
		<h3>Вас также может заинтересовать:</h3>
		<div class="entries clr">
			<?php while (have_posts()) : the_post(); ?>
				<?php
				$oceanwp_count++; ?>
				<?php
				get_template_part( 'partials/entry/layout', get_post_type() ); ?>
				<?php
				if ( oceanwp_blog_entry_columns() == $oceanwp_count ) {
					$oceanwp_count=0;
				} ?>
			<?php endwhile; ?>
		</div>
	</div>
<?php endif; ?>