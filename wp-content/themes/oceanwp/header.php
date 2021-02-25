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
 					<script src="/wp-content/themes/oceanwp/assets/js/slick.min.js"></script>
 					<div id="main-page-top">
 						<div class="container">
 							<h1>Грузоподъемное оборудование<br>любой сложности</h1>
 							<p>Срок изготовления кранов от 15 дней</p>
 							<a href="/kontakty" rel="nofollow" class="button-link" role="button">
 								<i aria-hidden="true" class="fas fa-pencil-alt"></i>
 								<span>ОСТАВЬТЕ ЗАЯВКУ</span>
 							</a>
 						</div>
 						<div class="main-page-top-bg">
 							<div style="background: url('/wp-content/themes/oceanwp/partials/new-header/img/1.jpg') 50% no-repeat; background-size: cover;"></div>
 							<div style="background: url('/wp-content/themes/oceanwp/partials/new-header/img/2.jpg') 50% no-repeat; background-size: cover;"></div>
 							<div style="background: url('/wp-content/themes/oceanwp/partials/new-header/img/3.jpg') 50% no-repeat; background-size: cover;"></div>
 						</div>
 					</div>
 					<script>jQuery('.main-page-top-bg').slick({
 						autoplay: true,
 						autoplaySpeed: 4000,
 						arrows: false,
 						fade: true,
 						speed: 1000
 					})</script>
				<?php else: ?>
					<div class="container">
						<?php the_breadcrumb(); ?>
						<div class="inner-heading">
							<h1><?php 
							if (is_page()) {
								echo $post->post_title;
							} elseif(is_single()) {
								the_title();
							} elseif(is_category()) {
								single_cat_title();
							} 							
							?></h1>
							<?php if (is_single()): ?>
								<?php if($price = get_post_meta($post->ID, 'price', 1)): ?>
									<div class="price-block d-flex flex-column flex-lg-row align-items-center justify-content-center">
										<p class="price-block__text"><?php echo get_post_meta($post->ID, 'price_text', 1);  ?></p>
										<div class="price-block__price d-flex align-items-center">от <?php echo number_format($price, 0, ',', ' '); ?> &#8381;</div>
										<button class="jsModalOpen" data-id-modal="callback">Заказать</button>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</div>						
					</div>
				<?php endif; ?>
