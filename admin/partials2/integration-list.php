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

$integrations = MWB_Mautic_For_WP_Integration_Manager::get_integrations();
?>

<div class="mwb-m4wp-admin-panel-main mwb-m4wp-admin-integration-panel">
	<table class="form-table mwb-m4wp-admin-table">
		<thead>
			<tr>
				<th class="name"><?php esc_html_e( 'Name', 'makewebbetter-mautic-for-wordpress' ); ?></th>
				<th class="des"><?php esc_html_e( 'Description', 'makewebbetter-mautic-for-wordpress' ); ?></th>
				<th class="status"><?php esc_html_e( 'Status', 'makewebbetter-mautic-for-wordpress' ); ?></th>
				<th class="setting"><?php esc_html_e( 'Settings', 'makewebbetter-mautic-for-wordpress' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $integrations as $key => $details ) :
				$integration = MWB_Mautic_For_WP_Integration_Manager::get_integration( $details );
				if ( ! $integration ) {
					continue;
				}
				?>
				<tr integration="<?php echo esc_attr( $key ); ?>">
					<td class="name"><?php echo esc_attr( $integration->get_name() ); ?></td>
					<td class="des"><?php echo esc_attr( $integration->get_description() ); ?></td>
					<td class="status">
						<label class="switch">
							<input type="checkbox" class="mwb-switch-checkbox" <?php checked( esc_attr( $integration->is_enabled() ), true ); ?> class="mwb-m4wp-enable-cb">
							<span class="slider round"></span>
						</label>
					</td>
					<td class="setting">
						<a href="?page=mwb-mautic-for-wp2&tab=integration&id=<?php echo esc_attr( $integration->get_id() ); ?>">
							<span class="dashicons dashicons-admin-generic"></span>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
