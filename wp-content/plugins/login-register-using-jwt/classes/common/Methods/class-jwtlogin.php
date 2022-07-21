<?php
/**
 * Settings Controller
 *
 * JWT Login Settings controller
 *
 * @category   JWT Login
 * @package    MoJWT\Method
 * @author     miniOrange <info@miniorange.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link       https://miniorange.com
 */

namespace MoJWT\Methods;

use MoJWT\JWTUtils;
use MoJWT\JWTHandler;
/**
 * Class to API Key Settings Controller.
 *
 * @category Api Key Auth
 * @package  MoJWT\Method\ApiKeyAuth
 * @author   miniOrange <info@miniorange.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link     https://miniorange.com
 */
class JWTLogin {

	/**
	 * Method Slug
	 *
	 * */
	private $method_slug;

	private $jwt_token;

	private $jwt_allow_login_with;

	private $jwt_redirection;

	private $method_config;

	private $current_config;

	private $client_secret;

    /**
	 * Constructor
	 *
	 * @return void
	 **/
	public function __construct($token=false) {

		global $mj_util;

		$this->method_slug = 'jwtlogin';
		$this->current_config = $mj_util->get_plugin_config('mo_jwt_config_settings') ? ( $mj_util->get_plugin_config('mo_jwt_config_settings') ) : false;
        $this->method_config =  $this->current_config ? ( $this->current_config[$this->method_slug]) : false;
        $this->jwt_allow_login_with = 'username';
        $this->jwt_redirection = $this->method_config ? $this->method_config['mo_jwt_redirection'] : false;
        $this->jwt_token = $token ? $token : false;
        $this->jwt_algo = $this->current_config ? ( $this->current_config['jwtcreate']['mo_jwt_sign_algo']) : false;
        $this->client_secret = $mj_util->mo_jwt_get_option('mo_jwt_client_secret') ? $mj_util->mo_jwt_get_option('mo_jwt_client_secret') : false;
        $this->token_validation_method = $this->method_config && isset($this->method_config['mo_jwt_token_validation_method']) ? $this->method_config['mo_jwt_token_validation_method'] : false;
        $this->get_jwt_from_url = $this->method_config && isset($this->method_config['mo_jwt_get_token_from_url']) ? $this->method_config['mo_jwt_get_token_from_url'] : false;
        $this->get_jwt_from_cookie = $this->method_config && isset($this->method_config['mo_jwt_get_token_from_cookie']) ? $this->method_config['mo_jwt_get_token_from_cookie'] : false;
        $this->get_jwt_attr =  $mj_util->get_plugin_config('mo_jwt_attr_settings') ? ( $mj_util->get_plugin_config('mo_jwt_attr_settings') ) : false;
	}	

	public function perform_jwt_login($jwt_token){
		
		global $mj_util;

		if($this->get_jwt_from_url == 'off'){
			return;
		}

		$jwt = '';

		$this->jwt_token = $jwt_token;

		if($this->token_validation_method != 'oauth_oidc'){
			$token = \explode( '.', $this->jwt_token );

			if( count($token) != 3 ){
				$mj_util->send_error_response_on_url('invalid_jwt');
			}
			$jwt = new JWTUtils( $this->jwt_token );
			
			$jwt_algo = $this->jwt_algo;
			$jwt_secret = $this->client_secret;

		}
		$response = false;
		$user = false;

		if( $mj_util->check_versi( 1 ) ){
			$jwt = new JWTUtils( $this->jwt_token );
			$instance_helper = new \MoJWT\Base\InstanceHelper();
			$login_handler_instance = $instance_helper->jwt_login_handler();
			$login_handler_instance->perform_jwt_login($jwt);
		}

		if($this->get_jwt_from_url){
			
			if($this->get_jwt_from_cookie!='on'){

				$jwt = new JWTUtils( $this->jwt_token );	
					
				$jwt_algo = $this->jwt_algo;
				$jwt_secret = $this->client_secret;
				
				if( $jwt->check_algo( $jwt_algo ) ) {
					if( $jwt->verify( $jwt_secret ) ) {

						$jwt_claims = $jwt->get_decoded_payload();
							
						if($this->get_jwt_attr){	

							$user_attr = $this->get_jwt_attr['default']['username'];

							if(array_key_exists($user_attr, $jwt_claims)){
								$user = get_user_by( 'login', $jwt_claims[$user_attr] );

								if(!$user){
									get_user_by( 'email', $jwt_claims[$user_attr] );
								}

								$userid = '';

						        if(!$user){
						            $password = $mj_util->gen_rand_str(10);
						            $userid = wp_create_user($jwt_claims[$user_attr], $password);
						            
						            if(is_wp_error($userid)){
						                wp_die('Error logging you in. Please try again or contact to your administrator.');exit();
						            }
						        }

						        if($userid == ''){
						            $userid = $user->ID;
						        }
						        wp_set_current_user($userid);
						        wp_set_auth_cookie($userid, false);

						        if($this->jwt_redirection == 'home_redirect'){
						        	wp_safe_redirect( home_url() );
						        	exit();
						        }
							}
							else{
								wp_die('Please Configure the Attribute Mapping.');exit();
							}
						}else{
							wp_die('Please Configure the Attribute Mapping.');exit();	
						}
					} else {
		                $mj_util->send_error_response_on_url('JWT Signature is invalid');
					}
				} else {
					$mj_util->send_error_response_on_url('Incorrect JWT Format');
				}
				return $response;
			}
		}
	}
	
}