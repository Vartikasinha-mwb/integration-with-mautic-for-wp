<?php

class Api_Base {
    public $base_url; 
    private $last_request;
    private $last_response;
    
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

        if( $code == 403 && $message == "Forbidden") {
            throw new Mautic_Api_Exception( $message , $code );
        }

        if( $code == 401 ){
            throw new Mautic_Api_Exception( $message , $code );
        }

        if($code == 0){
            $message = "Something went wrong, Please check your credentials" ; 
            throw new Mautic_Api_Exception( $message , $code );
        }

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
        $log  = "Url : ". $this->last_request[ 'url' ] . PHP_EOL ;
        $log .= "Method : ". $this->last_request[ 'method' ] . PHP_EOL ; 
        $log .= "Code : $code".PHP_EOL ; 
        $log .= "Message : $message".PHP_EOL ; 
        if( isset( $data[ 'errors' ] ) && is_array( $data[ 'errors' ] ) ){
            foreach( $data[ 'errors' ] as $key => $value ){
                $log .= "Error : " . $value['message'] . PHP_EOL ; 
            }
            $log .= "Response: ".json_encode($this->last_response).PHP_EOL ;
            $log .= "Req: ".json_encode($this->last_request).PHP_EOL ; 
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
    
    /**
    * Get Request.
    * @param string $endpoint Api endpoint of mautic.
    * @param array  $data Data to be used in request.
    */
    public function get( $endpoint, $data = array(), $headers = array() ){
        return $this->request( 'GET', $endpoint, $data , $headers ) ; 
    }
    
    /**
    * Post Request.
    * @param string $endpoint Api endpoint of mautic.
    * @param array  $data Data to be used in request.
    */
    public function post( $endpoint, $data = array(), $headers = array() ){
        return $this->request( 'POST', $endpoint, $data, $headers ) ; 
    }
    
    /**
    * Get headers. 
    * @return array
    */
    private function get_headers(){
        global $wp_version;
        $headers = array(
            'User-Agent'    => sprintf( 'MWB_M4WP/%s; WordPress/%s; %s', MWB_M4WP_VERSION, $wp_version, home_url() ),
        );
        // Copy Accept-Language from browser headers
        if ( ! empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
            $headers['Accept-Language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }        
        return $headers;
    }
    
    /**
    * Send mautic api request
    * @param string $method   HTTP method.
    * @param string $endpoint Api endpoint.
    * @param array  $data     Request data.
    * 
    */
    private function request( $method, $endpoint, $data = array() , $headers = array() ){
        
        $this->reset_request_data();
        $method = strtoupper( trim( $method ) );
        $url    = $this->base_url .'/'. $endpoint;
        $headers = array_merge( $headers, $this->get_headers() );
        $args   = array(
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
                $args['body']                    = json_encode( $data );
            }
        }  
        $args = apply_filters( 'mwb_m4wp_http_request_args', $args, $url );
        $response = wp_remote_request( $url, $args );
        $args[ 'url' ]         = $url ;
        $args[ 'method' ]      = $method ; 
        $this->last_request    = $args ;
        $this->last_response   = $response ;
        $data = $this->parse_response( $response );
        return $data;
    }
    
}