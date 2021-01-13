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

$base_url = "http://localhost/mautic2163/" ; 
$username = "mohitp" ;
$password = "password" ; 
$mautic_api = new MWB_M4WP_Mautic_Api( $base_url, $username, $password ) ; 
//echo '<pre>';
//print_r( $mautic_api->get( 'contacts' ) );
//print_r( $mautic_api->post( 'fields/contact/new' , array( 'label' => 'M4WP', 'type' => 'text' ) ) );