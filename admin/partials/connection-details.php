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
?>
<div class="connection-detail-wrap">
	<table class="form-table">
		<tr>
			<th><?php
			esc_html_e( 'Status', 'makewebbetter-mautic-for-wordpress' ); ?></th>
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
	</table>
</div>
<a class="button"
	href="<?php echo esc_attr( wp_nonce_url( admin_url( '/?m4wp_reset=1' ), 'm4wp_auth_nonce', 'm4wp_auth_nonce' ) ); ?>">
	<?php esc_html_e( 'Reset Connection', 'makewebbetter-mautic-for-wordpress' ); ?>
</a>
