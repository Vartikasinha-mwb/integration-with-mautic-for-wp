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

$helper     = Mwb_Wpm_Settings_Helper::get_instance();
$date_range = get_option( 'mwb_m4wp_date_range', array() );
if ( empty( $date_range ) ) {
	$date_range = Wp_Mautic_Integration_Admin::get_default_date_range();
}
$time_unit             = Wp_Mautic_Integration_Admin::get_time_unit( $date_range );
$data                  = array(
	'dateTo'   => $date_range['date_to'],
	'dateFrom' => $date_range['date_from'],
	'timeUnit' => $time_unit,
);
$created_leads_in_time = $helper->get_widget_data( 'created.leads.in.time', $data );
$page_hits_in_time     = $helper->get_widget_data( 'page.hits.in.time', $data );
$submissions_in_time   = $helper->get_widget_data( 'submissions.in.time', $data );
$top_lists             = $helper->get_widget_data( 'top.lists', $data );
$top_creators          = $helper->get_widget_data( 'top.creators', $data );
$base_url              = Wp_Mautic_Integration_Admin::get_mautic_base_url();
?>

<div class="mwb-col-wrap">
	<div class="table-responsive">
		<div class="mwb-m4wp-admin-panel-date">
			<form method="post">
				<span>
					<label for="mwb_m4wp_from_date"><?php esc_html_e( 'From', 'wp-mautic-integration' ); ?></label>
					<input type="text" name="mwb_m4wp_from_date" class="mwb-m4wp-datepicker" value="<?php echo esc_attr( $date_range['date_from'] ); ?>" />
				</span>
				<span>
					<label for="mwb_m4wp_from_date"><?php esc_html_e( 'To', 'wp-mautic-integration' ); ?></label>
					<input type="text" name="mwb_m4wp_to_date" class="mwb-m4wp-datepicker" value="<?php echo esc_attr( $date_range['date_to'] ); ?>" />
				</span>
				<input type="hidden" name="action" value="mwb_m4wp_date_range" />
				<button type="submit" class="mwb-btn mwb-btn-secondary mwb-save-btn"><?php esc_html_e( 'Apply', 'wp-mautic-integration' ); ?></button>
				<?php
				//phpcs:disable
				echo $helper->get_refresh_button_html( 'dashboard' );
				//phpcs:enable
				?>
			</form>
		</div>
	</div>
</div>

<?php if ( $created_leads_in_time ) : ?>
	<div class="mwb-col-wrap">
		<div class="table-responsive">
			<div class="mwb-m4wp-admin-widget-row">
				<div class="mwb-m4wp-admin-widget-head">
					<h2><?php esc_html_e( 'Created Leads In Time', 'wp-mautic-integration' ); ?></h2>
				</div>
				<?php if ( isset( $created_leads_in_time['data'] ) && ! empty( $created_leads_in_time['data'] ) ) : ?>
					<div id="created-leads-in-time" class="mwb-m4wp-admin-widget-wrap" data="<?php echo esc_attr( htmlspecialchars( wp_json_encode( $created_leads_in_time['data'] ) ) ); ?>">
						<canvas id="created-leads-in-time-chart" style="width: 100%; height: 300px;"></canvas>
					</div>
				<?php else : ?>
					<div class="widget-now-data-wrap">
						<p><?php esc_html_e( 'No data available', 'wp-mautic-integration' ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php if ( $page_hits_in_time ) : ?>
	<div class="mwb-col-wrap">
		<div class="table-responsive">
			<div class="mwb-m4wp-admin-widget-row">
				<div class="mwb-m4wp-admin-widget-head">
					<h2><?php esc_html_e( 'Page Hits In Time', 'wp-mautic-integration' ); ?></h2>
				</div>
				<?php if ( isset( $page_hits_in_time['data'] ) && ! empty( $page_hits_in_time['data'] ) ) : ?>
					<div id="page-hits-in-time" class="mwb-m4wp-admin-widget-wrap" data="<?php echo esc_attr( htmlspecialchars( wp_json_encode( $page_hits_in_time['data'] ) ) ); ?>">
						<canvas id="page-hits-in-time-chart" style="width: 100%; height: 300px;"></canvas>
					</div>
				<?php else : ?>
					<div class="widget-now-data-wrap">
						<p><?php esc_html_e( 'No data available', 'wp-mautic-integration' ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php endif; ?>

<?php if ( $submissions_in_time ) : ?>
	<div class="mwb-col-wrap">
		<div class="table-responsive">
			<div class="mwb-m4wp-admin-widget-row">
				<div class="mwb-m4wp-admin-widget-head">
					<h2><?php esc_html_e( 'Form Submissions In Time', 'wp-mautic-integration' ); ?></h2>
				</div>
				<?php if ( isset( $submissions_in_time['data'] ) && ! empty( $submissions_in_time['data'] ) ) : ?>
					<div id="submissions-in-time" class="mwb-m4wp-admin-widget-wrap" data="<?php echo esc_attr( htmlspecialchars( wp_json_encode( $submissions_in_time['data'] ) ) ); ?>">
						<canvas id="submissions-in-time-chart" style="width: 100%; height: 300px;"></canvas>
					</div>
				<?php else : ?>
					<div class="widget-now-data-wrap">
						<p><?php esc_html_e( 'No data available', 'wp-mautic-integration' ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<div class="mwb-dashboard-table-wrap">
	<?php if ( $top_lists ) : ?>
		<div class="mwb-col-wrap">
			<div class="table-responsive">
				<div class="mwb-m4wp-admin-widget-column">
					<div class="mwb-m4wp-admin-widget-head">
						<h2><?php esc_html_e( 'Top Segments', 'wp-mautic-integration' ); ?></h2>
					</div>
					<div id="top-lists" class="mwb-m4wp-admin-widget-wrap" data="<?php echo esc_attr( htmlspecialchars( wp_json_encode( $top_lists['data'] ) ) ); ?>">
		<?php if ( count( $top_lists['data']['bodyItems'] ) > 0 ) : ?>
							<table id="top-lists-table" class="widget-table mwb-table"  cellspacing=0>
								<tr>
									<th><?php esc_html_e( 'Segments', 'wp-mautic-integration' ); ?></th>
									<th><?php esc_html_e( 'Contacts', 'wp-mautic-integration' ); ?></th>
								</tr>
			<?php
			$seg_id     = '';
			$seg_id_new = '';
			foreach ( $top_lists['data']['bodyItems'] as $key => $list ) :
				$seg_id      = explode( 's/segments/edit/', $list[0]['link'] );
				$seg_id_new  = $seg_id[1];
				$support_url = 's/segments/edit/';
				?>
									<tr>
										<td><a target="_blank" href="<?php echo esc_attr( $base_url . $support_url . $seg_id_new ); ?>"><?php echo esc_attr( $list[0]['value'] ); ?></a></td>
										<td><a target="_blank" href="<?php echo esc_attr( $base_url . $seg_id_new ); ?>"><?php echo esc_attr( $list[1]['value'] ); ?></a></td>
									</tr>
			<?php endforeach; ?>
							</table>
						<?php else : ?>
							<div class="widget-now-data-wrap">
								<p>
							<?php esc_html_e( 'No data available', 'wp-mautic-integration' ); ?>

								</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( $top_creators ) : ?>
		<div class="mwb-col-wrap">
			<div class="table-responsive">
				<div class="mwb-m4wp-admin-widget-column">
					<div class="mwb-m4wp-admin-widget-head">
						<h2><?php esc_html_e( 'Top Creators', 'wp-mautic-integration' ); ?>
						</h2>
					</div>
					<div id="top-lists" class="mwb-m4wp-admin-widget-wrap" data="<?php echo esc_attr( htmlspecialchars( wp_json_encode( $top_lists['data'] ) ) ); ?>">
		<?php if ( count( $top_creators['data']['bodyItems'] ) > 0 ) : ?>
							<table id="top-lists-table" class="widget-table" cellspacing=0>
								<tr>
									<th><?php esc_html_e( 'Creator', 'wp-mautic-integration' ); ?></th>
									<th><?php esc_html_e( 'Contacts', 'wp-mautic-integration' ); ?></th>
								</tr>
			<?php foreach ( $top_creators['data']['bodyItems'] as $key => $list ) : ?>
									<tr>
										<?php //phpcs:disable ?>
										<td><a target="_blank" href="<?php echo $helper->get_item_link( $list[0]['link'], esc_attr( $base_url ) ); ?>"><?php echo esc_html( $list[0]['value'] ); ?></a></td>
										<?php //phpcs:enable ?>
										<td><?php echo esc_html( $list[1]['value'] ); ?></td>
									</tr>
			<?php endforeach; ?>
							</table>
						<?php else : ?>
							<div class="widget-now-data-wrap">
								<p><?php esc_html_e( 'No data available', 'wp-mautic-integration' ); ?></p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
