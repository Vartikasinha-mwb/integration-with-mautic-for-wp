<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    MWB_Mautic_For_WP
 * @subpackage MWB_Mautic_For_WP/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    MWB_Mautic_For_WP
 * @subpackage MWB_Mautic_For_WP/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MWB_Mautic_For_WP_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'makewebbetter-mautic-for-wordpress',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
