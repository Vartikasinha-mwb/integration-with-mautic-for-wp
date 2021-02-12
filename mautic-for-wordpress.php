<?php
/**
 * Plugin main file.
 *
 * @link    https://makewebbetter.com/
 * @since   1.0.0
 * @package Mautic_For_Wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       Mautic For WordPress
 * Plugin URI:        https://makewebbetter.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mautic-for-wordpress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mautic-for-wordpress-activator.php
 */
function activate_mautic_for_wordpress() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-mautic-for-wordpress-activator.php';
	Mautic_For_Wordpress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mautic-for-wordpress-deactivator.php
 */
function deactivate_mautic_for_wordpress() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-mautic-for-wordpress-deactivator.php';
	Mautic_For_Wordpress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mautic_for_wordpress' );
register_deactivation_hook( __FILE__, 'deactivate_mautic_for_wordpress' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mautic-for-wordpress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_mautic_for_wordpress() {
	mwb_m4wp_define_plugin_constants();
	$plugin = new Mautic_For_Wordpress();
	$plugin->run();
}
run_mautic_for_wordpress();

/**
 * Define plugin constants.
 *
 * @since 1.0.0
 */
function mwb_m4wp_define_plugin_constants() {
	$constants = array(
		'MWB_M4WP_VERSION'     => '1.0.0',
		'MWB_M4WP_PLUGIN_PATH' => plugin_dir_path( __FILE__ ),
		'MWB_M4WP_PLUGIN_URL'  => plugin_dir_url( __FILE__ ),
	);
	array_walk( $constants, 'mwb_m4wp_define_constant' );
	define( 'ONBOARD_PLUGIN_NAME', 'Mautic For Wordpress' );
}

/**
 * Check and define single constant.
 *
 * @since 1.0.0
 * @param string $value constant value.
 * @param string $key constant key.
 */
function mwb_m4wp_define_constant( $value, $key ) {
	if ( ! defined( $key ) ) {
		define( $key, $value );
	}
}
