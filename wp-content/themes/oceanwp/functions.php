<?php
/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Core Constants
define( 'OCEANWP_THEME_DIR', get_template_directory() );
define( 'OCEANWP_THEME_URI', get_template_directory_uri() );

final class OCEANWP_Theme_Class {

	/**
	 * Main Theme Class Constructor
	 *
	 * @since   1.0.0
	 */
	public function __construct() {
		

		// Define constants
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'constants' ), 0 );

		// Load all core theme function files
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'include_functions' ), 1 );

		// Load configuration classes
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'configs' ), 3 );

		// Load framework classes
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'classes' ), 4 );

		// Setup theme => add_theme_support, register_nav_menus, load_theme_textdomain, etc
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'theme_setup' ), 10 );

		// Setup theme => Generate the custom CSS file
		add_action( 'admin_bar_init', array( 'OCEANWP_Theme_Class', 'save_customizer_css_in_file' ), 9999 );

		// register sidebar widget areas
		add_action( 'widgets_init', array( 'OCEANWP_Theme_Class', 'register_sidebars' ) );

		// Registers theme_mod strings into Polylang
		if ( class_exists( 'Polylang' ) ) {
			add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'polylang_register_string' ) );
		}
		
		
		/** Admin only actions **/
		if ( is_admin() ) {

			// Load scripts in the WP admin
			add_action( 'admin_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'admin_scripts' ) );

			// Outputs custom CSS for the admin
			add_action( 'admin_head', array( 'OCEANWP_Theme_Class', 'admin_inline_css' ) );

			/** Non Admin actions **/
		} else {

			// Load theme CSS
			add_action( 'wp_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'theme_css' ) );

			// Load his file in last
			add_action( 'wp_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'custom_style_css' ), 9999 );

			// Remove Customizer CSS script from Front-end
			add_action( 'init', array( 'OCEANWP_Theme_Class', 'remove_customizer_custom_css' ) );

			// Load theme js
			add_action( 'wp_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'theme_js' ) );

			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'gtm_header' ), 1 );

			// Add a pingback url auto-discovery header for singularly identifiable articles
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'pingback_header' ), 1 );

			// Add meta viewport tag to header
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'meta_viewport' ), 1 );

			// Add an X-UA-Compatible header
			add_filter( 'wp_headers', array( 'OCEANWP_Theme_Class', 'x_ua_compatible_headers' ) );

			// Loads html5 shiv script
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'html5_shiv' ) );

			// Outputs custom CSS to the head
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'custom_css' ), 9999 );

			// Minify the WP custom CSS because WordPress doesn't do it by default
			add_filter( 'wp_get_custom_css', array( 'OCEANWP_Theme_Class', 'minify_custom_css' ) );

			// Alter the search posts per page
			add_action( 'pre_get_posts', array( 'OCEANWP_Theme_Class', 'search_posts_per_page' ) );

			// Alter WP categories widget to display count inside a span
			add_filter( 'wp_list_categories', array( 'OCEANWP_Theme_Class', 'wp_list_categories_args' ) );

			// Add a responsive wrapper to the WordPress oembed output
			add_filter( 'embed_oembed_html', array( 'OCEANWP_Theme_Class', 'add_responsive_wrap_to_oembeds' ), 99, 4 );

			// Adds classes the post class
			add_filter( 'post_class', array( 'OCEANWP_Theme_Class', 'post_class' ) );

			// Add schema markup to the authors post link
			add_filter( 'the_author_posts_link', array( 'OCEANWP_Theme_Class', 'the_author_posts_link' ) );

			// Add support for Elementor Pro locations
			add_action( 'elementor/theme/register_locations', array( 'OCEANWP_Theme_Class', 'register_elementor_locations' ) );

			// Remove the default lightbox script for the beaver builder plugin
			add_filter( 'fl_builder_override_lightbox', array( 'OCEANWP_Theme_Class', 'remove_bb_lightbox' ) );
			

		}

	
	}
	
	

	/**
	 * Define Constants
	 *
	 * @since   1.0.0
	 */
	public static function constants() {

		$version = self::theme_version();

		// Theme version
		define( 'OCEANWP_THEME_VERSION', $version );

		// Javascript and CSS Paths
		define( 'OCEANWP_JS_DIR_URI', OCEANWP_THEME_URI .'/assets/js/' );
		define( 'OCEANWP_CSS_DIR_URI', OCEANWP_THEME_URI .'/assets/css/' );

		// Include Paths
		define( 'OCEANWP_INC_DIR', OCEANWP_THEME_DIR .'/inc/' );
		define( 'OCEANWP_INC_DIR_URI', OCEANWP_THEME_URI .'/inc/' );

		// Check if plugins are active
		define( 'OCEAN_EXTRA_ACTIVE', class_exists( 'Ocean_Extra' ) );
		define( 'OCEANWP_ELEMENTOR_ACTIVE', class_exists( 'Elementor\Plugin' ) );
		define( 'OCEANWP_BEAVER_BUILDER_ACTIVE', class_exists( 'FLBuilder' ) );
		define( 'OCEANWP_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );
		define( 'OCEANWP_EDD_ACTIVE', class_exists( 'Easy_Digital_Downloads' ) );
		define( 'OCEANWP_LIFTERLMS_ACTIVE', class_exists( 'LifterLMS' ) );
		define( 'OCEANWP_ALNP_ACTIVE', class_exists( 'Auto_Load_Next_Post' ) );
		define( 'OCEANWP_LEARNDASH_ACTIVE', class_exists( 'SFWD_LMS' ) );
	}

	/**
	 * Load all core theme function files
	 *
	 * @since 1.0.0
	 */
	public static function include_functions() {
		$dir = OCEANWP_INC_DIR;
		require_once ( $dir .'helpers.php' );
		require_once ( $dir .'header-content.php' );
		require_once ( $dir .'customizer/controls/typography/webfonts.php' );
		require_once ( $dir .'walker/init.php' );
		require_once ( $dir .'walker/menu-walker.php' );
		require_once ( $dir .'third/class-gutenberg.php' );
		require_once ( $dir .'third/class-elementor.php' );
		require_once ( $dir .'third/class-beaver-themer.php' );
		require_once ( $dir .'third/class-bbpress.php' );
		require_once ( $dir .'third/class-buddypress.php' );
		require_once ( $dir .'third/class-lifterlms.php' );
		require_once ( $dir .'third/class-learndash.php' );
		require_once ( $dir .'third/class-sensei.php' );
		require_once ( $dir .'third/class-social-login.php' );
	}

	/**
	 * Configs for 3rd party plugins.
	 *
	 * @since 1.0.0
	 */
	public static function configs() {

		$dir = OCEANWP_INC_DIR;

		// WooCommerce
		if ( OCEANWP_WOOCOMMERCE_ACTIVE ) {
			require_once ( $dir .'woocommerce/woocommerce-config.php' );
		}

		// Easy Digital Downloads
		if ( OCEANWP_EDD_ACTIVE ) {
			require_once ( $dir .'edd/edd-config.php' );
		}
	}

	/**
	 * Returns current theme version
	 *
	 * @since   1.0.0
	 */
	public static function theme_version() {

		// Get theme data
		$theme = wp_get_theme();

		// Return theme version
		return $theme->get( 'Version' );

	}

	/**
	 * Load theme classes
	 *
	 * @since   1.0.0
	 */
	public static function classes() {

		// Admin only classes
		if ( is_admin() ) {

			// Recommend plugins
			require_once( OCEANWP_INC_DIR .'plugins/class-tgm-plugin-activation.php' );
			require_once( OCEANWP_INC_DIR .'plugins/tgm-plugin-activation.php' );

		}

		// Front-end classes
		else {

			// Breadcrumbs class
			require_once( OCEANWP_INC_DIR .'breadcrumbs.php' );

		}

		// Customizer class
		require_once( OCEANWP_INC_DIR .'customizer/customizer.php' );

	}

	/**
	 * Theme Setup
	 *
	 * @since   1.0.0
	 */
	public static function theme_setup() {

		// Load text domain
		load_theme_textdomain( 'oceanwp', OCEANWP_THEME_DIR .'/languages' );

		// Get globals
		global $content_width;

		// Set content width based on theme's default design
		if ( ! isset( $content_width ) ) {
			$content_width = 1200;
		}

		// Register navigation menus
		register_nav_menus( array(
			'topbar_menu'     => esc_html__( 'Top Bar', 'oceanwp' ),
			'main_menu'       => esc_html__( 'Main', 'oceanwp' ),
			'footer_menu'     => esc_html__( 'Footer', 'oceanwp' ),
			'mobile_menu'     => esc_html__( 'Mobile (optional)', 'oceanwp' )
		) );

		// Enable support for Post Formats
		add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio', 'quote', 'link' ) );

		// Enable support for <title> tag
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails on posts and pages
		add_theme_support( 'post-thumbnails' );

		/**
		 * Enable support for header image
		 */
		add_theme_support( 'custom-header', apply_filters( 'ocean_custom_header_args', array(
			'width'              => 2000,
			'height'             => 1200,
			'flex-height'        => true,
			'video'              => true,
		) ) );

		/**
		 * Enable support for site logo
		 */
		add_theme_support( 'custom-logo', apply_filters( 'ocean_custom_logo_args', array(
			'height'      => 45,
			'width'       => 164,
			'flex-height' => true,
			'flex-width'  => true,
		) ) );

		/*
		 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'widgets',
		) );

		// Declare WooCommerce support.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Add editor style
		add_editor_style( 'assets/css/editor-style.min.css' );
		

		// Declare support for selective refreshing of widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

	}

	public static function gtm_header() {
		$gtm = "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-PPGVMFQ');</script>";

		// Apply filters for child theme tweaking
	echo $gtm;
}



	/**
	 * Adds the meta tag to the site header
	 *
	 * @since 1.1.0
	 */
	public static function pingback_header() {

		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}

	}

	/**
	 * Adds the meta tag to the site header
	 *
	 * @since 1.0.0
	 */
	public static function meta_viewport() {

		// Meta viewport
		$viewport = '<meta name="viewport" content="width=device-width, initial-scale=1">';

		// Apply filters for child theme tweaking
		echo apply_filters( 'ocean_meta_viewport', $viewport );
	}

	/**
	 * Load scripts in the WP admin
	 *
	 * @since 1.0.0
	 */
	public static function admin_scripts() {
		global $pagenow;
		if ( 'nav-menus.php' == $pagenow ) {
			wp_enqueue_style( 'oceanwp-menus', OCEANWP_INC_DIR_URI .'walker/assets/menus.css' );
		}
	}

	/**
	 * Load front-end scripts
	 *
	 * @since   1.0.0
	 */
	public static function theme_css() {

		// Define dir
		$dir = OCEANWP_CSS_DIR_URI;
		$theme_version = OCEANWP_THEME_VERSION;

		// Remove font awesome style from plugins
		wp_deregister_style( 'font-awesome' );
		wp_deregister_style( 'fontawesome' );

		// Load font awesome style
		wp_enqueue_style( 'font-awesome', OCEANWP_THEME_URI .'/assets/fonts/fontawesome/css/all.min.css', false, '5.11.2' );

		// Register simple line icons style
		wp_enqueue_style( 'simple-line-icons', $dir .'third/simple-line-icons.min.css', false, '2.4.0' );

		// Register the lightbox style
		wp_enqueue_style( 'magnific-popup', $dir .'third/magnific-popup.min.css', false, '1.0.0' );

		// Register the slick style
		wp_enqueue_style( 'slick', $dir .'third/slick.min.css', false, '1.6.0' );

		// Main Style.css File
		wp_enqueue_style( 'oceanwp-style', $dir .'style.min.css', false, $theme_version );

		// Register hamburgers buttons to easily use them
		wp_register_style( 'oceanwp-hamburgers', $dir .'third/hamburgers/hamburgers.min.css', false, $theme_version );

		// Register hamburgers buttons styles
		$hamburgers = oceanwp_hamburgers_styles();
		foreach ( $hamburgers as $class => $name ) {
			wp_register_style( 'oceanwp-'. $class .'', $dir .'third/hamburgers/types/'. $class .'.css', false, $theme_version );
		}

		// Get mobile menu icon style
		$mobileMenu = get_theme_mod( 'ocean_mobile_menu_open_hamburger', 'default' );

		// Enqueue mobile menu icon style
		if ( ! empty( $mobileMenu ) && 'default' != $mobileMenu ) {
			wp_enqueue_style( 'oceanwp-hamburgers' );
			wp_enqueue_style( 'oceanwp-'. $mobileMenu .'' );
		}

		// If Vertical header style
		if ( 'vertical' == oceanwp_header_style() ) {
			wp_enqueue_style( 'oceanwp-hamburgers' );
			wp_enqueue_style( 'oceanwp-spin' );
		}
		wp_enqueue_style( 'modal-style', $dir .'modal_style.css', false, '1.6.0' );
		

	}

	/**
	 * Returns all js needed for the front-end
	 *
	 * @since 1.0.0
	 */
	public static function theme_js() {

		// Get js directory uri
		$dir = OCEANWP_JS_DIR_URI;

		// Get current theme version
		$theme_version = OCEANWP_THEME_VERSION;

		// Get localized array
		$localize_array = self::localize_array();

		// Comment reply
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Add images loaded
		wp_enqueue_script( 'imagesloaded' );

		// Register nicescroll script to use it in some extensions
		wp_register_script( 'nicescroll', $dir .'third/nicescroll.min.js', array( 'jquery' ), $theme_version, true );
		
		
		wp_enqueue_script( 'modal-open', $dir .'modalOpen.js', array( 'jquery' ), $theme_version, true );
		
		wp_enqueue_script( 'main-js', $dir .'main.js', array( 'jquery' ), $theme_version, true );

		// Enqueue nicescroll script if vertical header style
		if ( 'vertical' == oceanwp_header_style() ) {
			wp_enqueue_script( 'nicescroll' );
		}

		// Register Infinite Scroll script
		wp_register_script( 'infinitescroll', $dir .'third/infinitescroll.min.js', array( 'jquery' ), $theme_version, true );

		// WooCommerce scripts
		if ( OCEANWP_WOOCOMMERCE_ACTIVE
			&& 'yes' != get_theme_mod( 'ocean_woo_remove_custom_features', 'no' ) ) {
			wp_enqueue_script( 'oceanwp-woocommerce', $dir .'third/woo/woo-scripts.min.js', array( 'jquery' ), $theme_version, true );
		}

		// Load the lightbox scripts
		wp_enqueue_script( 'magnific-popup', $dir .'third/magnific-popup.min.js', array( 'jquery' ), $theme_version, true );
		wp_enqueue_script( 'oceanwp-lightbox', $dir .'third/lightbox.min.js', array( 'jquery' ), $theme_version, true );

		// Load minified js
		wp_enqueue_script( 'oceanwp-main', $dir .'main.min.js', array( 'jquery' ), $theme_version, true );
		
		// Localize array
		wp_localize_script( 'oceanwp-main', 'oceanwpLocalize', $localize_array );
		

	}

	/**
	 * Functions.js localize array
	 *
	 * @since 1.0.0
	 */
	public static function localize_array() {

		// Create array
		$sidr_side 		= get_theme_mod( 'ocean_mobile_menu_sidr_direction', 'left' );
		$sidr_side 		= $sidr_side ? $sidr_side : 'left';
		$sidr_target 	= get_theme_mod( 'ocean_mobile_menu_sidr_dropdown_target', 'link' );
		$sidr_target 	= $sidr_target ? $sidr_target : 'link';
		$vh_target 		= get_theme_mod( 'ocean_vertical_header_dropdown_target', 'link' );
		$vh_target 		= $vh_target ? $vh_target : 'link';
		$array = array(
			'isRTL'                 => is_rtl(),
			'menuSearchStyle'       => oceanwp_menu_search_style(),
			'sidrSource'       		=> oceanwp_sidr_menu_source(),
			'sidrDisplace'       	=> get_theme_mod( 'ocean_mobile_menu_sidr_displace', true ) ? true : false,
			'sidrSide'       		=> $sidr_side,
			'sidrDropdownTarget'    => $sidr_target,
			'verticalHeaderTarget'  => $vh_target,
			'customSelects'         => '.woocommerce-ordering .orderby, #dropdown_product_cat, .widget_categories select, .widget_archive select, .single-product .variations_form .variations select',
		);

		// WooCart
		if ( OCEANWP_WOOCOMMERCE_ACTIVE ) {
			$array['wooCartStyle'] 	= oceanwp_menu_cart_style();
		}

		// Apply filters and return array
		return apply_filters( 'ocean_localize_array', $array );
	}

	/**
	 * Add headers for IE to override IE's Compatibility View Settings
	 *
	 * @since 1.0.0
	 */
	public static function x_ua_compatible_headers( $headers ) {
		$headers['X-UA-Compatible'] = 'IE=edge';
		return $headers;
	}

	/**
	 * Load HTML5 dependencies for IE8
	 *
	 * @since 1.0.0
	 */
	public static function html5_shiv() {
		wp_register_script( 'html5shiv', OCEANWP_JS_DIR_URI . 'third/html5.min.js', array(), OCEANWP_THEME_VERSION, false );
		wp_enqueue_script( 'html5shiv' );
		wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
	}

	/**
	 * Registers sidebars
	 *
	 * @since   1.0.0
	 */
	public static function register_sidebars() {

		$heading = 'h4';
		$heading = apply_filters( 'ocean_sidebar_heading', $heading );

		// Default Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Default Sidebar', 'oceanwp' ),
			'id'			=> 'sidebar',
			'description'	=> esc_html__( 'Widgets in this area will be displayed in the left or right sidebar area if you choose the Left or Right Sidebar layout.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Left Sidebar
		register_sidebar( array(
			'name'			=> esc_html__( 'Left Sidebar', 'oceanwp' ),
			'id'			=> 'sidebar-2',
			'description'	=> esc_html__( 'Widgets in this area are used in the left sidebar region if you use the Both Sidebars layout.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="sidebar-box %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Search Results Sidebar
		if ( get_theme_mod( 'ocean_search_custom_sidebar', true ) ) {
			register_sidebar( array(
				'name'			=> esc_html__( 'Search Results Sidebar', 'oceanwp' ),
				'id'			=> 'search_sidebar',
				'description'	=> esc_html__( 'Widgets in this area are used in the search result page.', 'oceanwp' ),
				'before_widget'	=> '<div id="%1$s" class="sidebar-box %2$s clr">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<'. $heading .' class="widget-title">',
				'after_title'	=> '</'. $heading .'>',
			) );
		}

		// Footer 1
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 1', 'oceanwp' ),
			'id'			=> 'footer-one',
			'description'	=> esc_html__( 'Widgets in this area are used in the first footer region.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Footer 2
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 2', 'oceanwp' ),
			'id'			=> 'footer-two',
			'description'	=> esc_html__( 'Widgets in this area are used in the second footer region.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Footer 3
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 3', 'oceanwp' ),
			'id'			=> 'footer-three',
			'description'	=> esc_html__( 'Widgets in this area are used in the third footer region.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

		// Footer 4
		register_sidebar( array(
			'name'			=> esc_html__( 'Footer 4', 'oceanwp' ),
			'id'			=> 'footer-four',
			'description'	=> esc_html__( 'Widgets in this area are used in the fourth footer region.', 'oceanwp' ),
			'before_widget'	=> '<div id="%1$s" class="footer-widget %2$s clr">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<'. $heading .' class="widget-title">',
			'after_title'	=> '</'. $heading .'>',
		) );

	}

	/**
	 * Registers theme_mod strings into Polylang.
	 *
	 * @since 1.1.4
	 */
	public static function polylang_register_string() {

		if ( function_exists( 'pll_register_string' ) && $strings = oceanwp_register_tm_strings() ) {
			foreach( $strings as $string => $default ) {
				pll_register_string( $string, get_theme_mod( $string, $default ), 'Theme Mod', true );
			}
		}

	}

	/**
	 * All theme functions hook into the oceanwp_head_css filter for this function.
	 *
	 * @since 1.0.0
	 */
	public static function custom_css( $output = NULL ) {

	    // Add filter for adding custom css via other functions
		$output = apply_filters( 'ocean_head_css', $output );

		// If Custom File is selected
		if ( 'file' == get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {

			global $wp_customize;
			$upload_dir = wp_upload_dir();

			// Render CSS in the head
			if ( isset( $wp_customize ) || ! file_exists( $upload_dir['basedir'] .'/oceanwp/custom-style.css' ) ) {

				 // Minify and output CSS in the wp_head
				if ( ! empty( $output ) ) {
					echo "<!-- OceanWP CSS -->\n<style type=\"text/css\">\n" . wp_strip_all_tags( oceanwp_minify_css( $output ) ) . "\n</style>";
				}
			}

		} else {

			// Minify and output CSS in the wp_head
			if ( ! empty( $output ) ) {
				echo "<!-- OceanWP CSS -->\n<style type=\"text/css\">\n" . wp_strip_all_tags( oceanwp_minify_css( $output ) ) . "\n</style>";
			}

		}

	}


	/**
	 * Minify the WP custom CSS because WordPress doesn't do it by default.
	 *
	 * @since 1.1.9
	 */
	public static function minify_custom_css( $css ) {

		return oceanwp_minify_css( $css );

	}

	/**
	 * Save Customizer CSS in a file
	 *
	 * @since 1.4.12
	 */
	public static function save_customizer_css_in_file( $output = NULL ) {

		// If Custom File is not selected
		if ( 'file' != get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {
			return;
		}

		// Get all the customier css
		$output = apply_filters( 'ocean_head_css', $output );

	    // Get Custom Panel CSS
		$output_custom_css = wp_get_custom_css();

	    // Minified the Custom CSS
		$output .= oceanwp_minify_css( $output_custom_css );

		// We will probably need to load this file
		require_once( ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php' );
		
		global $wp_filesystem;
		$upload_dir = wp_upload_dir(); // Grab uploads folder array
		$dir = trailingslashit( $upload_dir['basedir'] ) . 'oceanwp'. DIRECTORY_SEPARATOR; // Set storage directory path

		WP_Filesystem(); // Initial WP file system
		$wp_filesystem->mkdir( $dir ); // Make a new folder 'oceanwp' for storing our file if not created already.
		$wp_filesystem->put_contents( $dir . 'custom-style.css', $output, 0644 ); // Store in the file.

	}

	/**
	 * Include Custom CSS file if present.
	 *
	 * @since 1.4.12
	 */
	public static function custom_style_css( $output = NULL ) {

		// If Custom File is not selected
		if ( 'file' != get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {
			return;
		}

		global $wp_customize;
		$upload_dir = wp_upload_dir();

		// Get all the customier css
		$output = apply_filters( 'ocean_head_css', $output );

	    // Get Custom Panel CSS
		$output_custom_css = wp_get_custom_css();

	    // Minified the Custom CSS
		$output .= oceanwp_minify_css( $output_custom_css );

		// Render CSS from the custom file
		if ( ! isset( $wp_customize ) && file_exists( $upload_dir['basedir'] .'/oceanwp/custom-style.css' ) && ! empty( $output ) ) { 
			wp_enqueue_style( 'oceanwp-custom', trailingslashit( $upload_dir['baseurl'] ) . 'oceanwp/custom-style.css', false, null );	    			
		}		
		
		
	}

	/**
	 * Remove Customizer style script from front-end
	 *
	 * @since 1.4.12
	 */
	public static function remove_customizer_custom_css() {

		// If Custom File is not selected
		if ( 'file' != get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {
			return;
		}
		
		global $wp_customize;

		// Disable Custom CSS in the frontend head
		remove_action( 'wp_head', 'wp_custom_css_cb', 11 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 101 );

		// If custom CSS file exists and NOT in customizer screen
		if ( isset( $wp_customize ) ) {
			add_action( 'wp_footer', 'wp_custom_css_cb', 9999 );
		}
	}

	/**
	 * Adds inline CSS for the admin
	 *
	 * @since 1.0.0
	 */
	public static function admin_inline_css() {
		echo '<style>div#setting-error-tgmpa{display:block;}</style>';
	}


	/**
	 * Alter the search posts per page
	 *
	 * @since 1.3.7
	 */
	public static function search_posts_per_page( $query ) {
		$posts_per_page = get_theme_mod( 'ocean_search_post_per_page', '8' );
		$posts_per_page = $posts_per_page ? $posts_per_page : '8';

		if ( $query->is_main_query() && is_search() ) {
			$query->set( 'posts_per_page', $posts_per_page );
		}
	}

	/**
	 * Alter wp list categories arguments.
	 * Adds a span around the counter for easier styling.
	 *
	 * @since 1.0.0
	 */
	public static function wp_list_categories_args( $links ) {
		$links = str_replace( '</a> (', '</a> <span class="cat-count-span">(', $links );
		$links = str_replace( ' )', ' )</span>', $links );
		return $links;
	}

	/**
	 * Alters the default oembed output.
	 * Adds special classes for responsive oembeds via CSS.
	 *
	 * @since 1.0.0
	 */
	
	
	
	public static function add_responsive_wrap_to_oembeds( $cache, $url, $attr, $post_ID ) {

		// Supported video embeds
		$hosts = apply_filters( 'ocean_oembed_responsive_hosts', array(
			'vimeo.com',
			'youtube.com',
			'blip.tv',
			'money.cnn.com',
			'dailymotion.com',
			'flickr.com',
			'hulu.com',
			'kickstarter.com',
			'vine.co',
			'soundcloud.com',
			'#http://((m|www)\.)?youtube\.com/watch.*#i',
			'#https://((m|www)\.)?youtube\.com/watch.*#i',
			'#http://((m|www)\.)?youtube\.com/playlist.*#i',
			'#https://((m|www)\.)?youtube\.com/playlist.*#i',
			'#http://youtu\.be/.*#i',
			'#https://youtu\.be/.*#i',
			'#https?://(.+\.)?vimeo\.com/.*#i',
			'#https?://(www\.)?dailymotion\.com/.*#i',
			'#https?://dai\.ly/*#i',
			'#https?://(www\.)?hulu\.com/watch/.*#i',
			'#https?://wordpress\.tv/.*#i',
			'#https?://(www\.)?funnyordie\.com/videos/.*#i',
			'#https?://vine\.co/v/.*#i',
			'#https?://(www\.)?collegehumor\.com/video/.*#i',
			'#https?://(www\.|embed\.)?ted\.com/talks/.*#i'
		) );

		// Supports responsive
		$supports_responsive = false;

		// Check if responsive wrap should be added
		foreach( $hosts as $host ) {
			if ( strpos( $url, $host ) !== false ) {
				$supports_responsive = true;
				break; // no need to loop further
			}
		}

		// Output code
		if ( $supports_responsive ) {
			return '<p class="responsive-video-wrap clr">' . $cache . '</p>';
		} else {
			return '<div class="oceanwp-oembed-wrap clr">' . $cache . '</div>';
		}

	}

	/**
	 * Adds extra classes to the post_class() output
	 *
	 * @since 1.0.0
	 */
	public static function post_class( $classes ) {

		// Get post
		global $post;

		// Add entry class
		$classes[] = 'entry';

		// Add has media class
		if ( has_post_thumbnail()
			|| get_post_meta( $post->ID, 'ocean_post_oembed', true )
			|| get_post_meta( $post->ID, 'ocean_post_self_hosted_media', true )
			|| get_post_meta( $post->ID, 'ocean_post_video_embed', true )
		) {
			$classes[] = 'has-media';
	}

		// Return classes
	return $classes;

}

	/**
	 * Add schema markup to the authors post link
	 *
	 * @since 1.0.0
	 */
	public static function the_author_posts_link( $link ) {

		// Add schema markup
		$schema = oceanwp_get_schema_markup( 'author_link' );
		if ( $schema ) {
			$link = str_replace( 'rel="author"', 'rel="author" '. $schema, $link );
		}

		// Return link
		return $link;

	}

	/**
	 * Add support for Elementor Pro locations
	 *
	 * @since 1.5.6
	 */
	public static function register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_all_core_location();
	}

	/**
	 * Add schema markup to the authors post link
	 *
	 * @since 1.1.5
	 */
	public static function remove_bb_lightbox() {
		return true;
	}

}

#--------------------------------------------------------------------------------
#region Freemius - This logic will only be executed when Ocean Extra is active and has the Freemius SDK
#--------------------------------------------------------------------------------

if ( ! function_exists( 'owp_fs' ) ) {
	if ( class_exists( 'Ocean_Extra' ) &&
		defined( 'OE_FILE_PATH' ) &&
		file_exists( dirname( OE_FILE_PATH ) . '/includes/freemius/start.php' )
	) {
        // Create a helper function for easy SDK access.
		function owp_fs() {
			global $owp_fs;

			if ( ! isset( $owp_fs ) ) {
                // Include Freemius SDK.
				require_once dirname( OE_FILE_PATH ) . '/includes/freemius/start.php';

				$owp_fs = fs_dynamic_init( array(
					'id'                => '3752',
					'bundle_id'         => '3767',
					'slug'              => 'oceanwp',
					'type'              => 'theme',
					'public_key'        => 'pk_043077b34f20f5e11334af3c12493',
					'bundle_public_key' => 'pk_c334eb1ae413deac41e30bf00b9dc',
					'is_premium'        => false,
					'has_addons'        => true,
					'has_paid_plans'    => true,
					'menu'              => array(
						'slug'    => 'oceanwp-panel',
						'account' => true,
						'contact' => false,
						'support' => false,
					),
					'navigation'        => 'menu',
					'is_org_compliant'  => true,
				) );
			}

			return $owp_fs;
		}

        // Init Freemius.
		owp_fs();
        // Signal that SDK was initiated.
		do_action( 'owp_fs_loaded' );
	}
}

#endregion

new OCEANWP_Theme_Class;



function the_breadcrumb( $sep = '', $l10n = array(), $args = array() ){
	$kb = new Kama_Breadcrumbs;
	$svg_arrow = '<svg viewBox="0 0 13 9" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0.70909 3.71209C1.1007 3.71209 10.579 3.71209 10.579 3.71209L8.24406 1.37711C7.96714 1.10023 7.96714 0.651237 8.24406 0.374313C8.52094 0.0974375 8.96994 0.0974375 9.24686 0.374313L12.7923 3.91976C13.0692 4.19664 13.0692 4.64563 12.7923 4.92256L9.24686 8.468C9.10835 8.60647 8.92692 8.67572 8.74544 8.67572C8.56396 8.67572 8.38248 8.60647 8.24406 8.468C7.96714 8.19113 7.96714 7.74213 8.24406 7.46521L10.579 5.13027H0.70909C0.317483 5.13027 0 4.81279 0 4.42118C0 4.02958 0.317483 3.71209 0.70909 3.71209Z"/></svg>';
	if ($sep == '') {
		$sep = $svg_arrow;
	}
	echo $kb->get_crumbs( $sep, $l10n, $args );
}

class Kama_Breadcrumbs {

    public $arg;
	// Локализация
	static $l10n = array(
		'home'       => 'Главная',
		'paged'      => '{:ru}Страница %d{:}{:en}Page %d{:}{:de}Seite %d{:}{:fr}La page %d{:}{:it}Pagina %d{:}{:es}Pagina %d{:}',
		'_404'       => 'Ошибка 404',
		'search'     => 'Результаты поиска по запросу - <b>%s</b>',
		'author'     => 'Архив автора: <b>%s</b>',
		'year'       => 'Архив за <b>%d</b> год',
		'month'      => 'Архив за: <b>%s</b>',
		'day'        => '',
		'attachment' => 'Медиа: %s',
		'tag'        => 'Записи по метке: <b>%s</b>',
		'tax_tag'    => '%1$s из "%2$s" по тегу: <b>%3$s</b>',
		// tax_tag выведет: 'тип_записи из "название_таксы" по тегу: имя_термина'.
		// Если нужны отдельные холдеры, например только имя термина, пишем так: 'записи по тегу: %3$s'
	);

	// Параметры по умолчанию
	static $args = array(
		'on_front_page'   => true,  // выводить крошки на главной странице
		'show_post_title' => true,  // показывать ли название записи в конце (последний элемент). Для записей, страниц, вложений
		'show_term_title' => true,  // показывать ли название элемента таксономии в конце (последний элемент). Для меток, рубрик и других такс
		'title_patt'      => '<span class="kb_title">%s</span>', // шаблон для последнего заголовка. Если включено: show_post_title или show_term_title
		'last_sep'        => true,  // показывать последний разделитель, когда заголовок в конце не отображается
		'markup'          => 'schema.org', // 'markup' - микроразметка. Может быть: 'rdf.data-vocabulary.org', 'schema.org', '' - без микроразметки
										   // или можно указать свой массив разметки:
										   // array( 'wrappatt'=>'<div class="kama_breadcrumbs">%s</div>', 'linkpatt'=>'<a href="%s">%s</a>', 'sep_after'=>'', )
		'priority_tax'    => array('category'), // приоритетные таксономии, нужно когда запись в нескольких таксах
		'priority_terms'  => array(), // 'priority_terms' - приоритетные элементы таксономий, когда запись находится в нескольких элементах одной таксы одновременно.
									  // Например: array( 'category'=>array(45,'term_name'), 'tax_name'=>array(1,2,'name') )
									  // 'category' - такса для которой указываются приор. элементы: 45 - ID термина и 'term_name' - ярлык.
									  // порядок 45 и 'term_name' имеет значение: чем раньше тем важнее. Все указанные термины важнее неуказанных...
		'nofollow' => false, // добавлять rel=nofollow к ссылкам?

		// служебные
		'sep'             => '',
		'linkpatt'        => '',
		'pg_end'          => '',
	);
	
	public function __construct() {
		self::$l10n['home'] = $this->shortNameSite();
	}
	
	function shortNameSite(){
		
		if(!empty(get_option('short_name_site'))) return get_option('short_name_site');
		
		return 'Главная';
	}
	
	function get_crumbs( $sep, $l10n, $args ){
        global $post, $wp_query, $wp_post_types;

		self::$args['sep'] = $sep;

		// Фильтрует дефолты и сливает
		$loc = (object) array_merge( apply_filters('kama_breadcrumbs_default_loc', self::$l10n ), $l10n );
		$arg = (object) array_merge( apply_filters('kama_breadcrumbs_default_args', self::$args ), $args );

		$arg->sep = '<em class="kb_sep">'. $arg->sep .'</em>'; // дополним

		// упростим
		$sep = & $arg->sep;
		$this->arg = & $arg;

		// микроразметка ---
		if(1){
			$mark = & $arg->markup;

			// Разметка по умолчанию
			if( ! $mark ) $mark = array(
				'wrappatt'  => '<div class="kama_breadcrumbs">%s</div>',
				'linkpatt'  => '<a href="%s">%s</a>',
				'sep_after' => '',
			);
			// rdf
			elseif( $mark === 'rdf.data-vocabulary.org' ) $mark = array(
				'wrappatt'   => '<div class="kama_breadcrumbs" prefix="v: http://rdf.data-vocabulary.org/#">%s</div>',
				'linkpatt'   => '<span typeof="v:Breadcrumb"><a href="%s" rel="v:url" property="v:title">%s</a>',
				'sep_after'  => '</span>', // закрываем span после разделителя!
			);
			// schema.org
			elseif( $mark === 'schema.org' ) $mark = array(
				'wrappatt'   => '<div class="kama_breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">%s</div>',
				'linkpatt'   => '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="%s" itemprop="item"><span itemprop="name">%s</span></a><meta itemprop="position" content="%s"></span>',
				'sep_after'  => '',
			);

			elseif( ! is_array($mark) )
				die( __CLASS__ .': "markup" parameter must be array...');

			$wrappatt  = $mark['wrappatt'];
			$arg->linkpatt  = $arg->nofollow ? str_replace('<a ','<a rel="nofollow"', $mark['linkpatt']) : $mark['linkpatt'];
			$arg->sep      .= $mark['sep_after']."\n";
		}

		$linkpatt = $arg->linkpatt; // упростим

		$q_obj = get_queried_object();

		// может это архив пустой таксы?
		$ptype = null;
		if( empty($post) ){
			if( isset($q_obj->taxonomy) )
				$ptype = & $wp_post_types[ get_taxonomy($q_obj->taxonomy)->object_type[0] ];
		}
		else $ptype = & $wp_post_types[ $post->post_type ];

		// paged
		$arg->pg_end = '';
		if( ($paged_num = get_query_var('paged')) || ($paged_num = get_query_var('page')) )
			$arg->pg_end = $sep . sprintf(WPGlobus_Core::text_filter($loc->paged, WPGlobus::Config()->language), (int) $paged_num );

		$pg_end = $arg->pg_end; // упростим

		$out = '';

		if( is_front_page() ){
			return $arg->on_front_page ? sprintf( $wrappatt, ( $paged_num ? sprintf($linkpatt, get_home_url(), $loc->home) . $pg_end : $loc->home ) ) : '';
		}
		// страница записей, когда для главной установлена отдельная страница.
		elseif( is_home() ) {
			$out = $paged_num ? ( sprintf( $linkpatt, get_permalink($q_obj), esc_html($q_obj->post_title) ) . $pg_end ) : esc_html($q_obj->post_title);
		}
		elseif( is_404() ){
			$out = $loc->_404;
		}
		elseif( is_search() ){
			$out = sprintf( $loc->search, esc_html( $GLOBALS['s'] ) );
		}
		elseif( is_author() ){
			$tit = sprintf( $loc->author, esc_html($q_obj->display_name) );
			$out = ( $paged_num ? sprintf( $linkpatt, get_author_posts_url( $q_obj->ID, $q_obj->user_nicename ) . $pg_end, $tit ) : $tit );
		}
		elseif( is_year() || is_month() || is_day() ){
			$y_url  = get_year_link( $year = get_the_time('Y') );

			if( is_year() ){
				$tit = sprintf( $loc->year, $year );
				$out = ( $paged_num ? sprintf($linkpatt, $y_url, $tit) . $pg_end : $tit );
			}
			// month day
			else {
				$y_link = sprintf( $linkpatt, $y_url, $year);
				$m_url  = get_month_link( $year, get_the_time('m') );

				if( is_month() ){
					$tit = sprintf( $loc->month, get_the_time('F') );
					$out = $y_link . $sep . ( $paged_num ? sprintf( $linkpatt, $m_url, $tit ) . $pg_end : $tit );
				}
				elseif( is_day() ){
					$m_link = sprintf( $linkpatt, $m_url, get_the_time('F'));
					$out = $y_link . $sep . $m_link . $sep . get_the_time('l');
				}
			}
		}
		// Древовидные записи
		elseif( is_singular() && $ptype->hierarchical ){
			$out = $this->_add_title( $this->_page_crumbs($post), $post );
		}
		// Таксы, плоские записи и вложения
		else {
			$term = $q_obj; // таксономии

			// определяем термин для записей (включая вложения attachments)
			if( is_singular() ){
				// изменим $post, чтобы определить термин родителя вложения
				if( is_attachment() && $post->post_parent ){
					$save_post = $post; // сохраним
					$post = get_post($post->post_parent);
				}

				// учитывает если вложения прикрепляются к таксам древовидным - все бывает :)
				$taxonomies = get_object_taxonomies( $post->post_type );
				// оставим только древовидные и публичные, мало ли...
				$taxonomies = array_intersect( $taxonomies, get_taxonomies( array('hierarchical' => true, 'public' => true) ) );

				if( $taxonomies ){
					// сортируем по приоритету
					if( ! empty($arg->priority_tax) ){
						usort( $taxonomies, function($a,$b)use($arg){
							$a_index = array_search($a, $arg->priority_tax);
							if( $a_index === false ) $a_index = 9999999;

							$b_index = array_search($b, $arg->priority_tax);
							if( $b_index === false ) $b_index = 9999999;

							return ( $b_index === $a_index ) ? 0 : ( $b_index < $a_index ? 1 : -1 ); // меньше индекс - выше
						} );
					}

					// пробуем получить термины, в порядке приоритета такс
					foreach( $taxonomies as $taxname ){
						if( $terms = get_the_terms( $post->ID, $taxname ) ){
							// проверим приоритетные термины для таксы
							$prior_terms = & $arg->priority_terms[ $taxname ];
							if( $prior_terms && count($terms) > 2 ){
								foreach( (array) $prior_terms as $term_id ){
									$filter_field = is_numeric($term_id) ? 'term_id' : 'slug';
									$_terms = wp_list_filter( $terms, array($filter_field=>$term_id) );

									if( $_terms ){
										$term = array_shift( $_terms );
										break;
									}
								}
							}
							else
								$term = array_shift( $terms );

							break;
						}
					}
				}

				if( isset($save_post) ) $post = $save_post; // вернем обратно (для вложений)
			}

			// вывод

			// все виды записей с терминами или термины
			if( $term && isset($term->term_id) ){
				$term = apply_filters('kama_breadcrumbs_term', $term );

				// attachment
				if( is_attachment() ){
					if( ! $post->post_parent )
						$out = sprintf( $loc->attachment, esc_html($post->post_title) );
					else {
						if( ! $out = apply_filters('attachment_tax_crumbs', '', $term, $this ) ){
							$_crumbs    = $this->_tax_crumbs( $term, 'self' );
							$parent_tit = sprintf( $linkpatt, get_permalink($post->post_parent), get_the_title($post->post_parent) );
							$_out = implode( $sep, array($_crumbs, $parent_tit) );
							$out = $this->_add_title( $_out, $post );
						}
					}
				}
				// single
				elseif( is_single() ){
					if( ! $out = apply_filters('post_tax_crumbs', '', $term, $this ) ){
						$_crumbs = $this->_tax_crumbs( $term, 'self' );
					    $out = $this->_add_title( $_crumbs, $post );
					}
				}
				// не древовидная такса (метки)
				elseif( ! is_taxonomy_hierarchical($term->taxonomy) ){
					// метка
					if( is_tag() )
						$out = $this->_add_title('', $term, sprintf( $loc->tag, esc_html($term->name) ) );
					// такса
					elseif( is_tax() ){
						$post_label = $ptype->labels->name;
						$tax_label = $GLOBALS['wp_taxonomies'][ $term->taxonomy ]->labels->name;
						$out = $this->_add_title('', $term, sprintf( $loc->tax_tag, $post_label, $tax_label, esc_html($term->name) ) );
					}
				}
				// древовидная такса (рибрики)
				else {
					if( ! $out = apply_filters('term_tax_crumbs', '', $term, $this ) ){
                        $_crumbs = $this->_tax_crumbs( $term, 'parent' );
						$out = $this->_add_title( $_crumbs, $term, esc_html($term->name) );                     
					}
				}
			}
			// влоежния от записи без терминов
			elseif( is_attachment() ){
				$parent = get_post($post->post_parent);
				$parent_link = sprintf( $linkpatt, get_permalink($parent), esc_html($parent->post_title) );
				$_out = $parent_link;

				// вложение от записи древовидного типа записи
				if( is_post_type_hierarchical($parent->post_type) ){
					$parent_crumbs = $this->_page_crumbs($parent);
					$_out = implode( $sep, array( $parent_crumbs, $parent_link ) );
				}

				$out = $this->_add_title( $_out, $post );
			}
			// записи без терминов
			elseif( is_singular() ){
				$out = $this->_add_title( '', $post );
			}
		}

		// замена ссылки на архивную страницу для типа записи
		$home_after = apply_filters('kama_breadcrumbs_home_after', '', $linkpatt, $sep, $ptype );

		if( '' === $home_after ){
			// Ссылка на архивную страницу типа записи для: отдельных страниц этого типа; архивов этого типа; таксономий связанных с этим типом.
			if( $ptype && $ptype->has_archive && ! in_array( $ptype->name, array('post','page','attachment') )
				&& ( is_post_type_archive() || is_singular() || (is_tax() && in_array($term->taxonomy, $ptype->taxonomies)) )
			){
				$pt_title = $ptype->labels->name;

				// первая страница архива типа записи
				if( is_post_type_archive() && ! $paged_num )
					$home_after = sprintf( $this->arg->title_patt, $pt_title );
				// singular, paged post_type_archive, tax
				else{
					$home_after = sprintf( $linkpatt, get_post_type_archive_link($ptype->name), $pt_title, 1 );

					$home_after .= ( ($paged_num && ! is_tax()) ? $pg_end : $sep ); // пагинация
				}
			}
		}

		$before_out = sprintf( $linkpatt, home_url(), $loc->home, 1 ) . ( $home_after ? $sep.$home_after : ($out ? $sep : '') );

		$out = apply_filters('kama_breadcrumbs_pre_out', $out, $sep, $loc, $arg );

        $out = sprintf( $wrappatt, $before_out . $out );

		return apply_filters('kama_breadcrumbs', $out, $sep, $loc, $arg );
	}

	function _page_crumbs( $post ){
		$parent = $post->post_parent;

		$crumbs = array();
		while( $parent ){
			$page = get_post( $parent );
			$crumbs[] = sprintf( $this->arg->linkpatt, get_permalink($page), esc_html($page->post_title) );
			$parent = $page->post_parent;
		}

		return implode( $this->arg->sep, array_reverse($crumbs) );
	}

	function _tax_crumbs($term, $start_from = 'self')
    {
        $termlinks = array();
        $GLOBALS['position'] = 1;
        $term_id = ($start_from === 'parent') ? $term->parent : $term->term_id;
        while ($term_id) {
            $GLOBALS['position']++;
            $term = get_term($term_id, $term->taxonomy);
            $termlinks[] = sprintf($this->arg->linkpatt, get_term_link($term), esc_html($term->name), $GLOBALS['position']);
            $term_id = $term->parent;

        }

        if ($termlinks)
            return implode($this->arg->sep, array_reverse($termlinks)) /*. $this->arg->sep*/ ;
        return '';
    }

	// добалвяет заголовок к переданному тексту, с учетом всех опций. Добавляет разделитель в начало, если надо.
	function _add_title( $add_to, $obj, $term_title = '' ){
		$arg = & $this->arg; // упростим...
		$title = $term_title ? $term_title : esc_html($obj->post_title); // $term_title чиститься отдельно, теги моугт быть...
		$show_title = $term_title ? $arg->show_term_title : $arg->show_post_title;

		// пагинация
		if( $arg->pg_end ){
            $link = $term_title ? get_term_link($obj) : get_permalink($obj);
			$add_to .= ($add_to ? $arg->sep : '') . sprintf( $arg->linkpatt, $link, $title, ++$GLOBALS['position'] ) . $arg->pg_end;
		}
		// дополняем - ставим sep
		elseif( $add_to ){
			if( $show_title )
				$add_to .= $arg->sep . sprintf( $arg->title_patt, $title );
			elseif( $arg->last_sep )
				$add_to .= $arg->sep;
		}
		// sep будет потом...
		elseif( $show_title )
			$add_to = sprintf( $arg->title_patt, $title );

		return $add_to;
	}

}

// function the_breadcrumb(){
	
	// $separator = ' &raquo; '; //  »
	// $short_name_site = get_option('short_name_site');
	
	// $tpl = '<ul itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs"><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . site_url() . '"><span itemprop="name">';
	
	// $tpl .= empty($short_name_site) ? "Главная" : trim($short_name_site);
	
	// $tpl .= '</span></a></li>';
	
	// echo $tpl;


	// if( is_single() ){ 

		// if( in_category([19, 20]) ){
			// echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="/dokumentaciya"><span itemprop="name">Документация</span></a></li>';
		// } else {
			// echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">'; the_category('</li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">', 'multiple');
		// }

		// echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">'; the_title(); echo '</span></li>';

	// } elseif ( is_page() ){ 

		// echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">'; the_title(); echo '</span></li>';

	// } elseif ( is_category() ) {

		// echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">'; single_cat_title(); echo '</span></li>';

	// } elseif( is_tag() ) {

		// echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">'; single_tag_title(); echo '</span></li>';

	// }  elseif ( is_404() ) {

		// echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">'; echo 'Ошибка 404'; echo '</span></li>';
	// }

	// echo '</ul>';
// }

add_action( 'template_redirect', 'remove_template_redirect', 10, 2);

function remove_template_redirect(){
  ob_start( function( $buffer ){
    $buffer = str_replace( array( 
        '<script type="text/javascript">',
        "<script type='text/javascript'>", 
        "<script type='text/javascript' src=",
        '<script type="text/javascript" src=',
        '<style type="text/css">', 
        "' type='text/css' media=", 
        '<style type="text/css" media',
        "' type='text/css'>"
    ), 
    array(
        '<script>', 
        "<script>", 
        "<script src=",
        '<script src=',
        '<style>', 
        "' media=", 
        '<style media',
        "' >"
    ), $buffer );

    return $buffer;
  });
};

function add_option_field_to_general_admin_page(){
	$option_name = 'short_name_site';

	register_setting( 'general', $option_name );

	add_settings_field( 
		'short_name_site-id', 
		'Краткое название сайта:', 
		'short_name_site_callback_function', 
		'general', 
		'default', 
		array( 
			'id' => 'short_name_site-id', 
			'option_name' => 'short_name_site' 
		)
	);
}
add_action('admin_menu', 'add_option_field_to_general_admin_page');

function short_name_site_callback_function( $val ){
	$id = $val['id'];
	$option_name = $val['option_name'];
	?>
	<input 
		type="text" 
		name="<? echo $option_name ?>" 
		id="<? echo $id ?>" 
		value="<? echo esc_attr( get_option($option_name) ) ?>" 
	/> 
	<p class="description" id="short_name_site-description">Укажите краткое название сайта, которое будет публиковаться в модуле 'speedbar', при публикации ссылки на главную страницу сайта</p>
	<?
}

add_action('wp_footer','microdata_hook');

function microdata_hook()
{

$output='<script type="application/ld+json">
	{
		"@context" : "http://schema.org",
		"@type" : "LocalBusiness",
		"address" : 
		{
			"@type" : "PostalAddress",
			"addressLocality" : "Пенза",
			"streetAddress" : "ул. Захарова, д.1, офис 8"
		},
		"location":
		[
		{
			"@type": "Place",
			"address": 
			{
				"@type" : "PostalAddress",
				"addressLocality" : "Пенза",
				"streetAddress" : "ул. Захарова, д.1, офис 8"
			},
			"geo": 
			{
				"@type": "GeoCoordinates",
				"latitude": "53.1942512",
				"longitude": "44.9999983"
			},
			"name": "Офис"
		},
		{
			"@type": "Place",
			"address": 
			{
				"@type" : "PostalAddress",
				"addressLocality" : "Москва",
				"streetAddress" : "просп. Мира, д. 102, корп. 1, этаж 8, комната 6, офис А2Ж."
			},
			"geo": 
			{
				"@type": "GeoCoordinates",
				"latitude": "55.8007603",
				"longitude": "37.6344671"
			},
			"name": "Офис"
		}
		],
		"identifier":
		[
		{
			"@type": "PropertyValue",
			"propertyID": "ИНН",
			"value":  "7717799421"
		}
		],
		"url" : "https://kran-tali.ru",
		"logo" : "https://kran-tali.ru/wp-content/uploads/2020/08/logo.png",
		"image" : "https://kran-tali.ru/wp-content/uploads/2020/08/img.jpg",
		"email" : "kran-tali@mail.ru",
		"name" : "ООО «Росттехмаш»",
		"telephone" : "+7 (8412) 30-56-63",
		"description" : "Грузоподъемное оборудование (мостовые, козловые, консольные краны), крановые эстакады и быстровозводимые здания от «Росттехмаш». Проектирование, продажа, монтаж, обслуживание по всей России.",
		"priceRange" : "цены зависят от модели и комплектации"
	}
</script>';

echo $output;
}


add_action('wp_footer','modalwrap_hook');

function modalwrap_hook()
{

$output='<div id="callback" class="styleModalFix">
				<div class="jsClose styleFade"></div>
				<div class="styleModalWrap">
					<div class="jsModalIn styleModalIn">
						<div class="styleModalPage jsModalPage">
							<div class="jsClose styleClose"></div>
							<div class="styleModalHead styleApp">Закажите звонок</div>
							<div class="styleModalBody">
								'.do_shortcode('[contact-form-7 id="1531"]').'
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div id="message" class="styleModalFix">
				<div class="jsClose styleFade"></div>
				<div class="styleModalWrap">
					<div class="jsModalIn styleModalIn">
						<div class="styleModalPage jsModalPage">
							<div class="jsClose styleClose"></div>
							<div class="styleModalBody"></div>
						</div>
					</div>
				</div>
			</div>';

echo $output;
}


add_action( 'wp_footer', 'mycustom_wp_footer' );
 
function mycustom_wp_footer() {
?>
<script type="text/javascript">
document.addEventListener( 'wpcf7mailsent', function( event ) {
	var message = event.detail.apiResponse.message;
	closeModal('callback');
	jQuery('#message .styleModalBody').html(message);
	openModal('message');
}, false );

document.addEventListener( 'wpcf7invalid', function( event ) {
	var message = event.detail.apiResponse.message;
	jQuery('#message .styleModalBody').html(message);
	openModal('message');
}, false );

document.addEventListener( 'wpcf7mailfailed', function( event ) {
	var message = event.detail.apiResponse.message;
	jQuery('#message .styleModalBody').html(message);
	openModal('message');
}, false );
</script>
<?php } ?>

<?php 
function is_mobile(){
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|ipad|playbook|silk/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
		return true;
	} else {
		return false; 
	}	  
}
$isMob = is_mobile();



 ?>

