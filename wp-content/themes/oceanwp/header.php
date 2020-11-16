<?php
/**
 * The Header for our theme.
 *
 * @package OceanWP WordPress theme
 */ ?>

 <!DOCTYPE html>
 <html class="<?php echo esc_attr( oceanwp_html_classes() ); ?>" <?php language_attributes(); ?>>
 <head>
 	<meta charset="<?php bloginfo( 'charset' ); ?>">
 	<link rel="profile" href="https://gmpg.org/xfn/11">


 	<?php wp_head(); ?>

 </head>

 <body <?php body_class(); ?> <?php oceanwp_schema_markup( 'html' ); ?>>

 	<?php wp_body_open(); ?>

 	<?php do_action( 'ocean_before_outer_wrap' ); ?>

 	<div id="outer-wrap" class="site clr">

 		<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'oceanwp' ); ?></a>

 		<?php do_action( 'ocean_before_wrap' ); ?>

 		<div id="wrap" class="clr">

 			<?php do_action( 'ocean_top_bar' ); ?>

 			<?php 
 			/*do_action( 'ocean_header' );*/
 			get_template_part('partials/new-header/main');
 			?>

 			<?php do_action( 'ocean_before_main' ); ?>

 			<main id="main" class="site-main clr <?php if (!is_front_page()) echo 'inner-main'; ?>"<?php oceanwp_schema_markup( 'main' ); ?> role="main">

 				<?php do_action( 'ocean_page_header' ); ?>

 				<?php if (is_front_page()): ?>
 					<div id="main-page-top">
 						<div class="container">
 							<h1>Грузоподъемное оборудование<br>любой сложности</h1>
 							<p>Срок изготовления кранов от 15 дней</p>
 							<a href="/kontakty" rel="nofollow" class="button-link" role="button">
 								<i aria-hidden="true" class="fas fa-pencil-alt"></i>
 								<span>ОСТАВЬТЕ ЗАЯВКУ</span>
 							</a>
 						</div>
 					</div>
				<?php else: ?>
					<div class="container">
						<?php the_breadcrumb(); ?>
						<div class="inner-heading">
							<h1><?php 
							if (is_single()) {
								the_title(); 
							} elseif(is_category()) {
								single_cat_title();
							} 							
							?></h1>
						</div>						
					</div>
				<?php endif; ?>
