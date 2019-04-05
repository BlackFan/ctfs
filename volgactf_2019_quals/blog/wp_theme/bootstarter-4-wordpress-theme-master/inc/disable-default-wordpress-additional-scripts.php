<?php
// Remove wp-embed.min.js
add_action('init', function(){
    if (!is_admin()) {
		wp_deregister_script('wp-embed');
	}
});

// Remove emoji script and style from the header both Theme and Admin
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// Remove <link rel='dns-prefetch' href='//s.w.org' />
remove_action('wp_head', 'wp_resource_hints', 2);

// Remove <link rel='https://api.w.org/' href='https://www.yourdomain.com/wp-json/' />
remove_action('wp_head', 'rest_output_link_wp_head');

// Remove <link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://www.yourdomain.com/xmlrpc.php?rsd" />
remove_action ('wp_head', 'rsd_link');

// Remove <link rel="wlwmanifest" type="application/wlwmanifest+xml" href="https://www.yourdomain.com/wp-includes/wlwmanifest.xml" />
remove_action('wp_head', 'wlwmanifest_link');

// Remove <meta name="generator" content="WordPress x.y.z" />
remove_action('wp_head', 'wp_generator');

// Remove <link rel='shortlink' href='https://yourdomain.com/' />
remove_action('wp_head', 'wp_shortlink_wp_head');

// Remove Rest API
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('template_redirect', 'rest_output_link_header', 11, 0 );
