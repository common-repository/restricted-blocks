<?php

/*
 * this class should be used to stores properties and methods shared by the
 * admin and public side of wordpress
 */

class daextrebl_Shared {

	//regex
	public $font_family_regex = '/^([A-Za-z0-9-\'", ]*)$/';

	protected static $instance = null;

	private $data = array();

	private function __construct() {

		$this->data['slug'] = 'daextrebl';
		$this->data['ver']  = '1.12';
		$this->data['dir']  = substr( plugin_dir_path( __FILE__ ), 0, - 7 );
		$this->data['url']  = substr( plugin_dir_url( __FILE__ ), 0, - 7 );

		//Here are stored the plugin option with the related default values
		$this->data['options'] = [

			//Database Version -----------------------------------------------------------------------------------------
			$this->get( 'slug' ) . "_database_version"                    => "0",

			//General --------------------------------------------------------------------------------------------------

			//Style ----------------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_font_family'                         => "'Open Sans', sans-serif",
			$this->get( 'slug' ) . '_container_background_color'          => "#ffffff",
			$this->get( 'slug' ) . '_title_font_color'                    => "#272729",
			$this->get( 'slug' ) . '_description_font_color'              => "#76808c",
			$this->get( 'slug' ) . '_validation_message_background_color' => "#fff2f9",
			$this->get( 'slug' ) . '_validation_message_font_color'       => "#9c7388",
			$this->get( 'slug' ) . '_controls_label_color'                => "#76808c",
			$this->get( 'slug' ) . '_borders_color'                       => "#d3daf0",
			$this->get( 'slug' ) . '_buttons_background_color'            => "#265cff",
			$this->get( 'slug' ) . '_buttons_font_color'                  => "#ffffff",
			$this->get( 'slug' ) . '_controls_background_color'           => "#ffffff",
			$this->get( 'slug' ) . '_controls_font_color'                 => "#1f2021",
			$this->get( 'slug' ) . '_icons_color'                         => "#76808c",
			$this->get( 'slug' ) . '_margin_top'                          => "20",
			$this->get( 'slug' ) . '_margin_bottom'                       => "20",

			//Advanced -------------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_google_font_url'                     => "https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap",
			$this->get( 'slug' ) . '_restrictions_menu_capability'         => "manage_options",
			$this->get( 'slug' ) . '_cookie_expiration'         => "2147483647",

		];

	}

	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	//retrieve data
	public function get( $index ) {
		return $this->data[ $index ];
	}

	/*
	 * If $needle is present in the $haystack array echos 'selected="selected"'.
	 *
	 * @param $haystack Array
	 * @param $needle String
	 */
	public function selected_array( $array, $needle ) {

		if ( is_array( $array ) and in_array( $needle, $array ) ) {
			return 'selected="selected"';
		}

	}

	/**
	 * Generate the HTML of a block associated with a restriction of type "Device".
	 *
	 * @param $restriction_obj
	 * @param $block_content
	 *
	 * @return string
	 */
	public function apply_restriction_device( $restriction_obj, $block_content ) {

		//Require and instantiate the class used to detect the type of device
		require_once( $this->get( 'dir' ) . 'public/inc/mobile-detect/Mobile_Detect.php' );
		$mobile_detect = new Daextrebl_Mobile_Detect();

		if ( intval( $restriction_obj->device, 10 ) === 0 and ! $mobile_detect->isMobile() ) {

			$display_block = true;

		} elseif ( intval( $restriction_obj->device, 10 ) === 0 and $mobile_detect->isMobile() ) {

			$display_block = false;

		} elseif ( intval( $restriction_obj->device, 10 ) === 1 and ! $mobile_detect->isMobile() ) {

			$display_block = false;

		} elseif ( intval( $restriction_obj->device, 10 ) === 1 and $mobile_detect->isMobile() ) {

			$display_block = true;

		}

		return $this->generate_output( $block_content, $display_block, $restriction_obj );

	}

	/**
	 * Generate the HTML of a block associated with a restriction of type "Fixed".
	 *
	 * @param $restriction_obj
	 * @param $block_content
	 *
	 * @return string
	 */
	public function apply_restriction_fixed( $restriction_obj ) {

		return $this->generate_restricted_block_output( $restriction_obj );

	}

	/**
	 * Generate the HTML of a block associated with a restriction of type "Time Range".
	 *
	 * The restriction of type "Time Range" generates the original block content only if the user is visiting the page
	 * with the block in a data/time included between the start_date and the end_date defined in the restriction.
	 *
	 * @param $restriction_obj
	 * @param $block_content
	 *
	 * @return string
	 */
	public function apply_restriction_time_range( $restriction_obj, $block_content ) {

		//get current time
		$current_time = current_time( 'mysql' );

		//get all the times in unix timestamp
		$current_time = strtotime( $current_time );
		$start_date   = strtotime( $restriction_obj->start_date );
		$end_date     = strtotime( $restriction_obj->end_date );

		if ( $current_time > $start_date and $current_time < $end_date ) {
			$display_block = true;
		} else {
			$display_block = false;
		}

		return $this->generate_output( $block_content, $display_block, $restriction_obj );

	}

	/**
	 * Generate the HTML of a block associated with a restriction of type "Capability".
	 *
	 * A restriction of type "Capability" generates the original block content only if the user that is visiting the page
	 * has at least one of the capabilities defined with the "Capabilities" field of the restriction.
	 *
	 * @param $restriction_obj
	 * @param $block_content
	 *
	 * @return string
	 */
	public function apply_restriction_capability( $restriction_obj, $block_content ) {

		//Init
		$display_block = false;

		//Generate an array with the capabilities defined in the restriction
		$capabilities_a = preg_split( '/\r\n|[\r\n]/', $restriction_obj->capabilities );

		//Verify if the users owns at least one of the capabilities defined in the restriction
		if ( is_array( $capabilities_a ) ) {
			foreach ( $capabilities_a as $key => $capability ) {
				if ( current_user_can( $capability ) ) {
					$display_block = true;
					break;
				}
			}
		}

		//Return the HTML
		return $this->generate_output( $block_content, $display_block, $restriction_obj );

	}

	/**
	 * Generate the HTML of a block associated with a restriction of type "IP Address".
	 *
	 * A restriction of type "IP Address" generates the original block content only if the user that is visiting the page
	 * has one of the IP Address defined with the "IP Address" field of the restriction.
	 *
	 * @param $restriction_obj
	 * @param $block_content
	 *
	 * @return string
	 */
	public function apply_restriction_ip_address( $restriction_obj, $block_content ) {

		//Init
		$display_block   = false;
		$user_ip_address = $this->get_ip_address();

		//Generate an array with the IP Address defined in the restriction
		$ip_address_a = preg_split( '/\r\n|[\r\n]/', $restriction_obj->ip_address );

		//Verify if the users has one of the IP Address defined in the restriction
		if ( is_array( $ip_address_a ) ) {
			foreach ( $ip_address_a as $key => $ip_address ) {
				if ( $ip_address === $user_ip_address ) {
					$display_block = true;
					break;
				}
			}
		}

		//Return the HTML
		return $this->generate_output( $block_content, $display_block, $restriction_obj );

	}

	/**
	 * Generate the HTML of a block associated with a restriction of type "Cookie".
	 *
	 * A restriction of type "Cookie" generates the original block content only if the user that is visiting the page
	 * has a cookie with name and value defined with the "Cookie Name" and "Cookie value" fields of the restriction.
	 *
	 * @param $restriction_obj
	 * @param $block_content
	 *
	 * @return string
	 */
	public function apply_restriction_cookie( $restriction_obj, $block_content ) {

		//If the cookie with the defined cookie name exists, then get its value.
		if ( isset( $_COOKIE[ $restriction_obj->cookie_name ] ) ) {
			$cookie_value = sanitize_text_field( $_COOKIE[ $restriction_obj->cookie_name ] );
		} else {
			$cookie_value = null;
		}

		//If the value of this cookie is the same of the "Cookie Value" field of the restriction, then display the block.
		if ( $cookie_value === $restriction_obj->cookie_value ) {
			$display_block = true;
		} else {
			$display_block = false;
		}

		//Return the HTML
		return $this->generate_output( $block_content, $display_block, $restriction_obj );

	}

	/**
	 * Generate the HTML of a block associated with a restriction of type "HTTP Headers".
	 *
	 * A restriction of type "HTTP Headers" generates the original block content only if the user that is visiting the
	 * page has a header with the name defined with the "Cookie Name" that includes one of the values defined with the
	 * "Cookie Value" field.
	 *
	 * Please note that the header names are treated as case-insensitive.
	 *
	 * @param $restriction_obj
	 * @param $block_content
	 *
	 * @return string
	 */
	public function apply_restriction_http_headers( $restriction_obj, $block_content ) {

		//Init
		$display_block = false;

		//Get the HTTP Header of this request
		$headers_raw = getallheaders();
		$headers     = [];

		//Change all the indexes names of the $headers array to lowercase
		foreach ( $headers_raw as $key => $header ) {
			$headers[ strtolower( $key ) ] = $header;
		}

		//Get the lowercase version of the HTTP Header names defined in the restriction
		$header_name = strtolower($restriction_obj->header_name);

		//Generate an array with the HTTP Header values defined in the restriction
		$header_value_a = preg_split( '/\r\n|[\r\n]/', $restriction_obj->header_value );

		//Verify if the request has the header defined in the restriction and one of the values defined in the restriction
		if ( is_array( $header_value_a ) ) {
			foreach ( $header_value_a as $key => $header_value ) {
				if ( isset( $headers[ $header_name ] ) and $headers[ $header_name ] === $header_value ) {
					$display_block = true;
					break;
				}
			}
		}

		//Return the HTML
		return $this->generate_output( $block_content, $display_block, $restriction_obj );

	}

	/**
	 * Generate the HTML of a block associated with a restriction of type "Password".
	 *
	 * A restriction of type "Password" generates the original block content only if the user submit the correct password
	 * in the provided form.
	 *
	 * The success of the password submission is stored with the $cookie_name cookie.
	 *
	 * @param $restriction_obj
	 * @param $block_content
	 *
	 * @return string
	 */
	public function apply_restriction_password( $restriction_obj, $block_content ) {

		/*
		 * Get the value of the cookie used to store a flag used to determine if the password has been already
		 * successfully submitted.
		 */
		$cookie_value = null;
		$cookie_name  = 'daextrebl-password-' . intval( $restriction_obj->restriction_id, 10 );
		if ( isset( $_COOKIE[ $cookie_name ] ) ) {
			$cookie_value = sanitize_key( $_COOKIE[ $cookie_name ] );
		}

		/*
		 * If the password has been already successfully submitted then display the original block content, otherwise
		 * generate the restricted block.
		 */
        $correct_cookie_value = hash('sha512', json_encode($restriction_obj->restriction_id . $restriction_obj->password));
        if ( $cookie_value === $correct_cookie_value ) {

			return $block_content;

		} else {

			//turn on output buffer
			ob_start();

			?>

            <div class="daextrebl-form daextrebl-password-form"
                 data-restriction-id="<?php echo esc_attr( $restriction_obj->restriction_id ); ?>">
                <div class="daextrebl-form-row daextrebl-form-row-password">
                    <div class="daextrebl-password-label"><?php esc_html_e( 'Password', 'restricted-blocks'); ?></div>
                    <input type="password" class="daextrebl-password">
                    <?php

                    //Store the eye visible svg in a string
                    $eye_visible_url = $this->get( 'url' ) . 'public/assets/img/eye-visible.svg';
                    $eye_visible_svg = file_get_contents( $eye_visible_url );

                    //Store the eye invisible svg in a string
                    $eye_invisible_url = $this->get( 'url' ) . 'public/assets/img/eye-invisible.svg';
                    $eye_invisible_svg = file_get_contents( $eye_invisible_url );

                    //Configure the allowed tags and attributes of the svg
                    $allowed_html = [
	                    'svg'  => [],
	                    'path' => [
		                    'class' => [],
		                    'd'     => [],
	                    ],
	                    'rect' => [
		                    'x' => [],
                            'y' => [],
                            'transform' => [],
		                    'class' => [],
		                    'width' => [],
		                    'height' => []
	                    ]
                    ];

                    ?>
                    <div class="daextrebl-password-toggle">
                        <div class="daextrebl-eye-visible-svg"><?php echo wp_kses($eye_visible_svg, $allowed_html); ?></div>
                        <div class="daextrebl-eye-invisible-svg daextrebl-display-none"><?php echo wp_kses($eye_invisible_svg, $allowed_html); ?></div>
                    </div>
                </div>
                <div class="daextrebl-form-row">
                    <button class="daextrebl-password-form-submit"><?php echo esc_html( stripslashes( $restriction_obj->output_button ) ); ?></button>
                </div>
                <div class="daextrebl-form-row">
                    <div class="daextrebl-password-invalid"><?php echo esc_html( stripslashes( $restriction_obj->output_validation ) ); ?></div>
                </div>
            </div>

			<?php

			$form_html = ob_get_clean();

			//Return the HTML
			return $this->generate_restricted_block_output( $restriction_obj, $form_html );

		}

	}

	/**
	 * Get the IP address of the user. If the retrieved IP address is not valid an empty string is returned.
	 *
	 * @return string
	 */
	public function get_ip_address() {

		$ip_address = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );

		if ( rest_is_ip_address( $ip_address ) ) {
			return $ip_address;
		} else {
			return '';
		}

	}

	/**
	 * Generate the complete output of the restricted block based on the provided $restriction_obj and $form_html.
	 *
	 * @param $restriction_obj
	 * @param string $form_html
	 *
	 * @return false|string
	 */
	public function generate_restricted_block_output( $restriction_obj, $form_html = '' ) {

		/*
		 * If the "Output Behavior" options is set to "0" then return an empty string to hide the block, otherwise
		 * return the restricted block.
		 */
		if ( intval($restriction_obj->output_behavior, 10) === 0 ) {

			return '';

		} else {

			ob_start();

			?>

            <div class="daextrebl-restricted-block-container">
				<?php if ( strlen( trim( $restriction_obj->output_image ) ) > 0 ) : ?>
                    <img class="daextrebl-restricted-block-icon"
                         src="<?php echo esc_url( stripslashes( $restriction_obj->output_image ) ); ?>">
				<?php endif; ?>
				<?php if ( strlen( trim( $restriction_obj->output_title ) ) > 0 ) : ?>
                    <div class="daextrebl-restricted-block-title"><?php echo esc_html( stripslashes( $restriction_obj->output_title ) ); ?></div>
				<?php endif; ?>
				<?php if ( strlen( trim( $restriction_obj->output_description ) ) > 0 ) : ?>
                    <div class="daextrebl-restricted-block-description"><?php echo esc_html( stripslashes( $restriction_obj->output_description ) ); ?></div>
				<?php endif; ?>
				<?php echo $form_html; ?>
            </div>

			<?php

			$html = ob_get_clean();

			//Return the HTML
			return $html;

		}

	}

	/**
	 * Get the name of the type of restriction based on the provided ID.
	 *
	 * @param $restriction_type
	 *
	 * @return string|void
	 */
	public function get_restriction_type_name( $restriction_type ) {

		switch ( $restriction_type ) {

			case 0:
				$name = __( 'Fixed', 'restricted-blocks');
				break;

			case 1:
				$name = __( 'Password', 'restricted-blocks');
				break;

			case 2:
				$name = __( 'Device', 'restricted-blocks');
				break;

			case 3:
				$name = __( 'Time Range', 'restricted-blocks');
				break;

			case 4:
				$name = __( 'Capability', 'restricted-blocks');
				break;

			case 5:
				$name = __( 'IP Address', 'restricted-blocks');
				break;

			case 6:
				$name = __( 'Cookie', 'restricted-blocks');
				break;

			case 7:
				$name = __( 'HTTP Headers', 'restricted-blocks');
				break;

		}

		return $name;

	}

	/**
	 * Generated the output of the block based on the provided values.
	 *
	 * @param $block_content The default content of the block.
	 * @param $display_block Whether to display or not the default content of the block.
	 * @param $restriction_obj The restriction associated with the block
	 *
	 * @return false|string
	 */
	public function generate_output( $block_content, $display_block, $restriction_obj ) {

		//Invert the result if the "Mode" option is set to "Exclude"
		if ( intval( $restriction_obj->mode, 10 ) === 1 ) {
			$display_block = ! $display_block;
		}

		//Return the original block content or the restricted block based on the value of $display_block
		if ( $display_block ) {
			return $block_content;
		} else {
			return $this->generate_restricted_block_output( $restriction_obj );
		}

	}

}