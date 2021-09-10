<?php
/**
 * The all Exceptions list here.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes/api
 */

/**
 * The Onboarding-specific functionality of the plugin admin side.
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes/api
 * @author      makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Wpm_Api_Exception extends Exception {

	/**
	 * Response variable
	 *
	 * @var array $response
	 */
	public $response = array();

	/**
	 * Request variable
	 *
	 * @var array $request
	 */
	public $request = array();

	/**
	 * Response Data variable
	 *
	 * @var array $response_data
	 */
	public $response_data = array();

	/**
	 * Mwb_Wpm_Api_Exception constructor.
	 *
	 * @param string $message Message.
	 * @param int    $code Code.
	 * @param array  $request Request.
	 * @param array  $response Response.
	 * @param object $data Data.
	 */
	public function __construct( $message, $code, $request = null, $response = null, $data = null ) {
		parent::__construct( $message, $code );

		$this->request       = $request;
		$this->response      = $response;
		$this->response_data = $data;
	}

}
