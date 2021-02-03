<?php

class MWB_M4WP_Mautic_Api  {
    
    private static $mautic_api ; 
    
    /**
    * Create or update contact in mautic.
    * @param array $data Contact data.
    * @return bool
    */
    public static function create_contact( $data ){
        $endpoint = 'api/contacts/new' ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        $headers = $mautic_api->get_auth_header();
        return $mautic_api->post( $endpoint , $data, $headers ) ;
    }

    public static function get_self_user(){
        $endpoint = 'api/users/self' ; 
        $mautic_api = self::get_mautic_api();
        $headers = $mautic_api->get_auth_header();
        return $mautic_api->get( $endpoint , array() , $headers ) ;
    }
    
    /**
    * Get mautic api class instance
    */
    public static function get_mautic_api(){
        
        //@todo get details wp options
        if( ! empty( self::$mautic_api ) ){
            return self::$mautic_api ; 
        }
        //$authentication_type = 'oauth2' ; //basic , oauth2 
        $authentication_type = get_option('mwb_m4wp_auth_type' , 'basic') ;     
        $base_url = get_option( 'mwb_m4wp_base_url', '' );
        if('' == $base_url){
            throw new Mautic_Api_Exception( 'Missing base url' , 001 );
            //return false ; 
        }

        if( 'oauth2' == $authentication_type ){
            $api_instance = Oauth2::get_instance(); 
            if(!$api_instance->is_authorized()){
                //throw new Mautic_Api_Exception( 'Unauthorized' , 004 );
                if( !($api_keys = $api_instance->have_valid_api_keys()) ){
                    throw new Mautic_Api_Exception( 'Missing api credentials' , 002 );
                    //return false;
                }
            }
            $api_instance->set_base_url( $base_url );
            if( !$api_instance->have_active_access_token()){
                if( !($api_keys = $api_instance->have_valid_api_keys()) ){
                    throw new Mautic_Api_Exception( 'Missing api credentials' , 002 );
                    //return false;
                }
                $refresh_token =  $api_instance->get_refresh_token();
                if(!$refresh_token){
                    throw new Mautic_Api_Exception( 'Missing refresh token' , 003 );
                    //return false;
                }
                $api_keys['refresh_token'] = $refresh_token ;
                $redirct_url = admin_url() ; 
                $api_keys['redirect_uri'] = $redirct_url ; 
                $api_keys['grant_type'] = 'refresh_token';
                $api_instance->renew_access_token( $api_keys ) ;
            }
            $api_instance->set_access_token();
            self::$mautic_api = $api_instance ; 
            return $api_instance ;
        }else{
            $credentials = get_option( 'mwb_m4wp_auth_details', array() );
            $username = isset($credentials['username']) ? $credentials['username'] : '' ; 
            $password = isset($credentials['password']) ? $credentials['password'] : '' ; 
            if( !empty( $base_url ) && !empty( $username ) && !empty( $password ) ){
                self::$mautic_api = new Basic_Auth( $base_url, $username, $password ) ;
                return self::$mautic_api ;
            }else{
                throw new Mautic_Api_Exception( 'Missing Api Details' , 006 );
            }
        }
        
        return false ; 
    }
    
    /**
    * Get segments 
    */
    public static function get_segments(){

        try{
            $endpoint = 'api/segments' ; 
            $mautic_api = self::get_mautic_api();
            $headers = $mautic_api->get_auth_header();
            return $mautic_api->get( $endpoint , array() , $headers ) ;
        }catch(Exception $e){
            return false;
        }
        
    }
    
    /**
    * Add contact to a segment
    */
    public static function add_contact_to_segment( $contact_id, $segment_id ){
        $endpoint = "api/segments/$segment_id/contact/$contact_id/add" ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        $headers = $mautic_api->get_auth_header();
        return $mautic_api->post( $endpoint , array() , $headers ) ;
    }
    
    /**
    * Get mautic forms
    */
    public static function get_forms(){
        $endpoint = 'api/forms' ; 
        try{
            $mautic_api = self::get_mautic_api();
            $headers = $mautic_api->get_auth_header();
            return $mautic_api->get( $endpoint , array() , $headers ) ;
        }catch(Exception $e){
           //print_r($e->getMessage()); 
           //show notification
           //log exception
           return false;
        } 
    }
    
    /**
    * Get all widgets
    */
    public static function get_widgets(){
        $endpoint = 'api/data' ; 
        try{
            $mautic_api = self::get_mautic_api();
            $headers = $mautic_api->get_auth_header();
            return $mautic_api->get( $endpoint , array() , $headers ) ;
        }catch( Exception $e){
            //print_r($e->getMessage()); 
            //show notification
            //log exception
            return false;
        }
        
    }
    
    /**
    * Get widget data
    */
    public static function get_widget_data( $widget_name, $data ){
        $endpoint = "api/data/$widget_name" ;
        try{
            $mautic_api = self::get_mautic_api();
            $headers = $mautic_api->get_auth_header();
            return $mautic_api->get( $endpoint, $data , $headers ) ;
        } catch ( Exception $e ){
             //print_r($e->getMessage()); 
            //show notification
            //log exception
            return false;
        }
    }
    
    /**
    * Get tags
    */
    public static function get_tags( ){
        $endpoint = "api/tags" ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        $headers = $mautic_api->get_auth_header();
        return $mautic_api->get( $endpoint , array() , $headers ) ;
    }
    
    /**
    * Add points to contact
    */
    public static function add_points( $contact_id , $points ){
        $endpoint = "api/contacts/$contact_id/points/plus/$points" ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        $headers = $mautic_api->get_auth_header();
        return $mautic_api->post( $endpoint , array(), $headers ) ;
    }
}