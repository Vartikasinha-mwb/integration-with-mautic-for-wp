<?php
/**
 * Base integration class.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes/integrations
 */

/**
 * The class responsible for integration functionality.
 *
 * @package     Wp_Mautic_Integration
 * @subpackage  Wp_Mautic_Integration/includes/integrations
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

	// User Registration Plugin Added.

	/**
	 * Check if integration enable dynamic tag.
	 *
	 * @return bool
	 */
	public function is_dynamic_tag_enable() {
		if ( isset( $this->settings['dynamic_tag'] ) && 'yes' === $this->settings['dynamic_tag'] ) {
			return true;
		}
		return false;
	}

	// User Registration Plugin Ended.

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
			// User Registration Plugin Added.
			'dynamic_tag'  => 'no',
			'add_segment_ur' => '-1',
			// User Registration Plugin Ended.
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

	// User Registration Plugin Added.
	/**
	 * Get saved setting option for User reg Plugin.
	 *
	 * @param string $key Key of the setting option.
	 * @return string  $value Setting value.
	 */
	public function get_option_new( $key = '' ) {

		if ( empty( $this->settings[ $key ] ) ) {
			return '';
		}
		$value = isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $this->get_default_settings()[ $key ];
		return $value;

	}
	// User Registration Plugin Ended.

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

		$sync            = false;
		$form_tag_suffix = '';

		if ( ! $this->is_implicit() ) {

			//phpcs:disable
			if ( isset( $_POST['mwb_m4wp_subscribe'] ) && 'yes' === sanitize_text_field( wp_unslash( $_POST['mwb_m4wp_subscribe'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$sync = true;
				// User Registration Plugin Added.
			} else if ( in_array( 'user-registration/user-registration.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				$fields = array(
					'first_name' => 'first_name',
					'last_name' => 'last_name',
				);
				if ( isset( $_POST['form_data'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$form_data = explode( '{', sanitize_text_field( wp_unslash( $_POST['form_data'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
					$counter = 0;
					foreach ( $form_data as $key => $value ) {
						if ( strpos( $value, 'Custom Checkbox' ) !== false ) {
							$new_value = explode( '\"', $value );
							if ( 'yes' === $new_value[3] ) {
								$sync = true;
								break;
							}
						} else if ( strpos( $value, 'Custom Hidden Field' ) !== false ) {
							$new_value = explode( '\"', $value );
							$form_tag_suffix = $new_value[3];
						} else if ( strpos( $value, 'Country' ) !== false || strpos( $value, 'country' ) !== false ) {
							if ( 0 == $counter ) {
								$counter++;
								continue;
							}
							$new_value = explode( '\"', $value );
							$data['country'] = self::country_code_to_country( $new_value[3] );
							$counter++;
						} else {
							if ( 0 == $counter ) {
								$counter++;
								continue;
							}
							$new_value = explode( '\"', $value );
							if ( array_key_exists( $new_value[15], $fields ) ) {
								$key_new = $new_value[15];
								$key_new = str_replace( array( '_' ), '', $key_new );
								$data[ $key_new ] = $new_value[3];
							}
							$counter++;
						}
					}
				}
			}
			// User Registration Plugin Ended.
			//phpcs:enable
		} else {
			$sync = true;
			//phpcs:disable
			// User Registration Plugin Added.
			if ( in_array( 'user-registration/user-registration.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				$fields = array(
					'first_name' => 'first_name',
					'last_name' => 'last_name',
				);
				if ( isset( $_POST['form_data'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$form_data = explode( '{', sanitize_text_field( wp_unslash( $_POST['form_data'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
					$counter = 0;
					foreach ( $form_data as $key => $value ) {
						if ( strpos( $value, 'Custom Hidden Field' ) !== false ) {
							$new_value = explode( '\"', $value );
							$form_tag_suffix = $new_value[3];
						} else if ( strpos( $value, 'Country' ) !== false || strpos( $value, 'country' ) !== false ) {
							if ( 0 == $counter ) {
								$counter++;
								continue;
							}
							$new_value = explode( '\"', $value );
							$data['country'] = self::country_code_to_country( $new_value[3] );
							$counter++;
						} else {
							if ( 0 == $counter ) {
								$counter++;
								continue;
							}
							$new_value = explode( '\"', $value );
							if ( array_key_exists( $new_value[15], $fields ) ) {
								$key_new = $new_value[15];
								$key_new = str_replace( array( '_' ), '', $key_new );
								$data[ $key_new ] = $new_value[3];
							}
							$counter++;
						}
					}
				}
			}
			//phpcs:enable
			// User Registration Plugin Ended.
		}
		if ( ! $sync ) {
			return;
		}
		$tags_string = $this->get_option( 'add_tag' );
		$contact_id  = 0;
		// User Registration Plugin Added.
		if ( in_array( 'user-registration/user-registration.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			$dynamic_tag = $this->get_option( 'dynamic_tag' );
			if ( '' !== $form_tag_suffix ) {
				if ( 'yes' === $dynamic_tag ) {
					$args    = array(
						'post_type'   => 'user_registration',
						'post_status' => 'publish',
					);
					$query   = new WP_Query( $args );
					$postsss = $query->posts;
					foreach ( $postsss as $postss ) {
						$form_id = $postss->ID;
						if ( $form_id === (int) $form_tag_suffix ) {
							$integation_details = MWB_Wpm_Integration_Manager::get_integrations();
							foreach ( $integation_details as $a1 => $a2 ) {
								if ( 'Mwb_Wpm_User_Registration_Plugin_Form' === $a2['class'] ) {
									$integation      = MWB_Wpm_Integration_Manager::get_integration( $a2 );
									$tags_string_new = $integation->get_option_new( 'add_tag' . $form_id );
								}
							}
							if ( ! empty( $tags_string_new ) ) {
								$tags         = explode( ',', $tags_string_new );
								$data['tags'] = $tags;
							}
						}
					}
					$segment_id  = $this->get_option( 'add_segment_ur' );
					$contact = Mwb_Wpm_Api::create_contact( $data );
					if ( '-1' !== $segment_id ) {
						if ( isset( $contact['contact'] ) ) {
							$contact_id = $contact['contact']['id'];
						}
						if ( $contact_id > 0 ) {
							Mwb_Wpm_Api::add_contact_to_segment( $contact_id, $segment_id );
						}
					}
				} else {
					if ( 'User Registration Plugin Form' === $this->get_name() ) {
						if ( ! empty( $tags_string ) ) {
							$tags         = explode( ',', $tags_string );
							$data['tags'] = $tags;
						}
					}
					$segment_id  = $this->get_option( 'add_segment_ur' );
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
			} else {
				if ( 'Registration Form' === $this->get_name() || 'Comment Form' === $this->get_name() ) {
					if ( ! empty( $tags_string ) ) {
						$tags         = explode( ',', $tags_string );
						$data['tags'] = $tags;
					}
				}
				$segment_id  = $this->get_option( 'add_segment' );
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
			// User Registration Plugin Ended.
		} else {
			if ( ! empty( $tags_string ) ) {
				$tags         = explode( ',', $tags_string );
				$data['tags'] = $tags;
			}
			$segment_id  = $this->get_option( 'add_segment' );
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

	// User Registration Plugin Added.
	/**
	 * Sync data.
	 *
	 * @param string $code Country code to be fetched.
	 */
	public static function country_code_to_country( $code ) {
		$code = strtoupper( $code );
		if ( 'AF' === $code ) {
			return 'Afghanistan';
		}
		if ( 'AX' === $code ) {
			return 'Aland Islands';
		}
		if ( 'AL' === $code ) {
			return 'Albania';
		}
		if ( 'DZ' === $code ) {
			return 'Algeria';
		}
		if ( 'AS' === $code ) {
			return 'American Samoa';
		}
		if ( 'AD' === $code ) {
			return 'Andorra';
		}
		if ( 'AO' === $code ) {
			return 'Angola';
		}
		if ( 'AI' === $code ) {
			return 'Anguilla';
		}
		if ( 'AQ' === $code ) {
			return 'Antarctica';
		}
		if ( 'AG' === $code ) {
			return 'Antigua and Barbuda';
		}
		if ( 'AR' === $code ) {
			return 'Argentina';
		}
		if ( 'AM' === $code ) {
			return 'Armenia';
		}
		if ( 'AW' === $code ) {
			return 'Aruba';
		}
		if ( 'AU' === $code ) {
			return 'Australia';
		}
		if ( 'AT' === $code ) {
			return 'Austria';
		}
		if ( 'AZ' === $code ) {
			return 'Azerbaijan';
		}
		if ( 'BS' === $code ) {
			return 'Bahamas the';
		}
		if ( 'BH' === $code ) {
			return 'Bahrain';
		}
		if ( 'BD' === $code ) {
			return 'Bangladesh';
		}
		if ( 'BD' === $code ) {
			return 'Barbados';
		}
		if ( 'BY' === $code ) {
			return 'Belarus';
		}
		if ( 'BE' === $code ) {
			return 'Belgium';
		}
		if ( 'BZ' === $code ) {
			return 'Belize';
		}
		if ( 'BJ' === $code ) {
			return 'Benin';
		}
		if ( 'BM' === $code ) {
			return 'Bermuda';
		}
		if ( 'BT' === $code ) {
			return 'Bhutan';
		}
		if ( 'BO' === $code ) {
			return 'Bolivia';
		}
		if ( 'BA' === $code ) {
			return 'Bosnia and Herzegovina';
		}
		if ( 'BW' === $code ) {
			return 'Botswana';
		}
		if ( 'BV' === $code ) {
			return 'Bouvet Island (Bouvetoya)';
		}
		if ( 'BR' === $code ) {
			return 'Brazil';
		}
		if ( 'IO' === $code ) {
			return 'British Indian Ocean Territory (Chagos Archipelago)';
		}
		if ( 'VG' === $code ) {
			return 'British Virgin Islands';
		}
		if ( 'BN' === $code ) {
			return 'Brunei Darussalam';
		}
		if ( 'BG' === $code ) {
			return 'Bulgaria';
		}
		if ( 'BF' === $code ) {
			return 'Burkina Faso';
		}
		if ( 'BI' === $code ) {
			return 'Burundi';
		}
		if ( 'KH' === $code ) {
			return 'Cambodia';
		}
		if ( 'CM' === $code ) {
			return 'Cameroon';
		}
		if ( 'CA' === $code ) {
			return 'Canada';
		}
		if ( 'CV' === $code ) {
			return 'Cape Verde';
		}
		if ( 'KY' === $code ) {
			return 'Cayman Islands';
		}
		if ( 'CF' === $code ) {
			return 'Central African Republic';
		}
		if ( 'TD' === $code ) {
			return 'Chad';
		}
		if ( 'CL' === $code ) {
			return 'Chile';
		}
		if ( 'CN' === $code ) {
			return 'China';
		}
		if ( 'CX' === $code ) {
			return 'Christmas Island';
		}
		if ( 'CC' === $code ) {
			return 'Cocos (Keeling) Islands';
		}
		if ( 'CO' === $code ) {
			return 'Colombia';
		}
		if ( 'KM' === $code ) {
			return 'Comoros the';
		}
		if ( 'CD' === $code ) {
			return 'Congo';
		}
		if ( 'CG' === $code ) {
			return 'Congo the';
		}
		if ( 'CK' === $code ) {
			return 'Cook Islands';
		}
		if ( 'CR' === $code ) {
			return 'Costa Rica';
		}
		if ( 'CI' === $code ) {
			return 'Cote d\'Ivoire';
		}
		if ( 'HR' === $code ) {
			return 'Croatia';
		}
		if ( 'CU' === $code ) {
			return 'Cuba';
		}
		if ( 'CY' === $code ) {
			return 'Cyprus';
		}
		if ( 'CZ' === $code ) {
			return 'Czech Republic';
		}
		if ( 'DK' === $code ) {
			return 'Denmark';
		}
		if ( 'DJ' === $code ) {
			return 'Djibouti';
		}
		if ( 'DM' === $code ) {
			return 'Dominica';
		}
		if ( 'DO' === $code ) {
			return 'Dominican Republic';
		}
		if ( 'EC' === $code ) {
			return 'Ecuador';
		}
		if ( 'EG' === $code ) {
			return 'Egypt';
		}
		if ( 'SV' === $code ) {
			return 'El Salvador';
		}
		if ( 'GQ' === $code ) {
			return 'Equatorial Guinea';
		}
		if ( 'ER' === $code ) {
			return 'Eritrea';
		}
		if ( 'EE' === $code ) {
			return 'Estonia';
		}
		if ( 'ET' === $code ) {
			return 'Ethiopia';
		}
		if ( 'FO' === $code ) {
			return 'Faroe Islands';
		}
		if ( 'FK' === $code ) {
			return 'Falkland Islands (Malvinas)';
		}
		if ( 'FJ' === $code ) {
			return 'iji the Fiji Islands';
		}
		if ( 'FI' === $code ) {
			return 'Finland';
		}
		if ( 'FR' === $code ) {
			return 'France, French Republic';
		}
		if ( 'GF' === $code ) {
			return 'French Guiana';
		}
		if ( 'PF' === $code ) {
			return 'French Polynesia';
		}
		if ( 'TF' === $code ) {
			return 'French Southern Territories';
		}
		if ( 'GA' === $code ) {
			return 'Gabon';
		}
		if ( 'GM' === $code ) {
			return 'Gambia the';
		}
		if ( 'GE' === $code ) {
			return 'Georgia';
		}
		if ( 'DE' === $code ) {
			return 'Germany';
		}
		if ( 'GH' === $code ) {
			return 'Ghana';
		}
		if ( 'GI' === $code ) {
			return 'Gibraltar';
		}
		if ( 'GR' === $code ) {
			return 'Greece';
		}
		if ( 'GL' === $code ) {
			return 'Greenland';
		}
		if ( 'GD' === $code ) {
			return 'Grenada';
		}
		if ( 'GP' === $code ) {
			return 'Guadeloupe';
		}
		if ( 'GU' === $code ) {
			return 'Guam';
		}
		if ( 'GT' === $code ) {
			return 'Guatemala';
		}
		if ( 'GG' === $code ) {
			return 'Guernsey';
		}
		if ( 'GN' === $code ) {
			return 'Guinea';
		}
		if ( 'GW' === $code ) {
			return 'Guinea-Bissau';
		}
		if ( 'GY' === $code ) {
			return 'Guyana';
		}
		if ( 'HT' === $code ) {
			return 'Haiti';
		}
		if ( 'HM' === $code ) {
			return 'Heard Island and McDonald Islands';
		}
		if ( 'VA' === $code ) {
			return 'Holy See (Vatican City State)';
		}
		if ( 'HN' === $code ) {
			return 'Honduras';
		}
		if ( 'HK' === $code ) {
			return 'Hong Kong';
		}
		if ( 'HU' === $code ) {
			return 'Hungary';
		}
		if ( 'IS' === $code ) {
			return 'Iceland';
		}
		if ( 'IN' === $code ) {
			return 'India';
		}
		if ( 'ID' === $code ) {
			return 'Indonesia';
		}
		if ( 'IR' === $code ) {
			return 'Iran';
		}
		if ( 'IQ' === $code ) {
			return 'Iraq';
		}
		if ( 'IE' === $code ) {
			return 'Ireland';
		}
		if ( 'IM' === $code ) {
			return 'Isle of Man';
		}
		if ( 'IL' === $code ) {
			return 'Israel';
		}
		if ( 'IT' === $code ) {
			return 'Italy';
		}
		if ( 'JM' === $code ) {
			return 'Jamaica';
		}
		if ( 'JP' === $code ) {
			return 'Japan';
		}
		if ( 'JE' === $code ) {
			return 'Jersey';
		}
		if ( 'JO' === $code ) {
			return 'Jordan';
		}
		if ( 'KZ' === $code ) {
			return 'Kazakhstan';
		}
		if ( 'KE' === $code ) {
			return 'Kenya';
		}
		if ( 'KI' === $code ) {
			return 'Kiribati';
		}
		if ( 'KP' === $code ) {
			return 'Korea';
		}
		if ( 'KR' === $code ) {
			return 'Korea';
		}
		if ( 'KW' === $code ) {
			return 'Kuwait';
		}
		if ( 'KG' === $code ) {
			return 'Kyrgyz Republic';
		}
		if ( 'LA' === $code ) {
			return 'Lao';
		}
		if ( 'LV' === $code ) {
			return 'Latvia';
		}
		if ( 'LB' === $code ) {
			return 'Lebanon';
		}
		if ( 'LS' === $code ) {
			return 'Lesotho';
		}
		if ( 'LR' === $code ) {
			return 'Liberia';
		}
		if ( 'LY' === $code ) {
			return 'Libyan Arab Jamahiriya';
		}
		if ( 'LI' === $code ) {
			return 'Liechtenstein';
		}
		if ( 'LT' === $code ) {
			return 'Lithuania';
		}
		if ( 'LU' === $code ) {
			return 'Luxembourg';
		}
		if ( 'MO' === $code ) {
			return 'Macao';
		}
		if ( 'MK' === $code ) {
			return 'Macedonia';
		}
		if ( 'MG' === $code ) {
			return 'Madagascar';
		}
		if ( 'MW' === $code ) {
			return 'Malawi';
		}
		if ( 'MY' === $code ) {
			return 'Malaysia';
		}
		if ( 'MV' === $code ) {
			return 'Maldives';
		}
		if ( 'ML' === $code ) {
			return 'Mali';
		}
		if ( 'MT' === $code ) {
			return 'Malta';
		}
		if ( 'MH' === $code ) {
			return 'Marshall Islands';
		}
		if ( 'MQ' === $code ) {
			return 'Martinique';
		}
		if ( 'MR' === $code ) {
			return 'Mauritania';
		}
		if ( 'MU' === $code ) {
			return 'Mauritius';
		}
		if ( 'YT' === $code ) {
			return 'Mayotte';
		}
		if ( 'MX' === $code ) {
			return 'Mexico';
		}
		if ( 'FM' === $code ) {
			return 'Micronesia';
		}
		if ( 'MD' === $code ) {
			return 'Moldova';
		}
		if ( 'MC' === $code ) {
			return 'Monaco';
		}
		if ( 'MN' === $code ) {
			return 'Mongolia';
		}
		if ( 'ME' === $code ) {
			return 'Montenegro';
		}
		if ( 'MS' === $code ) {
			return 'Montserrat';
		}
		if ( 'MA' === $code ) {
			return 'Morocco';
		}
		if ( 'MZ' === $code ) {
			return 'Mozambique';
		}
		if ( 'MM' === $code ) {
			return 'Myanmar';
		}
		if ( 'NA' === $code ) {
			return 'Namibia';
		}
		if ( 'NR' === $code ) {
			return 'Nauru';
		}
		if ( 'NP' === $code ) {
			return 'Nepal';
		}
		if ( 'AN' === $code ) {
			return 'Netherlands Antilles';
		}
		if ( 'NL' === $code ) {
			return 'Netherlands the';
		}
		if ( 'NC' === $code ) {
			return 'New Caledonia';
		}
		if ( 'NZ' === $code ) {
			return 'New Zealand';
		}
		if ( 'NI' === $code ) {
			return 'Nicaragua';
		}
		if ( 'NE' === $code ) {
			return 'Niger';
		}
		if ( 'NG' === $code ) {
			return 'Nigeria';
		}
		if ( 'NU' === $code ) {
			return 'Niue';
		}
		if ( 'NF' === $code ) {
			return 'Norfolk Island';
		}
		if ( 'MP' === $code ) {
			return 'Northern Mariana Islands';
		}
		if ( 'NO' === $code ) {
			return 'Norway';
		}
		if ( 'OM' === $code ) {
			return 'Oman';
		}
		if ( 'PK' === $code ) {
			return 'Pakistan';
		}
		if ( 'PW' === $code ) {
			return 'Palau';
		}
		if ( 'PS' === $code ) {
			return 'Palestinian Territory';
		}
		if ( 'PA' === $code ) {
			return 'Panama';
		}
		if ( 'PG' === $code ) {
			return 'Papua New Guinea';
		}
		if ( 'PY' === $code ) {
			return 'Paraguay';
		}
		if ( 'PE' === $code ) {
			return 'Peru';
		}
		if ( 'PH' === $code ) {
			return 'Philippines';
		}
		if ( 'PN' === $code ) {
			return 'Pitcairn Islands';
		}
		if ( 'PL' === $code ) {
			return 'Poland';
		}
		if ( 'PT' === $code ) {
			return 'Portugal, Portuguese Republic';
		}
		if ( 'PR' === $code ) {
			return 'Puerto Rico';
		}
		if ( 'QA' === $code ) {
			return 'Qatar';
		}
		if ( 'RE' === $code ) {
			return 'Reunion';
		}
		if ( 'RO' === $code ) {
			return 'Romania';
		}
		if ( 'RU' === $code ) {
			return 'Russian Federation';
		}
		if ( 'RW' === $code ) {
			return 'Rwanda';
		}
		if ( 'BL' === $code ) {
			return 'Saint Barthelemy';
		}
		if ( 'SH' === $code ) {
			return 'Saint Helena';
		}
		if ( 'KN' === $code ) {
			return 'Saint Kitts and Nevis';
		}
		if ( 'LC' === $code ) {
			return 'Saint Lucia';
		}
		if ( 'MF' === $code ) {
			return 'Saint Martin';
		}
		if ( 'PM' === $code ) {
			return 'Saint Pierre and Miquelon';
		}
		if ( 'VC' === $code ) {
			return 'Saint Vincent and the Grenadines';
		}
		if ( 'WS' === $code ) {
			return 'Samoa';
		}
		if ( 'SM' === $code ) {
			return 'San Marino';
		}
		if ( 'ST' === $code ) {
			return 'Sao Tome and Principe';
		}
		if ( 'SA' === $code ) {
			return 'Saudi Arabia';
		}
		if ( 'SN' === $code ) {
			return 'Senegal';
		}
		if ( 'RS' === $code ) {
			return 'Serbia';
		}
		if ( 'SC' === $code ) {
			return 'Seychelles';
		}
		if ( 'SL' === $code ) {
			return 'Sierra Leone';
		}
		if ( 'SG' === $code ) {
			return 'Singapore';
		}
		if ( 'SK' === $code ) {
			return 'Slovakia (Slovak Republic)';
		}
		if ( 'SI' === $code ) {
			return 'Slovenia';
		}
		if ( 'SB' === $code ) {
			return 'Solomon Islands';
		}
		if ( 'SO' === $code ) {
			return 'Somalia, Somali Republic';
		}
		if ( 'ZA' === $code ) {
			return 'South Africa';
		}
		if ( 'GS' === $code ) {
			return 'South Georgia and the South Sandwich Islands';
		}
		if ( 'ES' === $code ) {
			return 'Spain';
		}
		if ( 'LK' === $code ) {
			return 'Sri Lanka';
		}
		if ( 'SD' === $code ) {
			return 'Sudan';
		}
		if ( 'SR' === $code ) {
			return 'Suriname';
		}
		if ( 'SJ' === $code ) {
			return 'Svalbard & Jan Mayen Islands';
		}
		if ( 'SZ' === $code ) {
			return 'Swaziland';
		}
		if ( 'SE' === $code ) {
			return 'Sweden';
		}
		if ( 'CH' === $code ) {
			return 'Switzerland, Swiss Confederation';
		}
		if ( 'SY' === $code ) {
			return 'Syrian Arab Republic';
		}
		if ( 'TW' === $code ) {
			return 'Taiwan';
		}
		if ( 'TJ' === $code ) {
			return 'Tajikistan';
		}
		if ( 'TZ' === $code ) {
			return 'Tanzania';
		}
		if ( 'TH' === $code ) {
			return 'Thailand';
		}
		if ( 'TL' === $code ) {
			return 'Timor-Leste';
		}
		if ( 'TG' === $code ) {
			return 'Togo';
		}
		if ( 'TK' === $code ) {
			return 'Tokelau';
		}
		if ( 'TO' === $code ) {
			return 'Tonga';
		}
		if ( 'TT' === $code ) {
			return 'Trinidad and Tobago';
		}
		if ( 'TN' === $code ) {
			return 'Tunisia';
		}
		if ( 'TR' === $code ) {
			return 'Turkey';
		}
		if ( 'TM' === $code ) {
			return 'Turkmenistan';
		}
		if ( 'TC' === $code ) {
			return 'Turks and Caicos Islands';
		}
		if ( 'TV' === $code ) {
			return 'Tuvalu';
		}if ( 'UG' === $code ) {
			return 'Uganda';
		}
		if ( 'UA' === $code ) {
			return 'Ukraine';
		}
		if ( 'AE' === $code ) {
			return 'United Arab Emirates';
		}
		if ( 'GB' === $code ) {
			return 'United Kingdom';
		}
		if ( 'US' === $code ) {
			return 'United States of America';
		}
		if ( 'UM' === $code ) {
			return 'United States Minor Outlying Islands';
		}
		if ( 'VI' === $code ) {
			return 'United States Virgin Islands';
		}
		if ( 'UY' === $code ) {
			return 'Uruguay, Eastern Republic of';
		}if ( 'UZ' === $code ) {
			return 'Uzbekistan';
		}
		if ( 'VU' === $code ) {
			return 'Vanuatu';
		}
		if ( 'VE' === $code ) {
			return 'Venezuela';
		}
		if ( 'VN' === $code ) {
			return 'Vietnam';
		}
		if ( 'WF' === $code ) {
			return 'Wallis and Futuna';
		}
		if ( 'EH' === $code ) {
			return 'Western Sahara';
		}
		if ( 'YE' === $code ) {
			return 'Yemen';
		}
		if ( 'XK' === $code ) {
			return 'Kosovo';
		}
		if ( 'ZM' === $code ) {
			return 'Zambia';
		}
		if ( 'ZW' === $code ) {
			return 'Zimbabwe';
		}

		return '';
	}
	// User Registration Plugin Ended.
}
