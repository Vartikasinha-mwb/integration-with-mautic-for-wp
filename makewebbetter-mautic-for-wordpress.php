<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Makewebbetter_Mautic_For_Wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       Integration with Mautic for WordPress
 * Plugin URI:        https://makewebbetter.com/
 * Description:       Simple plugin to integrate your WordPress site with mautic marketing automation. Add tracking script, mautic forms to your site. Integrate your worpdress registration and comment form with mautic.
 * Version:           1.0.0
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       makewebbetter-mautic-for-wordpress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mwb-mautic-for-wp-activator.php
 */
function activate_mwb_mautic_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-mautic-for-wp-activator.php';
	MWB_Mautic_For_WP_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mwb-mautic-for-wp-deactivator.php
 */
function deactivate_mwb_mautic_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-mautic-for-wp-deactivator.php';
	MWB_Mautic_For_WP_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mwb_mautic_for_wp' );
register_deactivation_hook( __FILE__, 'deactivate_mwb_mautic_for_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mwb-mautic-for-wp.php';

/**
 * Add settings links in plugin listing.
 */
add_filter( 'plugin_action_links', 'mwb_mautic_admin_settings', 10, 5 );

/**
 * Add settings link in plugin listing.
 *
 * @since    1.0.0
 * @param array  $actions actions.
 * @param string $plugin_file plugin file path.
 */
function mwb_mautic_admin_settings( $actions, $plugin_file ) {
	static $plugin;
	if ( ! isset( $plugin ) ) {
		$plugin = plugin_basename( __FILE__ );
	}
	if ( $plugin === $plugin_file ) {
		$settings = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=mwb-mautic-for-wp' ) . '">' . __( 'Settings', 'mauwoo' ) . '</a>',
		);
		$actions  = array_merge( $settings, $actions );
	}
	return $actions;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mwb_mautic_for_wp() {
	mwb_mautic_for_wp_define_plugin_constants();
	$plugin = new MWB_Mautic_For_WP();
	$plugin->run();

}
run_mwb_mautic_for_wp();

/**
 * Define plugin constants.
 *
 * @since 1.0.0
 */
function mwb_mautic_for_wp_define_plugin_constants() {
	$constants = array(
		'MWB_MAUTIC_FOR_WP_VERSION' => '1.0.0',
		'MWB_MAUTIC_FOR_WP_PATH'    => plugin_dir_path( __FILE__ ),
		'MWB_MAUTIC_FOR_WP_URL'     => plugin_dir_url( __FILE__ ),
	);
	array_walk( $constants, 'mwb_mautic_for_wp_define_constant' );
	define( 'ONBOARD_PLUGIN_NAME', 'Integration with Mautic for WordPress' );
}

/**
 * Check and define single constant.
 *
 * @since 1.0.0
 * @param string $value constant value.
 * @param string $key constant key.
 */
function mwb_mautic_for_wp_define_constant( $value, $key ) {
	if ( ! defined( $key ) ) {
		define( $key, $value );
	}
}
