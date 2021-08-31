<?php
/**
 * Written all the API's data, how they worked n all.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MWB_Wpm_Api {

	/**
	 * Mautic API
	 *
	 * @var [type]
	 */
	private static $mautic_api;

	/**
	 * Create or update contact in mautic.
	 *
	 * @param array $data Contact data.
	 * @return bool
	 */
	public static function create_contact( $data ) {
		$endpoint   = 'api/contacts/new';
		$mautic_api = self::get_mautic_api();
		if ( ! $mautic_api ) {
			return;
		}
		$headers = $mautic_api->get_auth_header();
		return $mautic_api->post( $endpoint, $data, $headers );
	}

	/**
	 * Returns response.
	 *
	 * @since       1.0.0
	 * @return mixed $response Respose is returned.
	 */
	public static function get_self_user() {
		$response = array(
			'success' => false,
			'user'    => '',
			'msg'     => '',
		);
		try {
			$endpoint   = 'api/users/self';
			$mautic_api = self::get_mautic_api();
			$headers    = $mautic_api->get_auth_header();
			$user_data  = $mautic_api->get( $endpoint, array(), $headers );
			update_option( 'mwb_m4wp_connection_status', true );
			$response['success'] = true;
			$response['user']    = isset( $user_data['email'] ) ? $user_data['email'] : '';
			$response['msg']     = 'Success';
		} catch ( Exception $e ) {
			update_option( 'mwb_m4wp_connection_status', false );
			update_option( 'mwb_m4wp_oauth2_success', false );
			$response['msg'] = $e->getMessage();
		}
		return $response;
	}

	/**
	 * Get_mautic_api.
	 *
	 * @since       1.0.0
	 * @return mixed $response Respose is returned.
	 * @throws Mwb_Wpm_Api_Exception Mwb_Wpm_Api_Exception .
	 */
	public static function get_mautic_api() {

		// @todo get details wp options
		if ( ! empty( self::$mautic_api ) ) {
			return self::$mautic_api;
		}
		$authentication_type = get_option( 'mwb_m4wp_auth_type', 'basic' );
		$base_url            = get_option( 'mwb_m4wp_base_url', '' );
		if ( '' === $base_url ) {
			throw new Mwb_Wpm_Api_Exception( 'Missing base url', 001 );
		}

		if ( 'oauth2' === $authentication_type ) {
			$api_instance = Mwb_Wpm_Oauth2::get_instance();
			if ( ! $api_instance->is_authorized() ) {
				if ( ! ( $api_instance->have_valid_api_keys() ) ) {
					$api_keys = $api_instance->have_valid_api_keys();
					throw new Mwb_Wpm_Api_Exception( 'Missing api credentials', 002 );
				}
			}
			$api_instance->set_base_url( $base_url );
			if ( ! $api_instance->have_active_access_token() ) {
				if ( ! ( $api_instance->have_valid_api_keys() ) ) {
					$api_keys = $api_instance->have_valid_api_keys();
					throw new Mwb_Wpm_Api_Exception( 'Missing api credentials', 002 );
				}
				$refresh_token = $api_instance->get_refresh_token();
				if ( ! $refresh_token ) {
					throw new Mwb_Wpm_Api_Exception( 'Missing refresh token', 003 );
				}
				$api_keys['refresh_token'] = $refresh_token;
				$redirct_url               = admin_url();
				$api_keys['redirect_uri']  = $redirct_url;
				$api_keys['grant_type']    = 'refresh_token';
				$api_instance->renew_access_token( $api_keys );
			}
			$api_instance->set_access_token();
			self::$mautic_api = $api_instance;
			return $api_instance;
		} else {
			$credentials = get_option( 'mwb_m4wp_auth_details', array() );
			$username    = isset( $credentials['username'] ) ? $credentials['username'] : '';
			$password    = isset( $credentials['password'] ) ? $credentials['password'] : '';
			if ( ! empty( $base_url ) && ! empty( $username ) && ! empty( $password ) ) {
				self::$mautic_api = new Mwb_Wpm_Basic_Auth( $base_url, $username, $password );
				return self::$mautic_api;
			} else {
				throw new Mwb_Wpm_Api_Exception( 'Missing Api Details', 006 );
			}
		}

		return false;
	}

	/**
	 * Get segments
	 *
	 * @return mixed - get Segments.
	 */
	public static function get_segments() {

		try {
			$endpoint   = 'api/segments';
			$mautic_api = self::get_mautic_api();
			$headers    = $mautic_api->get_auth_header();
			return $mautic_api->get( $endpoint, array(), $headers );
		} catch ( Exception $e ) {
			return false;
		}

	}

	/**
	 * Add contact to a segment.
	 *
	 * @param int $contact_id Contact data.
	 * @param int $segment_id Segment data.
	 * @return bool
	 */
	public static function add_contact_to_segment( $contact_id, $segment_id ) {
		$endpoint   = "api/segments/$segment_id/contact/$contact_id/add";
		$mautic_api = self::get_mautic_api();
		if ( ! $mautic_api ) {
			return;
		}
		$headers = $mautic_api->get_auth_header();
		return $mautic_api->post( $endpoint, array(), $headers );
	}

	/**
	 * Get mautic forms
	 *
	 * @return mixed - get Mautic forms.
	 */
	public static function get_forms() {
		$endpoint = 'api/forms';
		try {
			$mautic_api = self::get_mautic_api();
			$headers    = $mautic_api->get_auth_header();
			return $mautic_api->get( $endpoint, array(), $headers );
		} catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * Get all widgets
	 *
	 * @return mixed - get Widgets.
	 */
	public static function get_widgets() {
		$endpoint = 'api/data';
		try {
			$mautic_api = self::get_mautic_api();
			$headers    = $mautic_api->get_auth_header();
			return $mautic_api->get( $endpoint, array(), $headers );
		} catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * Get Widget data.
	 *
	 * @param int $widget_name Widget name.
	 * @param int $data data.
	 * @return mixed - get Widget data.
	 */
	public static function get_widget_data( $widget_name, $data ) {
		$endpoint = "api/data/$widget_name";
		try {
			$mautic_api = self::get_mautic_api();
			$headers    = $mautic_api->get_auth_header();
			return $mautic_api->get( $endpoint, $data, $headers );
		} catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * Get tags
	 */
	public static function get_tags() {
		$endpoint   = 'api/tags';
		$mautic_api = self::get_mautic_api();
		if ( ! $mautic_api ) {
			return;
		}
		$headers = $mautic_api->get_auth_header();
		return $mautic_api->get( $endpoint, array(), $headers );
	}

	/**
	 * Add_points.
	 *
	 * @param int $contact_id Id of the contact.
	 * @param int $points points.
	 * @return bool
	 */
	public static function add_points( $contact_id, $points ) {
		$endpoint   = "api/contacts/$contact_id/points/plus/$points";
		$mautic_api = self::get_mautic_api();
		if ( ! $mautic_api ) {
			return;
		}
		$headers = $mautic_api->get_auth_header();
		return $mautic_api->post( $endpoint, array(), $headers );
	}
}
