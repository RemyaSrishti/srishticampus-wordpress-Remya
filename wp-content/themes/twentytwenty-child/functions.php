<?php
require_once( dirname( __FILE__ ) . '/inc/shortcodes.php' );

add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles' );

function child_theme_enqueue_styles() {

	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' ); 
	
	wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css',	array() );	
	
	if(is_front_page() ) {
		
		wp_enqueue_style( 'aos', get_stylesheet_directory_uri() . '/css/aos.css',	array() );				
	 
		wp_enqueue_style( 'home-style', get_stylesheet_directory_uri() . '/css/home-style.css',	array() );
	} else {
		
		wp_enqueue_style( 'page-style', get_stylesheet_directory_uri() . '/css/page-style.css',	array() );
		
		wp_enqueue_style( 'owl-carousel', get_stylesheet_directory_uri() . '/css/owl.carousel.min.css',	array() );		
	}
}

add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_scripts' );

function child_theme_enqueue_scripts() {
	wp_enqueue_script( 'jquery_js', get_stylesheet_directory_uri() . '/js/jquery.min.js' );	if(is_front_page() ) {		
		
		wp_enqueue_script( 'jquery_slim_js', get_stylesheet_directory_uri() . '/js/jquery.slim.min.js','','',true );
		
		wp_enqueue_script( 'bootstrap_bundle_js', get_stylesheet_directory_uri() . '/js/bootstrap.bundle.min.js','','',true );
		
		wp_enqueue_script( 'aos_js', get_stylesheet_directory_uri() . '/js/aos.js','','',true );
		
		wp_enqueue_script( 'myjs_js', get_stylesheet_directory_uri() . '/js/myjs.js','','',true );
	} else{		
	
		wp_enqueue_script( 'bootstarp_js', get_stylesheet_directory_uri() . '/js/bootstrap.min.js' );	
		
		wp_enqueue_script( 'owl_carousel_js', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js' );		
	}	
	wp_enqueue_script( 'fontawesome_js', get_stylesheet_directory_uri() . '/js/fontawesome.js' );
}
function mytheme_custom_excerpt_length( $length ) {
	 if ( is_page('99') ) {
		return 83;
	 }
	 return $length;
}
add_filter( 'excerpt_length', 'mytheme_custom_excerpt_length', 999 );

function wpb_change_search_url() {
    if ( is_search() && ! empty( $_GET['s'] ) ) {
        wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) );
        exit();
    }   
}
add_action( 'template_redirect', 'wpb_change_search_url' );

function mySearchFilter($query) {
	 if ($query->is_search && !is_admin() ) {
		$query->set('post_type', 'aa');		 
	 }
	 return $query;
}
add_filter('pre_get_posts','mySearchFilter');

function admin_style() {
  wp_enqueue_style('admin-styles', get_stylesheet_directory_uri().'/css/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');

