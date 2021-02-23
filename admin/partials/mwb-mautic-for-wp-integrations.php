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

if ( isset( $_GET['id'] ) && '' !== $_GET['id'] ) { // phpcs:ignore WordPress.Security.NonceVerification
	$file_path = 'admin/partials/mwb-mautic-for-wp-integration-setting.php';
} else {
	$file_path = 'admin/partials/mwb-mautic-for-wp-integration-list.php';
}
MWB_Mautic_For_WP_Admin::load_template( $file_path );
