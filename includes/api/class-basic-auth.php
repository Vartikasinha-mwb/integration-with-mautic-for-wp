<?php
/**
 * The Description of basic authentication here.
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
class Basic_Auth extends Api_Base {

	// mautic user_name.
	/**
	 * User Name variable
	 *
	 * @var string $user_name
	 */
	private $user_name;

	// mautic password.
	/**
	 * Password variable
	 *
	 * @var string $password
	 */
	private $password;

	/**
	 * Constructor.
	 *
	 * @param string $base_url Base url of your mautic instance.
	 * @param string $user_name Mautic user name.
	 * @param string $password Mautic password.
	 */
	public function __construct( $base_url, $user_name, $password ) {

		$this->base_url  = $base_url;
		$this->user_name = $user_name;
		$this->password  = $password;
	}

	/**
	 * Get headers.
	 *
	 * @return array
	 */
	public function get_auth_header() {
		// phpcs:disable
		$headers = array(
			'Authorization' => sprintf( 'Basic %s', base64_encode( $this->user_name . ':' . $this->password ) ),
		);
		// phpcs:enable
		return $headers;
	}
}
