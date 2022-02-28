<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Wp_Mautic_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       Integration with Mautic for WP
 * Plugin URI:        https://wordpress.org/plugins/wp-mautic-integration/
 * Description:       Simple plugin to integrate your WordPress site with mautic marketing automation. Add tracking script, mautic forms to your site. Integrate your worpdress registration and comment form with mautic.
 * Version:           1.0.4
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/
 * Requires at least: 4.4
 * Tested up to:      5.8.2
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       wp-mautic-integration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-mautic-integration-activator.php
 */
function activate_wp_mautic_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mautic-integration-activator.php';
	Wp_Mautic_Integration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-mautic-integration-deactivator.php
 */
function deactivate_wp_mautic_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mautic-integration-deactivator.php';
	Wp_Mautic_Integration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_mautic_integration' );
register_deactivation_hook( __FILE__, 'deactivate_wp_mautic_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-mautic-integration.php';

/**
 * Add settings links in plugin listing.
 */
add_filter( 'plugin_action_links', 'mwb_wpm_admin_settings', 10, 2 );

/**
 * Add settings link in plugin listing.
 *
 * @since    1.0.0
 * @param array  $actions actions.
 * @param string $plugin_file plugin file path.
 * @return array - Actions links
 */
function mwb_wpm_admin_settings( $actions, $plugin_file ) {
	static $plugin;
	if ( ! isset( $plugin ) ) {
		$plugin = plugin_basename( __FILE__ );
	}
	if ( $plugin === $plugin_file ) {
		$settings = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=mwb-wp-mautic' ) . '">' . __( 'Settings', 'wp-mautic-integration' ) . '</a>',
		);
		$actions  = array_merge( $settings, $actions );
	}
	return $actions;
}

// Discontinue notice.
add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), 'mwb_wmi_add_discontinue_notice', 0, 3 );

/**
 * Begins execution of the plugin.
 *
 * @param mixed $plugin_file The plugin file name.
 * @param mixed $plugin_data The plugin file data.
 * @param mixed $status      The plugin file status.
 * @since 1.0.0
 */
function mwb_wmi_add_discontinue_notice( $plugin_file, $plugin_data, $status ) {
	include_once plugin_dir_path( __FILE__ ) . 'extra-templates/makewebetter-plugin-discontinue-notice.html';
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
function run_wp_mautic_integration() {

	mwb_wp_mautic_define_plugin_constants();
	$plugin = new Wp_Mautic_Integration();
	$plugin->run();

}

run_wp_mautic_integration();


/**
 * Define plugin constants.
 *
 * @since 1.0.0
 */
function mwb_wp_mautic_define_plugin_constants() {
	$constants = array(
		'MWB_WP_MAUTIC_VERSION' => '1.0.4',
		'MWB_WP_MAUTIC_PATH'    => plugin_dir_path( __FILE__ ),
		'MWB_WP_MAUTIC_URL'     => plugin_dir_url( __FILE__ ),
	);
	array_walk( $constants, 'mwb_wp_mautic_define_constant' );
}

/**
 * Check and define single constant.
 *
 * @since 1.0.0
 * @param string $value constant value.
 * @param string $key constant key.
 */
function mwb_wp_mautic_define_constant( $value, $key ) {
	if ( ! defined( $key ) ) {
		define( $key, $value );
	}
}
