<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://windsorup.com/windsor-strava-club-wordpress-plugin/
 * @since      1.0.0
 *
 * @package    Windsor_Strava_Club
 * @subpackage Windsor_Strava_Club/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Windsor_Strava_Club
 * @subpackage Windsor_Strava_Club/admin
 * @author     Justin W Hall <justin@windsorup.com>
 */
class Windsor_Strava_Club_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * plugin options.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the options page for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wsc_register_options_page() {

		// This page will be under "Settings"
		add_options_page(
		    'Settings Admin', 
		    'Windsor Strava Club', 
		    'manage_options', 
		    'wsc-setting-admin', 
		    array( $this, 'wsd_create_admin_page' )
		);

	}

	/**
	 * Creates admin options page.
	 *
	 * @since    1.0.0
	 */
	public function wsd_create_admin_page() {

        $this->options = get_option( 'wsc_options' );
        ?>
        <div class="wrap">
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );   
                do_settings_sections( 'wsc-setting-admin' );
                submit_button(); 
            ?>
            </form>
            Follow me on <a target="blank" href="https://www.strava.com/athletes/533982">Strava</a> or <a target="blank" href="https://twitter.com/justinwhall">Twitter</a>.
        </div>
        <?php

	}

	/**
	 * Registers Plugin Settings
	 *
	 * @since    1.0.0
	 */    
	public function wsc_register_settings() {        
        register_setting(
            'my_option_group', 
            'wsc_options', 
            array( $this, 'sanitize' ) 
        );

        add_settings_section(
            'wsc_options', 
            'Windsor Strava Club Settings', 
            array( $this, 'print_section_info' ), 
            'wsc-setting-admin'
        );   

        add_settings_field(
            'api_key', 
            'Strava API Key',
            array( $this, 'id_number_callback' ), 
            'wsc-setting-admin', 
            'wsc_options'           
        );      

        add_settings_field(
            'gmaps_api_key', 
            'Google Maps API Key',
            array( $this, 'google_maps_api' ), 
            'wsc-setting-admin', 
            'wsc_options'           
        );      
      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
        $new_input = array();

        if( isset( $input['api_key'] ) )
            $new_input['api_key'] = sanitize_text_field( $input['api_key'] );

        if( isset( $input['gmaps_api_key'] ) )
            $new_input['gmaps_api_key'] = sanitize_text_field( $input['gmaps_api_key'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Learn how to get your API key from strava and google mapps <a target="blank" href="http://windsorup.com/windsor-strava-club-wordpress-plugin/">here</a>.';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="api_key" name="wsc_options[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
    }

    public function google_maps_api()
    {
        printf(
            '<input type="text" id="gmaps_api_key" name="wsc_options[gmaps_api_key]" value="%s" />',
            isset( $this->options['gmaps_api_key'] ) ? esc_attr( $this->options['gmaps_api_key']) : ''
        );
    }



}