<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/admin
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Wp_Mautic_Integration_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    array  $links       The links of this plugin extra details.
	 * @param    string $file        The version of this plugin.
	 * @return   array - Links.
	 */
	public function mwb_docs( $links, $file ) {

		if ( 'integration-with-mautic-for-wp/wp-mautic-integration.php' === $file ) {
			$row_meta = array(
				'docs'    => '<a href="' . esc_url( 'https://docs.makewebbetter.com/wp-mautic-integration/?utm_source=MWB-wpmautic-org&utm_medium=MWB-org-backend&utm_campaign=MWB-wpmautic-doc' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'wp-mautic-integration' ) . '"><img src="' . plugin_dir_url( dirname( __FILE__ ) ) . 'admin/src/images/Documentation.svg" class="mwb_m4wp_plugin_extra_custom_tab">' . esc_html__( 'Documentation', 'wp-mautic-integration' ) . '</a>',
				'support' => '<a href="' . esc_url( 'https://makewebbetter.com/submit-query/?utm_source=MWB-wpmautic-org&utm_medium=MWB-org-backend&utm_campaign=MWB-wpmautic-suppport' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'wp-mautic-integration' ) . '"><img src="' . plugin_dir_url( dirname( __FILE__ ) ) . 'admin/src/images/Support.svg" class="mwb_m4wp_plugin_extra_custom_tab">' . esc_html__( 'Support', 'wp-mautic-integration' ) . '</a>',
			);
			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

	/**
	 * Adding settings menu for mautic-for-wordpress.
	 *
	 * @since    1.0.0
	 */
	public function m4wp_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( 'MakeWebBetter', 'MakeWebBetter', 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), MWB_WP_MAUTIC_URL . 'admin/src/images/MWB_White-01-01-01.svg', 15 );
			$gaq_menus = apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $gaq_menus ) && ! empty( $gaq_menus ) ) {
				foreach ( $gaq_menus as $gaq_key => $gaq_value ) {
					add_submenu_page( 'mwb-plugins', $gaq_value['name'], $gaq_value['name'], 'manage_options', $gaq_value['menu_link'], array( $gaq_value['instance'], $gaq_value['function'] ) );
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since   1.0.0
	 */
	public function mwb_m4wp_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'mwb-plugins', $submenu ) ) {
			if ( isset( $submenu['mwb-plugins'][0] ) ) {
				unset( $submenu['mwb-plugins'][0] );
			}
		}
	}

	/**
	 * M4wp_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 * @return array - Submenu.
	 */
	public function m4wp_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'      => __( 'Integration with Mautic for WP', 'wp-mautic-integration' ),
			'slug'      => 'manage_options',
			'menu_link' => 'mwb-wp-mautic',
			'instance'  => $this,
			'function'  => 'include_admin_menu_display',
		);
		return $menus;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Mautic_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Mautic_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$current_screen = get_current_screen();
		$screens        = $this->get_plugin_screens();
		if ( isset( $current_screen ) && in_array( $current_screen->id, $screens, true ) ) {
			wp_enqueue_style( 'mwb-wpm-onboarding-style', plugin_dir_url( __FILE__ ) . 'css/makewebbetter-onboarding-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'mwb-wpm-select2-style', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'mwb-wpm-jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'mwb-wpm-admin-style', plugin_dir_url( __FILE__ ) . 'css/mwb-wpm-style.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( 'mwb-wpm-custom-admin-icon', plugin_dir_url( __FILE__ ) . 'src/scss/mwb-mautic-for-wordpress-admin-custom.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'mwb-a', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Mautic_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Mautic_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$current_screen = get_current_screen();
		$screens        = $this->get_plugin_screens();
		if ( isset( $current_screen ) && in_array( $current_screen->id, $screens, true ) ) {
			wp_enqueue_script( 'mwb-wpm-chart-script', plugin_dir_url( __FILE__ ) . 'chart/chart.js', array( 'jquery' ), '1.0.0', false );
			wp_enqueue_style( 'mwb-wpm-chart-style', plugin_dir_url( __FILE__ ) . 'chart/chart.css', array(), '1.0.0' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'mwb-wpm-admin-script', plugin_dir_url( __FILE__ ) . 'js/mwb-wpm-admin.js', array( 'jquery', 'jquery-ui-datepicker', 'mwb-wpm-chart-script' ), $this->version, false );
			$ajax_data = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			);
			wp_localize_script( 'mwb-wpm-admin-script', 'ajax_data', $ajax_data );
		}

	}

	/**
	 * Get_plugin_screens function
	 *
	 * @return array
	 */
	public function get_plugin_screens() {
		return array(
			'toplevel_page_mwb-wp-mautic',
			'makewebbetter_page_mwb-wp-mautic',
		);
	}

	/**
	 * Include_settings_display function
	 */
	public function include_admin_menu_display() {
		$file_path = 'admin/partials/mwb-wpm-admin-display.php';
		self::load_template( $file_path );
	}

	/**
	 * Check and include admin view file
	 *
	 * @param string $file_path Relative path of file.
	 * @param array  $params Array of extra params.
	 */
	public static function load_template( $file_path, $params = array() ) {
		$file = MWB_WP_MAUTIC_PATH . $file_path;
		if ( file_exists( $file ) ) {
			include $file;
		} else {
			esc_attr_e( 'Something went wrong', 'wp-mautic-integration' );
		}
	}

	/**
	 * Check and include admin view file
	 *
	 * @param string $date_range Define the range of date.
	 */
	public static function get_time_unit( $date_range ) {
		$time_unit = 'm';
		$to        = strtotime( $date_range['date_to'] );
		$from      = strtotime( $date_range['date_from'] );
		$diff      = $to - $from;
		$days      = $diff / ( 24 * 60 * 60 );
		switch ( $days ) {
			case ( $days < 61 ):
				$time_unit = 'd';
				break;
			case ( $days > 61 && $days < 91 ):
				$time_unit = 'W';
				break;
			case ( $days > 91 && $days < 366 ):
				$time_unit = 'm';
				break;
			case ( $days > 366 ):
				$time_unit = 'Y';
				break;
		}
		return $time_unit;
	}

	/**
	 * Check and include admin view file
	 *
	 * @return date
	 */
	public static function get_default_date_range() {
		$date_to   = gmdate( 'Y-m-d' );
		$date_from = gmdate( 'Y-m-d', strtotime( '-1 month' ) );
		return array(
			'date_to'   => $date_to,
			'date_from' => $date_from,
		);
	}

	/**
	 * Save_admin_settings function
	 */
	public function save_admin_settings() {

		$settings_notice = __( 'Settings Saved', 'wp-mautic-integration' );

		if ( isset( $_POST['action'] ) && 'mwb_m4wp_save' === $_POST['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification

			if ( wp_verify_nonce( isset( $_POST['_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ) : '', '_nonce' ) ) {

				$auth_type                    = sanitize_text_field( wp_unslash( isset( $_POST['authentication_type'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['authentication_type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$baseurl                      = sanitize_text_field( wp_unslash( isset( $_POST['baseurl'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['baseurl'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$baseurl                      = rtrim( $baseurl, '/' );
				$credentials                  = array();
				$credentials['client_id']     = sanitize_text_field( wp_unslash( isset( $_POST['client_id'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['client_id'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$credentials['client_secret'] = sanitize_text_field( wp_unslash( isset( $_POST['client_secret'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['client_secret'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$credentials['username']      = sanitize_text_field( wp_unslash( isset( $_POST['username'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$credentials['password']      = sanitize_text_field( wp_unslash( isset( $_POST['password'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				update_option( 'mwb_m4wp_auth_details', $credentials );
				update_option( 'mwb_m4wp_auth_type', $auth_type );
				update_option( 'mwb_m4wp_base_url', $baseurl );

				if ( 'basic' === $auth_type ) {
					$user = MWB_Wpm_Api::get_self_user();
					wp_cache_set( 'mwb_m4wp_user_data', $user );
					wp_cache_set( 'mwb_m4wp_notice', $user['msg'] );
				}
			}
		}

		if ( 'mwb_m4wp_setting_save' === ( isset( $_POST['action'] ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '' ) && isset( $_POST['action'] ) && sanitize_text_field( wp_unslash( $_POST['action'] ) ) ) {

			if ( wp_verify_nonce( isset( $_POST['_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ) : '', '_nonce' ) ) {

				$enable   = isset( $_POST['mwb_m4wp_tracking_enable'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_m4wp_tracking_enable'] ) ) : 'no';
				$location = isset( $_POST['mwb_m4wp_script_location'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_m4wp_script_location'] ) ) : 'footer';
				$base_url = isset( $_POST['mwb_m4wp_base_url'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_m4wp_base_url'] ) ) : 'ss';
				update_option( 'mwb_m4wp_script_location', $location );
				update_option( 'mwb_m4wp_tracking_enable', $enable );
				update_option( 'mwb_m4wp_base_url', $base_url );
				wp_cache_set( 'mwb_m4wp_notice', $settings_notice );
			}
		}

		if ( 'mwb_m4wp_date_range' === ( isset( $_POST['action'] ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '' ) && sanitize_text_field( wp_unslash( $_POST['action'] ) ) ) {
			$date_range = array(
				'date_from' => sanitize_text_field( wp_unslash( isset( $_POST['mwb_m4wp_from_date'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['mwb_m4wp_from_date'] ) ) : '',
				'date_to'   => sanitize_text_field( wp_unslash( isset( $_POST['mwb_m4wp_to_date'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['mwb_m4wp_to_date'] ) ) : '',
			);
			update_option( 'mwb_m4wp_date_range', $date_range );
			wp_cache_set( 'mwb_m4wp_notice', $settings_notice );
		}

		if ( 'mwb_m4wp_integration_save' === ( isset( $_POST['action'] ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '' ) && sanitize_text_field( wp_unslash( $_POST['action'] ) ) ) {
			if ( wp_verify_nonce( isset( $_POST['_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ) : '', 'mwb_m4wp_integration_nonce' ) ) {
				if ( isset( $_POST['integration'] ) && '' !== $_POST['integration'] ) {
					$integration              = isset( $_POST['integration'] ) ? sanitize_text_field( wp_unslash( $_POST['integration'] ) ) : '';
					$enable                   = isset( $_POST['enable'] ) ? sanitize_text_field( wp_unslash( $_POST['enable'] ) ) : 'no';
					$implicit                 = isset( $_POST['implicit'] ) ? sanitize_text_field( wp_unslash( $_POST['implicit'] ) ) : 'no';
					$checkbox_txt             = isset( $_POST['checkbox_txt'] ) ? sanitize_text_field( wp_unslash( $_POST['checkbox_txt'] ) ) : '';
					$precheck                 = isset( $_POST['precheck'] ) ? sanitize_text_field( wp_unslash( $_POST['precheck'] ) ) : 'no';
					$add_segment              = isset( $_POST['add_segment'] ) ? sanitize_text_field( wp_unslash( $_POST['add_segment'] ) ) : '-1';
					$add_segment_ur           = isset( $_POST['add_segment_ur'] ) ? sanitize_text_field( wp_unslash( $_POST['add_segment_ur'] ) ) : '-1';
					$add_tag                  = isset( $_POST['add_tag'] ) ? sanitize_text_field( wp_unslash( $_POST['add_tag'] ) ) : '';
					$settings                 = get_option( 'mwb_m4wp_integration_settings', array() );
					$settings[ $integration ] = compact( 'enable', 'implicit', 'checkbox_txt', 'precheck', 'add_segment', 'add_segment_ur', 'add_tag' );
					// User Registration Plugin Added.
					$dynamic_tag                             = isset( $_POST['dynamic_tag'] ) ? sanitize_text_field( wp_unslash( $_POST['dynamic_tag'] ) ) : 'no';
					$settings[ $integration ]['dynamic_tag'] = $dynamic_tag;
					$args                                    = array(
						'post_type'   => 'user_registration',
						'post_status' => 'publish',
					);
					$query                                   = new WP_Query( $args );
					$posts                                   = $query->posts;
					foreach ( $posts as $post ) {
						$form_id = $post->ID;
						if ( ! isset( $_POST[ 'add_form' . $form_id ] ) ) {
							continue;
						}
						$add_form = isset( $_POST[ 'add_form' . $form_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'add_form' . $form_id ] ) ) : '';
						$settings[ $integration ][ 'add_form' . $form_id ] = $add_form;
						$settings[ $integration ][ 'add_tag' . $add_form ] = isset( $_POST[ 'add_tag' . $form_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'add_tag' . $form_id ] ) ) : '';
					}
					// User Registration Plugin Ended.
					update_option( 'mwb_m4wp_integration_settings', $settings );
					wp_cache_set( 'mwb_m4wp_notice', $settings_notice );
				}
			}
		}
	}

	/**
	 * Get_oauth_code function
	 */
	public function get_oauth_code() {

		if ( isset( $_GET['m4wp_reset'] ) && 1 === (int) $_GET['m4wp_reset'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			if ( ! wp_verify_nonce( isset( $_GET['m4wp_auth_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['m4wp_auth_nonce'] ) ) : '', 'm4wp_auth_nonce' ) ) {
				wp_die( 'nonce not verified' );
			}
			update_option( 'mwb_m4wp_base_url', '' );
			update_option( 'mwb_m4wp_auth_details', array() );
			update_option( 'mwb_m4wp_oauth2_success', false );
			update_option( 'mwb_m4wp_connection_status', false );
			update_option( 'mwb_m4wp_auth_type', '' );
			wp_redirect( admin_url( 'admin.php?page=mwb-wp-mautic' ) ); // phpcs:ignore
		}

		if ( isset( $_GET['m4wp_auth'] ) && 1 === (int) $_GET['m4wp_auth'] ) {
			if ( ! wp_verify_nonce( isset( $_GET['m4wp_auth_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['m4wp_auth_nonce'] ) ) : '', 'm4wp_auth_nonce' ) ) {
				wp_die( 'nonce not verified' );
			}
			$baseurl     = self::get_mautic_base_url();
			$credentials = get_option( 'mwb_m4wp_auth_details', array() );
			$mautic_url  = $baseurl . 'oauth/v2/authorize';
			$redirct_url = admin_url();
			$data        = array(
				'client_id'     => $credentials['client_id'],
				'grant_type'    => 'authorization_code',
				'redirect_uri'  => $redirct_url,
				'response_type' => 'code',
				'state'         => wp_create_nonce( 'm4wp_nonce' ),
			);
			$auth_url    = add_query_arg( $data, $mautic_url );
			wp_redirect( $auth_url ); // phpcs:ignore
		}

		if ( isset( $_GET['state'] ) && isset( $_GET['code'] ) ) {
			if ( wp_verify_nonce( isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : '', 'm4wp_nonce' ) ) {
				$code                   = sanitize_text_field( wp_unslash( $_GET['code'] ) );
				$baseurl                = get_option( 'mwb_m4wp_base_url', '' );
				$credentials            = get_option( 'mwb_m4wp_auth_details', array() );
				$redirct_url            = admin_url();
				$data                   = array(
					'client_id'     => $credentials['client_id'],
					'client_secret' => $credentials['client_secret'],
					'grant_type'    => 'authorization_code',
					'redirect_uri'  => $redirct_url,
					'code'          => $code,
				);
				$api_instance           = Mwb_Wpm_Oauth2::get_instance();
				$api_instance->base_url = $baseurl;
				try {
					$response = $api_instance->get_oauth_token( $data );
					$api_instance->save_token_data( $response );
					update_option( 'mwb_m4wp_oauth2_success', true );
					update_option( 'mwb_m4wp_connection_status', true );
				} catch ( Exception $e ) {
					update_option( 'mwb_m4wp_oauth2_success', false );
					update_option( 'mwb_m4wp_connection_status', false );
				}
				wp_redirect( admin_url( 'admin.php?page=mwb-wp-mautic' ) ); // phpcs:ignore
				exit();
			}
		}
	}

	/**
	 * Include Plugin screen for Onboarding pop-up.
	 *
	 * @since    1.0.0
	 * @param array $valid_screens Valid screens written.
	 * @return array - Valid Screens.
	 */
	public function add_mwb_frontend_screens( $valid_screens = array() ) {

		if ( is_array( $valid_screens ) ) {
			// Push your screen here.
			array_push( $valid_screens, 'toplevel_page_mwb-wp-mautic' );
			array_push( $valid_screens, 'makewebbetter_page_mwb-wp-mautic' );
		}
		return $valid_screens;
	}

	/**
	 * Include plugin for Deactivation pop-up.
	 *
	 * @since    1.0.0
	 * @param array $valid_screens Valid screens written.
	 * @return array - Deactivation valid screens.
	 */
	public function add_mwb_deactivation_screens( $valid_screens = array() ) {

		if ( is_array( $valid_screens ) ) {
			// Push your screen here.
			array_push( $valid_screens, 'mwb-wp-mautic' );
		}
		return $valid_screens;
	}

	/**
	 * Get base url of your mautic instance.
	 *
	 * @since    1.0.0
	 * @return string - mautic base url.
	 */
	public static function get_mautic_base_url() {

		$baseurl = get_option( 'mwb_m4wp_base_url', '' );

		if ( ! empty( $baseurl ) ) {
			$baseurl = rtrim( $baseurl, '/' );
			$baseurl = $baseurl . '/';
		}
		return $baseurl;
	}
}
