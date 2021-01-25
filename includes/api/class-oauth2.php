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

    public function is_authorized( ){
        return get_option( 'mwb_m4wp_oauth2_success', false );
    }
    public function set_access_token(){
        $token_data = get_option( 'mwb_m4wp_token_data', array());
        $this->acess_token = $token_data['access_token'];
    }

    public function have_active_access_token(){
        return false;
        $token_data = get_option( 'mwb_m4wp_token_data', array()); 
        if(isset($token_data['expires_in']) && $token_data['expires_in'] > time()){
            return true;
        }
        return false;
    }

    public function have_valid_api_keys(){
        $credentials = get_option( 'mwb_m4wp_auth_details' , array() ) ; 
        if( isset( $credentials['client_id'] ) && '' != $credentials['client_id'] && 
        isset( $credentials['client_secret'] ) && '' != $credentials['client_secret']  ){
            return array( 
                'client_id' =>  $credentials['client_id'] ,
                'client_secret' => $credentials['client_secret']
            );
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

    public function get_oauth_token( $data ){
        $endpoint = 'oauth/v2/token' ;
        return $this->post($endpoint, $data); 
    }

    public function renew_access_token( $data ){
        $endpoint = 'oauth/v2/token' ;
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

    public function set_base_url( $base_url ){
        $this->base_url = $base_url;
    }
}