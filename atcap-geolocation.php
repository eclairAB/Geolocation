<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://atcapacity.com
 * @since             1.0.0
 * @package           Atcap_Geolocation
 *
 * @wordpress-plugin
 * Plugin Name:       Geolocations
 * Plugin URI:        https://atcapacity.com
 * Description:       Plugin for finding states and city in trailblazer accounts
 * Version:           1.0.0
 * Author:            Atcapacity
 * Author URI:        https://atcapacity.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       atcap-geolocation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ATCAP_GEOLOCATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-atcap-geolocation-activator.php
 */
function activate_atcap_geolocation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-atcap-geolocation-activator.php';
	Atcap_Geolocation_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-atcap-geolocation-deactivator.php
 */
function deactivate_atcap_geolocation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-atcap-geolocation-deactivator.php';
	Atcap_Geolocation_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_atcap_geolocation' );
register_deactivation_hook( __FILE__, 'deactivate_atcap_geolocation' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-atcap-geolocation.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_atcap_geolocation() {

	$plugin = new Atcap_Geolocation();
	$plugin->run();

}

require_once plugin_dir_path(__FILE__) . 'includes/geolocations.php';

function display_city_and_state() {
	// GEO_KEY defined on wp-config.php
    $geoService = new GeoService(GEO_KEY); 

    $city_and_state_params = [
        // 'state' => 'New York',
        // 'city' => 'New York',
        'postal_code' => '10001',
    ];

    $result = $geoService->getCityAndState($city_and_state_params);

	echo json_encode($result);
	echo "<hr/>";
	echo json_encode($geoService->getCoordinates());
}

add_action('init', 'display_city_and_state');


run_atcap_geolocation();
