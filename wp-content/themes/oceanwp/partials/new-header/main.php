<link rel="stylesheet" href="/wp-content/themes/oceanwp/partials/new-header/new-header.css">
<header class="<?php if (is_front_page()) {echo 'front-page';} else {echo 'inner-page';} ?>" id="new-header">
	<div class="new-header-top">
		<div class="new-header-top-inner container d-flex">
			<ul class="new-header-contact d-flex">
				<li>
					<span><i class="fas fa-map-marker-alt"></i>г. Пенза, ул. Захарова, д. 1, офис 8</span>
				</li>
				<li>
					<a href="mailto:kran-tali@mail.ru"><i class="far fa-envelope-open"></i>kran-tali@mail.ru</span></a>
				</li>
				<li>
					<a href="tel:88412305663"><i class="fas fa-phone-alt"></i><span>8 (8412) 30-56-63</span></a>
				</li>
			</ul>
			<?php echo do_shortcode('[elementor-template id="1553"]'); ?>
		</div>
	</div>
	<div class="new-header-bottom">
		<div class="new-header-bottom-inner container d-flex justify-content-between align-items-center">
			<?php echo get_custom_logo(); ?>
			<?php wp_nav_menu([
				'menu_id'		  => 'new-header-menu',	
				'menu_class'      => 'new-header-menu d-flex',
				'echo'            => true
			]); ?>
			<button class="new-header-mob-btn" id="moMenuBtn">
				<i class="eicon-menu-bar" aria-hidden="true"></i>				
			</button>	
		</div>
	</div>
</header>