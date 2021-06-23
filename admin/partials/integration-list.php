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

$integrations = MWB_Wpm_Integration_Manager::get_integrations();
?>

<div class="mwb-m4wp-admin-panel-main mwb-m4wp-admin-integration-panel">
	<table class="form-table mwb-m4wp-admin-table">
		<thead>
			<tr>
				<th class="name"><?php esc_html_e( 'Name', 'wp-mautic-integration' ); ?></th>
				<th class="des"><?php esc_html_e( 'Description', 'wp-mautic-integration' ); ?></th>
				<th class="status"><?php esc_html_e( 'Status', 'wp-mautic-integration' ); ?></th>
				<th class="setting"><?php esc_html_e( 'Settings', 'wp-mautic-integration' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $integrations as $key => $details ) :
				$integration = MWB_Wpm_Integration_Manager::get_integration( $details );
				if ( ! $integration ) {
					continue;
				}
				$checked       = $integration->is_enabled();
				$class_checked = $checked ? 'mwb-switch-checkbox--move' : '';
				?>
				<tr integration="<?php echo esc_attr( $key ); ?>">
					<td class="name"><?php echo esc_attr( $integration->get_name() ); ?></td>
					<td class="des"><?php echo esc_attr( $integration->get_description() ); ?></td>
					<td class="status">
						<label class="switch">
							<input type="checkbox" class="mwb-switch-checkbox mwb-m4wp-enable-cb <?php echo esc_attr( $class_checked ); ?>" <?php checked( $checked, true ); ?> >
							<span class="slider round"></span>
						</label>
					</td>
					<td class="setting">
						<a href="?page=mwb-wp-mautic&tab=integration&id=<?php echo esc_attr( $integration->get_id() ); ?>">
							<span class="dashicons dashicons-admin-generic"></span>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>

		</tbody>
	</table>
</div>
