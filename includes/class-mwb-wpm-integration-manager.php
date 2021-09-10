<?php
/**
 * Manage the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MWB_Wpm_Integration_Manager {

	/**
	 * Initialize_active_integrations.
	 *
	 * @since    1.0.0
	 */
	public static function initialize_active_integrations() {

		if ( ! self::get_connection_status() ) {
			return;
		}
		$integrations = self::get_integrations();
		foreach ( $integrations as $key => $details ) {
			$integration = self::get_integration( $details );
			if ( ! $integration ) {
				continue;
			}
			if ( $integration->is_active() && $integration->is_enabled() ) {
				$integration->initialize();
			}
		}
	}

	/**
	 * Get_integration.
	 *
	 * @since    1.0.0
	 * @param array $details     Detail for integration.
	 * @return mixed - integration
	 */
	public static function get_integration( $details ) {
		extract( $details ); //phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		if ( empty( $class ) || empty( $path ) || empty( $id ) ) {
			return false;
		}
		$file_path = MWB_WP_MAUTIC_PATH . 'includes/integrations/' . $path;

		$file_path = apply_filters( 'mwb_m4wp_integration_path', $file_path );

		if ( file_exists( $file_path ) ) {
			require_once $file_path;
			$all_settings = get_option( 'mwb_m4wp_integration_settings', array() );
			$settings     = isset( $all_settings[ $id ] ) ? $all_settings[ $id ] : array();
			$integration  = new $class( $id, $settings );
			return $integration;
		}
		return false;
	}

	/**
	 * Get_integrations.
	 *
	 * @param array $key     Key for integration.
	 * @since    1.0.0
	 */
	public static function get_integrations( $key = '' ) {

		$integrations = array(
			'mwb_m4wp_registration' => array(
				'class' => 'Mwb_Wpm_Registration_Form',
				'path'  => 'class-mwb-wpm-registration-form.php',
				'id'    => 'mwb_m4wp_registration',
			),
			'mwb_m4wp_comment'      => array(
				'class' => 'Mwb_Wpm_Comment_Form',
				'path'  => 'class-mwb-wpm-comment-form.php',
				'id'    => 'mwb_m4wp_comment',
			),
		);

		if ( in_array( 'user-registration/user-registration.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

			$integrations['mwb_m4wp_user_registration_plugin'] = array(
				'class' => 'Mwb_Wpm_User_Registration_Plugin_Form',
				'path'  => 'class-mwb-wpm-user-registration-plugin-form.php',
				'id'    => 'mwb_m4wp_user_registration_plugin',
			);

		}

		$integrations = apply_filters( 'mwb_m4wp_available_integrations', $integrations );

		if ( '' !== $key && isset( $integrations[ $key ] ) ) {
			return $integrations[ $key ];
		}

		return $integrations;
	}

	/**
	 * Initialize_active_integrations.
	 *
	 * @since    1.0.0
	 */
	public static function get_connection_status() {
		return get_option( 'mwb_m4wp_connection_status', false );
	}
}
