<?php
// Remove Dashboard Menu Items
function custom_menu_page_removing() {
	//remove_menu_page( 'edit.php' ); 			//Posts
	remove_menu_page( 'edit-comments.php' ); 	//Comments
	remove_menu_page( 'tools.php' ); 			//Tools
	if ( !current_user_can('manage_options') ) { // just for editors
		//remove_submenu_page( 'themes.php', 'themes.php' );
		//remove_submenu_page( 'themes.php', 'widgets.php' );
	}
}

// Remove dashboard widgets
function remove_dashboard_widgets() {
    global $wp_meta_boxes;

	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	// bbpress
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
	// yoast seo
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
	// gravity forms
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);

}
if (!current_user_can('manage_options')) {
    add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
}

// customise the login
function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );
function my_login_logo_url_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );
function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/css/style-login.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

// Change label of Media to Media Library
function change_post_menu_label() {
    global $menu;
    $menu[10][0] = 'Media Library';
	return;
}
add_action( 'admin_menu', 'change_post_menu_label' );

// Dashboard Custom Columns
add_filter('manage_edit-page_columns', 'add_template_column' );
function add_template_column( $page_columns ) {
	unset($page_columns['comments']);

	$author = $page_columns['author'];
	unset($page_columns['author']);
	$date = $page_columns['date'];
	unset($page_columns['date']);

	$page_columns['template'] = 'Page Template';
	$page_columns['order'] = 'Order';
	//$page_columns['author'] = $author;
	$page_columns['date'] = $date;

	return $page_columns;
}
add_action('manage_page_posts_custom_column', 'add_template_data' );
function add_template_data( $column_name ) {
	if ( 'template' !== $column_name ) {
		return;
	}
	global $post;

	$template_name = get_page_template_slug( $post->ID );
	$template      = untrailingslashit( get_stylesheet_directory() ) . '/' . $template_name;

	$template_name = ( 0 === strlen( trim( $template_name ) ) || ! file_exists( $template ) ) ?
		'Default' :
		ucwords( str_ireplace( array('-','.php'), array(' ',''), get_file_description( $template ) ) );

	echo esc_html( $template_name );
}
add_action('manage_page_posts_custom_column', 'add_order_data' );
function add_order_data( $column_name ) {
	if ( 'order' !== $column_name ) {
		return;
	}
	global $post;

	$order = $post->menu_order;

	echo esc_html( $order );
}

// add editor extra capabilities
function add_editor_extra_capabilities() {
	// get the the role object
	$role_object = get_role( 'editor' );
	// add $cap capability to this role object if it doesn't have yet
	if( !$role_object->has_cap('edit_theme_options') ){
		// $role_object->add_cap( 'edit_theme_options' );
	}
}
add_action( 'admin_init', 'add_editor_extra_capabilities' );

// add user manager role and remove some defaults
remove_role( 'admin_editor' );
$editor_role_set = get_role( 'editor' )->capabilities;
add_role(
    'admin_editor',
    __( 'Admin', 'bootstarter4' ),
	$editor_role_set
);
$role = get_role( 'admin_editor' );
$role->add_cap( 'is_admin_editor' );
$role->add_cap( 'edit_users' );
$role->add_cap( 'delete_users' );
$role->add_cap( 'create_users' );
$role->add_cap( 'list_users' );
$role->add_cap( 'remove_users' );
$role->add_cap( 'add_users' );
function add_admin_editor_js($hook) {
	if ( current_user_can('is_admin_editor') ) {
        wp_enqueue_script('my_custom_script', get_stylesheet_directory_uri() . '/js/admin_editor.js');
    }
}
add_action('admin_enqueue_scripts', 'add_admin_editor_js');
remove_role( 'subscriber' );
remove_role( 'contributor' );
remove_role( 'author' );
