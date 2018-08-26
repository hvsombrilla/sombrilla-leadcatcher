<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://codean.do/
 * @since      1.0.0
 *
 * @package    Sombrilla_Leadcatcher
 * @subpackage Sombrilla_Leadcatcher/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sombrilla_Leadcatcher
 * @subpackage Sombrilla_Leadcatcher/includes
 * @author     Codean.Do <info@codean.do>
 */
class Sombrilla_Leadcatcher_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sombrilla-leadcatcher',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
