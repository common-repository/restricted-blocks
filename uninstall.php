<?php

//Exit if this file is not called during the uninstall process
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}

require_once( plugin_dir_path( __FILE__ ) . 'shared/class-daextrebl-shared.php' );
require_once( plugin_dir_path( __FILE__ ) . 'admin/class-daextrebl-admin.php' );

//Delete options and tables
daextrebl_Admin::un_delete();