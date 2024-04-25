<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://atcapacity.com
 * @since      1.0.0
 *
 * @package    Atcap_Geolocation
 * @subpackage Atcap_Geolocation/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Atcap_Geolocation
 * @subpackage Atcap_Geolocation/includes
 * @author     Atcapacity <alan@atcapacity.com>
 */
class Atcap_Geolocation_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'atcap-geolocation',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
