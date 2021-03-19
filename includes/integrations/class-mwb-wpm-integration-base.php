<?php
/**
 * Base integration class.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes
 */

/**
 * The class responsible for integration functionality.
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes
 * @author      makewebbetter <webmaster@makewebbetter.com>
 */
abstract class Mwb_Wpm_Integration_Base {

	/**
	 * Name of the integration.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $name    Name of the integration.
	 */
	public $name = '';

	/**
	 * Name of the integration.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $desciption    Name of the integration.
	 */
	public $description = '';

	/**
	 * Id of the integration.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $id    Id of the integration.
	 */
	public $id = '';

	/**
	 * Settings of the integration.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $settings    Settings of the integration.
	 */
	public $settings = '';

	/**
	 * Constructor.
	 *
	 * @param string $id Id of the integration.
	 * @param array  $settings Settings of the integration.
	 */
	public function __construct( $id, $settings = array() ) {
		$this->id       = $id;
		$this->settings = ! empty( $settings ) ? $settings : $this->get_default_settings();
	}

	/**
	 * Check if integration is enable.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		if ( isset( $this->settings['enable'] ) && 'yes' === $this->settings['enable'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Check if integration is implicit.
	 *
	 * @return bool
	 */
	public function is_implicit() {
		if ( isset( $this->settings['implicit'] ) && 'yes' === $this->settings['implicit'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Check if integration checkbox is precheck.
	 *
	 * @return bool
	 */
	public function is_checkbox_precheck() {
		if ( isset( $this->settings['precheck'] ) && 'yes' === $this->settings['precheck'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Get id of integration.
	 *
	 * @return string $id Id of the integration.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get name of integration.
	 *
	 * @return string $name Name of the integration.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get description of integration.
	 *
	 * @return string $description description of the integration.
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Get default settings.
	 *
	 * @return array  settings.
	 */
	public function get_default_settings() {
		return array(
			'enable'       => 'no',
			'implicit'     => 'yes',
			'checkbox_txt' => __( 'Sign me up for the newsletter', 'wp-mautic-integration' ),
			'precheck'     => 'no',
			'add_segment'  => '-1',
			'add_tag'      => '',
		);
	}

	/**
	 * Get saved settings.
	 *
	 * @return array $settings settings.
	 */
	public function get_saved_settings() {
		$settings = array();
		foreach ( $this->get_default_settings() as $key => $value ) {
			$settings[ $key ] = isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $value;
		}
		return $settings;
	}

	/**
	 * Get saved setting option.
	 *
	 * @param string $key Key of the setting option.
	 * @return string  $value Setting value.
	 */
	public function get_option( $key = '' ) {

		if ( empty( $key ) ) {
			return '';
		}
		$value = isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $this->get_default_settings()[ $key ];
		return $value;

	}

	/**
	 * Get Checkbox html.
	 *
	 * @return string  Checkbox html.
	 */
	public function get_checkbox_html() {
		return '';
	}

	/**
	 * Add optin checkbox.
	 */
	public function add_checkbox() {

	}

	/**
	 * Initialize hooks.
	 */
	public function initialize() {
		$this->add_hooks();
	}

	/**
	 * Add hooks.
	 */
	public function add_hooks() {

	}

	/**
	 * Check if it is active.
	 *
	 * @return bool
	 */
	public function is_active() {
		return false;
	}

	/**
	 * Sync data.
	 *
	 * @param array $data Data to be synced.
	 */
	public function may_be_sync_data( $data ) {

		$sync = false;

		if ( ! $this->is_implicit() ) {
			//phpcs:disable
			if ( isset( $_POST['mwb_m4wp_subscribe'] ) && $_POST['mwb_m4wp_subscribe'] == 'yes' ) {
				$sync = true;
			}
			//phpcs:enable
		} else {
			$sync = true;
		}

		if ( ! $sync ) {
			return;
		}

		$tags_string = $this->get_option( 'add_tag' );
		$segment_id  = $this->get_option( 'add_segment' );
		$contact_id  = 0;
		if ( ! empty( $tags_string ) ) {
			$tags         = explode( ',', $tags_string );
			$data['tags'] = $tags;
		}
		$contact = Mwb_Wpm_Api::create_contact( $data );
		if ( '-1' !== $segment_id ) {
			if ( isset( $contact['contact'] ) ) {
				$contact_id = $contact['contact']['id'];
			}
			if ( $contact_id > 0 ) {
				Mwb_Wpm_Api::add_contact_to_segment( $contact_id, $segment_id );
			}
		}
	}
}
