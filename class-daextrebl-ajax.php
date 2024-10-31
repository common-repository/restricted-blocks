<?php

/*
 * This class should be used to include ajax actions.
 */

class daextrebl_Ajax {

	protected static $instance = null;
	private $shared = null;

	private function __construct() {

		//Assign an instance of the plugin info
		$this->shared = daextrebl_Shared::get_instance();

		//Ajax requests for logged-in users ----------------------------------------------------------------------------
		add_action( 'wp_ajax_daextrebl_verify_password', array( $this, 'verify_password' ) );
		add_action( 'wp_ajax_nopriv_daextrebl_verify_password', array( $this, 'verify_password' ) );

	}

	/*
	 * Return an instance of this class
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 * Verifies the password submitted by the user from a restricted block of type "Password".
	 */
	public function verify_password() {

		//check the referer
		check_ajax_referer( 'daextrebl', 'security' );

		//Init
        $response = [];

		//Sanitization
		$restriction_id = isset( $_POST['restriction_id'] ) ? intval( $_POST['restriction_id'], 10 ) : '';
		$password      = isset( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';

		//get the record in the database associated with the provided restriction id
		global $wpdb;
		$table_name     = $wpdb->prefix . $this->shared->get( 'slug' ) . "_restriction";
		$safe_sql       = $wpdb->prepare( "SELECT * FROM $table_name WHERE restriction_id = %d ", $restriction_id );
		$restriction_obj = $wpdb->get_row( $safe_sql );

		//verify the password
		if ( isset( $restriction_obj->password ) and $restriction_obj->password === $password ) {

			//The password is valid
            $response['valid'] = '1';
            $response['cookie_value'] = hash('sha512', json_encode($restriction_obj->restriction_id . $restriction_obj->password));

		} else {

			//The password is not valid
            $response['valid'] = '0';
            $response['cookie_value'] = '';

		}

		echo json_encode($response);
		die();

	}

}