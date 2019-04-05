<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bootstarter4
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'bootstarter4' ); ?></a>

	<header id="masthead" class="site-header">
		<nav class="navbar navbar-expand-sm navbar-dark bg-dark" role="navigation">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( get_bloginfo('name') ); ?>">
					<img src="/wp-content/uploads/2019/01/favicon-96x96.png" width="32" height="32" class="d-inline-block align-middle mr-1" alt="VolgaCTF">
					<?php esc_html_e( get_bloginfo('name') ); ?></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#primary_navigation" aria-controls="primary_navigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <?php
                wp_nav_menu( array(
                    'theme_location'    => 'primary',
                    'depth'             => 2,
                    'container'         => 'div',
                    'container_class'   => 'collapse navbar-collapse',
                    'container_id'      => 'primary_navigation',
                    'menu_class'        => 'nav navbar-nav',
                    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                    'walker'            => new WP_Bootstrap_Navwalker(),
                ) );
                ?>
            </div>
		</nav>

		<?php do_action('wpml_add_language_selector'); ?>

		<?php if ( function_exists( 'bootstarter4_woocommerce_header_cart' ) ) { bootstarter4_woocommerce_header_cart(); } ?>
	</header><!-- #masthead -->

	<div id="content" class="site-content container"><div class="jumbotron mt-4">
