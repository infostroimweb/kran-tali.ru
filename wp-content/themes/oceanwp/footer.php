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
                    <div class="footer-menu-container">
                        <?php 
                        wp_nav_menu([
                            'menu'       => '23', 
                            'container'  => false, 
                            'menu_class' => 'footer-menu flex-column',
                            'menu_id'    => 'footerProduction'
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="footer-col align-items-start align-items-lg-end">
                <div class="d-flex flex-column">
                    <h2 class="footer-menu-title">Услуги</h2>
                    <div class="footer-menu-container">
                        <?php 
                        wp_nav_menu([
                            'menu'       => '24', 
                            'container'  => false, 
                            'menu_class' => 'footer-menu flex-column',
                            'menu_id'    => 'footerProduction'
                        ]);
                        ?>
                    </div>
                </div>        
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            <div class="footer-copyright-inner d-flex justify-content-between align-items-lg-center flex-column flex-lg-row">
                <span class="footer-copyright-left">© ООО "Росттехмаш" 2008 — <?php echo date('Y') ?>. Информация на сайте не является публичной офертой.</span>
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
            jQuery(this).closest("label").html(filename);
        });
    });
</script>

<script>
    jQuery(document).ready( ($) => {
        $('#mobMenuBtn').on('click', () => {
            $('#new-header .header-menu-container').slideToggle()
            $('#mobMenuBtn i').toggle()
        })

        $('.new-header-menu > .menu-item-has-children > a').on('click', function(e) {
            e.preventDefault()
            if(document.body.clientWidth <= 1024) {
                $(this).next().slideToggle()
            }            
        })

        $('.footer-menu-title').on('click', function() {
            $(this).toggleClass('active')
            $(this).next('.footer-menu-container').slideToggle()
        })
    })
</script>

<?php $zoom = is_mobile() ? '5' : '7'; ?>
<?php if (is_page('kontakty')): ?>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=b9282ea3-6f59-41e7-8ac1-3108161a14d9&lang=ru_RU"></script>
    <script>
        var myMap;
        ymaps.ready(init);

        function init () {
            myMap = new ymaps.Map('map', {
                center: [54.664425, 41.387129],
                zoom: <?php echo $zoom; ?>,
                controls: ['zoomControl', 'typeSelector',  'fullscreenControl']
            });
            var moscow = new ymaps.Placemark([55.800578, 37.636649], {
                iconContent: 'Представительство в Москве',
                balloonContent: 'Представительство в Москве.<br>Контактный телефон: +7&nbsp;(495)&nbsp;532&#8209;36&#8209;09.<br>129626, г.&#8201;Москва, пр-кт Мира, д.&#8201;102, корпус 1, эт.&#8201;8, ком.&#8201;6, оф.&#8201;36<br> E-mail: kran-tali@mail.ru'
            }, {
                preset: 'islands#redStretchyIcon'
            });
            var penza = new ymaps.Placemark([53.194208, 45.001398], {
                iconContent: 'Офис и производство',
                balloonContent: 'г.&#8201;Пенза, ул.&#8201;Захарова, д.&#8201;1, офис 8<br> Контактный телефон: +7&nbsp;(8412)&nbsp;30&#8209;56&#8209;63.<br> E-mail: kran-tali@mail.ru'
            }, {
                preset: 'islands#redStretchyIcon'
            });
            myMap.geoObjects.add(moscow).add(penza);
            myMap.behaviors.disable('scrollZoom');
            <?php if(is_mobile()): ?>myMap.behaviors.disable('drag');<?php endif; ?>
        } 
    </script>
<?php endif ?>

<?php if (is_single()): ?>
    <?php if($price = get_post_meta($post->ID, 'price', 1)): ?>
        <?php $prodDescription = preg_replace('/\[contact-form-7.+\]/', '', strip_tags(get_the_content())); ?>
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Product",
                "image": "<?php the_post_thumbnail_url(); ?>",
                "url": "<?php echo get_post_permalink() ?>",
                "name": "<?php the_title(); ?>",
                "description": "<?php echo $prodDescription ?>",
                "brand": "Росттехмаш",
                "offers": 
                {
                    "@type": "Offer",
                    "availability": "https://schema.org/PreOrder",
                    "url": "<?php echo get_post_permalink() ?>",
                    "price": "<?php echo number_format($price, 2, '.', ''); ?>",
                    "priceCurrency": "RUB",
                    "availableDeliveryMethod": "собственным транспортом или компанией",
                    "seller": "ООО «Росттехмаш»",
                    "offeredBy": "ООО «Росттехмаш»",
                    "warranty": "гарантия от производителя",
                    "itemOffered": "производство под заказ",
                    "itemCondition": "новый" 
                }

            }
        </script>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>