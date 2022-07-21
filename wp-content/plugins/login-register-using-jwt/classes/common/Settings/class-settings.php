<?php
/**
 * App
 *
 * JWT Common Settings.
 *
 * @category   Common, Core
 * @package    MoJWT
 * @author     miniOrange <info@miniorange.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link       https://miniorange.com
 */

namespace MoJWT;

use MoJWT\MJUtils;
use MoJWT\Customer;



/**
 * Class for JWT Settings.
 *
 * @category Common, Core
 * @package  MoJWT
 * @author   miniOrange <info@miniorange.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link     https://miniorange.com
 */
class Settings {

	/**
	 * JWT Plugin Configuration
	 *
	 * @var Array $config
	 * */
	public $config;

	/**
	 * JWT utils
	 *
	 * @var \MoJWT\mj_utils $util
	 * */
	public $util;

	/**
	 * Constructor.
	 */
	public function __construct() {
		global $mj_util;
		$this->util   = $mj_util;
		add_action( 'admin_init', array( $this, 'miniorange_jwt_save_settings' ) );
		$this->config = $this->util->get_plugin_config('mo_jwt_config_settings');
		$this->attr_config = $this->util->get_plugin_config('mo_jwt_attr_settings');
	}

	/**
	 * Saves Settings.
	 *
	 * @return void
	 */
	public function miniorange_jwt_save_settings() {
		global $mj_util;
		if (sanitize_text_field($_SERVER['REQUEST_METHOD']) === 'POST' &&  current_user_can('administrator') ) {
			
			if( isset( $_POST['mo_jwt_config_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_jwt_config_nonce'] ) ), 'mo_jwt_config_settings' ) && isset( $_POST[ \MoJWTConstants::OPTION ] ) && 'mo_jwt_config_settings' === sanitize_text_field( $_POST[ \MoJWTConstants::OPTION ]) ){
				$this->save_configurations($_POST);	
			}

			if( isset( $_POST['mo_jwt_mapping_section_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_jwt_mapping_section_nonce'] ) ), 'mo_jwt_mapping_section' ) && isset( $_POST[ \MoJWTConstants::OPTION ] ) && 'mo_jwt_mapping_section' === sanitize_text_field( $_POST[ \MoJWTConstants::OPTION ]) ){
				$this->save_attr_mapping_configurations($_POST);	
			}

			if ( isset( $_POST['mo_jwt_change_miniorange_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_jwt_change_miniorange_nonce'] ) ), 'mo_jwt_change_miniorange' ) && isset( $_POST[ \MoJWTConstants::OPTION ] ) && 'mo_jwt_change_miniorange' === sanitize_text_field($_POST[ \MoJWTConstants::OPTION ] )) {
				mo_jwt_deactivate();
				return;
			}
			
			if ( isset( $_POST['mo_jwt_register_customer_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_jwt_register_customer_nonce'] ) ), 'mo_jwt_register_customer' ) && isset( $_POST[ \MoJWTConstants::OPTION ] ) && 'mo_jwt_register_customer' === sanitize_text_field($_POST[ \MoJWTConstants::OPTION ]) ) {
				// register the admin to miniOrange
				// validation and sanitization.
				$email            = '';
				$phone            = '';
				$password         = '';
				$fname            = '';
				$lname            = '';
				$company          = '';
				$confirm_password = '';
				if ( $this->util->mo_jwt_check_empty_or_null( $_POST['email'] ) || $this->util->mo_jwt_check_empty_or_null( $_POST['password'] ) || $this->util->mo_jwt_check_empty_or_null( $_POST['confirmPassword'] ) ) { // phpcs:ignore
					$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'All the fields are required. Please enter valid entries.' );
					$this->util->mo_jwt_show_error_message();
					return;
				}
				if ( strlen( $_POST['password'] ) < 8 || strlen( $_POST['confirmPassword'] ) < 8 ) { // phpcs:ignore
					$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Choose a password with minimum length 8.' );
					$this->util->mo_jwt_show_error_message();
					return;
				} else {
					$email            = sanitize_email( $_POST['email'] ); // phpcs:ignore
					$phone            = sanitize_text_field(stripslashes( $_POST['phone'] )); // phpcs:ignore
					$password         = sanitize_text_field(stripslashes( $_POST['password'] ) ); // phpcs:ignore
					$fname            = sanitize_text_field(stripslashes( $_POST['fname'] ) ); // phpcs:ignore
					$lname            = sanitize_text_field(stripslashes( $_POST['lname'] ) ); // phpcs:ignore
					$company          = sanitize_text_field(stripslashes( $_POST['company'] ) ); // phpcs:ignore
					$confirm_password = sanitize_text_field(stripslashes( $_POST['confirmPassword'] ) ); // phpcs:ignore
				}

				$this->util->mo_jwt_update_option( 'mo_jwt_admin_email', $email );
				$this->util->mo_jwt_update_option( 'mo_jwt_admin_phone', $phone );
				$this->util->mo_jwt_update_option( 'mo_jwt_admin_fname', $fname );
				$this->util->mo_jwt_update_option( 'mo_jwt_admin_lname', $lname );
				$this->util->mo_jwt_update_option( 'mo_jwt_admin_company', $company );

				if ( $this->util->mo_jwt_is_curl_installed() === 0 ) {
					return $this->util->mo_jwt_show_curl_error();
				}

				if ( strcmp( $password, $confirm_password ) === 0 ) {
					$this->util->mo_jwt_update_option( 'password', $password );
					$customer = new Customer();
					$email    = $this->util->mo_jwt_get_option( 'mo_jwt_admin_email' );
					$content  = json_decode( $customer->check_customer(), true );
					if ( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND' ) === 0 ) {
						$this->create_customer();
					} else {
						$this->mo_jwt_get_current_customer();
					}
				} else {
					$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Passwords do not match.' );
					$this->util->mo_jwt_update_option( 'mo_jwt_verify_customer' );
					$this->util->mo_jwt_show_error_message();
				}
			}
			if ( isset( $_POST['mo_jwt_verify_customer_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_jwt_verify_customer_nonce'] ) ), 'mo_jwt_verify_customer' ) && isset( $_POST[ \MoJWTConstants::OPTION ] ) && 'mo_jwt_verify_customer' === sanitize_text_field($_POST[ \MoJWTConstants::OPTION ]) ) {
				// register the admin to miniOrange.
				if ( $this->util->mo_jwt_is_curl_installed() === 0 ) {
					return $this->util->mo_jwt_show_curl_error();
				}
				// validation and sanitization.
				$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
				$password = isset( $_POST['password'] ) ? sanitize_text_field($_POST['password']) : '';
				if ( $this->util->mo_jwt_check_empty_or_null( $email ) || $this->util->mo_jwt_check_empty_or_null( $password ) ) {
					$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'All the fields are required. Please enter valid entries.' );
					$this->util->mo_jwt_show_error_message();
					return;
				}

				$this->util->mo_jwt_update_option( 'mo_jwt_admin_email', $email );
				$this->util->mo_jwt_update_option( 'password', $password );
				$customer     = new Customer();
				$content      = $customer->get_customer_key();
				$customer_key = json_decode( $content, true );
				if ( json_last_error() === JSON_ERROR_NONE ) {
					$this->util->mo_jwt_update_option( 'mo_jwt_admin_customer_key', $customer_key['id'] );
					$this->util->mo_jwt_update_option( 'mo_jwt_admin_api_key', $customer_key['apiKey'] );
					$this->util->mo_jwt_update_option( 'customer_token', $customer_key['token'] );
					if( isset( $customerKey['phone'] ) )
						$this->util->mo_jwt_update_option( 'mo_jwt_admin_phone', $customer_key['phone'] );
					$this->util->mo_jwt_delete_option( 'password' );
					$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Customer retrieved successfully' );
					$this->util->mo_jwt_delete_option( 'mo_jwt_verify_customer' );
					$this->util->mo_jwt_show_success_message();
				} else {
					$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Invalid username or password. Please try again.' );
					$this->util->mo_jwt_show_error_message();
				}
			}
			if ( isset( $_POST['mo_jwt_change_email_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_jwt_change_email_nonce'] ) ), 'mo_jwt_change_email' ) && isset( $_POST[ \MoJWTConstants::OPTION ] ) && 'mo_jwt_change_email' === sanitize_text_field($_POST[ \MoJWTConstants::OPTION ]) ) {
				// Adding back button.
				$this->util->mo_jwt_update_option( 'mo_jwt_verify_customer', '' );
				$this->util->mo_jwt_update_option( 'mo_jwt_registration_status', '' );
				$this->util->mo_jwt_update_option( 'mo_jwt_new_registration', 'true' );
			}
			if ( isset( $_POST['mo_jwt_contact_us_query_option_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_jwt_contact_us_query_option_nonce'] ) ), 'mo_jwt_contact_us_query_option' ) && isset( $_POST[ \MoJWTConstants::OPTION ] ) && 'mo_jwt_contact_us_query_option' === sanitize_text_field($_POST[ \MoJWTConstants::OPTION ]) ) {
				if ( $this->util->mo_jwt_is_curl_installed() === 0 ) {
					return $this->util->mo_jwt_show_curl_error();
				}
				// Contact Us query.
				$email       = isset( $_POST['mo_jwt_contact_us_email'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_jwt_contact_us_email'] ) ) : '';
				$phone       = isset( $_POST['mo_jwt_contact_us_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_jwt_contact_us_phone'] ) ) : '';
				$query       = isset( $_POST['mo_jwt_contact_us_query'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_jwt_contact_us_query'] ) ) : '';
				$customer = new Customer();
				if ( $this->util->mo_jwt_check_empty_or_null( $email ) || $this->util->mo_jwt_check_empty_or_null( $query ) ) {
					$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Please fill up Email and Query fields to submit your query.' );
					$this->util->mo_jwt_show_error_message();
				} else {
					$send_config = false;
					$submited = $customer->submit_contact_us( $email, $phone, $query, $send_config );
					if ( false === $submited ) {
						$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Your query could not be submitted. Please try again.' );
						$this->util->mo_jwt_show_error_message();
					} else {
						$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Thanks for getting in touch! We shall get back to you shortly.' );
						$this->util->mo_jwt_show_success_message();
					}
				}
			}
		}
	}


	/**
	 * Get current customer account.
	 *
	 * @return void
	 */
	public function mo_jwt_get_current_customer() {
		$customer     = new Customer();
		$content      = $customer->get_customer_key();
		$customer_key = json_decode( $content, true );
		if ( json_last_error() === JSON_ERROR_NONE ) {
			$this->util->mo_jwt_update_option( 'mo_jwt_admin_customer_key', $customer_key['id'] );
			$this->util->mo_jwt_update_option( 'mo_jwt_admin_api_key', $customer_key['apiKey'] );
			$this->util->mo_jwt_update_option( 'customer_token', $customer_key['token'] );
			$this->util->mo_jwt_update_option( 'password', '' );
			$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Customer retrieved successfully' );
			$this->util->mo_jwt_delete_option( 'mo_jwt_verify_customer' );
			$this->util->mo_jwt_delete_option( 'mo_jwt_new_registration' );
			$this->util->mo_jwt_show_success_message();
		} else {
			$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'You already have an account with miniOrange. Please enter a valid password.' );
			$this->util->mo_jwt_update_option( 'mo_jwt_verify_customer', 'true' );
			$this->util->mo_jwt_show_error_message();

		}
	}

	/**
	 * Create customer from API wrapper.
	 */
	public function create_customer() {
		global $mj_util;
		$customer     = new Customer();
		$customer_key = json_decode( $customer->create_customer(), true );
		if ( strcasecmp( $customer_key['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS' ) === 0 ) {
			$this->mo_jwt_get_current_customer();
			$this->util->mo_jwt_delete_option( 'mo_jwt_new_customer' );
		} elseif ( strcasecmp( $customer_key['status'], 'SUCCESS' ) === 0 ) {
			$this->util->mo_jwt_update_option( 'mo_jwt_admin_customer_key', $customer_key['id'] );
			$this->util->mo_jwt_update_option( 'mo_jwt_admin_api_key', $customer_key['apiKey'] );
			$this->util->mo_jwt_update_option( 'customer_token', $customer_key['token'] );
			$this->util->mo_jwt_update_option( 'password', '' );
			$this->util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Registered successfully.' );
			$this->util->mo_jwt_update_option( 'mo_jwt_registration_status', 'mo_jwt_REGISTRATION_COMPLETE' );
			$this->util->mo_jwt_update_option( 'mo_jwt_new_customer', 1 );
			$this->util->mo_jwt_delete_option( 'mo_jwt_verify_customer' );
			$this->util->mo_jwt_delete_option( 'mo_jwt_new_registration' );
			$this->util->mo_jwt_show_success_message();
		}
	}

	public function save_attr_mapping_configurations($post){
		global $mj_util;

		$attr_config = $mj_util->get_plugin_config('mo_jwt_attr_settings') ? $mj_util->get_plugin_config('mo_jwt_attr_settings') : array();

		$default = array();	
		
		$default['username'] = isset( $_POST['mo_jwt_username_attr'] ) ? sanitize_text_field(stripslashes( wp_unslash( $_POST['mo_jwt_username_attr'] ) ) ): '' ;
		$attr_config['default'] = $default;

		$mj_util->update_plugin_config($attr_config, 'mo_jwt_attr_settings');

		$mj_util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Settings are saved successfully.' );
		$mj_util->mo_jwt_show_success_message();
	}

	public function save_configurations($post) {
		global $mj_util;
		$jwtlogin = array();
		$jwtcreate = array();
		$jwtregister = array();
		$jwtdelete = array();
		
		if(isset($post['mo_jwt_method'])){

			$mj_util->mo_jwt_set_transient('mo_jwt', sanitize_text_field($post['mo_jwt_method']), 3600);
			$method_config = $this->config ? $this->config : array();
			$jwtlogin['mo_jwt_login_with'] = isset( $_POST['mo_jwt_login_with'] ) ? sanitize_text_field(stripslashes( wp_unslash( $_POST['mo_jwt_login_with'] ) ) ): '' ;
			$jwtlogin['mo_jwt_redirection'] = isset( $_POST['mo_jwt_redirection'] ) ? sanitize_text_field(stripslashes( wp_unslash( $_POST['mo_jwt_redirection'] ) ) ): '' ;
			$jwtlogin['mo_jwt_get_token_from_url'] = isset( $_POST['mo_jwt_get_token_from_url'] ) ? sanitize_text_field(stripslashes( wp_unslash( $_POST['mo_jwt_get_token_from_url'] ) ) ): '' ;
			$method_config['jwtlogin'] = $jwtlogin;
			$jwtlogin['mo_jwt_token_validation_method'] = 'signing_key';
			$jwtcreate['mo_jwt_sign_algo'] = 'HS256';
			$jwtcreate['mo_jwt_secret'] = isset( $_POST['mo_jwt_secret'] ) ? sanitize_text_field(stripslashes( wp_unslash( $_POST['mo_jwt_secret'] ) ) ): '' ;
			$jwtcreate['mo_jwt_dec_secret'] = isset( $_POST['mo_jwt_secret'] ) ? sanitize_text_field(stripslashes( wp_unslash( $_POST['mo_jwt_secret'] ) ) ): '' ;
			$jwtcreate['mo_jwt_token_expiry'] = 60;
			$method_config['jwtcreate'] = $jwtcreate;
			$jwtregister['mo_jwt_register_role'] = 'subscriber' ;
			$jwtregister['mo_jwt_allow_role'] = '0' ;
			$method_config['jwtregister'] = $jwtregister;

			$method_config['jwtdelete'] = $jwtdelete;

			$mj_util->update_plugin_config($method_config, 'mo_jwt_config_settings');

			$mj_util->mo_jwt_update_option( \MoJWTConstants::PANEL_MESSAGE_OPTION, 'Settings are saved successfully.' );
			$mj_util->mo_jwt_show_success_message();
		}
	}

	
}
