<?php
/**
 * User Registration Plugin form integration.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes/integrations
 */

// User Registration Plugin Added.
/**
 * The Registration form integration functionality.
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes/integrations
 * @author      makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Wpm_User_Registration_Plugin_Form extends Mwb_Wpm_Integration_Base {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $name    Name of the integration.
	 */
	public $name = 'User Registration Plugin Form';

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $description    Description of the integration.
	 */
	public $description = 'user registration plugin generated forms';

	/**
	 * Check and include admin view file
	 *
	 * @param string $form_data_array Define the form_data_array.
	 * @param string $form_id Define the form_id.
	 */
	public function user_registration_after_form_fields_new( $form_data_array, $form_id ) {

		if ( ! $this->is_implicit() ) {
			$args        = array(
				'post_type'   => 'user_registration',
				'post_status' => 'publish',
			);
			$query       = new WP_Query( $args );
			$posts       = $query->posts;
			$form_id_new = '';
			foreach ( $posts as $post ) {
				if ( $post->ID === (int) $form_id ) {
					$form_id_new = $post->ID;
				}
			}
			$checked = $this->is_checkbox_precheck() ? 'checked ' : '';

			echo '<div class="ur-form-row">
				 <div class="ur-form-grid ur-grid-1">
				 <div data-field-id="checkbox" class="ur-field-item field-checkbox ">
				 <span class="form-row validate-required user-registration-validated" id="checkbox_field" data-priority=""><label for="checkbox" class="ur-label"></label><input data-rules="" data-id="mwb_m4wp_subscribe2" type="hidden" class="input-checkbox ur-frontend-field user-registration-valid" name="checkbox2" id="checkbox2" placeholder="" value="' . esc_attr( $form_id_new ) . '" data-label="Custom Hidden Field" aria-invalid="false"></span>															</div>
				 </div>
		    	 </div>';

			echo '<div class="ur-form-row">
				 <div class="ur-form-grid ur-grid-1" id="checkbox-ur-no-implicit">
				 <div data-field-id="checkbox" class="ur-field-item field-checkbox ">
				 <span class="form-row validate-required user-registration-validated" id="checkbox_field" data-priority=""><input ' . esc_attr( $checked ) . ' data-rules="" data-id="mwb_m4wp_subscribe" type="checkbox" class="input-checkbox ur-frontend-field user-registration-valid" name="checkbox" id="checkbox" placeholder="" value="yes" data-label="Custom Checkbox" aria-invalid="false"><label for="checkbox" class="ur-label">' . esc_attr( $this->get_option( 'checkbox_txt' ) ) . '</label></span>															</div>
				 </div>
		    	 </div>';
		} else {
			$args        = array(
				'post_type'   => 'user_registration',
				'post_status' => 'publish',
			);
			$query       = new WP_Query( $args );
			$posts       = $query->posts;
			$form_id_new = '';
			foreach ( $posts as $post ) {
				if ( $post->ID === (int) $form_id ) {
					$form_id_new = $post->ID;
				}
			}

			echo '<div class="ur-form-row">
				 <div class="ur-form-grid ur-grid-1" id="checkbox-ur-implicit">
				 <div data-field-id="checkbox" class="ur-field-item field-checkbox ">
				 <div class="form-row validate-required user-registration-validated" id="checkbox_field" data-priority=""><label for="checkbox" class="ur-label"></label><input data-rules="" data-id="mwb_m4wp_subscribe2" type="hidden" class="input-checkbox ur-frontend-field user-registration-valid" name="checkbox2" id="checkbox2" placeholder="" value="' . esc_attr( $form_id_new ) . '" data-label="Custom Hidden Field" aria-invalid="false"></div>															</div>
				 </div>
		    	 </div>';
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
				if ( in_array( 'user-registration/user-registration.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
					add_action( 'user_registration_after_form_fields', array( $this, 'user_registration_after_form_fields_new' ), 1, 2 );
					add_action( 'user_register', array( $this, 'sync_registered_user' ), 99, 1 );
				}
			}
		} else {
			if ( in_array( 'user-registration/user-registration.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
				add_action( 'user_registration_after_form_fields', array( $this, 'user_registration_after_form_fields_new' ), 1, 2 );
				add_action( 'user_register', array( $this, 'sync_registered_user' ), 99, 1 );
			}
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
// User Registration Plugin Ended.
