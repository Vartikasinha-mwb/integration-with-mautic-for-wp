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
$forms    = $helper->get_forms();
$base_url = MWB_Mautic_For_WP_Admin::get_mautic_base_url();
?>
<div class="wrap">
	<div class="mwb-m4wp-admin-panel-head">
		<h3><?php esc_html_e( 'Mautic Forms', 'makewebbetter-mautic-for-wordpress' ); ?></h3>
		<?php echo $helper->get_refresh_button_html( 'forms' ); ?>
	</div>
	<?php if ( $forms ) : ?>
	<table class="form-table">
		<thead>
			<th><?php esc_html_e( 'Form id', 'makewebbetter-mautic-for-wordpress' ); ?></th>
			<th><?php esc_html_e( 'Form Name', 'makewebbetter-mautic-for-wordpress' ); ?></th>
			<th><?php esc_html_e( 'Status', 'makewebbetter-mautic-for-wordpress' ); ?></th>
			<th><?php esc_html_e( 'ShortCode', 'makewebbetter-mautic-for-wordpress' ); ?></th>
			<th><?php esc_html_e( 'View', 'makewebbetter-mautic-for-wordpress' ); ?></th>
		</thead>
		<tbody>
			<?php
			foreach ( $forms['forms'] as $key => $form ) :
				$form_link = $base_url . '/s/forms/view/' . $form['id'];
				?>
				<tr class="<?php echo ( $form['isPublished'] ) ? 'row-active' : 'row-inactive'; ?>">
					<td><?php echo esc_attr( $form['id'] ); ?></td>
					<td><?php echo esc_attr( $form['name'] ); ?></td>
					<td>
						<?php
						echo ( $form['isPublished'] ) ? 'Published' : 'Not Published';
						?>
					</td>
					<td>
						<input readonly type="text" id="form-input-<?php echo esc_attr( $form['id'] ); ?>"  value="<?php echo esc_attr( '[mwb_m4wp_form id=' . $form['id'] . ']' ); ?>"> 
						<a href="#" class="mwb-m4wp-form-code" form_id="<?php echo esc_attr( $form['id'] ); ?>">
							<span class="dashicons dashicons-editor-paste-text"></span>
						</a>
					</td>
					<td>
						<a class="mwb-m4wp-form-view button" form-id="<?php echo esc_attr( $form['id'] ); ?>" form-html="<?php echo esc_attr( htmlspecialchars( $form['cachedHtml'] ) ); ?>">
							<?php esc_html_e( 'View', 'makewebbetter-mautic-for-wordpress' ); ?>
						</a>
						<a class="mwb-m4wp-form-edit button" href="<?php echo esc_attr( $form_link ); ?>" target="_blank">
							<?php esc_html_e( 'Edit', 'makewebbetter-mautic-for-wordpress' ); ?>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
	<div id="mwb-m4wp-form-html">
	</div>
</div>
