<?php
/**
 * Plugin UI Base Structure
 *
 * JWT Login Config guides.
 *
 * @category   Core
 * @package    MoJWT
 * @author     miniOrange <info@miniorange.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link       https://miniorange.com
 */

namespace MoJWT\Base;


use MoJWT\Support;
require_once 'class-loader.php';

/**
 * Class to render Basic Structure of plugin UI.
 *
 * @category Core
 * @package  MoJWT
 * @author   miniOrange <info@miniorange.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @link     https://miniorange.com
 */
class BaseStructure {

	/**
	 * Loader instance
	 *
	 * @var MoJWT\Base\Loader
	 */
	private $loader;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		$this->loader = new Loader();
	}

	/**
	 * Functino to add Plugin to menu list.
	 */
	public function admin_menu() {
		$page = add_menu_page( 'JWT Login Settings ' . __( 'Configure JWT', 'mo_jwt_settings' ), 'miniOrange JWT Login', 'administrator', 'mo_jwt_settings', array( $this, 'menu_options' ), MJ_URL . 'resources/images/miniorange.png' );
		global $submenu;
		if ( is_array( $submenu ) && isset( $submenu['mo_jwt_settings'] ) ) {
			$submenu['mo_jwt_settings'][0][0] = __( 'Configure JWT', 'mo_jwt_settings' ); // phpcs:ignore
		}
	}

	/**
	 * Render Skeleton.
	 */
	public function menu_options() {
		global $mj_util;
		$mj_util->mo_jwt_update_option('mo_jwt_host_name', 'https://login.xecurify.com' );
		$currenttab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ): ''; // phpcs:ignore
		?>
		<div id="mo_api_authentication_settings">
			<div id='mrablock' class='mjwt-overlay dashboard'></div>
			<div class="miniorange_container">
				<?php
					$this->content_navbar( $currenttab );
				?>
				<table style="width:100%;">
					<tr>
						<td style="vertical-align:top;width:70%;">
							<?php
								$this->loader->load_current_tab( $currenttab );
							?>
						</td>
						
						<td style="vertical-align:top;padding-left:0.1%;">
						<?php
							$support = new Support();
							$support->support();
						?>
						</td>
						
					</tr>
				</table>
			</div>

		</div>
		<?php
	}

	/**
	 * Function to render tabs.
	 *
	 * @param string $currenttab Current active tab.
	 */
	public function content_navbar( $currenttab ) {
		global $mra_util;
		?>
		<div class="wrap">
			<div class="header-warp">
				<h1 style="font-weight: 700">miniOrange JWT Login (Single Sign On)</h1>

				<div><img style="float:left;" src="<?php echo MJ_URL . '/resources/images/logo.png'; // phpcs:ignore ?>"></div>
		</div>
		<div id="tab">
		<h2 class="nav-tab-wrapper">
			<a id="tab-config" class="nav-tab <?php echo ( 'config' === $currenttab || '' === $currenttab ) ? 'mo-jwt-nav-tab-active' : ''; ?>" href="admin.php?page=mo_jwt_settings&tab=config">Configure JWT Settings</a>
			<a id="acc_setup_button_id" class="nav-tab <?php echo ( 'account' === $currenttab ) ? 'mo-jwt-nav-tab-active' : ''; ?>" href="admin.php?page=mo_jwt_settings&tab=account">Account Setup</a>
			<a id="acc_setup_button_id" class="nav-tab <?php echo ( 'license' === $currenttab ) ? 'mo-jwt-nav-tab-active' : ''; ?>" href="admin.php?page=mo_jwt_settings&tab=license">Licensing</a>
		</h2>
		</div>
		<?php
	}
}
