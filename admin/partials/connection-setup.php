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
<div class="connection-form-wrap">
	<form method="post">
		<table class="form-table mwb-form-table">
			<tr>
				<th><?php
				esc_html_e( 'Status', 'makewebbetter-mautic-for-wordpress' ); ?></th>
				<td>
					<?php
					if ( get_option( 'mwb_m4wp_connection_status', false ) ) {
						echo '<span class="span-connected">Connected</span>';
					} else {
						echo '<span class="span-disconnected">DisConnected</span>';
					}
					?>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Type', 'makewebbetter-mautic-for-wordpress' ); ?></th>
				<td>
					<select name="authentication_type" id="mwb-m4wp-auth-type">
						<option value="basic" <?php selected( 'basic', $auth_type ); ?>>
							<?php esc_html_e( 'Basic', 'makewebbetter-mautic-for-wordpress' ); ?>
						</option>
						<option value="oauth2" <?php selected( 'oauth2', $auth_type ); ?>>
							<?php esc_html_e( 'OAuth2', 'makewebbetter-mautic-for-wordpress' ); ?>
						</option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Mautic Url', 'makewebbetter-mautic-for-wordpress' ); ?></th>
				<td>
					<input id="input-baseurl" type="text" value="<?php echo esc_attr( $baseurl ); ?>" name="baseurl" />
				</td>
			</tr>
			<tr class="mwb-m4wp-oauth-row <?php echo esc_attr( $row_oauth2 ); ?>">
				<th><?php esc_html_e( 'Client id', 'makewebbetter-mautic-for-wordpress' ); ?></th>
				<td>
					<input id="input-id" type="password" value="<?php echo esc_attr( $client_id ); ?>" name="client_id" />
				</td>
			</tr>
			<tr class="mwb-m4wp-oauth-row <?php echo esc_attr( $row_oauth2 ); ?>">
				<th><?php esc_html_e( 'Client Secret', 'makewebbetter-mautic-for-wordpress' ); ?></th>
				<td>
					<input id="input-secret" type="password" value="<?php echo esc_attr( $client_secret ); ?>" name="client_secret" />
				</td>
			</tr>
			<tr class="mwb-m4wp-basic-row <?php echo esc_attr( $row_basic ); ?>">
				<th><?php esc_html_e( 'Username', 'makewebbetter-mautic-for-wordpress' ); ?></th>
				<td>
					<input id="input-username" type="text" value="<?php echo esc_attr( $username ); ?>" name="username" />
				</td>
			</tr>
			<tr class="mwb-m4wp-basic-row <?php echo esc_attr( $row_basic ); ?>">
				<th><?php esc_html_e( 'Password', 'makewebbetter-mautic-for-wordpress' ); ?></th>
				<td>
					<input id="input-password" type="password" value="<?php echo esc_attr( $password ); ?>" name="password" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="action" value="mwb_m4wp_save" />
		<button id="mwb-m4wp-save-btn" type="submit" class="button"><?php esc_html_e( 'Save', 'makewebbetter-mautic-for-wordpress' ); ?></button>
	</form>
	<?php if ( ! empty( $credentials ) ) : ?>
		<button type="button" class="button" id="mwb-fwpro-test-connection"><?php esc_html_e( 'Test Connection', 'makewebbetter-mautic-for-wordpress' ); ?></button>
	<?php endif; ?>

	<?php if ( 'oauth2' === $auth_type ) : ?>
	<a class="button"
		href="<?php esc_attr( wp_nonce_url( admin_url( '/?m4wp_auth=1' ), 'm4wp_auth_nonce', 'm4wp_auth_nonce' ) ); ?>">
		<?php esc_html_e( 'Authorize App', 'makewebbetter-mautic-for-wordpress' ); ?>
	</a>
	<?php endif; ?>
</div>
