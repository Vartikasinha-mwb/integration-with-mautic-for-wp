<?php

class Oauth2 extends Api_Base {
    
    private $client_id ; 
    private $client_sceret ; 
    private $acess_token;
    private $refresh_token;
    private $exprire_in;
    private static $_instance = null ; 

    public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
		}
		return self::$_instance;
    }

    public function set_base_url(){
        $this->base_url = 'http://localhost/mautic2163/';
    }
    public function set_access_token(){
        $token_data = get_option( 'mwb_m4wp_token_data', array());
        $this->acess_token = $token_data['access_token'];
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
        $endpoint = '/oauth/v2/token' ;
        return $this->post($endpoint, $data); 
    }

    public function renew_access_token($url, $data ){
        $endpoint = '/oauth/v2/token' ;
        return $this->post($endpoint, $data); 
    }

    /**
    * Get headers. 
    * @return array
    */
    public function get_auth_header(){
        $headers = array(
            'Authorization' => sprintf( 'Bearer %s',  $this->acess_token ), 
        );   
        return $headers;
    }
}