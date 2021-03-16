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

if ( wp_cache_get( 'mwb_m4wp_user_data' ) ) {
	$user_data = wp_cache_get( 'mwb_m4wp_user_data' );
} else {
	$user_data = MWB_Mautic_For_WP_Api::get_self_user();
}
$user_email        = isset( $user_data['user'] ) ? $user_data['user'] : '';
$connection_status = ( '' === $user_email ) ? 'Dis Connected' : 'Connected';
$auth_type     = get_option( 'mwb_m4wp_auth_type', 'basic' );
$auth_type = ( 'basic' === $auth_type ) ? __( 'Basic', 'mautic-for-wordress' ) : __( 'OAuth2', 'mautic-for-wordress' );

?>
<div class="connection-detail-wrap">
	<table class="form-table">
		<tr>
			<th>
			<?php
				esc_html_e( 'Status', 'makewebbetter-mautic-for-wordpress' );
			?>
				</th>
			<td><?php echo esc_attr( $connection_status ); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Authentication Type', 'makewebbetter-mautic-for-wordpress' ); ?></th>
			<td><?php echo esc_attr( $auth_type ); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Admin Email', 'makewebbetter-mautic-for-wordpress' ); ?></th>
			<td>
				<?php echo esc_attr( $user_email ); ?>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<a class="mwb-btn mwb-btn-primary mwb-save-btn" href="<?php echo esc_attr( wp_nonce_url( admin_url( '/?m4wp_reset=1' ), 'm4wp_auth_nonce', 'm4wp_auth_nonce' ) ); ?>">
					<?php esc_html_e( 'Reset Connection', 'makewebbetter-mautic-for-wordpress' ); ?>
				</a>
				<a id="mwb-fwpro-test-connection" class="mwb-btn mwb-btn-primary" href="<?php echo esc_attr( wp_nonce_url( admin_url( '/?m4wp_reset=1' ), 'm4wp_auth_nonce', 'm4wp_auth_nonce' ) ); ?>">
					<?php esc_html_e( 'Test Connection', 'makewebbetter-mautic-for-wordpress' ); ?>
				</a>
			</td>
		</tr>
	</table>
</div>
