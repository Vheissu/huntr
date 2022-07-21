<?php
/**
 * Settings Controller
 *
 * JWT Login Settings controller
 *
 * @category   JWT Register
 * @package    MoJWT\Method
 * @author     miniOrange <info@miniorange.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link       https://miniorange.com
 */

namespace MoJWT\Methods;

use MoJWT\JWTUtils;
use MoJWT\JWTHandler;
/**
 * 
 *
 * @category JWT Register 
 * @package  MoJWT\Method\JWTRegister
 * @author   miniOrange <info@miniorange.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link     https://miniorange.com
 */
class JWTRegister {

	/**
	 * Method Slug
	 *
	 * */
	private $method_slug;

	private $client_secret;

    /**
	 * Constructor
	 *
	 * @return void
	 **/
	public function __construct() {
		global $mj_util;
		$this->method_slug = 'jwtregister';
		$this->current_config = $mj_util->get_plugin_config('mo_jwt_config_settings') ? ( $mj_util->get_plugin_config('mo_jwt_config_settings') ) : false;
		$this->method_config =  $this->current_config ? ( $this->current_config['jwtcreate']) : false;
		$this->sign_algo = $this->method_config ? $this->method_config['mo_jwt_sign_algo'] : false;
		$this->client_secret = $this->method_config ? $this->method_config['mo_jwt_secret'] : false;
		$this->default_role = $this->current_config ? $this->current_config['jwtregister']['mo_jwt_register_role'] : false;
		$this->allow_role = $this->current_config ? $this->current_config['jwtregister']['mo_jwt_allow_role'] : false;
		//$this->apikey = $this->current_config ? $this->current_config['jwtregister']['apikey'] : false;
	}

	public function create_user_with_jwt ($request){

		global $mj_util;

		if( isset( $request['username'] ) /*&& isset( $request['apikey'] */)  {

			/*if(sanitize_text_field($request['apikey']) != $this->apikey){
				$response = array(
					'status' => "error",
					'error' => 'INVALID_API_KEY',
					'code'  => '401',
					'error_description' => 'Invalid API key is passed. Try again with the correct API key'
				);
				return $response;
			}*/

			$username   = sanitize_text_field( $request['username'] );
			$password   = isset($request['password']) ? sanitize_text_field( $request['password'] ) : $mj_util->gen_rand_str(10);

			$user = wp_create_user($username, $password);

			if(is_wp_error($user)){
				$response = array(
					'status' => "error",
					'error' => 'BAD_REQUEST',
					'code'  => '401',
					'error_description' => $user->get_error_message()
				);
				$mj_util->send_json_response( $response );
			}

			$allow_default_role = 0;

			if($this->allow_role){
				$user_role = isset($request['role']) ? sanitize_text_field( $request['role'] ) : false;
				if($user_role){
					$all_roles = array_keys(wp_roles()->roles);
					
					if(!array_key_exists($user_role, $all_roles)){
						$response = array(
							'status' => "error",
							'error' => 'BAD_REQUEST',
							'code'  => '401',
							'error_description' => 'The role passed in the request does not exists in the WordPress. Pass a correct role.'
						);
						$mj_util->send_json_response( $response );
					}

					$wp_user       = new \WP_User( $user);
					$wp_user->set_role($user_role);
				}else{
					$allow_default_role = 1;
				}
			}
			if($allow_default_role){
				$wp_user       = new \WP_User( $user);
				$wp_user->set_role($this->default_role);
			}

			$client_secret = $this->client_secret;
			$sign_algo = $this->sign_algo;

			if ( $client_secret === false || $client_secret === "" ) {
				$response = array(
					'status' => "error",
					'error' => 'BAD_REQUEST',
					'code'  => '401',
					'error_description' => 'Sorry, client secret is required to make a request. Contact to your administrator.'
				);
				$mj_util->send_json_response( $response );
			}

			$user = get_user_by('login', $username);

			$token_data = '';
			$jwt_user  = array(
				'sub'   => $user->ID,
				'username'  => $user->user_login,
				'email' => $user->user_email
			);
			$jwt 	   = new JWTUtils();
			$response = $jwt->create_jwt_token( $jwt_user, $client_secret, $sign_algo, 60, $client_secret );
			$response['code'] = 200;
			return $response;

		} else {
			$response = array(
				'status' => "error",
				'error' => 'FORBIDDEN',
				'code'  => '403',
				'error_description' => 'Username or API Key are required.'
			);
			$mj_util->send_json_response( $response );
		}
	}

}