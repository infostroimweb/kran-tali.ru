<?php
/**
 * The template for displaying the footer.
 *
 * @package OceanWP WordPress theme
 */ 
?>



</main><!-- #main -->

<?php do_action( 'ocean_after_main' ); ?>

<?php do_action( 'ocean_before_footer' ); ?>

<?php
        // Elementor `footer` location
/*if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) { ?>

    <?php do_action( 'ocean_footer' ); ?>

<?php }*/ ?>

<footer class="footer">
    <div class="footer-top container">
        <div class="d-flex flex-wrap justify-content-between flex-column flex-lg-row">
            <div class="footer-col align-items-start">
                <a href="/" class="footer-logo">
                    <img class="lazyloaded" src="/wp-content/uploads/2020/11/logo-rosttehmash-vertical.png" data-src="/wp-content/uploads/2020/11/logo-rosttehmash-vertical.png" title="" alt="">
                </a>
                <p class="footer-description">Производство грузоподъёмного оборудования: мостовые краны, кран-балки, консольные краны, козловые краны, электротали, лебёдки, эстакады.</p>
                <!-- <ul class="footer-socials d-flex flex-wrap">
                    <li>
                        <a class="" target="_blank" rel="nofollow">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a class="" target="_blank" rel="nofollow">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                    <li>
                        <a class="" target="_blank" rel="nofollow">
                            <i class="fab fa-vk"></i>
                        </a>
                    </li>
                </ul> -->
                <?php echo do_shortcode('[elementor-template id="1553"]'); ?>
                <a href="/sitemap/">Карта сайта</a>
            </div>
            <div class="footer-col align-items-start align-items-lg-center">
                <div class="d-flex flex-column">
                    <h2 class="footer-menu-title">Продукция</h2>
                    <?php 
                    wp_nav_menu([
                        'menu'       => '23', 
                        'container'  => false, 
                        'menu_class' => 'footer-menu d-flex flex-column',
                        'menu_id'    => 'footerProduction'
                    ]);
                    ?>
                </div>
            </div>
            <div class="footer-col align-items-start align-items-lg-end">
                <div class="d-flex flex-column">
                    <h2 class="footer-menu-title">Услуги</h2>
                    <?php 
                    wp_nav_menu([
                        'menu'       => '24', 
                        'container'  => false, 
                        'menu_class' => 'footer-menu d-flex flex-column',
                        'menu_id'    => 'footerProduction'
                    ]);
                    ?>
                </div>        
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            <div class="footer-copyright-inner d-flex justify-content-between align-items-lg-center flex-column flex-lg-row">
                <span class="footer-copyright-left">© ООО "Росттехмаш" 2008 — 2021. Информация на сайте не является публичной офертой.</span>
                <a href="/policy/" class="footer-copyright-right">Соглашение на обработку персональных данных</a>                
            </div>           
        </div>
    </div>
</footer>



<?php do_action( 'ocean_after_footer' ); ?>

</div><!-- #wrap -->

<?php do_action( 'ocean_after_wrap' ); ?>

</div><!-- #outer-wrap -->

<?php do_action( 'ocean_after_outer_wrap' ); ?>

<?php
// If is not sticky footer
if ( ! class_exists( 'Ocean_Sticky_Footer' ) ) {
    get_template_part( 'partials/scroll-top' );
} ?>

<?php
// Search overlay style
if ( 'overlay' == oceanwp_menu_search_style() ) {
    get_template_part( 'partials/header/search-overlay' );
} ?>

<?php
// If sidebar mobile menu style
if ( 'sidebar' == oceanwp_mobile_menu_style() ) {

    // Mobile panel close button
    if ( get_theme_mod( 'ocean_mobile_menu_close_btn', true ) ) {
        get_template_part( 'partials/mobile/mobile-sidr-close' );
    } ?>

    <?php
    // Mobile Menu (if defined)
    get_template_part( 'partials/mobile/mobile-nav' ); ?>

    <?php
    // Mobile search form
    if ( get_theme_mod( 'ocean_mobile_menu_search', true ) ) {
        get_template_part( 'partials/mobile/mobile-search' );
    }

} ?>

<?php
// If full screen mobile menu style
if ( 'fullscreen' == oceanwp_mobile_menu_style() ) {
    get_template_part( 'partials/mobile/mobile-fullscreen' );
} ?>
<?php wp_footer(); ?>

<script>
	jQuery(document).ready( function() {
		jQuery("#file-load input[name='file']").change(function(){
			var filename = jQuery(this).val().replace(/.*\\/, "");
			console.log(filename);
			jQuery(this).closest("label").html(filename);
		});
	});
</script>

<script>
    jQuery(document).ready( ($) => {
        $('.new-header-mob-btn').on('click', () => {
            $('#new-header .menu-menu-container').slideToggle()
        })

        $('.new-header-menu > .menu-item-has-children > a').on('click', function(e) {
            e.preventDefault()
            if(document.body.clientWidth <= 1024) {
                $(this).next().slideToggle()
            }            
        })
    })
</script>
</body>
</html>