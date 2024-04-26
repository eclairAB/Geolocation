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

// Enqueue JavaScript file
function display_city_and_state_enqueue_scripts() {
    wp_enqueue_script('city-and-states', plugin_dir_url(__FILE__) . 'js/city-and-states.js', array('jquery'));
    wp_localize_script('city-and-states', 'city_state_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));

    add_action('wp_ajax_get_city_and_state', 'get_city_and_state_ajax');
    add_action('wp_ajax_nopriv_get_city_and_state', 'get_city_and_state_ajax');
}
add_action('wp_enqueue_scripts', 'display_city_and_state_enqueue_scripts');


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

	echo json_encode($geoService->getCoordinates()) . ' : [detect location]';
	echo '<hr/>';
	echo json_encode($result);
}

add_action('init', 'display_city_and_state');

function get_city_and_state_ajax() {

    $query = [];
    if(isset($_POST['state'])) {
        $query['state'] = sanitize_text_field($_POST['state']);
    }
    if(isset($_POST['city'])) {
        $query['city'] = sanitize_text_field($_POST['city']);
    }
    if(isset($_POST['postal_code'])) {
        $query['postal_code'] = sanitize_text_field($_POST['postal_code']);
    }

    $geoService = new GeoService(GEO_KEY);
    $result = $geoService->getCityAndState($query);

    // Output the city and state
    echo json_encode($result);

    wp_die();
}

// Add a form with text input on every page and post
function display_city_and_state_form() {
    ?>
    <br><br>
    <form id="city-state-form" method="post">
        <label for="state-input">Enter State Name:</label><br>
        <input id="state-input" type="text"><br>

        <label for="city-input">Enter City Name:</label><br>
        <input id="city-input" type="text"><br>

        <label for="postal-code-input">Enter Postal Code:</label><br>
        <input id="postal-code-input" type="text"><br>

        <input type="submit" value="Submit">
    </form>
    <div id="city-state-result"></div>
    <?php
}
add_action('wp_head', 'display_city_and_state_form');


run_atcap_geolocation();
