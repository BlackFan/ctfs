<?php
// Disable default WordPress additional scripts
include(locate_template('inc/disable-default-wordpress-additional-scripts.php'));

// Register Menu and Custom Bootstrap 4 Navigation Walker
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'bootstarter' ),
) );
include(locate_template('inc/class-wp-bootstrap-navwalker.php'));

// Let WordPress manage the document title.
add_theme_support( 'title-tag' );

// Enable support for Post Thumbnails on posts and pages.
add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );

// Switch default core markup for search form, comment form, and comments to output valid HTML5.
add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', ) );

// Custom image sizes
add_action( 'after_setup_theme', 'custom_image_sizes' );
function custom_image_sizes() {
    add_image_size( 'image-1920px-wide', 1920 ); // 1920 pixels wide (and unlimited height)
	// use 'medium_large' for 768 pixels wide
    add_image_size( 'image-384px-wide', 384 ); // 768 / 2 = 384 pixels wide (and unlimited height)
    add_image_size( 'image-192px-wide', 192 ); // 384 / 2 = 192 pixels wide (and unlimited height)
}

// custom excerpt lenght
function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// remove margin-top on the admin bar
add_action('get_header', 'remove_admin_login_header');
function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}

//Add Really Simple Captcha to Contact Form 7
add_filter( 'wpcf7_use_really_simple_captcha', '__return_true' );
