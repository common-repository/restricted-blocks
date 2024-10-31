<?php
/*
Plugin Name: Restricted Blocks
Description: Restricts access to Gutenberg blocks based on a great variety of conditions. (Lite Version)
Version: 1.12
Author: DAEXT
Author URI: https://daext.com
Text Domain: restricted-blocks
*/

//Prevent direct access to this file
if ( ! defined( 'WPINC' ) ) {
	die();
}

//Shared across public and admin
require_once( plugin_dir_path( __FILE__ ) . 'shared/class-daextrebl-shared.php' );

require_once( plugin_dir_path( __FILE__ ) . 'public/class-daextrebl-public.php' );
add_action( 'plugins_loaded', array( 'daextrebl_Public', 'get_instance' ) );

//Perform the Gutenberg related activities only if Gutenberg is present
if ( function_exists( 'register_block_type' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'blocks/src/init.php' );
}

//Admin
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	//Admin
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-daextrebl-admin.php' );
	add_action( 'plugins_loaded', array( 'daextrebl_Admin', 'get_instance' ) );

	//Activate
	register_activation_hook( __FILE__, array( daextrebl_Admin::get_instance(), 'ac_activate' ) );

}

//Admin
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-daextrebl-admin.php' );

	// If this is not an AJAX request, create a new singleton instance of the admin class.
	if(! defined( 'DOING_AJAX' ) || ! DOING_AJAX ){
		add_action( 'plugins_loaded', array( 'daextrebl_Admin', 'get_instance' ) );
	}

	// Activate the plugin using only the class static methods.
	register_activation_hook( __FILE__, array( 'daextrebl_Admin', 'ac_activate' ) );

}

//Ajax
if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

	//Admin
	require_once( plugin_dir_path( __FILE__ ) . 'class-daextrebl-ajax.php' );
	add_action( 'plugins_loaded', array( 'daextrebl_Ajax', 'get_instance' ) );

}

//Functions
require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );

//Customize the action links in the "Plugins" menu
function daextrebl_customize_action_links( $actions ) {
	$actions[] = '<a href="https://daext.com/restricted-blocks/">' . esc_html__('Buy the Pro Version', 'restricted-blocks') . '</a>';
	return $actions;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'daextrebl_customize_action_links' );