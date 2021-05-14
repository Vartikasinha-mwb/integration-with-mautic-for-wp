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

	// User Registration Plugin Added

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

	// User Registration Plugin Ended

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
			// User Registration Plugin Added
			'dynamic_tag'  => 'no',
			// User Registration Plugin Ended
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
		$form_tag_suffix = '';

		if ( ! $this->is_implicit() ) {
			
			//phpcs:disable
			if ( isset( $_POST['mwb_m4wp_subscribe'] ) && 'yes' === sanitize_text_field( wp_unslash( $_POST['mwb_m4wp_subscribe'] ) ) ) {
				$sync = true;
				// User Registration Plugin Added
			} else if ( in_array( 'user-registration/user-registration.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				$fields = array( 'first_name'=>'first_name', 'last_name'=>'last_name' );
				$form_data = explode( '{', $_POST['form_data']);
				$counter = 0;
				foreach( $form_data as $key=>$value ) {
					if ( strpos( $value, 'Custom Checkbox' ) !== false ) {
						$new_value = explode( '\"', $value );
						if( $new_value[3] == 'yes' ) {
							$sync = true;
							break;
						}
					} else if ( strpos( $value, 'Custom Hidden Field' ) !== false ) {
						$new_value = explode( '\"', $value );
						$form_tag_suffix = $new_value[3];
					} else if( strpos( $value, 'Country' ) !== false || strpos( $value, 'country' ) !== false ) { 
						if( $counter == 0 ) {
							$counter++;
							continue; 
						}
						$new_value = explode( '\"', $value );
						$data['country'] = self::countryCodeToCountry( $new_value[3] );
						$counter++;
					} else {
						if( $counter == 0 ) {
							$counter++;
							continue;
						}
						$new_value = explode( '\"', $value );
						if( array_key_exists( $new_value[15], $fields ) ) {
							$key_new = $new_value[15];
							$key_new = str_replace( array( '_' ), '', $key_new);
							$data[$key_new] = $new_value[3];
						}
						$counter++;
					}
				}
			}
			// User Registration Plugin Ended
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
		// User Registration Plugin Added
		if ( in_array( 'user-registration/user-registration.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$dynamic_tag = $this->get_option( 'dynamic_tag' );
			if( $form_tag_suffix != '' ) {
				if( $dynamic_tag == 'yes' ) {
	
					$query = new WP_Query( array( 'post_type' => 'user_registration', 'post_status' => 'publish' ) );
					$posts = $query->posts;
					foreach( $posts as $post ) {
						$form_id = $post->ID;
						if( $form_id == $form_tag_suffix ) {
							$integation_details = MWB_Wpm_Integration_Manager::get_integrations();
							foreach( $integation_details as $a1=>$a2 ) {
								if( $a2['class'] == 'Mwb_Wpm_User_Registration_Plugin_Form' ) {
									$integation = MWB_Wpm_Integration_Manager::get_integration( $a2 );
									$tags_string_new = $integation->get_option( 'add_tag' . $form_id );
								}
							}
							if ( ! empty( $tags_string_new ) ) {
								$tags = explode( ',', $tags_string_new );
								$data['tags'] = $tags;
							}
						}
					}
				} else {
					if( $this->get_name() == 'User Registration Plugin Form' ) {
						if ( ! empty( $tags_string ) ) {
							$tags = explode( ',', $tags_string );
							$data['tags'] = $tags;
						}
					}
				}
			} else {
				if( $this->get_name() == 'Registration Form' || $this->get_name() == 'Comment Form' ) {
					if ( ! empty( $tags_string ) ) {
						$tags = explode( ',', $tags_string );
						$data['tags'] = $tags;
					}
				}
			}
		// User Registration Plugin Ended	
		} else {
			if ( ! empty( $tags_string ) ) {
				$tags = explode( ',', $tags_string );
				$data['tags'] = $tags;
			}
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

	// User Registration Plugin Added
	public static function countryCodeToCountry($code) {
		$code = strtoupper($code);
		if ($code == 'AF') return 'Afghanistan';
		if ($code == 'AX') return 'Aland Islands';
		if ($code == 'AL') return 'Albania';
		if ($code == 'DZ') return 'Algeria';
		if ($code == 'AS') return 'American Samoa';
		if ($code == 'AD') return 'Andorra';
		if ($code == 'AO') return 'Angola';
		if ($code == 'AI') return 'Anguilla';
		if ($code == 'AQ') return 'Antarctica';
		if ($code == 'AG') return 'Antigua and Barbuda';
		if ($code == 'AR') return 'Argentina';
		if ($code == 'AM') return 'Armenia';
		if ($code == 'AW') return 'Aruba';
		if ($code == 'AU') return 'Australia';
		if ($code == 'AT') return 'Austria';
		if ($code == 'AZ') return 'Azerbaijan';
		if ($code == 'BS') return 'Bahamas the';
		if ($code == 'BH') return 'Bahrain';
		if ($code == 'BD') return 'Bangladesh';
		if ($code == 'BB') return 'Barbados';
		if ($code == 'BY') return 'Belarus';
		if ($code == 'BE') return 'Belgium';
		if ($code == 'BZ') return 'Belize';
		if ($code == 'BJ') return 'Benin';
		if ($code == 'BM') return 'Bermuda';
		if ($code == 'BT') return 'Bhutan';
		if ($code == 'BO') return 'Bolivia';
		if ($code == 'BA') return 'Bosnia and Herzegovina';
		if ($code == 'BW') return 'Botswana';
		if ($code == 'BV') return 'Bouvet Island (Bouvetoya)';
		if ($code == 'BR') return 'Brazil';
		if ($code == 'IO') return 'British Indian Ocean Territory (Chagos Archipelago)';
		if ($code == 'VG') return 'British Virgin Islands';
		if ($code == 'BN') return 'Brunei Darussalam';
		if ($code == 'BG') return 'Bulgaria';
		if ($code == 'BF') return 'Burkina Faso';
		if ($code == 'BI') return 'Burundi';
		if ($code == 'KH') return 'Cambodia';
		if ($code == 'CM') return 'Cameroon';
		if ($code == 'CA') return 'Canada';
		if ($code == 'CV') return 'Cape Verde';
		if ($code == 'KY') return 'Cayman Islands';
		if ($code == 'CF') return 'Central African Republic';
		if ($code == 'TD') return 'Chad';
		if ($code == 'CL') return 'Chile';
		if ($code == 'CN') return 'China';
		if ($code == 'CX') return 'Christmas Island';
		if ($code == 'CC') return 'Cocos (Keeling) Islands';
		if ($code == 'CO') return 'Colombia';
		if ($code == 'KM') return 'Comoros the';
		if ($code == 'CD') return 'Congo';
		if ($code == 'CG') return 'Congo the';
		if ($code == 'CK') return 'Cook Islands';
		if ($code == 'CR') return 'Costa Rica';
		if ($code == 'CI') return 'Cote d\'Ivoire';
		if ($code == 'HR') return 'Croatia';
		if ($code == 'CU') return 'Cuba';
		if ($code == 'CY') return 'Cyprus';
		if ($code == 'CZ') return 'Czech Republic';
		if ($code == 'DK') return 'Denmark';
		if ($code == 'DJ') return 'Djibouti';
		if ($code == 'DM') return 'Dominica';
		if ($code == 'DO') return 'Dominican Republic';
		if ($code == 'EC') return 'Ecuador';
		if ($code == 'EG') return 'Egypt';
		if ($code == 'SV') return 'El Salvador';
		if ($code == 'GQ') return 'Equatorial Guinea';
		if ($code == 'ER') return 'Eritrea';
		if ($code == 'EE') return 'Estonia';
		if ($code == 'ET') return 'Ethiopia';
		if ($code == 'FO') return 'Faroe Islands';
		if ($code == 'FK') return 'Falkland Islands (Malvinas)';
		if ($code == 'FJ') return 'Fiji the Fiji Islands';
		if ($code == 'FI') return 'Finland';
		if ($code == 'FR') return 'France, French Republic';
		if ($code == 'GF') return 'French Guiana';
		if ($code == 'PF') return 'French Polynesia';
		if ($code == 'TF') return 'French Southern Territories';
		if ($code == 'GA') return 'Gabon';
		if ($code == 'GM') return 'Gambia the';
		if ($code == 'GE') return 'Georgia';
		if ($code == 'DE') return 'Germany';
		if ($code == 'GH') return 'Ghana';
		if ($code == 'GI') return 'Gibraltar';
		if ($code == 'GR') return 'Greece';
		if ($code == 'GL') return 'Greenland';
		if ($code == 'GD') return 'Grenada';
		if ($code == 'GP') return 'Guadeloupe';
		if ($code == 'GU') return 'Guam';
		if ($code == 'GT') return 'Guatemala';
		if ($code == 'GG') return 'Guernsey';
		if ($code == 'GN') return 'Guinea';
		if ($code == 'GW') return 'Guinea-Bissau';
		if ($code == 'GY') return 'Guyana';
		if ($code == 'HT') return 'Haiti';
		if ($code == 'HM') return 'Heard Island and McDonald Islands';
		if ($code == 'VA') return 'Holy See (Vatican City State)';
		if ($code == 'HN') return 'Honduras';
		if ($code == 'HK') return 'Hong Kong';
		if ($code == 'HU') return 'Hungary';
		if ($code == 'IS') return 'Iceland';
		if ($code == 'IN') return 'India';
		if ($code == 'ID') return 'Indonesia';
		if ($code == 'IR') return 'Iran';
		if ($code == 'IQ') return 'Iraq';
		if ($code == 'IE') return 'Ireland';
		if ($code == 'IM') return 'Isle of Man';
		if ($code == 'IL') return 'Israel';
		if ($code == 'IT') return 'Italy';
		if ($code == 'JM') return 'Jamaica';
		if ($code == 'JP') return 'Japan';
		if ($code == 'JE') return 'Jersey';
		if ($code == 'JO') return 'Jordan';
		if ($code == 'KZ') return 'Kazakhstan';
		if ($code == 'KE') return 'Kenya';
		if ($code == 'KI') return 'Kiribati';
		if ($code == 'KP') return 'Korea';
		if ($code == 'KR') return 'Korea';
		if ($code == 'KW') return 'Kuwait';
		if ($code == 'KG') return 'Kyrgyz Republic';
		if ($code == 'LA') return 'Lao';
		if ($code == 'LV') return 'Latvia';
		if ($code == 'LB') return 'Lebanon';
		if ($code == 'LS') return 'Lesotho';
		if ($code == 'LR') return 'Liberia';
		if ($code == 'LY') return 'Libyan Arab Jamahiriya';
		if ($code == 'LI') return 'Liechtenstein';
		if ($code == 'LT') return 'Lithuania';
		if ($code == 'LU') return 'Luxembourg';
		if ($code == 'MO') return 'Macao';
		if ($code == 'MK') return 'Macedonia';
		if ($code == 'MG') return 'Madagascar';
		if ($code == 'MW') return 'Malawi';
		if ($code == 'MY') return 'Malaysia';
		if ($code == 'MV') return 'Maldives';
		if ($code == 'ML') return 'Mali';
		if ($code == 'MT') return 'Malta';
		if ($code == 'MH') return 'Marshall Islands';
		if ($code == 'MQ') return 'Martinique';
		if ($code == 'MR') return 'Mauritania';
		if ($code == 'MU') return 'Mauritius';
		if ($code == 'YT') return 'Mayotte';
		if ($code == 'MX') return 'Mexico';
		if ($code == 'FM') return 'Micronesia';
		if ($code == 'MD') return 'Moldova';
		if ($code == 'MC') return 'Monaco';
		if ($code == 'MN') return 'Mongolia';
		if ($code == 'ME') return 'Montenegro';
		if ($code == 'MS') return 'Montserrat';
		if ($code == 'MA') return 'Morocco';
		if ($code == 'MZ') return 'Mozambique';
		if ($code == 'MM') return 'Myanmar';
		if ($code == 'NA') return 'Namibia';
		if ($code == 'NR') return 'Nauru';
		if ($code == 'NP') return 'Nepal';
		if ($code == 'AN') return 'Netherlands Antilles';
		if ($code == 'NL') return 'Netherlands the';
		if ($code == 'NC') return 'New Caledonia';
		if ($code == 'NZ') return 'New Zealand';
		if ($code == 'NI') return 'Nicaragua';
		if ($code == 'NE') return 'Niger';
		if ($code == 'NG') return 'Nigeria';
		if ($code == 'NU') return 'Niue';
		if ($code == 'NF') return 'Norfolk Island';
		if ($code == 'MP') return 'Northern Mariana Islands';
		if ($code == 'NO') return 'Norway';
		if ($code == 'OM') return 'Oman';
		if ($code == 'PK') return 'Pakistan';
		if ($code == 'PW') return 'Palau';
		if ($code == 'PS') return 'Palestinian Territory';
		if ($code == 'PA') return 'Panama';
		if ($code == 'PG') return 'Papua New Guinea';
		if ($code == 'PY') return 'Paraguay';
		if ($code == 'PE') return 'Peru';
		if ($code == 'PH') return 'Philippines';
		if ($code == 'PN') return 'Pitcairn Islands';
		if ($code == 'PL') return 'Poland';
		if ($code == 'PT') return 'Portugal, Portuguese Republic';
		if ($code == 'PR') return 'Puerto Rico';
		if ($code == 'QA') return 'Qatar';
		if ($code == 'RE') return 'Reunion';
		if ($code == 'RO') return 'Romania';
		if ($code == 'RU') return 'Russian Federation';
		if ($code == 'RW') return 'Rwanda';
		if ($code == 'BL') return 'Saint Barthelemy';
		if ($code == 'SH') return 'Saint Helena';
		if ($code == 'KN') return 'Saint Kitts and Nevis';
		if ($code == 'LC') return 'Saint Lucia';
		if ($code == 'MF') return 'Saint Martin';
		if ($code == 'PM') return 'Saint Pierre and Miquelon';
		if ($code == 'VC') return 'Saint Vincent and the Grenadines';
		if ($code == 'WS') return 'Samoa';
		if ($code == 'SM') return 'San Marino';
		if ($code == 'ST') return 'Sao Tome and Principe';
		if ($code == 'SA') return 'Saudi Arabia';
		if ($code == 'SN') return 'Senegal';
		if ($code == 'RS') return 'Serbia';
		if ($code == 'SC') return 'Seychelles';
		if ($code == 'SL') return 'Sierra Leone';
		if ($code == 'SG') return 'Singapore';
		if ($code == 'SK') return 'Slovakia (Slovak Republic)';
		if ($code == 'SI') return 'Slovenia';
		if ($code == 'SB') return 'Solomon Islands';
		if ($code == 'SO') return 'Somalia, Somali Republic';
		if ($code == 'ZA') return 'South Africa';
		if ($code == 'GS') return 'South Georgia and the South Sandwich Islands';
		if ($code == 'ES') return 'Spain';
		if ($code == 'LK') return 'Sri Lanka';
		if ($code == 'SD') return 'Sudan';
		if ($code == 'SR') return 'Suriname';
		if ($code == 'SJ') return 'Svalbard & Jan Mayen Islands';
		if ($code == 'SZ') return 'Swaziland';
		if ($code == 'SE') return 'Sweden';
		if ($code == 'CH') return 'Switzerland, Swiss Confederation';
		if ($code == 'SY') return 'Syrian Arab Republic';
		if ($code == 'TW') return 'Taiwan';
		if ($code == 'TJ') return 'Tajikistan';
		if ($code == 'TZ') return 'Tanzania';
		if ($code == 'TH') return 'Thailand';
		if ($code == 'TL') return 'Timor-Leste';
		if ($code == 'TG') return 'Togo';
		if ($code == 'TK') return 'Tokelau';
		if ($code == 'TO') return 'Tonga';
		if ($code == 'TT') return 'Trinidad and Tobago';
		if ($code == 'TN') return 'Tunisia';
		if ($code == 'TR') return 'Turkey';
		if ($code == 'TM') return 'Turkmenistan';
		if ($code == 'TC') return 'Turks and Caicos Islands';
		if ($code == 'TV') return 'Tuvalu';
		if ($code == 'UG') return 'Uganda';
		if ($code == 'UA') return 'Ukraine';
		if ($code == 'AE') return 'United Arab Emirates';
		if ($code == 'GB') return 'United Kingdom';
		if ($code == 'US') return 'United States of America';
		if ($code == 'UM') return 'United States Minor Outlying Islands';
		if ($code == 'VI') return 'United States Virgin Islands';
		if ($code == 'UY') return 'Uruguay, Eastern Republic of';
		if ($code == 'UZ') return 'Uzbekistan';
		if ($code == 'VU') return 'Vanuatu';
		if ($code == 'VE') return 'Venezuela';
		if ($code == 'VN') return 'Vietnam';
		if ($code == 'WF') return 'Wallis and Futuna';
		if ($code == 'EH') return 'Western Sahara';
		if ($code == 'YE') return 'Yemen';
		if ($code == 'XK') return 'Kosovo';
		if ($code == 'ZM') return 'Zambia';
		if ($code == 'ZW') return 'Zimbabwe';
		return '';
	}
	// User Registration Plugin Ended
}
