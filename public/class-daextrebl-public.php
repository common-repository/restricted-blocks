<?php

/*
 * this class should be used to work with the public side of wordpress
 */

class daextrebl_Public {

	protected static $instance = null;
	private $shared = null;

	private function __construct() {

		//assign an instance of the plugin info
		$this->shared = daextrebl_Shared::get_instance();

		//Filters the content of a single block
		add_filter( 'render_block', array( $this, 'filter_block_content' ), 10, 2 );

		//Load public css
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		//Load public js
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/*
	 * create an instance of this class
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	public function enqueue_styles() {

		//Adds the Google Fonts if they are defined in the "Google Font URL" option.
		if ( strlen( trim( get_option( $this->shared->get( 'slug' ) . "_google_font_url" ) ) ) > 0 ) {
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-google-font',
				esc_url( get_option( $this->shared->get( 'slug' ) . '_google_font_url' ) ), false );
		}

		//Enqueue the main stylesheet
		wp_enqueue_style( $this->shared->get( 'slug' ) . '-general',
			$this->shared->get( 'url' ) . 'public/assets/css/general.css', array(), $this->shared->get( 'ver' ) );

		//Enqueue the custom CSS file based on the plugin options
		$upload_dir_data = wp_upload_dir();
		wp_enqueue_style( $this->shared->get( 'slug' ) . '-custom',
			$upload_dir_data['baseurl'] . '/daextrebl_uploads/custom-' . get_current_blog_id() . '.css', array(),
			$this->shared->get( 'ver' ) );

	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->shared->get( 'slug' ) . '-general',
			$this->shared->get( 'url' ) . 'public/assets/js/general.js', array( 'jquery' ), $this->shared->get( 'ver' ),
			true );

		//Store the JavaScript parameters in the window.DAEXTREBL_PARAMETERS object
		$script = 'window.DAEXTREBL_PARAMETERS = {';
		$script .= 'ajax_url: "' . admin_url( 'admin-ajax.php' ) . '",';
		$script .= 'nonce: "' . wp_create_nonce( "daextrebl" ) . '",';
		$script .= 'cookieExpiration: ' . intval(get_option( $this->shared->get( 'slug' ) . '_cookie_expiration' ) , 10);
		$script .= '};';
		if ( $script !== false ) {
			wp_add_inline_script( $this->shared->get( 'slug' ) . '-general', $script, 'before' );
		}

	}

	/**
	 * This function return the original block content or the HTML of the restriction.
	 *
	 * @param $block_content
	 * @param $block
	 *
	 * @return string
	 */
	public function filter_block_content( $block_content, $block ) {

		//Verify if this block is associated with a restriction
		if ( isset( $block['attrs']['daextreblRestrictionId'] ) and $block['attrs']['daextreblRestrictionId'] !== 0 ) {

			//Get the ID of the restriction
			$restriction_id = intval( $block['attrs']['daextreblRestrictionId'], 10 );

			//search the restriction in the database
			global $wpdb;
			$table_name     = $wpdb->prefix . $this->shared->get( 'slug' ) . "_restriction";
			$safe_sql       = $wpdb->prepare( "SELECT * FROM $table_name WHERE restriction_id = %d ", $restriction_id );
			$restriction_obj = $wpdb->get_row( $safe_sql );

			/**
			 * Return the original block content if the selected restriction doesn't exist.
			 */
			if ( $restriction_obj === null ) {
				return $block_content;
			}

			//apply the restriction
			switch ( $restriction_obj->type ) {

                //Fixed
                case 0:

                    $modified_block_content = $this->shared->apply_restriction_fixed( $restriction_obj );

                    break;


                //Password
                case 1:

                    $modified_block_content = $this->shared->apply_restriction_password( $restriction_obj,
                        $block_content );

                    break;

				//Device
				case 2:

					$modified_block_content = $this->shared->apply_restriction_device( $restriction_obj, $block_content );

					break;

				//Time Range
				case 3:

					$modified_block_content = $this->shared->apply_restriction_time_range( $restriction_obj,
						$block_content );

					break;

				//Capability
				case 4:

					$modified_block_content = $this->shared->apply_restriction_capability( $restriction_obj,
						$block_content );

					break;

				//IP Address
				case 5:

					$modified_block_content = $this->shared->apply_restriction_ip_address( $restriction_obj,
						$block_content );

					break;

				//Cookie
				case 6:

					$modified_block_content = $this->shared->apply_restriction_cookie( $restriction_obj, $block_content );

					break;

				//User Agent
				case 7:

					$modified_block_content = $this->shared->apply_restriction_http_headers( $restriction_obj,
						$block_content );

					break;


			}

			return $modified_block_content;

		} else {

			return $block_content;

		}

	}

}