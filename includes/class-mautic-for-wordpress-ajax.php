<?php

class Mautic_For_Wordpress_Ajax {

	public function test_api_connection() {
		$response = array();
		$response = MWB_M4WP_Mautic_Api::get_self_user();
		wp_send_json( $response );
		wp_die();
	}

	public function enable_integration() {
		$response    = array(
			'success' => true,
			'msg'     => 'Success',
		);
		$enable      = $_POST['enable'];
		$integration = $_POST['integration'];
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

	public function refresh_data() {
		$page         = $_POST['page'];
		$helper       = new Mautic_For_Wordpress_Settings_Helper();
		$segment_list = array();
		switch ( $page ) {
			case 'forms':
				$helper->get_forms( true );
				break;

			case 'dashboard':
				$helper     = new Mautic_For_Wordpress_Settings_Helper();
				$date_range = get_option( 'mwb_m4wp_date_range', array() );
				if ( empty( $date_range ) ) {
					$date_range = Mautic_For_Wordpress_Admin::get_default_date_range();
				}
				$time_unit = Mautic_For_Wordpress_Admin::get_time_unit( $date_range );
				$data      = array(
					'dateTo'   => $date_range['date_to'],
					'dateFrom' => $date_range['date_from'],
					'timeUnit' => $time_unit,
				);
				$helper->get_widget_data( 'created.leads.in.time', $data, true );
				$helper->get_widget_data( 'page.hits.in.time', $data, true );
				$helper->get_widget_data( 'submissions.in.time', $data, true );
				$helper->get_widget_data( 'top.lists', $data, true );
				break;

			case 'segments':
				$segment_list = $helper->get_segment_options( true );
				break;

			default:
				break;
		}
		echo json_encode(
			array(
				'success'      => true,
				'segment_list' => $segment_list,
			)
		);
		wp_die();
	}
}

