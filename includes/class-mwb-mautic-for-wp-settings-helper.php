<?php
/**
 * The file that defines settings of the WordPress
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    MWB_Mautic_For_WP
 * @subpackage MWB_Mautic_For_WP/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MWB_Mautic_For_WP
 * @subpackage MWB_Mautic_For_WP/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MWB_Mautic_For_WP_Settings_Helper {

	/**
	 * Instance.
	 *
	 * @var [type]
	 */
	private static $_instance;

	/**
	 * Get mautic forms.
	 *
	 * @since       1.0.0
	 * @param       bool $refresh         The basic class for the refresh.
	 * @return array $forms Forms array.
	 */
	public function get_forms( $refresh = false ) {
		if ( ! $refresh && get_transient( 'mwb_m4wp_forms' ) ) {
			return get_transient( 'mwb_m4wp_forms' );
		}
		$forms = MWB_Mautic_For_WP_Api::get_forms();
		set_transient( 'mwb_m4wp_forms', $forms, DAY_IN_SECONDS );
		return $forms;
	}

	/**
	 * Returns form fields html.
	 *
	 * @since       3.0.0
	 * @param       string $widget               widget is retrieved.
	 * @param       array  $data         The basic class for the data.
	 * @param       bool   $refresh         The basic class for the refresh.
	 */
	public function get_widget_data( $widget, $data, $refresh = false ) {
		$key         = $widget . '-' . implode( '-', $data );
		$cached_data = get_transient( $key );
		if ( ! $refresh && $cached_data ) {
			return $cached_data;
		}
		$created_leads_in_time = MWB_Mautic_For_WP_Api::get_widget_data( $widget, $data );
		set_transient( $key, $created_leads_in_time, DAY_IN_SECONDS );
		return $created_leads_in_time;
	}

	/**
	 * Get instance.
	 *
	 * @since 1.0.0
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Returns form fields html.
	 *
	 * @param bool $refresh Refresh.
	 */
	public function get_segment_options( $refresh = false ) {

		$segment_list = get_transient( 'mwb_m4wp_segment_list' );
		if ( $segment_list && ! $refresh ) {
			return $segment_list;
		}
		$segments = MWB_Mautic_For_WP_Api::get_segments();
		if ( ! $segments ) {
			return array();
		}
		$options   = array();
		$options[] = array(
			'id'   => -1,
			'name' => __( '--Select--', 'makewebbetter-mautic-for-wordpress' ),
		);
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

	/**
	 * Returns form fields html.
	 *
	 * @since       1.0.0
	 * @param       string $page               widget is retrieved.
	 */
	public function get_refresh_button_html( $page = '' ) {
		$html = '<a href="#" class="mwb-m4wp-refresh-btn mwb-refresh-btn" page="' . $page . '">
            <span class="dashicons dashicons-update-alt mwb-refresh-icon"></span>
        </a>';
		return $html;
	}


	/**
	 * Get mautic link for widget item.
	 *
	 * @since       1.0.0
	 * @param       string $temp_link              item link.
	 * @param       string $base_url               base url.
	 */
	public function get_item_link( $temp_link, $base_url ) {

		$link = $temp_link;
		if ( strpos( $temp_link, 's' ) !== false ) {
			$link_arr = explode( '/s/', $temp_link );
			if ( isset( $link_arr[1] ) ) {
				$link = $base_url . 's/' . $link_arr[1];
			}
		}

		return $link;
	}

	/**
	 * Get admin panel tabs.
	 *
	 * @since 1.0.0
	 */
	public function get_settings_tab() {
		$tabs = array(
			array(
				'id'         => 'connection',
				'name'       => __( 'Connection', 'makewebbetter-mautic-for-wordpress' ),
				'dependency' => '',
			),
			array(
				'id'         => 'dashboard',
				'name'       => __( 'Dashboard', 'makewebbetter-mautic-for-wordpress' ),
				'dependency' => 'is_connection_setup',
			),
			array(
				'id'         => 'forms',
				'name'       => __( 'Mautic Forms', 'makewebbetter-mautic-for-wordpress' ),
				'dependency' => 'is_connection_setup',
			),
			array(
				'id'         => 'integration',
				'name'       => __( 'Integrations', 'makewebbetter-mautic-for-wordpress' ),
				'dependency' => 'is_connection_setup',
			),
			array(
				'id'         => 'settings',
				'name'       => __( 'Settings', 'makewebbetter-mautic-for-wordpress' ),
				'dependency' => '',
			),
		);
		return $tabs;
	}

	/**
	 * Checks if mautic api is connected.
	 *
	 * @since 1.0.0
	 */
	public function is_connection_setup() {
		return get_option( 'mwb_m4wp_connection_status', false );
	}

	/**
	 * Get mautic link for widget item.
	 *
	 * @since 1.0.0
	 * @param string $selected selected tab.
	 */
	public function get_settings_tab_html( $selected = 'connection' ) {

		$tabs      = $this->get_settings_tab();
		$tabs_html = '';

		foreach ( $tabs as $key => $tab ) {
			$callback = $tab['dependency'];
			if ( ! empty( $callback ) && ! $this->$callback() ) {
				continue;
			}
			$active     = ( $tab['id'] === $selected ) ? 'active' : '';
			$tabs_html .= '<li><a href="?page=mwb-mautic-for-wp&tab=' . $tab['id'] . '" class="mwb-link ' . $active . '">' . $tab['name'] . '</a></li>';
		}

		return $tabs_html;
	}

	/**
	 * Load admin template.
	 *
	 * @since 1.0.0
	 * @param string $file_name name of file.
	 * @param array  $params    extra params.
	 */
	public function load_admin_template( $file_name, $params = array() ) {

		$file = MWB_MAUTIC_FOR_WP_PATH . 'admin/partials/' . $file_name . '.php';

		if ( file_exists( $file ) ) {

			include $file;

		} else {

			echo 'Something went wrong';

		}

	}

	/**
	 * Get plugin version text.
	 *
	 * @since 1.0.0
	 */
	public function get_plugin_version_txt() {
		$version = '';
		if ( defined( 'MWB_MAUTIC_FOR_WP_VERSION' ) ) {
			$version = __( 'Version', 'makewebbetter-mautic-for-wordpress' ) . ' ' . MWB_MAUTIC_FOR_WP_VERSION;
		}
		return $version;
	}
}
