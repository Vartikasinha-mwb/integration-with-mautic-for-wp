<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    MWB_Mautic_For_WP
 * @subpackage MWB_Mautic_For_WP/admin/partials
 */

$helper = MWB_Mautic_For_WP_Settings_Helper::get_instance();

if ( isset( $_GET['id'] ) && '' !== $_GET['id'] ) { // phpcs:ignore WordPress.Security.NonceVerification
	$helper->load_admin_template( 'integration-settings' );

} else {

	$helper->load_admin_template( 'integration-list' );
}
