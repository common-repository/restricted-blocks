<?php

/*
 * this class should be used to work with the administrative side of wordpress
 */

class daextrebl_Admin {

	protected static $instance = null;
	private $shared = null;

	private $screen_id_restrictions = null;
	private $screen_id_help = null;
    private $screen_id_pro_version = null;
	private $screen_id_options = null;

	public $menu_options = null;

	private function __construct() {

		//assign an instance of the plugin info
		$this->shared = daextrebl_Shared::get_instance();

		//Load admin stylesheets and JavaScript
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		//Add the admin menu
		add_action( 'admin_menu', array( $this, 'me_add_admin_menu' ) );

		//Require and instantiate the class used to register the menu options
		require_once( $this->shared->get( 'dir' ) . 'admin/inc/class-daextrebl-menu-options.php' );
		$this->menu_options = new Daextrebl_Menu_Options( $this->shared );

		//Load the options API registrations and callbacks
		add_action( 'admin_init', array( $this, 'op_register_options' ) );

	}

	/*
	 * return an instance of this class
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}


	public function enqueue_admin_styles() {

		$screen = get_current_screen();

		//menu restrictions
		if ( $screen->id == $this->screen_id_restrictions ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-datetimepicker',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery.datetimepicker.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-menu',
				$this->shared->get( 'url' ) . 'admin/assets/css/framework/menu.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-restrictions',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-restrictions.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-tooltip.css', array(),
				$this->shared->get( 'ver' ) );

			//jQuery UI Dialog
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-dialog',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-dialog.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-dialog-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-dialog-custom.css', array(),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'shared/assets/inc/select2/css/select2.min.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/select2-custom.css', array(),
				$this->shared->get( 'ver' ) );

		}

		//Menu Help
		if ( $screen->id == $this->screen_id_help ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-help',
				$this->shared->get( 'url' ) . 'admin/assets/css/menu-help.css', array(), $this->shared->get( 'ver' ) );

		}

        //Menu Pro Version
        if ( $screen->id == $this->screen_id_pro_version ) {

            wp_enqueue_style( $this->shared->get( 'slug' ) . '-menu-pro-version',
                $this->shared->get( 'url' ) . 'admin/assets/css/menu-pro-version.css', array(), $this->shared->get( 'ver' ) );

        }

		//menu options
		if ( $screen->id == $this->screen_id_options ) {

			wp_enqueue_style( $this->shared->get( 'slug' ) . '-framework-options',
				$this->shared->get( 'url' ) . 'admin/assets/css/framework/options.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip',
				$this->shared->get( 'url' ) . 'admin/assets/css/jquery-ui-tooltip.css', array(),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'shared/assets/inc/select2/css/select2.min.css', array(),
				$this->shared->get( 'ver' ) );
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-select2-custom',
				$this->shared->get( 'url' ) . 'admin/assets/css/select2-custom.css', array(),
				$this->shared->get( 'ver' ) );

			//WP Color Picker
			wp_enqueue_style( 'wp-color-picker' );

		}

	}

	public function enqueue_admin_scripts() {


		$screen = get_current_screen();

		//menu restrictions
		if ( $screen->id == $this->screen_id_restrictions ) {

			//jQuery Datetime picker
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-datetimepicker',
				$this->shared->get( 'url' ) . 'admin/assets/js/jquery.datetimepicker.js', 'jquery',
				$this->shared->get( 'ver' ) );

			//jQuery UI Tooltips
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'shared/assets/inc/select2/js/select2.min.js', 'jquery',
				$this->shared->get( 'ver' ) );

			//Media Uploader
			wp_enqueue_media();
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-media-uploader',
				$this->shared->get( 'url' ) . 'admin/assets/js/media-uploader.js', 'jquery',
				$this->shared->get( 'ver' ) );

			//Menu Restrictions
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-menu-restrictions',
				$this->shared->get( 'url' ) . 'admin/assets/js/menu-restrictions.js', array(
					'jquery',
					'jquery-ui-dialog',
					'daextrebl-select2',
					'jquery-ui-tooltip',
					'daextrebl-jquery-ui-tooltip-init'
				), $this->shared->get( 'ver' ) );
			$wp_localize_script_data = array(
				'deleteText' => strip_tags( __( 'Delete', 'daim' ) ),
				'cancelText' => strip_tags( __( 'Cancel', 'daim' ) ),
			);
			wp_localize_script( $this->shared->get( 'slug' ) . '-menu-restrictions', 'objectL10n',
				$wp_localize_script_data );

			//Store the JavaScript parameters in the window.DAEXTREBL_PARAMETERS object
			$script = 'window.DAEXTREBL_PARAMETERS = {';
			$script .= 'adminUrl: "' . get_admin_url() . '",';
			$script .= '};';
			if ( $script !== false ) {
				wp_add_inline_script( $this->shared->get( 'slug' ) . '-menu-restrictions', $script, 'before' );
			}

		}

		//menu options
		if ( $screen->id == $this->screen_id_options ) {

			//jQuery UI Tooltips
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-jquery-ui-tooltip-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/jquery-ui-tooltip-init.js', array( 'jquery' ),
				$this->shared->get( 'ver' ) );

			//Select2
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-select2',
				$this->shared->get( 'url' ) . 'shared/assets/inc/select2/js/select2.min.js', 'jquery',
				$this->shared->get( 'ver' ) );

			//Color Picker Initialization
			wp_enqueue_script( $this->shared->get( 'slug' ) . '-wp-color-picker-init',
				$this->shared->get( 'url' ) . 'admin/assets/js/wp-color-picker-init.js',
				array( 'jquery', 'wp-color-picker' ), false, true );

		}

		/**
		 * When the editor file is loaded (only in the post editor) add the names and IDs of all the restrictions as
         * json data in a property of the window.DAEXTREBL_PARAMETERS object.
		 *
		 * These data are used to populate the "Restrictions" selector available in the inspector of all the blocks.
		 */
		global $wpdb;
		$table_name   = $wpdb->prefix . $this->shared->get( 'slug' ) . "_restriction";
		$sql          = "SELECT restriction_id, name FROM $table_name ORDER BY restriction_id DESC";
		$restriction_a = $wpdb->get_results( $sql, ARRAY_A );

		$restriction_a_alt   = [];
		$restriction_a_alt[] = [
			'value' => 0,
			'label' => __( 'None', 'restricted-blocks'),
		];
		foreach ( $restriction_a as $key => $value ) {
			$restriction_a_alt[] = [
				'value' => intval($value['restriction_id'], 10),
				'label' => stripslashes($value['name']),
			];
		}

		//Store the JavaScript parameters in the window.DAEXTREBL_PARAMETERS object
		$initialization_script = 'window.DAEXTREBL_PARAMETERS = {';
		$initialization_script .= "restrictions: " . json_encode( $restriction_a_alt );
		$initialization_script .= '};';
		wp_add_inline_script( $this->shared->get( 'slug' ) . '-editor-js', $initialization_script, 'before' );

	}

	/*
	 * plugin activation
	 */
	static public function ac_activate( $networkwide ) {

		/*
		 * create options and tables for all the sites in the network
		 */
		if ( function_exists( 'is_multisite' ) and is_multisite() ) {

			/*
			 * if this is a "Network Activation" create the options and tables
			 * for each blog
			 */
			if ( $networkwide ) {

				//get the current blog id
				global $wpdb;
				$current_blog = $wpdb->blogid;

				//create an array with all the blog ids
				$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

				//iterate through all the blogs
				foreach ( $blogids as $blog_id ) {

					//switch to the iterated blog
					switch_to_blog( $blog_id );

					//create options and tables for the iterated blog
					self::ac_initialize_options();
					self::ac_create_database_tables();
					self::ac_initialize_custom_css();

				}

				//switch to the current blog
				switch_to_blog( $current_blog );

			} else {

				/*
				 * if this is not a "Network Activation" create options and
				 * tables only for the current blog
				 */
				self::ac_initialize_options();
				self::ac_create_database_tables();
				self::ac_initialize_custom_css();

			}

		} else {

			/*
			 * if this is not a multisite installation create options and
			 * tables only for the current blog
			 */
			self::ac_initialize_options();
			self::ac_create_database_tables();
			self::ac_initialize_custom_css();

		}

	}

	//create the options and tables for the newly created blog
	public function new_blog_create_options_and_tables( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

		global $wpdb;

		/*
		 * if the plugin is "Network Active" create the options and tables for
		 * this new blog
		 */
		if ( is_plugin_active_for_network( 'league-table/init.php' ) ) {

			//get the id of the current blog
			$current_blog = $wpdb->blogid;

			//switch to the blog that is being activated
			switch_to_blog( $blog_id );

			//create options and database tables for the new blog
			$this->ac_initialize_options();
			$this->ac_create_database_tables();
			$this->ac_initialize_custom_css();

			//switch to the current blog
			switch_to_blog( $current_blog );

		}

	}

	//delete options and tables for the deleted blog
	public function delete_blog_delete_options_and_tables( $blog_id ) {

		global $wpdb;

		//get the id of the current blog
		$current_blog = $wpdb->blogid;

		//switch to the blog that is being activated
		switch_to_blog( $blog_id );

		//create options and database tables for the new blog
		$this->un_delete_options();
		$this->un_delete_database_tables();

		//switch to the current blog
		switch_to_blog( $current_blog );

	}

	/*
	 * initialize plugin options
	 */
	static private function ac_initialize_options() {

		//assign an instance of Daextrebl_Shared
		$shared = Daextrebl_Shared::get_instance();

		foreach ( $shared->get( 'options' ) as $key => $value ) {
			add_option( $key, $value );
		}

	}

	/*
	 * create the plugin database tables
	 */
	static private function ac_create_database_tables() {

		global $wpdb;

		//Get the database character collate that will be appended at the end of each query
		$charset_collate = $wpdb->get_charset_collate();

		//check database version and create the database
		if ( intval( get_option( 'daextrebl_database_version' ), 10 ) < 1 ) {

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			//create *prefix*_restriction
			$table_name = $wpdb->prefix . "daextrebl_restriction";
			$sql        = "CREATE TABLE $table_name (
                restriction_id bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name text NOT NULL DEFAULT '',
                description text NOT NULL DEFAULT '',
                type tinyint(1) UNSIGNED DEFAULT NULL,
                mode tinyint(1) UNSIGNED DEFAULT NULL,
                device tinyint(1) UNSIGNED DEFAULT NULL,
                start_date datetime DEFAULT NULL,
                end_date datetime DEFAULT NULL,
                capabilities text NOT NULL DEFAULT '',
                ip_address text NOT NULL DEFAULT '',
                cookie_name text NOT NULL DEFAULT '',
                cookie_value text NOT NULL DEFAULT '',
                output_behavior tinyint(1) UNSIGNED DEFAULT NULL,	
                output_image text NOT NULL DEFAULT '',
                output_title text NOT NULL DEFAULT '',
                output_description text NOT NULL DEFAULT '',
                output_button text NOT NULL DEFAULT '',
                output_validation text NOT NULL DEFAULT '',
                header_name text NOT NULL DEFAULT '',
                header_value text NOT NULL DEFAULT '',
                password text NOT NULL DEFAULT ''
            ) $charset_collate";
			dbDelta( $sql );

			//Update database version
			update_option( 'daextrebl_database_version', "1" );

		}

	}

	/*
	 * plugin delete
	 */
	static public function un_delete() {

		/*
		 * delete options and tables for all the sites in the network
		 */
		if ( function_exists( 'is_multisite' ) and is_multisite() ) {

			//get the current blog id
			global $wpdb;
			$current_blog = $wpdb->blogid;

			//create an array with all the blog ids
			$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

			//iterate through all the blogs
			foreach ( $blogids as $blog_id ) {

				//switch to the iterated blog
				switch_to_blog( $blog_id );

				//create options and tables for the iterated blog
				daextrebl_Admin::un_delete_options();
				daextrebl_Admin::un_delete_database_tables();

			}

			//switch to the current blog
			switch_to_blog( $current_blog );

		} else {

			/*
			 * if this is not a multisite installation delete options and
			 * tables only for the current blog
			 */
			daextrebl_Admin::un_delete_options();
			daextrebl_Admin::un_delete_database_tables();

		}

	}

	/*
	 * Delete plugin options.
	 */
	static public function un_delete_options() {

		//assign an instance of Daextrebl_Shared
		$shared = Daextrebl_Shared::get_instance();

		foreach ( $shared->get( 'options' ) as $key => $value ) {
			delete_option( $key );
		}

	}

	/*
	 * delete plugin database tables
	 */
	static public function un_delete_database_tables() {

		//assign an instance of Daextrebl_Shared
		$shared = Daextrebl_Shared::get_instance();

		global $wpdb;
		$table_name = $wpdb->prefix . $shared->get( 'slug' ) . "_restriction";
		$sql        = "DROP TABLE $table_name";
		$wpdb->query( $sql );

	}

	/*
	 * register the admin menu
	 */
	public function me_add_admin_menu() {

		add_menu_page(
			esc_html__( 'PB', 'restricted-blocks'),
			esc_html__( 'Restrictions', 'restricted-blocks'),
			get_option( $this->shared->get( 'slug' ) . '_restrictions_menu_capability' ),
			$this->shared->get( 'slug' ) . '-restrictions',
			array( $this, 'me_display_menu_restrictions' ),
			'dashicons-lock'
		);

		$this->screen_id_restrictions = add_submenu_page(
			$this->shared->get( 'slug' ) . '-restrictions',
			esc_html__( 'PB - Restrictions', 'restricted-blocks'),
			esc_html__( 'Restrictions', 'restricted-blocks'),
			get_option( $this->shared->get( 'slug' ) . '_restrictions_menu_capability' ),
			$this->shared->get( 'slug' ) . '-restrictions',
			array( $this, 'me_display_menu_restrictions' )
		);

		$this->screen_id_help = add_submenu_page(
			$this->shared->get( 'slug' ) . '-restrictions',
			esc_html__( 'PB - Help', 'restricted-blocks'),
			esc_html__( 'Help', 'restricted-blocks'),
			'manage_options',
			$this->shared->get( 'slug' ) . '-help',
			array( $this, 'me_display_menu_help' )
		);

        $this->screen_id_pro_version = add_submenu_page(
            $this->shared->get( 'slug' ) . '-restrictions',
            esc_html__( 'PB - Pro Version', 'restricted-blocks'),
            esc_html__( 'Pro Version', 'restricted-blocks'),
            'manage_options',
            $this->shared->get( 'slug' ) . '-pro-version',
            array( $this, 'me_display_menu_pro_version' )
        );

		$this->screen_id_options = add_submenu_page(
			$this->shared->get( 'slug' ) . '-restrictions',
			esc_html__( 'PB - Options', 'restricted-blocks'),
			esc_html__( 'Options', 'restricted-blocks'),
			'manage_options',
			$this->shared->get( 'slug' ) . '-options',
			array( $this, 'me_display_menu_options' )
		);

	}

	/*
	 * includes the restrictions menu
	 */
	public function me_display_menu_restrictions() {
		include_once( 'view/restrictions.php' );
	}

	/*
	 * includes the help menu
	 */
	public function me_display_menu_help() {
		include_once( 'view/help.php' );
	}

    /*
     * includes the pro version menu
     */
    public function me_display_menu_pro_version() {
        include_once( 'view/pro_version.php' );
    }

	/*
	 * includes the options menu
	 */
	public function me_display_menu_options() {
		include_once( 'view/options.php' );
	}

	/*
	 * register options
	 */
	public function op_register_options() {

		$this->menu_options->register_options();

	}

	/*
     * Generate the custom.css file based on the values of the options and write them down in the custom.css file.
     */
	static public function write_custom_css() {

		//turn on output buffering
		ob_start();

		//font family
		echo '.daextrebl-restricted-block-container,
        .daextrebl-restricted-block-container *{font-family: ' . htmlspecialchars( get_option( "daextrebl_font_family" ),
				ENT_COMPAT ) . ' !important; }';

		//Container Background Color
		echo ".daextrebl-restricted-block-container{ background: " . esc_attr( get_option( "daextrebl_container_background_color" ) ) . " !important;}";

		//Title font color
		echo ".daextrebl-restricted-block-title{ color: " . esc_attr( get_option( "daextrebl_title_font_color" ) ) . " !important;}";

		//Description font color
		echo ".daextrebl-restricted-block-description{ color: " . esc_attr( get_option( "daextrebl_description_font_color" ) ) . " !important;}";

		//Validation Message Background Color
		echo ".daextrebl-age-restriction-form .daextrebl-validation-message,
		.daextrebl-password-invalid{ background: " . esc_attr( get_option( "daextrebl_validation_message_background_color" ) ) . " !important;}";

		//Validation Message Font Color
		echo ".daextrebl-age-restriction-form .daextrebl-validation-message,
		.daextrebl-password-invalid{ color: " . esc_attr( get_option( "daextrebl_validation_message_font_color" ) ) . " !important;}";

		//Controls Label Color
		echo ".daextrebl-password-label{ color: " . esc_attr( get_option( "daextrebl_controls_label_color" ) ) . " !important;}";

		//Borders Color
		echo ".daextrebl-password,
		.daextrebl-restricted-block-container{ border-color: " . esc_attr( get_option( "daextrebl_borders_color" ) ) . " !important;}";

		//Buttons Background Color
		echo ".daextrebl-form-row button{ background: " . esc_attr( get_option( "daextrebl_buttons_background_color" ) ) . " !important;}";

		//Buttons Font Color
		echo ".daextrebl-form-row button{ color: " . esc_attr( get_option( "daextrebl_buttons_font_color" ) ) . " !important;}";

		//Controls Background Color
		echo ".daextrebl-password,
        .daextrebl-restricted-block-container .daextrebl-password{ background: " . esc_attr( get_option( "daextrebl_controls_background_color" ) ) . " !important;}";

		//Controls Font Color
		echo ".daextrebl-password{ color: " . esc_attr( get_option( "daextrebl_controls_font_color" ) ) . " !important;}";

		//Icons Color
		echo '.daextrebl-svg-color{fill:' . esc_attr( get_option( "daextrebl_icons_color" ) ) . ' !important;}';

		//Margin Top
		echo ".daextrebl-restricted-block-container{ margin-top: " . esc_attr( get_option( "daextrebl_margin_top" ) ) . "px !important;}";

		//Margin Bottom
		echo ".daextrebl-restricted-block-container{ margin-bottom: " . esc_attr( get_option( "daextrebl_margin_bottom" ) ) . "px !important;}";

		$custom_css_string = ob_get_clean();

		//Get the upload directory path and the file path
		$upload_dir_path = self::get_plugin_upload_path();
		$upload_file_path = self::get_plugin_upload_path() . 'custom-' . get_current_blog_id() . '.css';

		//If the plugin upload directory doesn't exists create it
		if (!is_dir($upload_dir_path)) {
			mkdir($upload_dir_path);
		}

		//Write the custom css file
		return @file_put_contents( $upload_file_path,
			$custom_css_string, LOCK_EX );

	}

	/*
	 * initialize the custom-[blog_id].css file
	 */
	static public function ac_initialize_custom_css() {

		/*
		 * Write the custom-[blog_id].css file or die if the file can't be created or modified.
		 */
		if ( self::write_custom_css() === false ) {
			die( "The plugin can't write files in the upload directory." );
		}

	}

	/**
	 * Get the plugin upload path.
	 *
	 * @return string The plugin upload path
	 */
	static public function get_plugin_upload_path() {

		$upload_path = WP_CONTENT_DIR . '/uploads/daextrebl_uploads/';

		return $upload_path;

	}

	/**
	 * Echo all the dismissible notices based on the values of the $notices array.
	 *
	 * @param $notices
	 */
	public function dismissible_notice($notices){

		foreach($notices as $key => $notice){
			echo '<div class="' . esc_attr($notice['class']) . ' settings-error notice is-dismissible below-h2"><p>' . esc_html($notice['message']) . '</p></div>';
		}

	}

}