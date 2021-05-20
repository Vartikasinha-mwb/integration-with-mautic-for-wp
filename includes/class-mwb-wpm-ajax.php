<?php
/**
 * Fired during any ajax call
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MWB_Wpm_Ajax {

	/**
	 * Test_api_connection.
	 *
	 * @return void
	 */
	public function test_api_connection() {
		$response = array();
		$response = Mwb_Wpm_Api::get_self_user();
		wp_send_json( $response );
		wp_die();
	}

	/**
	 * Enable_integration.
	 *
	 * @return void
	 */
	public function enable_integration() {
		$response    = array(
			'success' => true,
			'msg'     => 'Success',
		);
		$enable      = sanitize_text_field( wp_unslash( isset( $_POST['enable'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['enable'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$integration = sanitize_text_field( wp_unslash( isset( $_POST['integration'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['integration'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$settings    = get_option( 'mwb_m4wp_integration_settings', array() );
		if ( isset( $settings[ $integration ] ) ) {
			$settings[ $integration ]['enable'] = $enable;
		} else {
			$settings[ $integration ]           = $this->get_integration_default_settings();
			$settings[ $integration ]['enable'] = $enable;
		}
		update_option( 'mwb_m4wp_integration_settings', $settings );
		wp_send_json( $response );
		wp_die();
	}

	/**
	 * Get_integration_default_settings.
	 *
	 * @return array
	 */
	public function get_integration_default_settings() {
		$settings = array(
			'enable'       => 'no',
			'implicit'     => 'no',
			'checkbox_txt' => '',
			'precheck'     => 'no',
			'add_segment'  => '-1',
			'add_tag'      => '',
		);
		return $settings;
	}

	/**
	 * Refresh_data.
	 */
	public function refresh_data() {
		$page         = sanitize_text_field( wp_unslash( isset( $_POST['page'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$helper       = new Mwb_Wpm_Settings_Helper();
		$segment_list = array();
		switch ( $page ) {
			case 'forms':
				$helper->get_forms( true );
				break;

			case 'dashboard':
				$helper     = new Mwb_Wpm_Settings_Helper();
				$date_range = get_option( 'mwb_m4wp_date_range', array() );
				if ( empty( $date_range ) ) {
					$date_range = Wp_Mautic_Integration_Admin::get_default_date_range();
				}
				$time_unit = Wp_Mautic_Integration_Admin::get_time_unit( $date_range );
				$data      = array(
					'dateTo'   => $date_range['date_to'],
					'dateFrom' => $date_range['date_from'],
					'timeUnit' => $time_unit,
				);
				$helper->get_widget_data( 'created.leads.in.time', $data, true );
				$helper->get_widget_data( 'page.hits.in.time', $data, true );
				$helper->get_widget_data( 'submissions.in.time', $data, true );
				$helper->get_widget_data( 'top.lists', $data, true );
				$helper->get_widget_data( 'top.creators', $data, true );
				break;
			case 'segments':
				$segment_list = $helper->get_segment_options( true );
				break;

			default:
				break;
		}
		echo wp_json_encode(
			array(
				'success'      => true,
				'segment_list' => $segment_list,
			)
		);
		wp_die();
	}

	// User Registration Plugin Added.
	/**
	 * Add New Rule For Dynamic Tags.
	 */
	public function add_new_row() {
		$integation_details = MWB_Wpm_Integration_Manager::get_integrations( isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		$integation         = MWB_Wpm_Integration_Manager::get_integration( $integation_details );
		$add_form           = $integation->get_option( 'add_form' );

		echo '<tr style="border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;" class="row-dynamic_tag">
				<th><label style="margin-left:30px;" for="add_form">Form</label></th>
					<td>
						<select name="add_form" class="mwb-m4wp-form-select">
						<option selected value="">--Select--</option>';
		?>
		<?php
		$args  = array(
			'post_type'   => 'user_registration',
			'post_status' => 'publish',
		);
		$query = new WP_Query( $args );
		$posts = $query->posts;
		foreach ( $posts as $post ) {
				$form_id   = $post->ID;
				$form_name = $post->post_title;
			?>
			<?php
					echo '<option value="' . esc_attr( $form_id ) . '"' . selected( esc_attr( $form_id ), $add_form ) . '>
							' . esc_attr( $form_name ) . '
						  </option>';
			?>
<?php } ?>
		<?php
		echo '</select>
						<p class="description">
							Select Form for which the contact should be added.
						</p>
					</td>
			 </tr>';

		echo '<tr style="border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;" class="row-dynamic_tag">
				<th><label style="margin-left:30px;" for="add_tag">Tags</label></th>
				<td>
					<input type="text" name="add_tag" id="add_tag" value="">
					<p class="description">
					Enter tags separated by commas to assign to contact.
					</p>
				</td>
			</tr>';
		wp_die();
	}
	// User Registration Plugin Ended.
}

