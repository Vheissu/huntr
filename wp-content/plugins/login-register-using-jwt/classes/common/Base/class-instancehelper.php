<?php
/**
 * Core
 *
 * JWT Login Instance Helper.
 *
 * @category   Common, Core
 * @package    MoJWT\Base
 * @author     miniOrange <info@miniorange.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link       https://miniorange.com
 */

namespace MoJWT\Base;

/**
 * Class to Select Instance of JWT Login.
 *
 * @category Common, Core
 * @package  MoJWT\Base
 * @author   miniOrange <info@miniorange.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link     https://miniorange.com
 */
class InstanceHelper {

	/**
	 * JWT Login Current Version
	 *
	 * @var string $current_version
	 * */
	private $current_version = 'FREE';

	/**
	 * JWT Login common utils
	 *
	 * @var MoJWT\MJUtils $utils
	 * */
	private $utils;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->utils           = new \MoJWT\MJUtils();
		$this->current_version = $this->utils->get_versi_str();
	}


	/**
	 * Function to get Account Instance
	 *
	 * @return mixed
	 */
	public function get_accounts_instance(){
		return new \MoJWT\Accounts();
	}


	/**
	 * Function to get Licensing Instance
	 *
	 * @return mixed
	 */
	public function get_licensing_instance(){
		return new \MoJWT\Licensing();
	}

	/**
	 * Function to get proper All Method Config Settings
	 *
	 * @return mixed
	 */
	public function get_all_method_instances() {
		
		$all_declared_classes = get_declared_classes();
		$method_classes = array_filter($all_declared_classes, function ($var) { return (stripos($var, 'MoJWT\Methods') !== false); } );
		unset( $method_classes[ array_search( 'MoJWT\Methods', $method_classes ) ] );
		return $method_classes;
	}

	/**
	 * Function to get all registered authentication method
	 */
	public function get_registered_method_views(){
		if ( class_exists( 'MoJWT\MethodViewHandler' ) ) {
			$all_declared_classes = get_declared_classes();
			
			$method_views_classes = array_filter($all_declared_classes, function ($var) { return (stripos($var, 'MoJWT\MethodViewHandler') !== false); } );
			
			unset( $method_views_classes[ array_search( 'MoJWT\MethodViewHandler', $method_views_classes ) ] );
			
			return $method_views_classes;
		}
	}


	/**
	 * Function to get proper Settings instance.
	 *
	 * @return mixed
	 */
	public function get_settings_instance() {
		
		if( class_exists( 'MoJWT\Premium\PremiumSettings' ) ){
			return new \MoJWT\Premium\PremiumSettings();
		}
		elseif ( class_exists( 'MoJWT\Free\FreeSettings' ) ) {
			return new \MoJWT\Free\FreeSettings();
		} else {
			wp_die( 'Please Change The version back to what it really was' );
			exit();
		}
	}

	/**
	 * Function to initialze JWT flow.
	 *
	 * @return mixed
	 */
	public function jwt_flow_handler() {
		
		if ( class_exists( 'MoJWT\JWTFlowHandler' ) ) {
			return new \MoJWT\JWTFlowHandler();
		} else {
			wp_die( 'Please Change The version back to what it really was' );
			exit();
		}
	}


	public function jwt_login_handler() {
		if(class_exists('MoJWT\Premium\JWTLoginHandler')){
			return new \MoJWT\Premium\JWTLoginHandler();
		}
	}

	/**
	 * Function to get proper Settings instance.
	 *
	 * @return mixed
	 */
	public function get_config_instance() {
		if ( class_exists( 'MoJWT\MethodViewHandler' ) ) {
			return new \MoJWT\MethodViewHandler();
		}
	}

	/**
	 * Function to get proper Utils instance.
	 *
	 * @return mixed
	 */
	public function get_utils_instance() {
		return $this->utils;
	}
}

