<?php
/**
 * Registration form integration.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes/integrations
 */

/**
 * The Registration form integration functionality.
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes/integrations
 * @author      makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Wpm_Registration_Form extends Mwb_Wpm_Integration_Base {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $name    Name of the integration.
	 */
	public $name = 'Registration Form';

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $description    Description of the integration.
	 */
	public $description = 'WordPress default registration form';

	/**
	 * Add optin checkbox.
	 */
	public function add_checkbox() {
		if ( ! $this->is_implicit() ) {
			$checked = $this->is_checkbox_precheck() ? 'checked ' : '';
			echo '<p><input ' . esc_attr( $checked ) . ' type="checkbox" name="mwb_m4wp_subscribe" id="mwb_m4wp_subscribe" value="yes">';
			echo '<label for="mwb_m4wp_subscribe">' . esc_attr( $this->get_option( 'checkbox_txt' ) ) . '</label></p>';
		}
	}

	/**
	 * Add hooks related to integration.
	 */
	public function add_hooks() {
		$auth_type = get_option( 'mwb_m4wp_auth_type', 'basic' );
		if ( 'oauth2' === $auth_type ) {
			$oauth2_object = new Mwb_Wpm_Oauth2();
			if ( $oauth2_object->have_active_access_token() ) {
				add_action( 'register_form', array( $this, 'add_checkbox' ) );
				add_action( 'user_register', array( $this, 'sync_registered_user' ), 99, 1 );
			}
		} else {
			add_action( 'register_form', array( $this, 'add_checkbox' ) );
			add_action( 'user_register', array( $this, 'sync_registered_user' ), 99, 1 );
		}
	}

	/**
	 * Sync user.
	 *
	 * @param int $user_id User id.
	 */
	public function sync_registered_user( $user_id ) {
		// gather user data.
		$user = get_userdata( $user_id );

		// check if user exist.
		if ( ! $user instanceof WP_User ) {
			return false;
		}
		// get mapped user data.
		$data = $this->get_mapped_properties( $user );
		// create contact in mautic.

		$this->may_be_sync_data( $data );
	}

	/**
	 * Get mapped properties.
	 *
	 * @param object $user WP user object.
	 * @return array - user data.
	 */
	public function get_mapped_properties( $user ) {
		// initialize firstname as username.
		$data = array(
			'email'     => $user->user_email,
			'firstname' => $user->user_login,
		);

		if ( '' !== $user->first_name ) {
			$data['firstname'] = $user->first_name;
		}

		if ( '' !== $user->last_name ) {
			$data['lastname'] = $user->last_name;
		}

		return $data;
	}

	/**
	 * Check if integration is active.
	 *
	 * @return bool.
	 */
	public function is_active() {
		return true;
	}

}
