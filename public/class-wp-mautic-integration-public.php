<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/public
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Wp_Mautic_Integration_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

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
		 * defined in Wp_Mautic_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Mautic_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-mautic-integration-public.css', array(), $this->version, 'all' );

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
		 * defined in Wp_Mautic_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Mautic_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-mautic-integration-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Hook mautic tracking script.
	 */
	public function add_tracking_script() {

		if ( 'yes' === get_option( 'mwb_m4wp_tracking_enable', 'no' ) ) {
			$script_location = get_option( 'mwb_m4wp_script_location', 'footer' );
			add_action( "wp_{$script_location}", array( $this, 'add_mautic_tracking_script' ) );
		}
	}

	/**
	 * Add mautic tracking code.
	 *
	 * @todo add gdpr stuff.
	 */
	public function add_mautic_tracking_script() {

		if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
			return false;
		}

		$base_url = Wp_Mautic_Integration_Admin::get_mautic_base_url();

		$script_url = $base_url . '/mtc.js';
		$user_data  = $this->get_tracking_data();
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

	/**
	 * Add_form_shortcode function
	 *
	 * @param array $attr attribute.
	 * @return string output
	 */
	public function add_form_shortcode( $attr = array() ) {
		ob_start();
		require MWB_WP_MAUTIC_PATH . 'public/partials/forms.php';
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * Adding a shortcode
	 */
	public function add_shortcodes() {
		add_shortcode( 'mwb_m4wp_form', array( $this, 'add_form_shortcode' ) );
	}

	/**
	 * Get_tracking_data function.
	 *
	 * @return array tracking_data
	 */
	public function get_tracking_data() {

		$tracking_data = array();

		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();
			if ( $user ) {
				$tracking_data['email'] = $user->user_email;
			}
		}

		$page_title = '';
		$page_title = function_exists( 'wp_title' ) ? wp_title( '', false ) : '';
		if ( '' === $page_title ) {
			$page_title = function_exists( 'wp_get_document_title' ) ? wp_get_document_title() : '';
		}
		$tracking_data['language']   = get_locale();
		$tracking_data['page_url']   = $this->get_page_url();
		$tracking_data['page_title'] = $page_title;
		$tracking_data['referrer']   = function_exists( 'wp_get_referer' ) ? wp_get_referer() : '';
		return $tracking_data;
	}

	/**
	 * Get Page URL
	 *
	 * @return string url
	 */
	public function get_page_url() {
		global $wp;
		return( home_url( $wp->request ) );
	}

}
