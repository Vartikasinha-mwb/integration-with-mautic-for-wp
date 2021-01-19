<?php

class MWB_M4WP_Mautic_Api  {
    
    private static $mautic_api ; 
    
    /**
    * Create or update contact in mautic.
    * @param array $data Contact data.
    * @return bool
    */
    public static function create_contact( $data ){
        $endpoint = '/contacts/new' ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        return $mautic_api->post( $endpoint , $data ) ;
    }
    
    /**
    * Get mautic api class instance
    */
    public static function get_mautic_api(){
        
        //@todo get details wp options
        if( ! empty( self::$mautic_api ) ){
            return self::$mautic_api ; 
        }
        
        $authentication_type = 'oauth2' ; //basic , oauth2 
        if( 'oauth2' == $authentication_type ){
            $api_instance = MWB_M4WP_Mautic_Api_Base_V2::get_instance(); 
            if( !($api_keys = $api_instance->have_valid_api_keys()) ){
                return '1';
            }
            if( !$api_instance->have_active_access_token()){
                $refresh_token =  $api_instance->get_refresh_token();
                if(!$refresh_token){
                    return '2' ;
                }
                $api_keys['refresh_token'] = $refresh_token ;
                $redirct_url = 'http://localhost/wp551/wp-admin/admin.php' ; 
				$mautic_url = 'http://localhost/mautic2163/' ; 
                $api_keys['redirect_uri'] = $redirct_url ; 
                $api_keys['grant_type'] = 'refresh_token';
                $data =  $api_instance->renew_access_token($mautic_url, $api_keys ) ;
                $api_instance->save_token_data( $data );
            }
            self::$mautic_api = $api_instance ; 
            return $api_instance;
        }else{
            $base_url = get_option( "mwb_m4wp_baseurl", "http://localhost/mautic2163/" ) ; 
            $username = get_option( "mwb_m4wp_username", "mohitp" ) ;
            $password = get_option( "mwb_m4wp_password", "password" ) ; 
            if( !empty( $base_url ) && !empty( $username ) && !empty( $password ) ){
                self::$mautic_api = new MWB_M4WP_Mautic_Api_Base( $base_url, $username, $password ) ;
                return self::$mautic_api ;
            }
        }
        
        return false ; 
    }
    
    /**
    * Get segments 
    */
    public static function get_segments(){
        $endpoint = '/segments' ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        return $mautic_api->get( $endpoint ) ;
    }
    
    /**
    * Add contact to a segment
    */
    public static function add_contact_to_segment( $contact_id, $segment_id ){
        $endpoint = "/segments/$segment_id/contact/$contact_id/add" ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        return $mautic_api->post( $endpoint ) ;
    }
    
    /**
    * Get mautic forms
    */
    public static function get_forms(){
        $endpoint = '/forms' ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        return $mautic_api->get( $endpoint ) ;
    }
    
    /**
    * Get all widgets
    */
    public static function get_widgets(){
        $endpoint = '/data' ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        return $mautic_api->get( $endpoint ) ;
    }
    
    /**
    * Get widget data
    */
    public static function get_widget_data( $widget_name, $data ){
        $endpoint = "/data/$widget_name" ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        return $mautic_api->get( $endpoint, $data ) ;
    }
    
    /**
    * Get tags
    */
    public static function get_tags( ){
        $endpoint = "/tags" ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        return $mautic_api->get( $endpoint ) ;
    }
    
    /**
    * Add points to contact
    */
    public static function add_points( $contact_id , $points ){
        $endpoint = "/contacts/$contact_id/points/plus/$points" ; 
        $mautic_api = self::get_mautic_api();
        if( !$mautic_api ) {
            return ;
        }
        return $mautic_api->post( $endpoint ) ;
    }
    
    
}