<?php
/**
 * App
 *
 * JWT Flow Handler.
 *
 * @category   Core
 * @package    MoJWT
 * @author     miniOrange <info@miniorange.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link       https://miniorange.com
 */

namespace MoJWT;

use MoJWT\Base\InstanceHelper;
use MoJWT\Methods\JWTLogin;
use MoJWT\Methods\JWTCreate;
use MoJWT\Methods\JWTRegister;
use MoJWT\Methods\JWTDelete;

/**
 * App
 *
 * JWT Login Handler.
 *
 * @category   Core
 * @package    MoJWT
 * @author     miniOrange <info@miniorange.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link       https://miniorange.com
 */
class JWTFlowHandler {


	/**
	 * Constructor
	 */
	public function __construct() {

        global $mj_util;
        add_action( 'init', array( $this, 'mo_jwt_initalize_flow'), 10, 1);
        add_action( 'rest_api_init', array( $this, 'mo_jwt_initialize_rest_flow' ), 10, 1 );
    }
   
    /**
     * function to fetch JWT from URL
     */
    public function mo_jwt_initalize_flow() {
        if(!is_user_logged_in()){
            if(isset($_GET['mo_jwt_token'])){
                $mo_jwt_token = sanitize_text_field($_GET['mo_jwt_token']); 
                $this->mo_jwt_login_exc($mo_jwt_token);
            }
            elseif(isset($_COOKIE['mo_jwt_token'])){
                $mo_jwt_token = sanitize_text_field($_COOKIE['mo_jwt_token']); 
                $this->mo_jwt_login_exc($mo_jwt_token);
            }
        }
    }

    public function mo_jwt_login_exc($token){
        $mo_jwt_login = new JWTLogin($token);
        $mo_jwt_login->perform_jwt_login($token);
    }

	/**
	 * REST API Flow initiate for JWT Flow
	 */
	public function mo_jwt_initialize_rest_flow() {

        global $mj_util;    

        // Handle Token Response 
        if(strpos( sanitize_text_field($_SERVER['REQUEST_URI']), '/api/v1/mo-jwt-register' ) !== false){
            $json = file_get_contents('php://input');

            $json = json_decode( $json, true );

            if( json_last_error() !== JSON_ERROR_NONE ) {
                $json = $_POST;
            }
            $mo_jwt_register = new JWTRegister();
            $response = $mo_jwt_register->create_user_with_jwt($json);
            
            $mj_util->send_json_response( $response );
        }
        elseif( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), '/api/v1/mo-jwt-delete' ) !== false ){
            $json = file_get_contents('php://input');

            $json = json_decode( $json, true );

            if( json_last_error() !== JSON_ERROR_NONE ) {
                $json = $_POST;
            }
            $mo_jwt_register = new JWTDelete();
            $response = $mo_jwt_register->delete_user_with_jwt($json);
            
            $mj_util->send_json_response( $response );
        }
        elseif( strpos( sanitize_text_field($_SERVER['REQUEST_URI']), '/api/v1/mo-jwt' ) !== false ) {
            $json = file_get_contents('php://input');

            $json = json_decode( $json, true );

            if( json_last_error() !== JSON_ERROR_NONE ) {
                $json = $_POST;
            }
            $mo_jwt_create = new JWTCreate();
            $response = $mo_jwt_create->create_jwt_response($json);

            $mj_util->send_json_response( $response );
           
        }

    }

}
