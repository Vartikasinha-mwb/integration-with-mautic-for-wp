<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Makewebbetter_Mautic_For_Wordpress
 * @subpackage Makewebbetter_Mautic_For_Wordpress/admin/partials
 */

$helper    = MWB_Mautic_For_WP_Settings_Helper::get_instance();

$connected = get_option( 'mwb_m4wp_connection_status', false );

if ( $connected ) {

	$helper->load_admin_template( 'connection-details' );

} else {

	$helper->load_admin_template( 'connection-setup' );

}
