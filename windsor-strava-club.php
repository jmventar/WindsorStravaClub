<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://justinwhall.com
 * @since             1.0
 * @package           Windsor Strava Club
 *
 * @wordpress-plugin
 * Plugin Name:       Windsor Strava Club
 * Plugin URI:        https://windsorup.com/windsor-strava-club-wordpress-plugin/
 * Description:       Displays your Strava Club's rides, stats and awesomeness on your WordPress Site.
 * Version:           1.0.12
 * Author:            Justin W Hall
 * Author URI:        https://windsorup.com/windsor-strava-club-wordpress-plugin/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       windsor-strava-club
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Create a helper function for easy SDK access.
function wsc_fs() {
    global $wsc_fs;

    if ( ! isset( $wsc_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $wsc_fs = fs_dynamic_init( array(
            'id'                  => '1367',
            'slug'                => 'windsor-strava-club',
            'type'                => 'plugin',
            'public_key'          => 'pk_34c76c231e969de15e0f338f9c5f4',
            'is_premium'          => false,
            'has_addons'          => false,
            'has_paid_plans'      => false,
            'menu'                => array(
                'slug'           => 'wsc-setting-admin',
                'parent'         => array(
                    'slug' => 'options-general.php',
					'support'        => false
                ),
            ),
        ) );
    }

    return $wsc_fs;
}

// Init Freemius.
wsc_fs();
// Signal that SDK was initiated.
do_action( 'wsc_fs_loaded' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_windsor_strava_club() {
	$wsc_options = get_option('wsc_options');

	if ($wsc_options == false) {
		$wsc_options = array();
	}

	if (!array_key_exists('api_key', $wsc_options)) {
		$wsc_options['api_key'] = '';
	}
	if (!array_key_exists('gmaps_api_key', $wsc_options)) {
		$wsc_options['gmaps_api_key'] = '';
	}

	update_option( 'wsc_options', $wsc_options );


}

register_activation_hook( __FILE__, 'activate_windsor_strava_club' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-windsor-strava-club.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_windsor_strava_club() {

	$plugin = new Windsor_Strava_Club();
	$plugin->run();

}
run_windsor_strava_club();
