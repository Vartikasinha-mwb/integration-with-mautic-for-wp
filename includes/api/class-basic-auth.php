<?php

class Basic_Auth extends Api_Base {
    
    //mautic user_name
    private $user_name;
    
    //mautic password
    private $password;
    
    /**
    * Constructor.
    * @param string $base_url Base url of your mautic instance.
    * @param string $user_name Mautic user name.
    * @param string $password Mautic password.
    */
    public function __construct ( $base_url, $user_name, $password ){
        
        $this->base_url  = $base_url ; 
        $this->user_name = $user_name ; 
        $this->password  = $password ; 
    }
    
    /**
    * Get headers. 
    * @return array
    */
    public function get_auth_header() {
        $headers = array(
            'Authorization' => sprintf( 'Basic %s', base64_encode( $this->user_name . ':' . $this->password ) ),
        );       
        return $headers;
    }
}