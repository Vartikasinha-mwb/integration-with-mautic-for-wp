<?php

abstract class Mautic_For_Wordpress_Integration {

	public $name        = '';
	public $description = '';
	public $id          = '';
	public $settings    = '';

	public function __construct( $id, $settings = array() ) {
		$this->id       = $id;
		$this->settings = ! empty( $settings ) ? $settings : $this->get_default_settings();
	}

	// check if integration is enable
	public function is_enabled() {
		if ( isset( $this->settings['enable'] ) && $this->settings['enable'] == 'yes' ) {
			return true;
		}
		return false;
	}

	// check if integration is enable
	public function is_implicit() {
		if ( isset( $this->settings['implicit'] ) && $this->settings['implicit'] == 'yes' ) {
			return true;
		}
		return false;
	}

	public function is_checkbox_precheck() {
		if ( isset( $this->settings['precheck'] ) && $this->settings['precheck'] == 'yes' ) {
			return true;
		}
		return false;
	}

	// get integration id
	public function get_id() {
		return $this->id;
	}

	// get integration name
	public function get_name() {
		return $this->name;
	}

	public function get_description() {
		return $this->description;
	}

	// get default settings for the integration
	public function get_default_settings() {
		return array(
			'enable'       => 'no',
			'implicit'     => 'yes',
			'checkbox_txt' => __( 'Sign me up for the newsletter', 'mautic-for-wordpress' ),
			'precheck'     => 'no',
			'add_segment'  => '-1',
			'add_tag'      => '',
		);
	}

	// get saved settings
	public function get_saved_settings() {
		$settings = array();
		foreach ( $this->get_default_settings() as $key => $value ) {
			$settings[ $key ] = isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $value;
		}
		return $settings;
	}

	// get setting value
	public function get_option( $key = '' ) {

		if ( empty( $key ) ) {
			return '';
		}
		$value = isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $this->get_default_settings()[ $key ];
		return $value;

	}

	// get implicit checkbox html
	public function get_checkbox_html() {
		return '';
	}

	// add opt in checkbox
	public function add_checkbox() {

	}

	// initialize integration
	public function initialize() {
		$this->add_hooks();
	}

	// add hooks required for the integration
	public function add_hooks() {

	}

	// check dependencies for the integration here
	public function is_active() {
		return false;
	}

	public function may_be_sync_data( $data ) {

		$sync = false;

		if ( ! $this->is_implicit() ) {
			if ( isset( $_POST['mwb_m4wp_subscribe'] ) && $_POST['mwb_m4wp_subscribe'] == 'yes' ) {
				$sync = true;
			}
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
		$contact = MWB_Mautic_For_WP_Api::create_contact( $data );
		if ( $segment_id != '-1' ) {
			if ( isset( $contact['contact'] ) ) {
				$contact_id = $contact['contact']['id'];
			}
			if ( $contact_id > 0 ) {
				MWB_Mautic_For_WP_Api::add_contact_to_segment( $contact_id, $segment_id );
			}
		}
	}
}
