<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       https://makewebbetter.com/
* @since      1.0.0
*
* @package    Mautic_For_Wordpress
* @subpackage Mautic_For_Wordpress/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    Mautic_For_Wordpress
* @subpackage Mautic_For_Wordpress/admin
* @author     MakeWebBetter <webmaster@makewebbetter.com>
*/
class Mautic_For_Wordpress_Admin {
	
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
	* @param      string    $plugin_name       The name of this plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
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
		* defined in Mautic_For_Wordpress_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The Mautic_For_Wordpress_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/

		$current_screen = get_current_screen() ; 
		$screens = $this->get_plugin_screens();
		
		if( isset( $current_screen ) && in_array( $current_screen->id , $screens)  ){
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mautic-for-wordpress-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'mwb-m4wp-jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );
		}
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
		* defined in Mautic_For_Wordpress_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The Mautic_For_Wordpress_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/
		
		$current_screen = get_current_screen() ; 
		$screens = $this->get_plugin_screens();
		if( isset( $current_screen ) && in_array( $current_screen->id , $screens)  ){
			wp_enqueue_script( 'mwb-fwpro-chart-script' , plugin_dir_url( __FILE__ ) . 'chart/chart.js', array( 'jquery' ), '1.0.0', false );
			wp_enqueue_style( 'mwb-fwpro-chart-style' , plugin_dir_url( __FILE__ ) . 'chart/chart.css' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'mwb-fwpro-admin-script' , plugin_dir_url( __FILE__ ) . 'js/mautic-for-wordpress-admin.js', array( 'jquery', 'mwb-fwpro-chart-script', 'jquery-ui-datepicker' ), time(), false );
			$ajax_data = array(
				'ajax_url' => admin_url('admin-ajax.php'),
			);
			wp_localize_script( 'mwb-fwpro-admin-script', 'ajax_data', $ajax_data);
		}
	}

	public function get_plugin_screens(){
		return array(
			'mwb-mautic_page_mautic-dashboard',
			'toplevel_page_mautic-for-wordpress',
			'mwb-mautic_page_mautic-forms',
			'mwb-mautic_page_integrations', 
			'mwb-mautic_page_settings',
		);
	}
	
	/**
	* Add plugin menu page.
	*/
	public function add_admin_menu_page(){
		
		add_menu_page(
			__( 'MWB Mautic Settings', 'mautic-for-wordpress' ),
			__( 'MWB Mautic', 'mautic-for-wordpress' ),
			'manage_options',
			'mautic-for-wordpress',
			array( $this, 'include_admin_menu_display' ),
			'dashicons-admin-plugins'
		);

		add_submenu_page( 
			'mautic-for-wordpress' , 
			__( 'Dashboard', 'mautic-for-wordpress' ),
			__( 'Dashboard', 'mautic-for-wordpress' ),
			'manage_options',
			'mautic-dashboard',
			array( $this, 'include_mautic_dashboard')
		) ;

		add_submenu_page( 
			'mautic-for-wordpress' , 
			__( 'Forms', 'mautic-for-wordpress' ),
			__( 'Forms', 'mautic-for-wordpress' ),
			'manage_options',
			'mautic-forms',
			array( $this, 'include_mautic_forms_display')
		) ;

		add_submenu_page( 
			'mautic-for-wordpress' , 
			__( 'Integrations', 'mautic-for-wordpress' ),
			__( 'Integrations', 'mautic-for-wordpress' ),
			'manage_options',
			'integrations',
			array( $this, 'include_integrations_display')
		) ;
		
		add_submenu_page( 
			'mautic-for-wordpress' , 
			__( 'Settings', 'mautic-for-wordpress' ),
			__( 'Settings', 'mautic-for-wordpress' ),
			'manage_options',
			'settings',
			array( $this, 'include_settings_display')
		) ;
	}

	public function include_settings_display(){
		$file_path = 'admin/partials/mautic-for-wordpress-settings.php' ; 
		self::load_template( $file_path ) ; 
	}

	public function include_integrations_display(){
		$file_path = 'admin/partials/mautic-for-wordpress-integrations.php' ; 
		self::load_template( $file_path ) ; 
	}

	public function include_mautic_dashboard(){
		$file_path = 'admin/partials/mautic-for-wordpress-dashboard.php' ; 
		self::load_template( $file_path ) ; 
	}

	/**
	* Include admin forms view.
	*/
	public function include_mautic_forms_display(){
		$file_path = 'admin/partials/mautic-for-wordpress-forms-display.php' ; 
		self::load_template( $file_path ) ; 
	}
	
	/**
	* Include admin menu view.
	*/
	public function include_admin_menu_display(){
		$file_path = 'admin/partials/mautic-for-wordpress-admin-display.php' ; 
		self::load_template( $file_path ) ; 
	}
	
	/**
	* Check and include admin view file
	* @param string $file_path Relative path of file.
	* @param array $params Array of extra params.
	*/
	public static function load_template( $file_path, $params = array() ){
		$file  = MWB_M4WP_PLUGIN_PATH.$file_path ; 
		if( file_exists( $file) ){
			include $file ; 
		}else{
			echo __( 'Something went wrong', 'mautic-for-wordpress' ) ;
		}
	}


	public static function get_time_unit( $date_range ){
		$time_unit = 'm' ; 
		$to = strtotime($date_range['date_to']) ;
		$from = strtotime($date_range['date_from']) ;
		$diff = $to - $from ; 
		$days = $diff/(24*60*60) ; 
		switch ($days) {
			case ($days < 61) : 
				$time_unit = 'd' ; 
				break ; 
			case ($days > 61 && $days < 91) : 
				$time_unit = 'W' ; 
				break ; 
			case ($days > 91 && $days < 366) : 
				$time_unit = 'm' ; 
				break ; 
			case ($days > 366) : 
				$time_unit = 'Y' ; 
				break ; 
		}
		return $time_unit ; 
	}

	public static function get_default_date_range(){
		$date_to = date("Y-m-d") ; 
		$date_from = date("Y-m-d",strtotime("-1 month")) ; 
		return array(
			'date_to' => $date_to,
			'date_from' => $date_from
		);
	}

	public function save_admin_settings(){
		
		if( isset( $_POST['action'] ) && $_POST['action'] == 'mwb_m4wp_setting_save' ){
			if(wp_verify_nonce( $_POST['_nonce'] , '_nonce' )){
				$enable = isset($_POST['mwb_m4wp_tracking_enable']) ? $_POST['mwb_m4wp_tracking_enable'] : 'no' ;
				$location = isset($_POST['mwb_m4wp_script_location']) ? $_POST['mwb_m4wp_script_location'] : 'footer' ; 
				update_option( 'mwb_m4wp_script_location' , $location ) ; 
				update_option( 'mwb_m4wp_tracking_enable' , $enable ) ;
			}
		}
		
		if( isset( $_POST['action'] ) && $_POST['action'] == 'mwb_m4wp_date_range' ){
			$date_range = array(
				'date_from' => $_POST['mwb_m4wp_from_date'] , 
				'date_to' => $_POST['mwb_m4wp_to_date'] 
			);
			update_option( 'mwb_m4wp_date_range' , $date_range );
		}

		if( isset( $_POST['action'] ) && $_POST['action'] == 'mwb_m4wp_integration_save' ){
			if(wp_verify_nonce( $_POST['_nonce'] , 'mwb_m4wp_integration_nonce' )){
				if(isset($_POST['integration']) && $_POST['integration'] != ''){
					$integration = $_POST['integration'] ; 
					$enable = isset($_POST['enable']) ? $_POST['enable'] : 'no' ; 
					$implicit = isset($_POST['implicit']) ? $_POST['implicit'] : 'no' ; 
					$checkbox_txt = isset($_POST['checkbox_txt']) ? $_POST['checkbox_txt'] : '' ; 
					$precheck = isset($_POST['precheck']) ? $_POST['precheck'] : 'no' ; 
					$add_segment = isset($_POST['add_segment']) ? $_POST['add_segment'] : '-1' ; 
					$add_tag = isset($_POST['add_tag']) ? $_POST['add_tag'] : '' ;
					$settings = get_option('mwb_m4wp_integration_settings' , array());
					$settings[$integration] = compact('enable', 'implicit', 'checkbox_txt', 'precheck', 'add_segment', 'add_tag') ;
					update_option('mwb_m4wp_integration_settings', $settings);
				}
			}
		}
	}

	public function get_oauth_code(){

		if(isset($_GET['m4wp_reset']) && $_GET['m4wp_reset'] == 1){
			if(!wp_verify_nonce($_GET['m4wp_auth_nonce'] , 'm4wp_auth_nonce')){
				wp_die('nonce not verified');
			}
			update_option( 'mwb_m4wp_base_url' , '') ;
			update_option( 'mwb_m4wp_auth_details' , array() ) ;  
			update_option( 'mwb_m4wp_oauth2_success' , false);
			update_option( 'mwb_m4wp_connection_status' , false);
			update_option( 'mwb_m4wp_auth_type', '') ;
			wp_redirect( admin_url('admin.php?page=mautic-for-wordpress') ) ;
		}
		
		if(isset($_GET['m4wp_auth']) && $_GET['m4wp_auth'] == 1){
			
			if(!wp_verify_nonce($_GET['m4wp_auth_nonce'] , 'm4wp_auth_nonce')){
				wp_die('nonce not verified');
			}
			$baseurl = get_option('mwb_m4wp_base_url' , '') ;
			$credentials = get_option( 'mwb_m4wp_auth_details' , array() ) ;  
			$mautic_url = $baseurl.'/oauth/v2/authorize' ; 
			$redirct_url = admin_url() ; 
			$data = array(
				'client_id' => $credentials['client_id'],
				'grant_type' => 'authorization_code',
				'redirect_uri' => $redirct_url,
				'response_type' => 'code',
				'state' => wp_create_nonce( 'm4wp_nonce' )
			);
			$auth_url = add_query_arg( $data, $mautic_url ) ; 
			wp_redirect( $auth_url ) ; 
		}

		if( isset( $_GET['state'] ) && isset( $_GET['code'] ) ){
			if(wp_verify_nonce($_GET['state'], 'm4wp_nonce' )){
				$code = $_GET['code'] ; 
				$baseurl = get_option('mwb_m4wp_base_url' , '') ;
				$credentials = get_option( 'mwb_m4wp_auth_details' , array() ) ;  
				$redirct_url = admin_url() ; 
				$data = array(
					'client_id' => $credentials['client_id'],
					'client_secret' => $credentials['client_secret'],
					'grant_type' => 'authorization_code',
					'redirect_uri' => $redirct_url,
					'code' => $code,
				);
				$api_instance = Oauth2::get_instance(); 
				$api_instance->base_url = $baseurl ; 
				try{
					$response =  $api_instance->get_oauth_token( $data );
					$api_instance->save_token_data($response);
					update_option('mwb_m4wp_oauth2_success' , true);
					update_option( 'mwb_m4wp_connection_status' , true);
				}catch(Exception $e){
					update_option('mwb_m4wp_oauth2_success' , false);
					update_option( 'mwb_m4wp_connection_status' , false);
				}
				wp_redirect( admin_url('admin.php?page=mautic-for-wordpress') ) ; 
			}
		}
	}

	/**
	 * Include Plugin screen for Onboarding pop-up.
	 *
	 * @since    1.0.0
	 */
	public function add_mwb_frontend_screens( $valid_screens = array() ) {

		if ( is_array( $valid_screens ) ) {
			// Push your screen here.
			array_push( $valid_screens, 'toplevel_page_mautic-for-wordpress' );
		}
		return $valid_screens;
	}

	/**
	 * Include Upsell plugin for Deactivation pop-up.
	 *
	 * @since    3.0.0
	 */
	public function add_mwb_deactivation_screens( $valid_screens = array() ) {

		if ( is_array( $valid_screens ) ) {
			// Push your screen here.
			array_push( $valid_screens, 'mautic-for-wordpress' );
		}
		return $valid_screens;
	}

	public static function get_mautic_base_url(){
		$baseurl = get_option( 'mwb_m4wp_base_url' , '' ) ;
		if(!empty($baseurl)){
			$baseurl  = rtrim( $baseurl, "/" ) ;
			$baseurl = $baseurl.'/' ; 
		} 
		return $baseurl; 
	}
}
