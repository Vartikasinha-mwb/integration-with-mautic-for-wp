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
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mautic-for-wordpress-admin.css', array(), $this->version, 'all' );
		
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
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mautic-for-wordpress-admin.js', array( 'jquery' ), $this->version, false );
		
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
	}
	
	/**
	* Include admin menu view.
	*/
	public function include_admin_menu_display(){
		$file_path = 'admin/partials/mautic-for-wordpress-admin-display.php' ; 
		$this->load_template( $file_path ) ; 
	}
	
	/**
	* Check and include admin view file
	* @param string $file_path Relative path of file.
	* @param array $params Array of extra params.
	*/
	public function load_template( $file_path, $params = array() ){
		$file  = MWB_M4WP_PLUGIN_PATH.$file_path ; 
		if( file_exists( $file) ){
			include $file ; 
		}else{
			echo __( 'Something went wrong', 'mautic-for-wordpress' ) ;
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
		return MWB_M4WP_Mautic_Api::create_contact( $data, $user_id );
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
	
	public function get_oauth_code(){

		if(isset($_GET['m4wp'])){
			
			$client_id = '1_1a5y0bvvunwg8wwwc4s4o4ooo8kwc8gg8c840owoccw4w4o8wc' ; 
			$client_secret = '65o9w2j9a3okw8g4kosgs8wkggsswwocs0w0k4wssg4w4c8o8o' ; 
			$redirct_url = 'http://localhost/wp551/wp-admin/admin.php' ; 
			$mautic_url = 'http://localhost/mautic2163/oauth/v2/authorize' ; 
			$data = array(
				'client_id' => $client_id,
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
				$client_id = '1_1a5y0bvvunwg8wwwc4s4o4ooo8kwc8gg8c840owoccw4w4o8wc' ; 
				$client_secret = '65o9w2j9a3okw8g4kosgs8wkggsswwocs0w0k4wssg4w4c8o8o' ; 
				$redirct_url = 'http://localhost/wp551/wp-admin/admin.php' ; 
				$mautic_url = 'http://localhost/mautic2163/' ; 
				
				$data = array(
					'client_id' => $client_id,
					'client_secret' => $client_secret,
					'grant_type' => 'authorization_code',
					'redirect_uri' => $redirct_url,
					'code' => $code,
				);

				$api_instance = MWB_M4WP_Mautic_Api_Base_V2::get_instance(); 
				$response =  $api_instance->get_oauth_token( $mautic_url, $data );
				$api_instance->save_token_data($response);
			}
		}
	}
}
