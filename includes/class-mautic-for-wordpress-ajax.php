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

    public function enable_integration(){
        $response = array(
            'success' => true,
            'msg' => 'Success'
        );
        $enable = $_POST['enable'] ;
        $integration = $_POST['integration'] ;
        $settings = get_option('mwb_m4wp_integration_settings' , array());
        if(isset($settings[$integration])){
            $settings[$integration]['enable'] = $enable ; 
        }else{
            $settings[$integration] = $this->get_integration_default_settings();
            $settings[$integration]['enable'] = $enable ; 
        }
        update_option( 'mwb_m4wp_integration_settings', $settings ) ;
        wp_send_json($response);
        wp_die();
    }

    public function get_integration_default_settings(){
        $settings = array(
            'enable' => 'no',
            'implicit' => 'no',
            'checkbox_txt' => '',
            'precheck' => 'no',
            'add_segment' => '-1',
            'add_tag' => '',
        );
        return $settings ; 
    }
}
