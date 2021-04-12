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

// $segments = MWB_M4WP_Mautic_Api::add_points(6,100);
// echo '<pre>';
// print_r($segments);
$data = array('firstname' => 'fn' , 'lastname' => 'ln' , 'email' => 'fn@ln3.com' , 'tags' => array( 'newuser' ) , 'points' => 100 ) ; 
$contact = MWB_M4WP_Mautic_Api::create_contact( $data ) ;
$contact_id = 0  ;  
echo '<pre>';
if(isset( $contact['contact'] )){
    $contact_id = $contact['contact']['id'];
}
if($contact_id > 0 ){
    //MWB_M4WP_Mautic_Api::add_contact_to_segment($contact_id , 2) ; 
    //MWB_M4WP_Mautic_Api::add_points( $contact_id, 100 );
}