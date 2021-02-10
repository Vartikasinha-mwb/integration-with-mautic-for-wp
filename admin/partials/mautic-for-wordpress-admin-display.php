<?php

/**
* Provide a admin area view for the plugin
*
* This file is used to markup the admin-facing aspects of the plugin.
*
* @link       https://makewebbetter.com/
* @since      1.0.0
*
* @package    Mautic_For_Wordpress
* @subpackage Mautic_For_Wordpress/admin/partials
*/


// $data = array( 'dateTo' => '01/31/2021' , 'dateFrom' => '01/01/2021', 'timeUnit' => 'd') ; 
// $segments = MWB_M4WP_Mautic_Api::get_widget_data( 'created.leads.in.time' , $data );
// echo '<pre>';
// print_r($segments);

// $segments = MWB_M4WP_Mautic_Api::get_segments();
// echo '<pre>';
// print_r($segments);
// die;

// $segments = MWB_M4WP_Mautic_Api::add_points(6,100);
// echo '<pre>';
// print_r($segments);
// $data = array('firstname' => 'fn' , 'lastname' => 'ln' , 'email' => 'fn@ln3.com' , 'tags' => array( 'newuser' ) , 'points' => 100 ) ; 
// $contact = MWB_M4WP_Mautic_Api::create_contact( $data ) ;
// print_r($contact);
// $contact_id = 0  ;  
// echo '<pre>';
// if(isset( $contact['contact'] )){
    //     $contact_id = $contact['contact']['id'];
    // }
    // GET /oauth/v2/authorize?
    //     client_id=CLIENT_ID
    //     &grant_type=authorization_code
    //     &redirect_uri=https%3A%2F%2Fyour-redirect-uri.com%2Fcallback
    //     &response_type=code
    //     &state=UNIQUE_STATE_STRING
    
    //http://localhost/wp551/wp-admin/?state=6aef0c3afa&code=YzM5YjgyNWNhNjhjNDY4MDA1NmExMWE4OTg4MjgzMzkwNjJhOTc2OGNhNjQ3YjMzYTMwZWU5MDQxMzcxMGQ3MA
    
    $user = '' ; 
    if(isset($_POST['action']) && $_POST['action'] == 'mwb_m4wp_save' ){
        
        $type = $_POST['authentication_type'] ;
        $baseurl = $_POST['baseurl'];
        $baseurl = rtrim($baseurl , '/');
        $credentials = array();
        $credentials['client_id'] = $_POST['client_id'] ; 
        $credentials['client_secret'] = $_POST['client_secret'] ;
        $credentials['username'] = $_POST['username'] ; 
        $credentials['password'] = $_POST['password'] ; 
        update_option( 'mwb_m4wp_auth_details' , $credentials );
        update_option( 'mwb_m4wp_auth_type', $type );
        update_option( 'mwb_m4wp_base_url', $baseurl );

        if($type == 'basic'){
            $user = MWB_M4WP_Mautic_Api::get_self_user();
            echo $user['msg'];
        }

    }

    $credentials = get_option( 'mwb_m4wp_auth_details', array() );
    $type = get_option( 'mwb_m4wp_auth_type', 'basic' );
    $type = ($type == '') ? 'basic' : $type ; 
    $baseurl = get_option( 'mwb_m4wp_base_url', '' );
    $username = isset($credentials['username']) ? $credentials['username'] : '' ; 
    $password = isset($credentials['password']) ? $credentials['password'] : '' ; 
    $client_id = isset($credentials['client_id']) ? $credentials['client_id'] : '' ; 
    $client_secret = isset($credentials['client_secret']) ? $credentials['client_secret'] : '' ; 
    
    $row_basic = 'row-hide' ; 
    $row_oauth2 = 'row-hide' ; 
    ($type == 'basic') ? ( $row_basic = '')  : ( $row_oauth2 = '') ;
    $auth_type = ($type == 'basic') ? __('Basic' , 'mautic-for-wordress') :__('OAuth2' , 'mautic-for-wordress')  ;
    
    ?>
<div class="wrap mwb-m4wp-admin-wrap">
    <div class="mwb-m4wp-admin-panel-head">
        <h3><?php esc_html_e( 'Mautic WordPress Integration', 'mautic-for-wordpress' ) ?></h3>
    </div>

    <div class="mwb-m4wp-admin-panel-main">

    <?php
    $connected = get_option('mwb_m4wp_connection_status' , false) ; 
    if($connected){

        if($user == ''){
            $user = MWB_M4WP_Mautic_Api::get_self_user();
        }
        $user_email = isset($user['user']) ? $user['user'] : '' ; 
        $connection_status = ( $user_email == '' ) ? 'Dis Connected' : 'Connected' ; 

        include_once 'connection_details.php' ; 
    }else{
        include_once 'connection_setup.php' ;
    }
    ?>

    </div>
</div>