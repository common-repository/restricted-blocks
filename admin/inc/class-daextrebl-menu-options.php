<?php

/**
 * This class adds the options with the related callbacks and validations.
 */
class Daextrebl_Menu_Options {

	private $shared = null;

	public function __construct( $shared ) {

		//assign an instance of the plugin info
		$this->shared = $shared;

	}

	public function register_options() {

		//Section Style ----------------------------------------------------------
		add_settings_section(
			'daextrebl_style_settings_section',
			null,
			null,
			'daextrebl_style_options'
		);

		add_settings_field(
			'font_family',
			esc_html__( 'Font Family', 'restricted-blocks'),
			array( $this, 'font_family_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_font_family',
			array( $this, 'font_family_validation' )
		);

		add_settings_field(
			'container_background_color',
			esc_html__( 'Container Background Color', 'restricted-blocks'),
			array( $this, 'container_background_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_container_background_color',
			array( $this, 'container_background_color_validation' )
		);
		
		add_settings_field(
			'title_font_color',
			esc_html__( 'Title Font Color', 'restricted-blocks'),
			array( $this, 'title_font_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_title_font_color',
			array( $this, 'title_font_color_validation' )
		);

		add_settings_field(
			'description_font_color',
			esc_html__( 'Description Font Color', 'restricted-blocks'),
			array( $this, 'description_font_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_description_font_color',
			array( $this, 'description_font_color_validation' )
		);

		add_settings_field(
			'validation_message_background_color',
			esc_html__( 'Validation Message Background Color', 'restricted-blocks'),
			array( $this, 'validation_message_background_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_validation_message_background_color',
			array( $this, 'validation_message_background_color_validation' )
		);

		add_settings_field(
			'validation_message_font_color',
			esc_html__( 'Validation Message Font Color', 'restricted-blocks'),
			array( $this, 'validation_message_font_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_validation_message_font_color',
			array( $this, 'validation_message_font_color_validation' )
		);

		add_settings_field(
			'controls_label_color',
			esc_html__( 'Controls Label Color', 'restricted-blocks'),
			array( $this, 'controls_label_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_controls_label_color',
			array( $this, 'controls_label_color_validation' )
		);

		add_settings_field(
			'borders_color',
			esc_html__( 'Borders Color', 'restricted-blocks'),
			array( $this, 'borders_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_borders_color',
			array( $this, 'borders_color_validation' )
		);

		add_settings_field(
			'buttons_background_color',
			esc_html__( 'Buttons Background Color', 'restricted-blocks'),
			array( $this, 'buttons_background_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_buttons_background_color',
			array( $this, 'buttons_background_color_validation' )
		);

		add_settings_field(
			'buttons_font_color',
			esc_html__( 'Buttons Font Color', 'restricted-blocks'),
			array( $this, 'buttons_font_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_buttons_font_color',
			array( $this, 'buttons_font_color_validation' )
		);

		add_settings_field(
			'controls_background_color',
			esc_html__( 'Controls Background Color', 'restricted-blocks'),
			array( $this, 'controls_background_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_controls_background_color',
			array( $this, 'controls_background_color_validation' )
		);
		
		add_settings_field(
			'controls_font_color',
			esc_html__( 'Controls Font Color', 'restricted-blocks'),
			array( $this, 'controls_font_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_controls_font_color',
			array( $this, 'controls_font_color_validation' )
		);

		add_settings_field(
			'icons_color',
			esc_html__( 'Icons Color', 'restricted-blocks'),
			array( $this, 'icons_color_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_icons_color',
			array( $this, 'icons_color_validation' )
		);

		add_settings_field(
			'margin_top',
			esc_html__( 'Margin Top', 'restricted-blocks'),
			array( $this, 'margin_top_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_margin_top',
			array( $this, 'margin_top_validation' )
		);

		add_settings_field(
			'margin_bottom',
			esc_html__( 'Margin Bottom', 'restricted-blocks'),
			array( $this, 'margin_bottom_callback' ),
			'daextrebl_style_options',
			'daextrebl_style_settings_section'
		);

		register_setting(
			'daextrebl_style_options',
			'daextrebl_margin_bottom',
			array( $this, 'margin_bottom_validation' )
		);

		//Section Advanced ----------------------------------------------------------
		add_settings_section(
			'daextrebl_advanced_settings_section',
			null,
			null,
			'daextrebl_advanced_options'
		);

		add_settings_field(
			'google_font_url',
			esc_html__( 'Google Font URL', 'restricted-blocks'),
			array( $this, 'google_font_url_callback' ),
			'daextrebl_advanced_options',
			'daextrebl_advanced_settings_section'
		);

		register_setting(
			'daextrebl_advanced_options',
			'daextrebl_google_font_url',
			array( $this, 'google_font_url_validation' )
		);

		add_settings_field(
			'restrictions_menu_capability',
			esc_html__( 'Restrictions Menu Capability', 'restricted-blocks'),
			array( $this, 'restrictions_menu_capability_callback' ),
			'daextrebl_advanced_options',
			'daextrebl_advanced_settings_section'
		);

		register_setting(
			'daextrebl_advanced_options',
			'daextrebl_restrictions_menu_capability',
			array( $this, 'restrictions_menu_capability_validation' )
		);

		add_settings_field(
			'cookie_expiration',
			esc_html__( 'Cookie Expiration', 'restricted-blocks'),
			array( $this, 'cookie_expiration_callback' ),
			'daextrebl_advanced_options',
			'daextrebl_advanced_settings_section'
		);

		register_setting(
			'daextrebl_advanced_options',
			'daextrebl_cookie_expiration',
			array( $this, 'cookie_expiration_validation' )
		);

	}

	//Style Section ----------------------------------------------------------------------------------------------------
	public function font_family_callback( $args ) {

		echo '<input maxlength="2000" type="text" id="daextrebl_font_family" name="daextrebl_font_family" class="regular-text" value="' . esc_attr( get_option( "daextrebl_font_family" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the font family used in the restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function font_family_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( ! preg_match( $this->shared->font_family_regex, $input ) or strlen( $input ) > 2000 ) {
			add_settings_error( 'daextrebl_font_family', 'daextrebl_font_family',
				esc_html__( 'Please enter a valid value in the "Font Family" option.', 'restricted-blocks') );
			$output = get_option( 'daextrebl_font_family' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function container_background_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_container_background_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_container_background_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_container_background_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function container_background_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}
	
	public function title_font_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_title_font_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_title_font_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_title_font_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the restricted block title.', 'restricted-blocks') . '"></div>';

	}

	public function title_font_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}

	public function description_font_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_description_font_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_description_font_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_description_font_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the restricted block description.', 'restricted-blocks') . '"></div>';

	}

	public function description_font_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}

	public function validation_message_background_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_validation_message_background_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_validation_message_background_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_validation_message_background_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the restricted block validation message.', 'restricted-blocks') . '"></div>';

	}

	public function validation_message_background_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}

	public function validation_message_font_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_validation_message_font_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_validation_message_font_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_validation_message_font_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the restricted block validation message.', 'restricted-blocks') . '"></div>';

	}

	public function validation_message_font_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}

	public function controls_label_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_controls_label_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_controls_label_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_controls_label_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the labels displayed restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function controls_label_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}

	public function borders_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_borders_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_borders_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_borders_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the color of the borders displayed in the restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function borders_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}

	public function buttons_background_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_buttons_background_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_buttons_background_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_buttons_background_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the button displayed in the restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function buttons_background_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}

	public function buttons_font_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_buttons_font_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_buttons_font_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_buttons_font_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the button displayed in the restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function buttons_font_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}

	public function controls_background_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_controls_background_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_controls_background_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_controls_background_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the controls displayed in the restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function controls_background_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}
	
	public function controls_font_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_controls_font_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_controls_font_color') .  '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_controls_font_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the controls displayed in the restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function controls_font_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}

	public function icons_color_callback() {

		echo '<input class="wp-color-picker" type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_icons_color') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_icons_color') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_icons_color' ) ) . '" class="color" maxlength="7" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the color of the icons displayed in the restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function icons_color_validation( $input ) {

		return sanitize_hex_color( $input );

	}

	public function margin_top_callback() {

		echo '<input type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_margin_top') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_margin_top') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_margin_top' ) ) . '" class="color" maxlength="6" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the top margin of the restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function margin_top_validation( $input ) {

		return intval( $input, 10 );

	}

	public function margin_bottom_callback() {

		echo '<input type="text" id="' . esc_attr($this->shared->get( 'slug' ) . '_margin_bottom') . '" name="' . esc_attr($this->shared->get( 'slug' ) . '_margin_bottom') . '" value="' . esc_attr( get_option( $this->shared->get( 'slug' ) . '_margin_bottom' ) ) . '" class="color" maxlength="6" size="6" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'This option determines the bottom margin of the restricted block.', 'restricted-blocks') . '"></div>';

	}

	public function margin_bottom_validation( $input ) {

		return intval( $input, 10 );

	}

	//Section Advanced -------------------------------------------------------------------------------------------------
	public function google_font_url_callback( $args ) {

		echo '<input maxlength="2083" type="text" id="daextrebl_google_font_url" name="daextrebl_google_font_url" class="regular-text" value="' . esc_attr( get_option( "daextrebl_google_font_url" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'Load one or more Google Fonts in the front-end of your website by entering the embed code URL in this option.', 'restricted-blocks') . '"></div>';

	}

	public function google_font_url_validation( $input ) {

		$input = esc_url_raw( $input );

		if ( strlen( $input ) > 2083 ) {
			add_settings_error( 'daextrebl_google_font_url', 'daextrebl_google_font_url',
				esc_html__( 'Please enter a valid value in the "Google Font URL" option.', 'restricted-blocks') );
			$output = get_option( 'daextrebl_google_font_url' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function restrictions_menu_capability_callback( $args ) {

		echo '<input maxlength="100" type="text" id="daextrebl_restrictions_menu_capability" name="daextrebl_restrictions_menu_capability" class="regular-text" value="' . esc_attr( get_option( "daextrebl_restrictions_menu_capability" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The capability required to get access on the "Restrictions" menu.', 'restricted-blocks') . '"></div>';

	}

	public function restrictions_menu_capability_validation( $input ) {

		return sanitize_key( $input );

	}

	public function cookie_expiration_callback( $args ) {

		echo '<input maxlength="100" type="text" id="daextrebl_cookie_expiration" name="daextrebl_cookie_expiration" class="regular-text" value="' . esc_attr( get_option( "daextrebl_cookie_expiration" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The expiration of the cookies used to save the state of the restrictions of type "Password".', 'restricted-blocks') . '"></div>';

	}

	public function cookie_expiration_validation( $input ) {

		return intval( $input, 10 );

	}

}