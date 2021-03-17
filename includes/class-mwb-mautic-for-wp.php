<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Makewebbetter_Mautic_For_Wordpress
 * @subpackage Makewebbetter_Mautic_For_Wordpress/includes
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
 * @package    Makewebbetter_Mautic_For_Wordpress
 * @subpackage Makewebbetter_Mautic_For_Wordpress/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MWB_Mautic_For_WP {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MWB_Mautic_For_WP_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MWB_MAUTIC_FOR_WP_VERSION' ) ) {
			$this->version = MWB_MAUTIC_FOR_WP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'makewebbetter-mautic-for-wordpress';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_ajax_hooks();
		$this->load_integrations();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MWB_Mautic_For_WP_Loader. Orchestrates the hooks of the plugin.
	 * - MWB_Mautic_For_WP_i18n. Defines internationalization functionality.
	 * - MWB_Mautic_For_WP_Admin. Defines all hooks for the admin area.
	 * - MWB_Mautic_For_WP_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-mautic-for-wp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-mautic-for-wp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mwb-mautic-for-wp-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mwb-mautic-for-wp-public.php';

		/**
		 * The class responsible for defining all actions that occur via ajax.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-mautic-for-wp-ajax.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/class-api.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/class-mautic-exception.php';
		/**
		 * The class responsible for defining all functionalities related to mautic api
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/class-basic-auth.php';

		/**
		 * The class responsible for defining all functionalities related to mautic api
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/class-oauth2.php';

		/**
		 * The class responsible for defining all functionalities related to mautic api
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-mautic-for-wp-mautic-api.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/integrations/class-mwb-mautic-for-wp-integration.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-mautic-for-wp-integration-manager.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-mautic-for-wp-settings-helper.php';

		/**
		 * The class responsible for the Onboarding functionality.
		 */
		if ( ! class_exists( 'Makewebbetter_Onboarding_Helper' ) ) {

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-makewebbetter-onboarding-helper.php';
		}

		if ( class_exists( 'Makewebbetter_Onboarding_Helper' ) ) {

			$onboard = new Makewebbetter_Onboarding_Helper();
		
		}

		$this->loader = new MWB_Mautic_For_WP_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MWB_Mautic_For_WP_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MWB_Mautic_For_WP_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new MWB_Mautic_For_WP_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// add plugins menu page.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu_page' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'get_oauth_code', 8 );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'save_admin_settings' );

		// Include screen for Onboarding pop-up.
		$this->loader->add_filter( 'mwb_helper_valid_frontend_screens', $plugin_admin, 'add_mwb_frontend_screens' );

		// Include plugin for Deactivation pop-up.
		$this->loader->add_filter( 'mwb_deactivation_supported_slug', $plugin_admin, 'add_mwb_deactivation_screens' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new MWB_Mautic_For_WP_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// hook tracking script.
		$this->loader->add_action( 'init', $plugin_public, 'add_tracking_script' );
		$this->loader->add_action( 'init', $plugin_public, 'add_shortcodes' );
	}

	/**
	 * Define Ajax Hooks.
	 *
	 * @since    1.0.0
	 */
	private function define_ajax_hooks() {

		$plugin_ajax = new MWB_Mautic_For_WP_Ajax();
		$this->loader->add_action( 'wp_ajax_mwb_m4wp_test_api_connection', $plugin_ajax, 'test_api_connection' );
		$this->loader->add_action( 'wp_ajax_mwb_m4wp_enable_integration', $plugin_ajax, 'enable_integration' );
		$this->loader->add_action( 'wp_ajax_mwb_m4wp_refresh', $plugin_ajax, 'refresh_data' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    MWB_Mautic_For_WP_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Initializes the integrations.
	 *
	 * @since     1.0.0
	 */
	public function load_integrations() {
		MWB_Mautic_For_WP_Integration_Manager::initialize_active_integrations();
	}

}
