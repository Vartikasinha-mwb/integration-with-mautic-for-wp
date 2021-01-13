<?php

/**
* The public-facing functionality of the plugin.
*
* @link       https://makewebbetter.com/
* @since      1.0.0
*
* @package    Mautic_For_Wordpress
* @subpackage Mautic_For_Wordpress/public
*/

/**
* The public-facing functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the public-facing stylesheet and JavaScript.
*
* @package    Mautic_For_Wordpress
* @subpackage Mautic_For_Wordpress/public
* @author     MakeWebBetter <webmaster@makewebbetter.com>
*/
class Mautic_For_Wordpress_Public {
	
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
	* @param      string    $plugin_name       The name of the plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}
	
	/**
	* Register the stylesheets for the public-facing side of the site.
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
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mautic-for-wordpress-public.css', array(), $this->version, 'all' );
		
	}
	
	/**
	* Register the JavaScript for the public-facing side of the site.
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
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mautic-for-wordpress-public.js', array( 'jquery' ), $this->version, false );
		
	}
	
	/**
	* Hook mautic tracking script.
	*/
	public function add_tracking_script(){
		
		$script_location = get_option( "mwb_m4wp_script_location", "head" ) ;
		add_action( "wp_{$script_location}", array( $this, "add_mautic_tracking_script" ) ) ;
	}
	
	/**
	* Add mautic tracking code.
	* @todo add gdpr stuff
	*/
	public function add_mautic_tracking_script(){
		
		$base_url = get_option( "mwb_m4wp_baseurl", "http://89b8a42a65a5.ngrok.io/mautic2163" ) ; 
		$script_url = $base_url . '/mtc.js' ; 
		$user_data = $this->get_tracking_data();
		?>
		<script type="text/javascript">
		<?php
		if ( ! empty( $base_url ) ) :
			?>
			(function(w,d,t,u,n,a,m){w['MauticTrackingObject']=n;
				w[n]=w[n]||function(){(w[n].q=w[n].q||[]).push(arguments)},a=d.createElement(t),
				m=d.getElementsByTagName(t)[0];a.async=1;a.src=u;m.parentNode.insertBefore(a,m)
			})(window,document,'script','<?php echo esc_url( $script_url ); ?>','mt');
			mt('send', 'pageview'<?php echo count( $user_data ) > 0 ? ', ' . wp_json_encode( $user_data ) : ''; ?>);
			<?php
		endif;
		?>
		</script>
		<?php
		
	}
	
	public function get_tracking_data(){
		
		global $wp;
		$tracking_data = array();
		// $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
		
		// $tracking_data['language']   = get_locale();
		// $tracking_data['page_url']   = $current_url;
		// $tracking_data['page_title'] = function_exists( 'wp_get_document_title' )
		// ? wp_get_document_title()
		// : wp_title( '&raquo;', false );
		// $tracking_data['referrer']   = function_exists( 'wp_get_raw_referer' )
		// ? wp_get_raw_referer()
		// : null;
		// if ( false === $tracking_data['referrer'] ) {
		// 	$tracking_data['referrer'] = $current_url;
		// }
		
		$track_user_data = get_option( 'mwb_m4wp_track_user_data', 'yes' ) ; 
		if( is_user_logged_in() && 'yes' == $track_user_data ){
			$user = wp_get_current_user();
			if( $user ){
				$tracking_data['email'] = $user->user_email ; 
			}
		}
		return $tracking_data ; 
	}
	
}
