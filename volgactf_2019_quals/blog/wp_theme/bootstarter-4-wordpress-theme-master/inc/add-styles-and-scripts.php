<?php
function add_styles_and_scripts() {
    // Bootstrap 4
    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri().'/css/bootstrap.min.css', array(), '4.1.1');
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_stylesheet_directory_uri().'/js/jquery-3.3.1.min.js', array(), '3.3.1', true);
    wp_enqueue_script('bootstrap', get_stylesheet_directory_uri().'/js/bootstrap.bundle.min.js', array('jquery'), '4.1.1', true);

    // FontAwesome
    wp_enqueue_style('fontawesome', get_stylesheet_directory_uri().'/css/fontawesome-all.min.css', array(), '5.0.13');

    // Custom styles and scripts
    wp_enqueue_style('custom', get_stylesheet_directory_uri().'/css/custom.css', array('bootstrap'), time());
    wp_enqueue_script('custom', get_stylesheet_directory_uri().'/js/custom.js', array('bootstrap'), time(), true);
}
add_action( 'wp_enqueue_scripts', 'add_styles_and_scripts' );
