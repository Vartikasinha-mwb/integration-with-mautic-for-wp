<?php

class Mautic_For_Wordpress_Settings_Helper {

	public function get_forms( $refresh = false ) {
		if ( ! $refresh && get_transient( 'mwb_m4wp_forms' ) ) {
			return get_transient( 'mwb_m4wp_forms' );
		}
		$forms = MWB_M4WP_Mautic_Api::get_forms();
		set_transient( 'mwb_m4wp_forms', $forms, DAY_IN_SECONDS );
		return $forms;
	}

	public function get_widget_data( $widget, $data, $refresh = false ) {
		$key         = $widget . '-' . implode( '-', $data );
		$cached_data = get_transient( $key );
		if ( ! $refresh && $cached_data ) {
			return $cached_data;
		}
		$created_leads_in_time = MWB_M4WP_Mautic_Api::get_widget_data( $widget, $data );
		set_transient( $key, $created_leads_in_time, DAY_IN_SECONDS );
		return $created_leads_in_time;
	}

	public function get_segment_options( $refresh = false ) {

		$segment_list = get_transient( 'mwb_m4wp_segment_list' );
		if ( $segment_list && ! $refresh ) {
			return $segment_list;
		}
		$segments = MWB_M4WP_Mautic_Api::get_segments();
		if ( ! $segments ) {
			return array();
		}
		$options = array();
		if ( isset( $segments['lists'] ) && count( $segments['lists'] ) > 0 ) {
			foreach ( $segments['lists'] as $key => $segment ) {
				if ( $segment['isPublished'] ) {
					$options[] = array(
						'id'   => $segment['id'],
						'name' => $segment['name'],
					);
				}
			}
		}
		set_transient( 'mwb_m4wp_segment_list', $options, DAY_IN_SECONDS );
		return $options;
	}

	public function get_refresh_button_html( $page = '' ) {
		$html = '<a href="#" class="mwb-m4wp-refresh-btn" page="' . $page . '">
            <span class="dashicons dashicons-update-alt"></span>
        </a>';
		return $html;
	}
}
