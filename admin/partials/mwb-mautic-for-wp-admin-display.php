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

$user = '';
if ( isset( $_POST['action'] ) && 'mwb_m4wp_save' === $_POST['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification

	$auth_type                    = sanitize_text_field( wp_unslash( isset( $_POST['authentication_type'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['authentication_type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$baseurl                      = sanitize_text_field( wp_unslash( isset( $_POST['baseurl'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['baseurl'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$baseurl                      = rtrim( $baseurl, '/' );
	$credentials                  = array();
	$credentials['client_id']     = sanitize_text_field( wp_unslash( isset( $_POST['client_id'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['client_id'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$credentials['client_secret'] = sanitize_text_field( wp_unslash( isset( $_POST['client_secret'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['client_secret'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$credentials['username']      = sanitize_text_field( wp_unslash( isset( $_POST['username'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$credentials['password']      = sanitize_text_field( wp_unslash( isset( $_POST['password'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	update_option( 'mwb_m4wp_auth_details', $credentials );
	update_option( 'mwb_m4wp_auth_type', $auth_type );
	update_option( 'mwb_m4wp_base_url', $baseurl );

	if ( 'basic' === $auth_type ) {
		$user = MWB_Mautic_For_WP_Api::get_self_user();
		echo esc_attr( $user['msg'] );
	}
}

	$credentials   = get_option( 'mwb_m4wp_auth_details', array() );
	$auth_type     = get_option( 'mwb_m4wp_auth_type', 'basic' );
	$auth_type     = ( '' === $auth_type ) ? 'basic' : $auth_type;
	$baseurl       = get_option( 'mwb_m4wp_base_url', '' );
	$username      = isset( $credentials['username'] ) ? $credentials['username'] : '';
	$password      = isset( $credentials['password'] ) ? $credentials['password'] : '';
	$client_id     = isset( $credentials['client_id'] ) ? $credentials['client_id'] : '';
	$client_secret = isset( $credentials['client_secret'] ) ? $credentials['client_secret'] : '';

	$row_basic  = 'row-hide';
	$row_oauth2 = 'row-hide';
	( 'basic' === $auth_type ) ? ( $row_basic = '' ) : ( $row_oauth2 = '' );
	$auth_type = ( 'basic' === $auth_type ) ? __( 'Basic', 'mautic-for-wordress' ) : __( 'OAuth2', 'mautic-for-wordress' );
?>
<div class="wrap mwb-m4wp-admin-wrap">
	<div class="mwb-m4wp-admin-panel-head">
		<h3><?php esc_html_e( 'Mautic WordPress Integration', 'mautic-for-wordpress' ); ?></h3>
	</div>

	<div class="mwb-m4wp-admin-panel-main">

	<?php
	$connected = get_option( 'mwb_m4wp_connection_status', false );
	if ( $connected ) {

		if ( '' === $user ) {
			$user = MWB_Mautic_For_WP_Api::get_self_user();
		}
		$user_email        = isset( $user['user'] ) ? $user['user'] : '';
		$connection_status = ( '' === $user_email ) ? 'Dis Connected' : 'Connected';

		include_once 'connection-details.php';
	} else {
		include_once 'connection-setup.php';
	}
	?>

</div>
</div>
