<?php
/**
 * Core
 *
 * Create JWT Method view Handler.
 *
 * @category   Common, Core
 * @package    MoJWT\MethodViewHandler
 * @author     miniOrange <info@miniorange.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link       https://miniorange.com
 */

namespace MoJWT\MethodViewHandler;

/**
 * Class to Create JWT Method View Handler.
 *
 * @category Common, Core
 * @package  MoJWT\MethodViewHandler\CreateJWTView
 * @author   miniOrange <info@miniorange.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link     https://miniorange.com
 */
class CreateJWTView {

    /**
	 * Method Name
	 *
	 * */
    private $method_name;
    
    /**
	 * Method Name
	 *
	 * */
    private $image_name;
    
    /**
	 * Method Slug
	 *
	 * */
    private $method_slug;
    
    /**
	 * Method priority to view 
	 *
	 * */
    private $priority;
    
    /**
	 * Method Configuration
	 *
	 * */
    private $method_config;
    
    
    /**
     * Constructor
     */
    public function __construct( $method_config = false, $selected_method = false ) {
        global $mj_util;
        $this->method_name   = 'Create JWT';
        $this->image_name    = 'jwt-token.png'; 
        $this->method_slug   = 'jwtcreate';
        $this->priority      = 1;
        $this->current_config = $mj_util->get_plugin_config('mo_jwt_config_settings') ? ( $mj_util->get_plugin_config('mo_jwt_config_settings') ) : false;
        $this->method_config =  $this->current_config ? ( $this->current_config[$this->method_slug]) : false;
        $this->is_selected  = $selected_method ? ( $selected_method === $this->method_slug ? true : false ) : false;
        $this->jwt_sign_algo = $this->method_config ? $this->method_config['mo_jwt_sign_algo'] : false;
        $this->jwt_secret = $this->method_config ? $this->method_config['mo_jwt_secret'] : false;
        $this->jwt_dec_secret = $this->method_config ? $this->method_config['mo_jwt_dec_secret'] : false;
        $this->token_expiry = $this->method_config ? $this->method_config['mo_jwt_token_expiry'] : 60;
        $this->jwt_versi = $mj_util->get_versi();
        $this->jwt_label = $mj_util->get_label();
    }

    public function get_priority(){
        return $this->priority;
    }

    public function get_method_name()
    {
        return $this->method_name;
    }

    public function get_image_name()
    {
        return $this->image_name;
    }

    public function get_method_slug()
    {
        return $this->method_slug;
    }

    /**
     * Config UI
     */
    
    public function load_config_view()
    {   
        global $mj_util;
        ?>
        <div style="<?php if( ! $this->is_selected ) echo "display:none;" ?>" id="<?php echo $this->method_slug . "_div"; ?>" >
            <br>
            <div class="mo_jwt_method_note">This feature will help you to create the JWT token based on WordPress user credentials. This feature also helps you authenticate your users on other app trying to login using WordPress credentials. Click on <b><i>Save Settings</i></b> to know more.</div>

            <h3>JWT Security Settings</h3>
            
            <table width="120%">
                <tr>
                    <td>
                        <b>Signing Algorithm :</b> 
                        <br><small>Select the algorithm using which you want to sign the JWT</small>
                    </td>
                    <td style="padding-left: 5px;">
                        <select name="mo_jwt_sign_algo" style="min-width:200px">
                            <option value="HS256" <?php if( "HS256" === $this->jwt_sign_algo ) echo "selected"; ?> selected>HS256</option>
                            <option value="RS256" <?php if( "RS256" === $this->jwt_sign_algo ) echo "selected"; if(!$this->jwt_versi){echo 'disabled';} ?> >RS256&nbsp;&nbsp;<?php echo $this->jwt_label;?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <br>
            <table width="120%">
                <tr>
                    <td>
                        <b>Signing key/certificate : <small><?php echo $this->jwt_label ?></small></b> 
                        <br><small>Enter the signing key/certificate to sign the JWT</small>
                    </td>
                    <td>
                        <td><textarea type="textbox" placeholder="Configure your certificate or secret key" name="mo_jwt_secret" style="width:350px;" required <?php if(!$this->jwt_versi){echo 'readonly';} ?> ><?php if ( $this->jwt_secret ){echo $this->jwt_secret;}else{$this->jwt_secret = $mj_util->gen_rand_str(32); echo $this->jwt_secret;} ?></textarea>
                        </td>
                    </td>
                </tr>
            </table>
            <br>
            <table width="120%">
                <tr>
                    <td>
                        <b>Decryption key/certificate : <small><?php echo $this->jwt_label ?></small></b> 
                        <br><small>Enter the key/certificate to Decrypt the JWT</small>
                    </td>
                    <td>
                        <td><textarea type="textbox" placeholder="Configure your certificate or secret key" name="mo_jwt_dec_secret" style="width:350px;" required <?php if(!$this->jwt_versi){echo 'readonly';} ?> ><?php if ( $this->jwt_dec_secret ){echo $this->jwt_dec_secret;}else{echo $this->jwt_secret;} ?></textarea>
                        </td>
                    </td>
                </tr>
            </table>
            <br>
            <table width="120%">
                <tr>
                        <td><b>Access Token Expiry Time (In minutes) : <small><?php echo $this->jwt_label ?></small></b></td>
                        <td style="padding-left: 5px;">
                            <input type="text" id="mo_jwt_token_expiry" placeholder="JWT Token Expiry Time (In minutes)" name="mo_jwt_token_expiry" value="<?php echo $this->token_expiry; ?>" <?php if(!$this->jwt_versi){echo 'disabled';} ?>/>
                        </td>
                    </tr>
            </table>
            <br>
              </div>
                
        </div>

        <?php }

    public function load_doc_view()
    {
        ?>
        <div class="mo_support_layout">
            <div id="mo_jwt_support_createjwt" class="mo_jwt_common_div_css">
              <table width="150%">
                <tr>
                    <td>
                       <b> <h3 class="mo-jwt-doc-heading">Create JWT using the following API endpoint: </h3></b>
                    </td>
                </tr>
            </table>
              <br>
              <div class="mo-jwt-code">
                  <button class="mo-jwt-api-button">POST</button> /wp-json/api/v1/mo-jwt
              </div>
              <br>
              <div class="mo-jwt-code" style="height: 150px;">
                
              <table width="100%">
                <tr class="mo-jwt-table-heading">
                    <td>
                        Parameter
                    </td>
                    <td>
                        Description
                    </td>
                </tr>
                <tr>
                    <td><hr style="width: 170%"></td>
                </tr>
                <tr class="mo-jwt-table-desc">
                    <td>username</td>
                    <td><i style="color: red;">(Required)</i> The WordPress username or email of the user</td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr class="mo-jwt-table-desc">
                    <td>password</td>
                    <td><i style="color: red;">(Required)</i> The WordPress password associated for the user</td>
                </tr>
            </table>
        </div>
        <br>
        <div id="mo_jwt_support_createjwt" class="mo_jwt_common_div_css">
              
            <h2 class="mo-jwt-doc-heading">Sample Example to request the user based JWT</h2>
            <br>
            <div class="mo-jwt-code" style="min-height: 100px;">
                <table width="100%" class="mo_jwt_settings_table">
                <tr class="mo-jwt-table-heading">
                    <td>
                        Request
                    </td>
                    <td>
                        Format
                    </td>
                </tr>
                <tr>
                    <td><hr style="width: 170%"></td>
                </tr>
                <tr class="mo-jwt-table-desc">
                    <td>Curl</td>
                    <td class="mo-jwt-doc-body">curl -d "username=&lt;wordpress_username&gt;&password=&lt;wordpress_password&gt;" -X POST <?php echo get_home_url();?>/wp-json/api/v1/mo-jwt</td>
                </tr>
            </table>
        </div>
        <br>
        <h2 class="mo-jwt-doc-heading">Response Codes</h2>
        <div class="mo-jwt-code" style="min-height: 170px;">
            <table width="100%" class="mo_jwt_settings_table">
                <tr class="mo-jwt-table-heading">
                    <td>
                        Code
                    </td>
                    <td>
                        Description
                    </td>
                </tr>
                <tr>
                    <td><hr style="width: 280%"></td>
                </tr>
                <tr class="mo-jwt-table-desc">
                    <td>200</td>
                    <td class="mo-jwt-doc-body"> Successful Response - JWT is created successfuly</td>
                </tr>
                <tr>
                    <td><hr style="width: 280%"></td>
                </tr>
                <tr class="mo-jwt-table-desc">
                    <td>400</td>
                    <td class="mo-jwt-doc-body"> Invalid Credentials - Invalid username or password.</td>
                </tr>
                <tr>
                    <td><hr style="width: 280%"></td>
                </tr>
                <tr class="mo-jwt-table-desc">
                    <td>403</td>
                    <td class="mo-jwt-doc-body"> Forbidden - Username and password are required.</td>
                </tr>
            </table>
        </div>
            </div>
    	</div>

        <?php
    }

}