<?php

class Mautic_Api_Exception extends Exception {
    
    /**
    * @var object
    */
    public $response = array();
    
    /**
    * @var object
    */
    public $request = array();
    
    /**
    * @var array
    */
    public $response_data = array();
    
    /**
    * Mautic_Api_Exception constructor.
    *
    * @param string $message
    * @param int $code
    * @param array $request
    * @param array $response
    * @param object $data
    */
    public function __construct( $message, $code, $request = null, $response = null, $data = null ) {
        parent::__construct( $message, $code );
        
        $this->request  = $request;
        $this->response = $response;
        $this->response_data = $data;
    }
    
}
