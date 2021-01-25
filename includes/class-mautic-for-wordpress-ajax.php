<?php

class Mautic_For_Wordpress_Ajax {
    
    public function test_api_connection(){
        $response = array();
        try{
            $user =  MWB_M4WP_Mautic_Api::get_self_user();
            $response['success'] = true ; 
            $response['msg'] = 'Success' ; 
        }catch(Exception $e){
            $response['success'] = false ; 
            $response['msg'] = $e->getMessage() ; 
        }
        wp_send_json($response);
        wp_die();
    }
}
