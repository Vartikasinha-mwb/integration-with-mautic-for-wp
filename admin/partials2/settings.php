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

$helper   = MWB_Mautic_For_WP_Settings_Helper::get_instance();
$location = get_option('mwb_m4wp_script_location', 'footer');
$enable   = get_option('mwb_m4wp_tracking_enable', 'no');
$base_url = get_option('mwb_m4wp_base_url', '');
?>
<div class="mwb-m4wp-admin-panel-main">
	<div class="setting-table-wrap mwb-m4wp-admin-table">
		<form method="post">
			<table class="form-table">
				<tr>
					<th><?php esc_html_e('Enable Mautic Tracking', 'makewebbetter-mautic-for-wordpress'); ?></th>
					<td class="tracking">
						<label class="switch">
							<input type="checkbox" class="mwb-switch-checkbox" name="mwb_m4wp_tracking_enable" value="yes" <?php echo checked('yes', $enable); ?>>
							<span class="slider round"></span>
						</label>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e('Mautic Url', 'makewebbetter-mautic-for-wordpress'); ?></th>
					<td>
						<input type="text" class="mwb-admin-table-inputfield" value="<?php echo esc_attr($base_url); ?>" name="mwb_m4wp_base_url" />
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e('Script Location', 'makewebbetter-mautic-for-wordpress'); ?></th>
					<td>
						<input type="radio" name="mwb_m4wp_script_location" value="head" <?php echo checked('head', $location); ?> />
						<label for="mwb_m4wp_script_location"><?php esc_html_e('Head', 'makewebbetter-mautic-for-wordpress'); ?></label>
						<input type="radio" name="mwb_m4wp_script_location" value="footer" <?php echo checked('footer', $location); ?> />
						<label for="mwb_m4wp_script_location"><?php esc_html_e('Footer', 'makewebbetter-mautic-for-wordpress'); ?></label>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input type="hidden" name="action" value="mwb_m4wp_setting_save" />
						<input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('_nonce'); ?>" /><br>
						<button id="mwb-m4wp-save-btn" class="mwb-btn mwb-btn-primary mwb-save-btn" type="submit" class="button"><?php esc_html_e('Save', 'makewebbetter-mautic-for-wordpress'); ?></button>
					</td>
				</tr>
			</table>

		</form>
	</div>
</div>