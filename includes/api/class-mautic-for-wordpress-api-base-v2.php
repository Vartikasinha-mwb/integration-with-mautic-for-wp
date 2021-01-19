<?php

class MWB_M4WP_Mautic_Api_Base_V2 {
    
    private $base_url ;
    private $client_id ; 
    private $client_sceret ; 
    private $acess_token;
    private $refresh_token;
    private $exprire_in;
    private static $_instance = null ; 
    //last request
    private $last_request ; 
    //last response
    private $last_response ; 

    public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }

    public function have_active_access_token(){
        $token_data = get_option( 'mwb_m4wp_token_data', array()); 
        if(isset($token_data['expires_in']) && $token_data['expires_in'] > time()){
            return true;
        }
        return false;
    }

    public function have_valid_api_keys(){
        $client_id = '1_1a5y0bvvunwg8wwwc4s4o4ooo8kwc8gg8c840owoccw4w4o8wc' ; 
        $client_secret = '65o9w2j9a3okw8g4kosgs8wkggsswwocs0w0k4wssg4w4c8o8o' ;
        $credentials = array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        );
        return $credentials;
        $credentials = get_option( 'mwb_m4wp_api_keys', array() );
        if( isset( $credentials['client_id'] ) && isset( $credentials['client_secret'] ) ){
            return $credentials;
        }
        return false;
    }

    public function get_refresh_token(){
        $token_data = get_option( 'mwb_m4wp_token_data', array()); 
        if( isset( $token_data['refresh_token'] ) ){
            return $token_data['refresh_token'];
        }
        return false;
    }
    
    public function save_token_data( $response ){
		if( isset( $response['access_token'] ) ){
            $token_data = array(
                'access_token' => $response['access_token'],
                'refresh_token' => $response['refresh_token'],
                'expires_in' => time() + $response['expires_in']
            );
			update_option( 'mwb_m4wp_token_data', $token_data ) ; 
		}
	}

    public function get_oauth_token( $url, $data ){
        $this->reset_request_data(); 
        $endpoint = '/oauth/v2/token' ; 
        $url    = $url . ltrim( $endpoint, '/' );
        $args   = array(
            'method'    => 'POST',
            'timeout'   => 20,
            'headers'   => array( 'Content-Type' => 'application/json' ),
            'body'		=> json_encode( $data )
        );
        $response = wp_remote_request( $url, $args );
        $args['url'] = $url;
        $args['method'] = 'POST' ;
        $this->last_request = $args;  
        $this->last_response = $response;  
        $data = $this->parse_response( $response ) ;
        return $data ; 
    }

    public function renew_access_token($url, $data ){
        $this->reset_request_data(); 
        $endpoint = '/oauth/v2/token' ; 
        $url    = $url . ltrim( $endpoint, '/' );
        $args   = array(
            'method'    => 'POST',
            'timeout'   => 20,
            'headers'   => array( 'Content-Type' => 'application/json' ),
            'body'		=> json_encode( $data )
        );
        $response = wp_remote_request( $url, $args );
        $args['url'] = $url;
        $args['method'] = 'POST' ;
        $this->last_request = $args;  
        $this->last_response = $response;  
        $data = $this->parse_response( $response ) ;
        return $data ; 
    }

    /**
     * Parse response and get back the data
     * @param array $response HTTP response 
    */
    private function parse_response( $response ){
        if ( $response instanceof WP_Error ) {
			//throw expection
        }
        // decode response body
		$code    = (int) wp_remote_retrieve_response_code( $response );
		$message = wp_remote_retrieve_response_message( $response );
        $body    = wp_remote_retrieve_body( $response );
        $data    = json_decode( $body , ARRAY_A ); 
        $this->create_error_log( $code , $message , $data ) ;
        return $data ; 
    }

    /**
     * Log error 
     * @param string $code Http response code.
     * @param string $message Response message.
     * @param array  $data Reponse data.
    */
    public function create_error_log( $code , $message , $data = array() ){
        $file = MWB_M4WP_PLUGIN_PATH.'/error.log' ; 
        $log =  "Url : ". $this->last_request[ 'url' ] . PHP_EOL ;
        $log .= "Method : ". $this->last_request[ 'method' ] . PHP_EOL ; 
        $log .= "Code : $code".PHP_EOL ; 
        $log .= "Message : $message".PHP_EOL ; 
        if( isset( $data[ 'errors' ] ) && is_array( $data[ 'errors' ] ) ){
            foreach( $data[ 'errors' ] as $key => $value ){
                $log .= "Error : " . $value['message'] . PHP_EOL ; 
            }
        } 
        $log .= "Time: ".current_time("F j, Y  g:i a").PHP_EOL ; 
        $log .= "------------------------------------".PHP_EOL ;  
        file_put_contents( $file , $log, FILE_APPEND ) ;
    }

    /**
     * Reset last request data
    */
    private function reset_request_data(){
        $this->last_request = '' ; 
        $this->last_response = '' ; 
    }
}