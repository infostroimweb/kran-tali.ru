<link rel="stylesheet" href="/wp-content/themes/oceanwp/partials/new-header/new-header.css?v=1.4">
<header class="<?php if (is_front_page()) {echo 'front-page';} else {echo 'inner-page';} ?>" id="new-header">
	<?php if (!is_mobile()): ?>
		<div class="new-header-top">
			<div class="new-header-top-inner container d-flex">
				<address class="d-flex align-items-center" style="margin: 0;">
					<ul class="new-header-contact d-flex">
						<li>
							<span><i class="fas fa-map-marker-alt"></i>г. Пенза, ул. Захарова, д. 1, офис 8</span>
						</li>
						<li>
							<a href="mailto:kran-tali@mail.ru"><i class="far fa-envelope-open"></i><span>kran-tali@mail.ru</span></a>
						</li>
						<li>
							<a href="tel:88412305663"><i class="fas fa-phone-alt"></i><span>8 (8412) 30-56-63</span></a>
						</li>
					</ul>
				</address>
				<button class="button jsModalOpen" data-id-modal="callback">Закажите звонок</button>
			</div>
		</div>
	<?php endif; ?>
	<div class="new-header-bottom">
		<nav class="new-header-bottom-inner container d-flex justify-content-between align-items-center">
			<?php echo get_custom_logo(); ?>
			<div class="header-menu-container">
				<?php wp_nav_menu([
					'menu_id'		  => 'new-header-menu',	
					'menu_class'      => 'new-header-menu d-flex',
					'container' 	  => false
				]); ?>
				<?php if (is_mobile()): ?>
					<ul class="new-header-contact d-flex">
						<li>
							<a href="tel:88412305663"><i class="fas fa-phone-alt"></i><span>8 (8412) 30-56-63</span></a>
						</li>
					</ul>
					<button class="button jsModalOpen" data-id-modal="callback">Закажите звонок</button>
				<?php endif; ?>
			</div>			
			<button class="new-header-mob-btn" id="mobMenuBtn" aria-label = "menu">
				<i class="eicon-menu-bar fas fa-bars" aria-hidden="true"></i>
				<i class="eicon-close fas fa-times" aria-hidden="true" style="display:none"></i>				
			</button>	
		</nav>
	</div>
	<?php if (is_mobile()): ?>
		<div class="new-header-phone-bar">
			<div class="container">
				<span style="margin-right: 30px"><i class="fas fa-map-marker-alt"></i><span>г.&#8239;Пенза</span></span>
				<a href="tel:88412305663"><i class="fas fa-phone-alt"></i><span>8 (8412) 30-56-63</span></a>
			</div>
		</div>
	<?php endif; ?>
</header>