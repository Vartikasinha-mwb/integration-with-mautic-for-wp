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

$helper   = Mwb_Wpm_Settings_Helper::get_instance();
$forms    = $helper->get_forms();
$base_url = Wp_Mautic_Integration_Admin::get_mautic_base_url();
?>

<div class="mwb-m4wp-form-table-wrap">
	<?php if ( $forms['total'] > 0 ) : ?>
		<div class="mwb-m4wp-form-table-head">
			<a class="mwb-btn mwb-btn-secondary mwb-m4wp-form-refresh">
		<?php esc_html_e( 'Refresh', 'wp-mautic-integration' ); ?>
			</a>
		</div>
		<table class="form-table mwb-form-stripped-table mwb-table">
			<thead>
				<th><?php esc_html_e( 'Form id', 'wp-mautic-integration' ); ?></th>
				<th><?php esc_html_e( 'Form Name', 'wp-mautic-integration' ); ?></th>
				<th><?php esc_html_e( 'Status', 'wp-mautic-integration' ); ?></th>
				<th><?php esc_html_e( 'ShortCode', 'wp-mautic-integration' ); ?></th>
				<th class="mwb-heading-center"><?php esc_html_e( 'View', 'wp-mautic-integration' ); ?></th>
			</thead>
			<tbody>
		<?php
		foreach ( $forms['forms'] as $key => $form ) :
			$form_link = $base_url . 's/forms/view/' . $form['id'];
			?>
					<tr class="<?php echo ( $form['isPublished'] ) ? 'row-active' : 'row-inactive'; ?>">
						<td><?php echo esc_attr( $form['id'] ); ?></td>
						<td><?php echo esc_attr( $form['name'] ); ?></td>
						<td>
			<?php
			echo ( $form['isPublished'] ) ? 'Published' : 'Not Published';
			?>
						</td>
						<td class=" mwb-form-code">
							<input readonly type="text" id="form-input-<?php echo esc_attr( $form['id'] ); ?>" value="<?php echo esc_attr( '[mwb_m4wp_form id=' . $form['id'] . ']' ); ?>">
							<a href="#" class="mwb-m4wp-form-code" form_id="<?php echo esc_attr( $form['id'] ); ?>">
								<span class="dashicons dashicons-editor-paste-text mwb-paste-text-icon"></span>
							</a>
						</td>
						<td>
							<a class="mwb-m4wp-form-view mwb-btn mwb-btn-secondary" form-id="<?php echo esc_attr( $form['id'] ); ?>" form-html="<?php echo esc_attr( htmlspecialchars( $form['cachedHtml'] ) ); ?>">
			<?php esc_html_e( 'View', 'wp-mautic-integration' ); ?>
							</a>
							<a class="mwb-m4wp-form-edit mwb-btn mwb-btn-secondary" href="<?php echo esc_attr( $form_link ); ?>" target="_blank">
			<?php esc_html_e( 'Edit', 'wp-mautic-integration' ); ?>
							</a>
						</td>
					</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
	<div id="forms-refresh-section">
		<a class="mwb-btn mwb-btn-secondary mwb-m4wp-form-refresh">
			<?php esc_html_e( 'Refresh', 'wp-mautic-integration' ); ?>
		</a><br>
		<center><h3 id="no-forms-heading">
		<?php echo esc_html( 'Sorry , there are no forms available on your mautic to show.' ); ?>
		</h3>
		</center>
	</div>
	<?php endif; ?>
</div>

<div id="mwb-m4wp-form-html">
	<div id="mwb-m4wp-form-head" class="mwb-m4wp-form-head">
		<h2>Form Preview</h2>
		<div class="mwb-preview-close">
			<span id="mwb-preview-close--icon" class="dashicons dashicons-plus-alt mwb-preview-close--icon"></span>
		</div>
	</div>
	<div id="mwb-m4wp-form-main">
	</div>
</div>
