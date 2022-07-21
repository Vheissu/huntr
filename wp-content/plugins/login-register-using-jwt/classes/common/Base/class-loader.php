<?php
/**
 * Core
 *
 * JWT Loader.
 *
 * @category   Common, Core, UI
 * @package    MoJWT
 * @author     miniOrange <info@miniorange.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link       https://miniorange.com
 */

namespace MoJWT\Base;

use MoJWT\Base\InstanceHelper;
use MoJWT\MJUtils;
/**
 * Class to save Load and Render REST API UI
 *
 * @category Common, Core
 * @package  MoJWT\Standard
 * @author   miniOrange <info@miniorange.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link     https://miniorange.com
 */
class Loader {

	/**
	 * Instance Helper
	 *
	 * @var \MoJWT\Base\InstanceHelper $instance_helper
	 * */
	private $instance_helper;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_settings_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_settings_script' ) );
		
		$this->instance_helper = new InstanceHelper();
	}

	/**
	 * Function to enqueue CSS
	 */
	public function plugin_settings_style() {
		wp_enqueue_style( 'mo_jwt_admin_settings_style', MJ_URL . 'resources/css/style_settings.css', array(), $ver = null, $in_footer = false );
		wp_enqueue_style( 'mo_jwt_admin_settings_phone_style', MJ_URL . 'resources/css/phone.css', array(), $ver    = null, $in_footer = false );
		wp_enqueue_style( 'mo_jwt_admin_settings_datatable', MJ_URL . 'resources/css/jquery.dataTables.min.css', array(), $ver = null, $in_footer = false );
		wp_enqueue_style( 'mo-wp-font-awesome', MJ_URL . 'resources/css/font-awesome.min.css?version=4.8', array(), $ver = null, $in_footer = false );
		
		if(isset($_GET['tab']) && sanitize_text_field($_GET['tab']) == 'license'){
			wp_enqueue_style( 'mo-jwt_license', MJ_URL . 'resources/css/bootstrap/bootstrap.css', array(), $ver = null, $in_footer = false );
			}
		}

	/**
	 * Function to enqueue JS
	 */
	public function plugin_settings_script() {
		wp_enqueue_script( 'mo_jwt_admin_settings_script', MJ_URL . 'resources/js/settings.js', array(), $ver = null, $in_footer = false );
		wp_enqueue_script( 'mo_jwt_admin_settings_phone_script', MJ_URL . 'resources/js/phone.js', array(), $ver = null, $in_footer = false );
		wp_enqueue_script( 'mo_jwt_admin_settings_datatable', MJ_URL . 'resources/js/jquery.dataTables.min.js', array(), $ver = null, $in_footer = false );
	}

	/**
	 * Function to load appropriate view
	 *
	 * @param string $currenttab Tab to load and render view for.
	 *
	 * @return void
	 */
	public function load_current_tab( $currenttab ) {
		global $mj_util;
	
		if( 'account' === $currenttab) {
			$accounts = $this->instance_helper->get_accounts_instance();
			if ( $mj_util->mo_jwt_get_option( 'mo_jwt_verify_customer' ) === 'true' ) {
				$accounts->verify_password_ui();
			} elseif ( trim( $mj_util->mo_jwt_get_option( 'mo_jwt_admin_email' ) ) !== '' && trim( $mj_util->mo_jwt_get_option( 'mo_jwt_admin_api_key' ) ) === '' && $mj_util->mo_jwt_get_option( 'mo_jwt_new_registration' ) !== 'true' ) {
				$accounts->verify_password_ui();
			} else {
				$accounts->register();
			} 
		} elseif( 'config' === $currenttab || '' === $currenttab ) {
			$this->instance_helper->get_config_instance()->render_ui();
		} 
		elseif('license' === $currenttab){
			$licensing = $this->instance_helper->get_licensing_instance();
			$licensing->show_licensing_page();
		}
	}
	
}
