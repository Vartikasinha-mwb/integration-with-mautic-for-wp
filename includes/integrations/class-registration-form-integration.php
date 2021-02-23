<?php
/**
 * Registration form integration.
 *
 * @link       https://makewebbetter.com
 * @since      3.0.0
 *
 * @package     woo_one_click_upsell_funnel
 * @subpackage  woo_one_click_upsell_funnel/includes
 */

/**
 * The Onboarding-specific functionality of the plugin admin side.
 *
 * @package     woo_one_click_upsell_funnel
 * @subpackage  woo_one_click_upsell_funnel/includes
 * @author      makewebbetter <webmaster@makewebbetter.com>
 */
class Registration_Form_Integration extends Mautic_For_Wordpress_Integration {
	public $name        = 'Registration Form';
	public $description = 'WordPress default registration form';

	public function add_checkbox() {
		if ( ! $this->is_implicit() ) {
			$checked = $this->is_checkbox_precheck() ? 'checked ' : '';
			echo '<p><input ' . $checked . ' type="checkbox" name="mwb_m4wp_subscribe" id="mwb_m4wp_subscribe" value="yes">';
			echo '<label for="mwb_m4wp_subscribe">' . $this->get_option( 'checkbox_txt' ) . '</label></p>';
		}
	}

	// add hooks to show opt in checkbox
	public function add_hooks() {
		add_action( 'register_form', array( $this, 'add_checkbox' ) );
		add_action( 'user_register', array( $this, 'sync_registered_user' ), 99, 1 );
	}

	public function sync_registered_user( $user_id ) {
		// gather user data
		$user = get_userdata( $user_id );
		// check if user exist
		if ( ! $user instanceof WP_User ) {
			return false;
		}
		// get mapped user data
		$data = $this->get_mapped_properties( $user );
		// create contact in mautic
		$this->may_be_sync_data( $data );
	}

	public function get_mapped_properties( $user ) {
		// initialize firstname as username
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

	// check dependencies for the integration here
	public function is_active() {
		return true;
	}

}
