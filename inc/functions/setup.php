<?php
/**
 * fastshop setup functions
 *
 * @package fastshop
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

/**
 * Assign the Fast shop version to a var
 */
$theme 					= wp_get_theme( 'fastshop' );
$fastshop_version 	= $theme['Version'];

if ( ! function_exists( 'fastshop_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function fastshop_setup() {

		/*
		 * Load Localisation files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 */

		// wp-content/languages/themes/fastshop-it_IT.mo
		load_theme_textdomain( 'fastshop', trailingslashit( WP_LANG_DIR ) . 'themes/' );

		// wp-content/themes/child-theme-name/languages/it_IT.mo
		load_theme_textdomain( 'fastshop', get_stylesheet_directory() . '/languages' );

		// wp-content/themes/fastshop/languages/it_IT.mo
		load_theme_textdomain( 'fastshop', get_template_directory() . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to head.
		 */
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'primary'		=> __( 'Primary Menu', 'fastshop' ),
			'secondary'		=> __( 'Secondary Menu', 'fastshop' ),
			'handheld'		=> __( 'Handheld Menu', 'fastshop' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'widgets',
		) );

		// Setup the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'fastshop_custom_background_args', array(
			'default-color' => apply_filters( 'fastshop_default_background_color', 'fcfcfc' ),
			'default-image' => '',
		) ) );

		// Add support for the Site Logo plugin and the site logo functionality in JetPack
		// https://github.com/automattic/site-logo
		// http://jetpack.me/
		add_theme_support( 'site-logo', array( 'size' => 'full' ) );

		// Declare WooCommerce support
		add_theme_support( 'woocommerce' );

		// Declare support for title theme feature
		add_theme_support( 'title-tag' );
	}
endif; // fastshop_setup

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function fastshop_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'fastshop' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Below Header', 'fastshop' ),
		'id'            => 'header-1',
		'description'   => 'Widgets added to this region will appear beneath the header and above the main content.',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	$footer_widget_regions = apply_filters( 'fastshop_footer_widget_regions', 4 );

	for ( $i = 1; $i <= intval( $footer_widget_regions ); $i++ ) {
		register_sidebar( array(
			'name' 				=> sprintf( __( 'Footer %d', 'fastshop' ), $i ),
			'id' 				=> sprintf( 'footer-%d', $i ),
			'description' 		=> sprintf( __( 'Widgetized Footer Region %d.', 'fastshop' ), $i ),
			'before_widget' 	=> '<aside id="%1$s" class="widget %2$s">',
			'after_widget' 		=> '</aside>',
			'before_title' 		=> '<h3>',
			'after_title' 		=> '</h3>',
			)
		);
	}
}

/**
 * Enqueue scripts and styles.
 * @since  1.0.0
 */
function fastshop_scripts() {
	global $fastshop_version;

	wp_enqueue_style( 'fastshop-style', get_template_directory_uri() . '/style.css', '', $fastshop_version );

	wp_style_add_data( 'fastshop-style', 'rtl', 'replace' );

	wp_enqueue_script( 'fastshop-angular', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js', '1.5', true );

	wp_enqueue_script( 'fastshop-ng-app', get_template_directory_uri() . '/js/ng-app.js', array( 'fastshop-angular' ), '1.0.0', true );

	wp_enqueue_script( 'fastshop-navigation', get_template_directory_uri() . '/js/navigation.min.js', array( 'jquery' ), '20120206', true );

	wp_enqueue_script( 'fastshop-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.min.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

/**
 * Enqueue child theme stylesheet.
 * A separate function is required as the child theme css needs to be enqueued _after_ the parent theme
 * primary css and the separate WooCommerce css.
 * @since  1.5.3
 */
function fastshop_child_scripts() {
	if ( is_child_theme() ) {
		wp_enqueue_style( 'fastshop-child-style', get_stylesheet_uri(), '' );
	}
}