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

$helper   = new MWB_Mautic_For_WP_Settings_Helper();
$location = get_option( 'mwb_m4wp_script_location', 'footer' );
$enable   = get_option( 'mwb_m4wp_tracking_enable', 'no' );
?>
<div class="wrap mwb-m4wp-admin-wrap">
	<div class="mwb-m4wp-admin-panel-head">
		<h3><?php esc_html_e( 'Mautic WordPress Integration', 'makewebbetter-mautic-for-wordpress' ); ?></h3>
	</div>
	<div class="mwb-m4wp-admin-panel-main">
		<div class="setting-table-wrap mwb-m4wp-admin-table">
			<form method="post">
				<table class="form-table">
					<tr>
						<th><?php esc_html_e( 'Enable Mautic Tracking', 'makewebbetter-mautic-for-wordpress' ); ?></th>
						<td class="tracking">
							<label class="switch">
								<input type="checkbox" name="mwb_m4wp_tracking_enable" value="yes" <?php echo checked( 'yes', $enable ); ?>>
								<span class="slider round"></span>
							</label>
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Script Location', 'makewebbetter-mautic-for-wordpress' ); ?></th>
						<td>
							<input type="radio" name="mwb_m4wp_script_location" value="head" <?php echo checked( 'head', $location ); ?> />
							<label
								for="mwb_m4wp_script_location"><?php esc_html_e( 'Head', 'makewebbetter-mautic-for-wordpress' ); ?></label>
							<input type="radio" name="mwb_m4wp_script_location" value="footer" <?php echo checked( 'footer', $location ); ?> />
							<label
								for="mwb_m4wp_script_location"><?php esc_html_e( 'Footer', 'makewebbetter-mautic-for-wordpress' ); ?></label>
						</td>
					</tr>
				</table>
				<input type="hidden" name="action" value="mwb_m4wp_setting_save" />
				<input type="hidden" name="_nonce" value="<?php echo wp_create_nonce( '_nonce' ); ?>" /><br>
				<button id="mwb-m4wp-save-btn" class="button button-primary button-large" type="submit" class="button"><?php esc_html_e( 'Save', 'makewebbetter-mautic-for-wordpress' ); ?></button>
			</form>
		</div>
	</div>
</div>
