<?php
/**
 * The Description of Oauth2 authentication here.
 *
 * @link       https://makewebbetter.com
 * @since      3.0.0
 *
 * @package     MWB_Mautic_For_WP
 * @subpackage  MWB_Mautic_For_WP/includes
 */

/**
 * The Onboarding-specific functionality of the plugin admin side.
 *
 * @package     MWB_Mautic_For_WP
 * @subpackage  MWB_Mautic_For_WP/includes
 * @author      makewebbetter <webmaster@makewebbetter.com>
 */
class Oauth2 extends Api_Base {

	/**
	 * Client_id variable
	 *
	 * @var string $client_id
	 */
	private $client_id;
	/**
	 * Client_sceret variable
	 *
	 * @var string $client_sceret
	 */
	private $client_sceret;
	/**
	 * Acess_token variable
	 *
	 * @var string $acess_token
	 */
	private $acess_token;
	/**
	 * Refresh_token variable
	 *
	 * @var string $refresh_token
	 */
	private $refresh_token;
	/**
	 * Exprire_in variable
	 *
	 * @var string $exprire_in
	 */
	private $exprire_in;
	/**
	 * _instance variable
	 *
	 * @var string $_instance
	 */
	private static $_instance = null;

	/**
	 * Get_instance.
	 *
	 * @return string $_instance Instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Get_instance.
	 *
	 * @return Authorization.
	 */
	public function is_authorized() {
		return get_option( 'mwb_m4wp_oauth2_success', false );
	}

	/**
	 * Set_access_token.
	 */
	public function set_access_token() {
		$token_data        = get_option( 'mwb_m4wp_token_data', array() );
		$this->acess_token = $token_data['access_token'];
	}

	/**
	 * Have active access token.
	 *
	 * @return bool Have active access token.
	 */
	public function have_active_access_token() {
		$token_data = get_option( 'mwb_m4wp_token_data', array() );
		if ( isset( $token_data['expires_in'] ) && $token_data['expires_in'] > time() ) {
			return true;
		}
		return false;
	}

	/**
	 * Have valid API keys.
	 *
	 * @return mixed Valid API keys.
	 */
	public function have_valid_api_keys() {
		$credentials = get_option( 'mwb_m4wp_auth_details', array() );
		if ( isset( $credentials['client_id'] ) && '' !== $credentials['client_id'] &&
		isset( $credentials['client_secret'] ) && '' !== $credentials['client_secret'] ) {
			return array(
				'client_id'     => $credentials['client_id'],
				'client_secret' => $credentials['client_secret'],
			);
		}
		return false;
	}

	/**
	 * Get refresh token.
	 *
	 * @return mixed Token data.
	 */
	public function get_refresh_token() {
		$token_data = get_option( 'mwb_m4wp_token_data', array() );
		if ( isset( $token_data['refresh_token'] ) ) {
			return $token_data['refresh_token'];
		}
		return false;
	}

	/**
	 * Save_token_data.
	 *
	 * @param array $response Response.
	 */
	public function save_token_data( $response ) {
		if ( isset( $response['access_token'] ) ) {
			$token_data = array(
				'access_token'  => $response['access_token'],
				'refresh_token' => $response['refresh_token'],
				'expires_in'    => time() + $response['expires_in'],
			);
			update_option( 'mwb_m4wp_token_data', $token_data );
		}
	}

	/**
	 * Mautic_Api_Exception constructor.
	 *
	 * @param mixed $data Data.
	 * @return string Auth token.
	 */
	public function get_oauth_token( $data ) {
		$endpoint = 'oauth/v2/token';
		return $this->post( $endpoint, $data );
	}

	/**
	 * Mautic_Api_Exception constructor.
	 *
	 * @param object $data Data.
	 * @throws Mautic_Api_Exception Mautic_Api_Exception.
	 */
	public function renew_access_token( $data ) {
		$endpoint = 'oauth/v2/token';
		$response = $this->post( $endpoint, $data );
		if ( isset( $response['errors'] ) ) {
			update_option( 'mwb_m4wp_oauth2_success', false );
			throw new Mautic_Api_Exception( 'Something went wrong', 003 );
		}
		$this->save_token_data( $response );
		update_option( 'mwb_m4wp_oauth2_success', true );
	}

	/**
	 * Get headers.
	 *
	 * @return array
	 */
	public function get_auth_header() {
		$headers = array(
			'Authorization' => sprintf( 'Bearer %s', $this->acess_token ),
		);
		return $headers;
	}
	/**
	 * Mautic_Api_Exception constructor.
	 *
	 * @param object $base_url Base URL.
	 */
	public function set_base_url( $base_url ) {
		$this->base_url = $base_url;
	}
}
