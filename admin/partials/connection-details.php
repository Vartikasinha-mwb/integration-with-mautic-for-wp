<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/admin/partials
 */

if ( wp_cache_get( 'mwb_m4wp_user_data' ) ) {
	$user_data = wp_cache_get( 'mwb_m4wp_user_data' );
} else {
	$user_data = Mwb_Wpm_Api::get_self_user();
}
$admin_email       = isset( $user_data['user'] ) ? $user_data['user'] : '';
$connection_status = ( '' === $admin_email ) ? 'Dis Connected' : 'Connected';
$auth_type         = get_option( 'mwb_m4wp_auth_type', 'basic' );
$auth_type         = ( 'basic' === $auth_type ) ? __( 'Basic', 'wp-mautic-integration' ) : __( 'OAuth2', 'wp-mautic-integration' );

?>
<div class="connection-detail-wrap">
	<table class="form-table">
		<tr>
			<th>
			<?php
				esc_html_e( 'Status', 'wp-mautic-integration' );
			?>
				</th>
			<td><?php echo esc_attr( $connection_status ); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Authentication Type', 'wp-mautic-integration' ); ?></th>
			<td><?php echo esc_attr( $auth_type ); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Admin Email', 'wp-mautic-integration' ); ?></th>
			<td>
				<?php echo esc_attr( $admin_email ); ?>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<a class="mwb-btn mwb-btn-primary mwb-save-btn" href="<?php echo esc_attr( wp_nonce_url( admin_url( '/?m4wp_reset=1' ), 'm4wp_auth_nonce', 'm4wp_auth_nonce' ) ); ?>">
					<?php esc_html_e( 'Reset Connection', 'wp-mautic-integration' ); ?>
				</a>
				<a id="mwb-fwpro-test-connection" class="mwb-btn mwb-btn-primary" href="<?php echo esc_attr( wp_nonce_url( admin_url( '/?m4wp_reset=1' ), 'm4wp_auth_nonce', 'm4wp_auth_nonce' ) ); ?>">
					<?php esc_html_e( 'Test Connection', 'wp-mautic-integration' ); ?>
				</a>
			</td>
		</tr>
	</table>
</div>
