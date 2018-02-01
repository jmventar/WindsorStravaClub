<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://windsorup.com/windsor-strava-club-wordpress-plugin/
 * @since      1.0.0
 *
 * @package    Windsor_Strava_Club
 * @subpackage Windsor_Strava_Club/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Windsor_Strava_Club
 * @subpackage Windsor_Strava_Club/public
 * @author     Justin W Hall <justin@windsorup.com>
 */
class Windsor_Strava_Club_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/windsor-strava-club-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$wsc_options = get_option('wsc_options');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/windsor-strava-club-public.js', array( 'jquery' ), $this->version, false );
		if(array_key_exists('gmaps_api_key', $wsc_options)){
			wp_enqueue_script( 'windsor_google_maps', '//maps.google.com/maps/api/js?libraries=geometry&key='. $wsc_options['gmaps_api_key'] , array( 'jquery', $this->plugin_name ), '', false );
		}
		wp_enqueue_script( 'windsor_rich_marker', plugin_dir_url( __FILE__ ) . 'js/richmarker-compiled.js', array('windsor_google_maps', 'jquery', $this->plugin_name ), '', false );
		wp_enqueue_script( 'moment_js', plugin_dir_url( __FILE__ ) . 'js/moment-with-locales.min.js', array('windsor_google_maps', 'jquery', $this->plugin_name ), '', false );		
	}

	/**
	 * render the shortcode.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $atts // shortcode options
	 * @return string
	 */
	public static function wsd_render_club_page( $atts ) {
		$wsc_options = get_option('wsc_options');

		if ($wsc_options['api_key'] && $wsc_options['gmaps_api_key']){
			// Provide defualts. No state? You get Colorado!
			$atts = shortcode_atts(
				array(
					'clubid' => 132130,
					'zoom' => 8,
					'lat' => 39.7469,
					'lng' => -105.2108
				), $atts );

			$url='https://www.strava.com/api/v3/clubs/' . $atts['clubid'] . '/activities?per_page=200';
			$headers = array('Authorization: Bearer ' . $wsc_options['api_key']);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$rides = curl_exec($ch);

			$url="https://www.strava.com/api/v3/clubs/" . $atts['clubid'];
			$headers = array('Authorization: Bearer ' . $wsc_options['api_key']);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$club = curl_exec($ch);
			$club = json_decode($club);
			// Add WP locale to attributes
			$atts['locale'] = get_locale();

			// We'll be sending this array to the client
			$atts = json_encode($atts);

			ob_start();
			?>

			<div class="ride-stats">
				<?php if ($club->profile): ?>
					<?php if($club->profile_medium):?>
							<img src="<?php echo $club->profile_medium; ?>" >
					<?php endif; ?>
					<?php echo $club->name; ?> activities since <span class="wsc-date"></span>
				<?php endif ?>
			</div>
			<div id="wsc">
				<div id="map"></div>
			</div>
			<script>
				jQuery(document).ready(function($) {
					// Use WP locale also
					WindsorStravaClub.initMap( <?php echo $rides; ?>, <?php echo $atts; ?>);
				});
			</script>



			<?php
			return ob_get_clean();
		}
		else{
			ob_start(); ?>
			<div>No API Key Saved. How to do this is explained <a href="http://windsorup.com/windsor-strava-club-wordpress-plugin/">here.</a></div>
			<div>Questions, comments and support should be directed <a href="https://wordpress.org/support/plugin/windsor-strava-club">here.</a></div>
			<?php
			return ob_get_clean();
		}
	}

}
