<?php
/**
 * Core
 *
 * Login with JWT Method view Handler.
 *
 * @category   Common, Core
 * @package    MoJWT\MethodViewHandler
 * @author     miniOrange <info@miniorange.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link       https://miniorange.com
 */

namespace MoJWT\MethodViewHandler;

/**
 * Class to JWT Login Method View Handler.
 *
 * @category Common, Core
 * @package  MoJWT\MethodViewHandler\LoginJWTView
 * @author   miniOrange <info@miniorange.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link     https://miniorange.com
 */
class LoginJWTView {

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
     * Is Selected 
     *
     * */
    private $is_selected;


    /**
    * Get Config
    *
    * */
    private $current_config;

    /**
     * Constructor
     */
    public function __construct( $method_config = false, $selected_method = false ) {
        global $mj_util;
        $this->method_name   = 'Login User with JWT';
        $this->image_name    = 'login-user.png'; 
        $this->method_slug   = 'jwtlogin';
        $this->priority      = 4;
        $this->current_config = $mj_util->get_plugin_config('mo_jwt_config_settings') ? ( $mj_util->get_plugin_config('mo_jwt_config_settings') ) : false;
        $this->method_config =  ($this->current_config && $this->current_config[$this->method_slug]) ? ( $this->current_config[$this->method_slug]) : false;
        $this->inbuilt_secret = ($this->current_config && $this->current_config['jwtcreate']) ? ( $this->current_config['jwtcreate']['mo_jwt_dec_secret']) : false;
        $this->jwt_redirection = $this->method_config ? $this->method_config['mo_jwt_redirection'] : false;
        $this->is_selected  = $selected_method ? ( $selected_method === $this->method_slug ? true : false ) : false;
        $this->get_jwt_from_url = $this->method_config && isset($this->method_config['mo_jwt_get_token_from_url']) ? $this->method_config['mo_jwt_get_token_from_url'] : false;
        $this->get_jwt_from_cookie = $this->method_config && isset($this->method_config['mo_jwt_get_token_from_cookie']) ? $this->method_config['mo_jwt_get_token_from_cookie'] : false;
        $this->token_validation_method = $this->method_config && isset($this->method_config['mo_jwt_token_validation_method']) ? $this->method_config['mo_jwt_token_validation_method'] : false;
        $this->introspection_endpoint = $this->method_config && isset($this->method_config['introspection_endpoint']) ? $this->method_config['introspection_endpoint'] : false;
        $this->client_id = $this->method_config && isset($this->method_config['oauth_client_id']) ? $this->method_config['oauth_client_id'] : false;
        $this->client_secret = $this->method_config && isset($this->method_config['oauth_client_secret']) ? $this->method_config['oauth_client_secret'] : false;
        $this->jwks_endpoint = $this->method_config && isset($this->method_config['jwks_endpoint']) ? $this->method_config['jwks_endpoint'] : false;
        $this->jwt_sign_algo = $this->method_config && isset($this->method_config['jwt_sign_algo']) ? $this->method_config['jwt_sign_algo'] : false;
        $this->jwt_dec_secret = $this->method_config && isset($this->method_config['jwt_sign_key']) ? $this->method_config['jwt_sign_key'] : false;
        $this->attr_config = $mj_util->get_plugin_config('mo_jwt_attr_settings') ? ( $mj_util->get_plugin_config('mo_jwt_attr_settings') ) : false;
        $this->attr_uname = $this->attr_config && isset($this->attr_config['default']['username']) ? $this->attr_config['default']['username'] : false;
        $this->attr_email = $this->attr_config && isset($this->attr_config['default']['email']) ? $this->attr_config['default']['email'] : false;
        $this->attr_fname = $this->attr_config && isset($this->attr_config['default']['fname']) ? $this->attr_config['default']['fname'] : false;
        $this->attr_lname = $this->attr_config && isset($this->attr_config['default']['lname']) ? $this->attr_config['default']['lname'] : false;
        $this->attr_dname = $this->attr_config && isset($this->attr_config['default']['dname']) ? $this->attr_config['default']['dname'] : false;
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

    public function get_allow_jwt_login_action()
    {
        return $this->jwt_allow_login;
    }

    public function get_jwt_redirection_action()
    {
        return $this->jwt_redirection;
    }

    /**
     * Config UI
     */
    public function load_config_view()
    {   
        ?>
        <div style="padding-left: 20px;<?php if( ! $this->is_selected ) echo "display:none;"?>" id="<?php echo $this->method_slug . "_div"; ?>" >
            
            <br>
            <div class="mo_jwt_method_note">This feature will help you to auto login (Single Sign On) your users in WordPress using the user based JWT token either created from the plugin or obtained from external identities like OAuth 2.0/OpenID Connect providers, Firebase etc. Click on <b><i>Save Settings</i></b> to know more.</div>

            <h3>Get JWT token from: </h3>
            <small>(This setting will allow the plugin to identify from the JWT token needs to be fetched)</small><br><br>
            <table width="60%">
                <tr>
                    <td>
                        <b>Request URL Parameter:</b> 
                    </td>
                    <td><select name="mo_jwt_get_token_from_url" style="min-width:80px">
                            <option value="on" <?php if( "on" == $this->get_jwt_from_url ) echo "selected"; ?> selected>on</option>
                            <option value="off" <?php if( "off" == $this->get_jwt_from_url ) echo "selected"; ?>  >off</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Cookie: <small><?php echo $this->jwt_label ?></small></b> 
                    </td>
                    <td><select name="mo_jwt_get_token_from_cookie" style="min-width:80px" <?php if(!$this->jwt_versi ){echo 'disabled';} ?> >
                            <option value="off" <?php if( "off" == $this->get_jwt_from_cookie ) echo "selected"; ?> selected>off</option>
                            <option value="on"  <?php if( "on" == $this->get_jwt_from_cookie ) echo "selected"; ?> >on</option>
                    </select>
                    </td>
                </tr>
            </table>
            <br>
            <h3>User Redirection after Auto-login:</h3>
            <table width="120%">
                <tr>
                    <td colspan="2">
                        <input type="radio" name="mo_jwt_redirection" value="home_redirect" <?php echo "checked"; ?> > Homepage 
                    </td>
                    <td colspan="2">
                        <input type="radio" name="mo_jwt_redirection" value="no_redirect" <?php if ( 'no_redirect' === $this->jwt_redirection ) echo "checked"; ?> > No Redirect (Users will be redirecting on same page from where auto-login is initiated.)
                    </td>
                </tr>
            </table>
            <br>
            <h3>JWT token validation Method:</h3>    
            <table width="120%">

                <tr>
                    <td colspan="2">
                        <input type="radio" name="mo_jwt_login_validation" value="signing_key" <?php if('signing_key' === $this->token_validation_method) echo "checked"; ?> onclick="MoJWTLoadThirdPartyMethod('SHA', 'SHA,OAuth,JWKS')" checked> Signing Key/Certificate Validation
                    </td>
                    <td colspan="2">
                        <input type="radio" name="mo_jwt_login_validation" value="jwks" <?php if ( 'jwks' === $this->token_validation_method ) echo "checked"; ?> onclick="MoJWTLoadThirdPartyMethod('JWKS', 'SHA,OAuth,JWKS')" <?php if(!$this->jwt_versi){echo 'disabled';} ?> > JWKS Validation <small><?php echo $this->jwt_label; ?></small>
                    </td>
                    <td colspan="2">
                        <input type="radio" name="mo_jwt_login_validation" value="oauth_oidc" <?php if ( 'oauth_oidc' === $this->token_validation_method ) echo "checked"; ?> onclick="MoJWTLoadThirdPartyMethod('OAuth', 'SHA,OAuth,JWKS')" <?php if(!$this->jwt_versi){echo 'disabled';} ?> > OAuth/OIDC Validation <small><?php echo $this->jwt_label; ?></small>
                    </td>
                </tr>
            </table>
            <br/>
            <section id="SHA_div" style="<?php if( ! ( 'signing_key' === $this->token_validation_method || false === $this->token_validation_method ) )echo "display:none;" ?>">
                <p><table width="120%">
                <tr>
                    <td>
                        <b>Signing Algorithm :</b> 
                        <br><small>Select the algorithm using which you want to sign the JWT</small>
                    </td>
                    <td style="padding-left: 5px;">
                        <select name="mo_jwt_login_sign_algo" style="min-width:100px">
                            <option value="HS256" <?php if( "HS256" === $this->jwt_sign_algo ) echo "selected"; ?> selected>HS256</option>
                            <option value="RS256" <?php if( "RS256" === $this->jwt_sign_algo ) echo "selected"; ?> <?php if(!$this->jwt_versi){echo 'disabled';} ?>>RS256</option>
                        </select>
                    </td>
                </tr>
            </table></p>
            <br>
            <table width="120%">
                <tr>
                    <td>
                        <b>Decryption key/certificate : <small><?php echo $this->jwt_label ?></small></b> 
                        <br><small>Enter the key/certificate to Decrypt the JWT</small>
                    </td>
                    <td>
                        <td><textarea type="textbox" placeholder="Configure your certificate or secret key" name="mo_jwt_login_dec_secret" style="width:350px;" required <?php if(!$this->jwt_versi){echo 'disabled';} ?>><?php if(!$this->jwt_versi){echo $this->inbuilt_secret;}elseif ( $this->jwt_dec_secret ){echo $this->jwt_dec_secret;} ?> </textarea>
                        </td>
                    </td>
                </tr>
            </table>

            </section>
        </br>
            <section id="OAuth_div" style="<?php if( ! ( 'oauth_oidc' === $this->token_validation_method ) ) echo "display:none;" ?>">
                <p><b>OAuth 2.0 Introspection/Userinfo Endpoint : </b>&nbsp;<br/>
                <small>This endpoint is used to query Third Party OAuth provider to identify if the OAuth token exists and is valid</small><br/>
                <input type="textbox" placeholder="OAuth Introspection endpoint" name="introspection_endpoint" style="width:80%;padding:3px" value="<?php if( $this->introspection_endpoint ) echo $this->introspection_endpoint; ?>" /></p>

                <p><b>OAuth 2.0 Client ID : </b>&nbsp;<br/>
                <input type="textbox" placeholder="OAuth Client ID" name="client_id" style="width:80%;padding:3px" value="<?php if( $this->client_id ) echo $this->client_id; ?>" /></p>

                <p><b>OAuth 2.0 Client Secret : </b>&nbsp;<br/>
                <input type="textbox" placeholder="OAuth Client Secret" name="client_secret" style="width:80%;padding:3px" value="<?php if( $this->client_secret ) echo $this->client_secret; ?>" /></p>
            </section>

            <section id="JWKS_div" style="<?php if( ! ( 'jwks' === $this->token_validation_method ) ) echo "display:none;" ?>">
                <p><b>JWKS URL : </b>&nbsp;<br/>
                <small>This endpoint is used to create the public keys for the JWT token and validate the signature</small><br/>
                <input type="textbox" placeholder="JWKS endpoint" name="jwks_endpoint" style="width:80%;padding:3px" value="<?php if( $this->jwks_endpoint ) echo $this->jwks_endpoint; ?>" /></p>
            </section>

        </div>

        <script>
           function MoJWTLoadThirdPartyMethod( section, allsectionid ){
                MoRESTAPIhideVisibility( allsectionid );
                document.getElementById( section + "_div" ).style.display = "block";
            }
        </script>


        <?php
    }

    public function load_doc_view()
    {
        ?>
        <div class="mo_support_layout">
            <div id="mo_jwt_login_mapping">
            <div>
                <form name="mo_jwt_mapping_form" method="post" action="">
                <?php wp_nonce_field( 'mo_jwt_mapping_section', 'mo_jwt_mapping_section_nonce' ); ?>
                <input type="hidden" name="option" value="mo_jwt_mapping_section">
                <h3>Attribute Mapping Settings: <small><?php echo $this->jwt_label; ?></small></h3>
                
                <table class="mo_settings_table">
                    <tr>
                        <td style="vertical-align:top"><h4 class="mo_jwt_attr_h4" style="margin-top:10px;">Username : <span style="color: red;">(Required)</span></h4></td>
                        <td style="vertical-align:top"><h4 class="mo_jwt_attr_h4" style="margin-top:10px;"><input type="text" name="mo_jwt_username_attr" value="<?php echo ($this->attr_uname); ?>" ></h4></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top"><h4 class="mo_jwt_attr_h4">Email : <small><?php echo $this->jwt_label; ?></small></h4></td>
                        <td style="vertical-align:top"><h4 class="mo_jwt_attr_h4"><input type="text" name="mo_jwt_email_attr" value="<?php echo ($this->attr_email); ?>" <?php if(!$this->jwt_versi){echo "disabled";} ?>></h4></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top"><h4 class="mo_jwt_attr_h4" >FirstName : <small><?php echo $this->jwt_label; ?></small></h4></td>
                        <td style="vertical-align:top"><h4 class="mo_jwt_attr_h4" ><input type="text" name="mo_jwt_fname_attr" value="<?php echo ($this->attr_fname); ?>" <?php if(!$this->jwt_versi){echo "disabled";} ?>></h4></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top"><h4 class="mo_jwt_attr_h4" >LastName : <small><?php echo $this->jwt_label; ?></small></h4></td>
                        <td style="vertical-align:top"><h4 class="mo_jwt_attr_h4" ><input type="text" name="mo_jwt_lname_attr" value="<?php echo ($this->attr_lname); ?>" <?php if(!$this->jwt_versi){echo "disabled";} ?>></h4></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top"><h4 class="mo_jwt_attr_h4" >DisplayName : <small><?php echo $this->jwt_label; ?></small></h4></td>
                        <td style="vertical-align:top"><h4 class="mo_jwt_attr_h4" ><input type="text" name="mo_jwt_dname_attr" value="<?php echo ($this->attr_dname); ?>" <?php if(!$this->jwt_versi){echo "disabled";} ?>></h4></td>
                    </tr>
                    <tr>
                        <td>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button class="button-primary" style="margin-left: 140%;width: 40%;">Save Settings</button>
                        </td>
                        <br>
                    </tr>
                </table>
                </form>
                <br>
                </div>

        </div>
    </div>

    <br>
    <div class="mo_support_layout">
        <div id="mo_jwt_support_loginjwt" class="mo_jwt_common_div_css">
            <h3 class="mo-jwt-doc-heading">Sample Example to perform auto login using JWT as URL parameter -</h3>
            <div class="mo-jwt-code" style="min-height: 110px;">
                <p>Append the argument <strong style="color: #084ebf">mo_jwt_token=&lt;user-jwt-token&gt;</strong>in any URL of WordPress site from where you want users to get auto logged in.</p>
                <br>
            <table width="30%" class="mo_jwt_settings_table">
                <tr class="mo-jwt-table-desc">
                    <td>
                        Example Request - &nbsp;&nbsp;<?php echo get_home_url(); ?>?mo_jwt_token=&lt;user-jwt-token&gt;
                    </td>
                </tr>
            </table>
        </div>
        </div>
        <div id="mo_jwt_support_loginjwt" class="mo_jwt_common_div_css">
            <h3 class="mo-jwt-doc-heading">Sample Example to perform auto login using JWT as Cookie <small><?php echo $this->jwt_label; ?></small>-</h3>
            <div class="mo-jwt-code" style="min-height: 110px;">
                <p>Set the cookie with name <strong style="color: #084ebf">mo_jwt_token and value=&lt;user-jwt-token&gt;</strong> in your root domain for the user to get auto logged in on site access.</p>
                <br>
            <table width="100%" class="mo_jwt_settings_table">
                <tr class="mo-jwt-table-desc">
                    <td>
                       Sample Code in PHP - setcookie('mo_jwt_token', <'jwt-token'>, time() + 60, $path='/');
                    </td>
                </tr>
                
            </table>
        </div>
        </div>
        <br>
    	</div>
        <br>
        <?php
    }

}