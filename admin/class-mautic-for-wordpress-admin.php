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

	public function may_be_sync_data($integration , $data){
		$settings = get_option('mwb_m4wp_integration_settings' , array()); 
		if(isset($settings[$integration])){
			if($settings[$integration]['enable'] == 'yes'){
				$tags_string = $settings[$integration]['add_tag'] ; 
				$segment_id = $settings[$integration]['add_segment'] ;
				$contact_id = 0 ;
				if(!empty($tags_string)){
					$tags = explode( ',' , $tags_string ) ; 
					$data['tags'] = $tags ;
				}
				$contact =  MWB_M4WP_Mautic_Api::create_contact( $data );
				if($segment_id != '-1' ){
					if(isset( $contact['contact'] )){
						$contact_id = $contact['contact']['id'];
					}
					if($contact_id > 0 ){
						MWB_M4WP_Mautic_Api::add_contact_to_segment($contact_id , $segment_id) ; 
					}
				}
			}
		}
	}
	
	/**
	* Capture registerd user data and create mautic contact
	* @param int $user_id User id.
	* @return bool 
	*/
	public function create_registered_user( $user_id ){
		
		// gather user data
		$user = get_userdata( $user_id );
		// check if user exist
		if ( ! $user instanceof WP_User ) {
			return false;
		}
		//get mapped user data
		$data = $this->get_mapped_properties( $user );
		//create contact in mautic
		$this->may_be_sync_data( 'mwb_m4wp_registration' , $data ) ; 
		
	}
	
	/**
	* Update user data in mautic
	* @param int $user_id Used id of updated user.
	* @param WP_User $old_user_data old user data.
	*/
	public function update_registered_user( $user_id , $old_user_data ){
		// gather user data
		$user = get_userdata( $user_id );
		
		// check if user exist
		if ( ! $user instanceof WP_User ) {
			return false;
		}
		//get mapped user data
		$data = $this->get_mapped_properties( $user );
		$data[ 'tags' ] = array( 'wpuser' ) ;  
		$data[ 'points' ] = 100 ; 
		$contact =  MWB_M4WP_Mautic_Api::create_contact( $data );
		if(isset( $contact['contact'] )){
			$contact_id = $contact['contact']['id'];
		}
		if($contact_id > 0 ){
			MWB_M4WP_Mautic_Api::add_contact_to_segment($contact_id , 2) ; 
		}
		return $contact ; 
	}
	
	/**
	* Get mapped properties.
	* @param WP_User $user Instance of wp_user
	* @return array 
	*/
	public function get_mapped_properties( $user ){
		
		// initialize firstname as username
		$data = array(
			'email' => $user->user_email,
			'firstname'  => $user->user_login,
		);
		
		if ( '' !== $user->first_name ) {
			$data['firstname']  = $user->first_name;
		}
		
		if ( '' !== $user->last_name ) {
			$data['lastname'] = $user->last_name;
		}
		
		return $data ; 
	}
	
	public function get_assigned_tags(){
		$tags = get_option( 'mwb_m4wp_registration_tags' , array( 'wp new' ) ) ; 
		return $tags;
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

	public static function get_segment_options(){
		
		$segment_list = get_option( 'mwb_m4wp_segment_list' , array() ) ; 
		if(!empty($segment_list)){
			return $segment_list;
		}
		$segments =  MWB_M4WP_Mautic_Api::get_segments();
		if(!$segments){
			return array();
		}
		$options = array() ; 
		if(isset($segments['lists']) && count($segments['lists']) > 0 ){
			foreach( $segments['lists'] as $key => $segment ){
				$options[] = array(
					'id' => $segment['id'],
					'name' => $segment['name']
				);
			}
		}
		update_option( 'mwb_m4wp_segment_list' , $options ) ; 
		return $options ; 
	}
	
	public static function get_integrations( $key = '' ){

		$integrations = array(

			'mwb_m4wp_registration' => array(
				'id' => 'mwb_m4wp_registration',
				'name' => __( 'Registration form' , 'mautic-for-wordpress' ),
				'des' => __( 'Wordpress default registration form' , 'mautic-for-wordpress' ),
				'status' => 'inactive',
			),
			'mwb_m4wp_comment' => array(
				'id' => 'mwb_m4wp_comment',
				'name' => __( 'Comment form' , 'mautic-for-wordpress' ),
				'des' => __( 'Wordpress default Comment form' , 'mautic-for-wordpress' ),
				'status' => 'inactive',
			)
		) ; 
		
		if($key != '' && isset($integrations[ $key ])){
			return $integrations[ $key ] ; 
		}

		return $integrations ; 
	}

	public function sync_commentor_data( $comment_id, $comment_approved = '' ){
		// is this a spam comment?
		if ( $comment_approved === 'spam' ) {
			return false;
		}
		$comment = get_comment( $comment_id );
		$data = array(
			'email'    => $comment->comment_author_email,
			'firstname' => $comment->comment_author,
		);
		$this->may_be_sync_data( 'mwb_m4wp_comment' , $data ) ; 
	}

	public function get_oauth_code(){

		if(isset($_GET['m4wp']) && $_GET['m4wp'] == 1){
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
				$response =  $api_instance->get_oauth_token( $data );
				if($response){
					$api_instance->save_token_data($response);
					update_option('mwb_m4wp_oauth2_success' , true);
				}else{
					update_option('mwb_m4wp_oauth2_success' , false);
				}
				wp_redirect( admin_url('admin.php?page=mautic-for-wordpress') ) ; 
			}
		}
	}
}
