<?php
/**
 * The all API's list here.
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
class Api_Base {
	/**
	 * Base URL variable
	 *
	 * @var string $base_url
	 */
	public $base_url;
	/**
	 * Last Request variable
	 *
	 * @var string $last_request
	 */
	private $last_request;
	/**
	 * Last Response variable
	 *
	 * @var string $last_response
	 */
	private $last_response;

	/**
	 * Parse response and get back the data
	 *
	 * @param array $response HTTP response.
	 * @throws Mautic_Api_Exception Mautic_Api_Exception.
	 */
	private function parse_response( $response ) {
		if ( $response instanceof WP_Error ) {
			$message = 'Something went wrong, Please check your credentials';
			throw new Mautic_Api_Exception( $message, 0 );
		}
		// decode response body.
		$code    = (int) wp_remote_retrieve_response_code( $response );
		$message = wp_remote_retrieve_response_message( $response );
		$body    = wp_remote_retrieve_body( $response );
		$data    = json_decode( $body, ARRAY_A );

		$this->create_error_log( $code, $message, $data );

		if ( 403 === $code && 'Forbidden' === $message ) {
			throw new Mautic_Api_Exception( $message, $code );
		}

		if ( 401 === $code ) {
			throw new Mautic_Api_Exception( $message, $code );
		}

		if ( 0 === $code ) {
			$message = 'Something went wrong, Please check your credentials';
			throw new Mautic_Api_Exception( $message, $code );
		}

		return $data;
	}

	/**
	 * Log error
	 *
	 * @param string $code Http response code.
	 * @param string $message Response message.
	 * @param array  $data Reponse data.
	 */
	public function create_error_log( $code, $message, $data = array() ) {
		$file = MWB_MAUTIC_FOR_WP_PATH . '/error.log';
		$log  = 'Url : ' . $this->last_request['url'] . PHP_EOL;
		$log .= 'Method : ' . $this->last_request['method'] . PHP_EOL;
		$log .= "Code : $code" . PHP_EOL;
		$log .= "Message : $message" . PHP_EOL;
		if ( isset( $data['errors'] ) && is_array( $data['errors'] ) ) {
			foreach ( $data['errors'] as $key => $value ) {
				$log .= 'Error : ' . $value['message'] . PHP_EOL;
			}
			$log .= 'Response: ' . wp_json_encode( $this->last_response ) . PHP_EOL;
			$log .= 'Req: ' . wp_json_encode( $this->last_request ) . PHP_EOL;
		}
		$log .= 'Time: ' . current_time( 'F j, Y  g:i a' ) . PHP_EOL;
		$log .= '------------------------------------' . PHP_EOL;
		//phpcs:disable
		file_put_contents( $file, $log, FILE_APPEND );
		//phpcs:enable
	}

	/**
	 * Reset last request data
	 */
	private function reset_request_data() {
		$this->last_request  = '';
		$this->last_response = '';
	}

	/**
	 * Get Request.
	 *
	 * @param string $endpoint Api endpoint of mautic.
	 * @param array  $data Data to be used in request.
	 * @param array  $headers header to be used in request.
	 */
	public function get( $endpoint, $data = array(), $headers = array() ) {
		return $this->request( 'GET', $endpoint, $data, $headers );
	}

	/**
	 * Post Request.
	 *
	 * @param string $endpoint Api endpoint of mautic.
	 * @param array  $data Data to be used in request.
	 * @param array  $headers header to be used in request.
	 */
	public function post( $endpoint, $data = array(), $headers = array() ) {
		return $this->request( 'POST', $endpoint, $data, $headers );
	}

	/**
	 * Get headers.
	 *
	 * @return array
	 */
	private function get_headers() {
		global $wp_version;
		$headers = array(
			'User-Agent' => sprintf( 'MWB_M4WP/%s; WordPress/%s; %s', MWB_MAUTIC_FOR_WP_VERSION, $wp_version, home_url() ),
		);
		// Copy Accept-Language from browser headers.
		if ( ! empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
			$headers['Accept-Language'] = sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) );
		}
		return $headers;
	}

	/**
	 * Send mautic api request
	 *
	 * @param string $method   HTTP method.
	 * @param string $endpoint Api endpoint.
	 * @param array  $data     Request data.
	 * @param array  $headers header to be used in request.
	 */
	private function request( $method, $endpoint, $data = array(), $headers = array() ) {

		$this->reset_request_data();
		$method  = strtoupper( trim( $method ) );
		$url     = $this->base_url . '/' . $endpoint;
		$headers = array_merge( $headers, $this->get_headers() );
		$args    = array(
			'method'    => $method,
			'headers'   => $headers,
			'timeout'   => 20,
			'sslverify' => apply_filters( 'mwb_m4wp_use_sslverify', true ),
		);

		if ( ! empty( $data ) ) {
			if ( in_array( $method, array( 'GET', 'DELETE' ), true ) ) {
				$url = add_query_arg( $data, $url );
			} else {
				$args['headers']['Content-Type'] = 'application/json';
				$args['body']                    = wp_json_encode( $data );
			}
		}
		$args                = apply_filters( 'mwb_m4wp_http_request_args', $args, $url );
		$response            = wp_remote_request( $url, $args );
		$args['url']         = $url;
		$args['method']      = $method;
		$this->last_request  = $args;
		$this->last_response = $response;
		$data                = $this->parse_response( $response );
		return $data;
	}

}
