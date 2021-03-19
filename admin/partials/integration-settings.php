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

$helper             = Mwb_Wpm_Settings_Helper::get_instance();
$segment_list       = $helper->get_segment_options();
$integation_details = MWB_Wpm_Integration_Manager::get_integrations( isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
$integation         = MWB_Wpm_Integration_Manager::get_integration( $integation_details );
$enable             = $integation->is_enabled();
$implicit           = $integation->is_implicit();
$checkbox_txt       = $integation->get_option( 'checkbox_txt' );
$precheck           = $integation->is_checkbox_precheck();
$add_segment        = $integation->get_option( 'add_segment' );
$add_tag            = $integation->get_option( 'add_tag' );
$hide_row           = $implicit ? 'row-hide' : '';
?>
<div class="wrap">
	<div class="mwb-m4wp-admin-panel-main mwb-m4wp-admin-integration-panel">
		<div class="mwb-m4wp-admin-form-wrap-title">
		<a href="?page=mwb-mautic-for-wp&tab=integration"><span class="dashicons dashicons-arrow-left-alt mwb-arrow-left"></span></a>
		<h2><?php echo esc_attr( $integation->get_name() ); ?></h2>
		</div>
		<div class="mwb-m4wp-admin-form-wrap mwb-m4wp-admin-integration-form-wrap">
			<form action="" method="post">
				<table class="form-table mwb-m4wp-admin-table mwb-admin-table">
					<tr>
						<th><label for="enable"><?php esc_html_e( 'Enable', 'wp-mautic-integration' ); ?></label></th>
						<td>
							<input type="radio" value="yes" name="enable" <?php checked( true, $enable ); ?>>
							<label><?php esc_html_e( 'Yes', 'wp-mautic-integration' ); ?></label>
							<input type="radio" value="no" name="enable" <?php checked( false, $enable ); ?>>
							<label><?php esc_html_e( 'No', 'wp-mautic-integration' ); ?></label>
							<p class="description">
								<?php esc_html_e( 'Select "yes" to enable the integration. ', 'wp-mautic-integration' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th><label for="implicit"><?php esc_html_e( 'Implicit', 'wp-mautic-integration' ); ?></label></th>
						<td>
							<input class="mwb-m4wp-implicit-cb" type="radio" value="yes" name="implicit" <?php checked( true, $implicit ); ?>>
							<label><?php esc_html_e( 'Yes', 'wp-mautic-integration' ); ?></label>
							<input class="mwb-m4wp-implicit-cb" type="radio" value="no" name="implicit" <?php checked( false, $implicit ); ?>>
							<label><?php esc_html_e( 'No', 'wp-mautic-integration' ); ?></label>
							<p class="description">
								<?php esc_html_e( 'Select "yes" if you want to subscribe people without asking them explicitly.', 'wp-mautic-integration' ); ?>
							</p>
						</td>
					</tr>
					<tr class="row-implicit <?php echo esc_attr( $hide_row ); ?>">
						<th><label for="checkbox_txt"><?php esc_html_e( 'Checkbox Label Text', 'wp-mautic-integration' ); ?></label></th>
						<td>
							<input type="text" name="checkbox_txt" value="<?php echo esc_attr( $checkbox_txt ); ?>">
							<p class="description">
								<?php esc_html_e( 'Checkbox label to be shown next to checkbox.', 'wp-mautic-integration' ); ?>
							</p>
						</td>
					</tr>
					<tr class="row-implicit <?php echo esc_attr( $hide_row ); ?>">
						<th><label for="precheck"><?php esc_html_e( 'Pre Check Checkbox', 'wp-mautic-integration' ); ?></label></th>
						<td>
							<input type="radio" value="yes" name="precheck" <?php checked( true, $precheck ); ?>>
							<label><?php esc_html_e( 'Yes', 'wp-mautic-integration' ); ?></label>
							<input type="radio" value="no" name="precheck" <?php checked( false, $precheck ); ?>>
							<label><?php esc_html_e( 'No', 'wp-mautic-integration' ); ?></label>
							<p class="description">
								<?php esc_html_e( 'Select "yes" if you want to check the checkbox by default.', 'wp-mautic-integration' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th><label for="add_segment"><?php esc_html_e( 'Segment', 'wp-mautic-integration' ); ?></label></th>
						<td>
							<select name="add_segment" id="mwb-m4wp-segment-select">
								<?php foreach ( $segment_list as $key => $segment ) : ?>
									<option value="<?php echo esc_attr( $segment['id'] ); ?>" <?php esc_attr( selected( $segment['id'], $add_segment ) ); ?>>
									<?php echo esc_attr( $segment['name'] ); ?>
									</option>
								<?php endforeach; ?>
							</select>
								<?php // phpcs:disable ?>
								<?php echo $helper->get_refresh_button_html( 'segments' ); ?>
								<?php // phpcs:enable ?>
							<p class="description">
								<?php esc_html_e( 'Select segment in which the contact should be added.', 'wp-mautic-integration' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th><label for="add_tag"><?php esc_html_e( 'Tags', 'wp-mautic-integration' ); ?></label></th>
						<td>
							<input type="text" name="add_tag" value="<?php echo esc_attr( $add_tag ); ?>">
							<p class="description">
								<?php esc_html_e( 'Enter tags separated by commas to assign to contact.', 'wp-mautic-integration' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th></th>
						<td>
							<div class="mwb-m4wp-admin-button-wrap">
								<button class="button mwb-m4wp-admin-button" type="submit"><?php esc_html_e( 'Save', 'wp-mautic-integration' ); ?></button>
							</div>
						</td>
					</tr>
				</table>
				<input type="hidden" name="_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mwb_m4wp_integration_nonce' ) ); ?>" />
				<input type="hidden" name="integration" value="<?php echo esc_attr( $integation->get_id() ); ?>" />
				<input type="hidden" name="action" value="mwb_m4wp_integration_save" />    
			</form>
		</div>
	</div>
</div>