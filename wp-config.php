<?php
if(file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define( 'AS3CF_SETTINGS', serialize( array(
  'provider' => 'aws',
  'access-key-id' => '********************',
  'secret-access-key' => '**************************************',
) ) );

define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOSTNAME'));

define( 'DB_CHARSET',       'utf8' );
define( 'DB_COLLATE',       '' );

// Generate your own here: https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'J5F+?!JeAB/26`#,0?+u[)23z}NV?&)wT6)[o+^] }u6NX+z]_XvEZ.$pZ9M-+>2');
define('SECURE_AUTH_KEY',  'Q_rntT0ePGnQ_h*^8^jXJ$l~&Co0VFLh_nzqE2vD IO4-cE0NA9{H-BvDOvG{N|,');
define('LOGGED_IN_KEY',    ' Hq}Lp,,GJ->HBi]mE>_foIpO13>Ln@*L~-+7$2YWk=Y[{^J4{/][Y0m?.^}#:cz');
define('NONCE_KEY',        'k#$BzeYNqqZ/=wz6|A0LMyOUx!+BmaC*-hu>G`{@AHYT33I{HI0j u{r6X +D;eB');
define('AUTH_SALT',        'ph1OK$y?)[/Le322|AOHR qviHW*Y4<3#D6BUYBDU]DWQtl[0=e(yFBKh})!<;=*');
define('SECURE_AUTH_SALT', '7||oWhdZM7Ek+V<`*@mG+60zBw3mL1l86_h;Z<kR1,ey2*{MI*#f+6(JhB1|.=I8');
define('LOGGED_IN_SALT',   'Oa8nK+%cB9u1:(XOt[7Pl-y6_IE#V_xR&e-=3a5Q|n:fHcE4Z&jE#1^60OSktd[h');
define('NONCE_SALT',       '.;z,p2#`:!d$Oe:zv,)[R_+NyMv.*RNn1G[-(oSduZ&c~aV9sCw)i{|,5)myePB9');

$table_prefix =             "wp_";

define('FS_METHOD', "direct");
define('WP_AUTO_UPDATE_CORE', "minor");

define( 'WP_CONTENT_DIR',   dirname(__FILE__) . '/wp-content');
define( 'WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST'] . '/');
define( 'WP_HOME', 'https://' . $_SERVER['HTTP_HOST'] . '/');

if ( !defined('WP_ENVIRONMENT_TYPE') ) {
    define('WP_ENVIRONMENT_TYPE', $_SERVER['WP_ENVIRONMENT_TYPE']);
}

if (!isset($_SERVER['REQUEST_METHOD']) || WP_ENVIRONMENT_TYPE == 'development' || WP_ENVIRONMENT_TYPE == 'local') :
    define('DISPLAY_ERRORS', TRUE);
    define('WP_DEBUG', TRUE);
    define('WP_DEBUG_DISPLAY', TRUE);
    define('SAVEQUERIES', TRUE);
    define('SCRIPT_DEBUG', TRUE);
  
  elseif (WP_ENVIRONMENT_TYPE == 'staging') :
    define('DISPLAY_ERRORS', FALSE);
    define('WP_DEBUG', FALSE);
    define('WP_DEBUG_DISPLAY', FALSE);
    define('SCRIPT_DEBUG', FALSE);
    define('DISALLOW_FILE_MODS', TRUE);
  
  elseif (WP_ENVIRONMENT_TYPE == 'production') :
    define('DISPLAY_ERRORS', TRUE);
    define('WP_DEBUG', TRUE);
    define('WP_DEBUG_DISPLAY', TRUE);
    define('SCRIPT_DEBUG', TRUE);
    define('DISALLOW_FILE_MODS', FALSE);
  endif;

  /* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );