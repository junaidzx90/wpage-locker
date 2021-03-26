<?php
ob_start();
/**
 * @link              https://www.fiverr.com/junaidzx90
 * @since             1.0.0
 * @package           Wpage_Lcr
 *
 * @wordpress-plugin
 * Plugin Name:       WPage Locker
 * Plugin URI:        https://www.fiverr.com/junaidzx90
 * Description:       This Plugin helps to lock wp page content with some conditions.
 * Version:           1.0.0
 * Author:            Md Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpage-lcr
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPAGE_LCR_VERSION', '1.0.0' );
define( 'WPAGE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPAGE_NAME', 'wpage-lcr' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpage-lcr-activator.php
 */
function activate_wpage_lcr() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpage-lcr-activator.php';
	Wpage_Lcr_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpage-lcr-deactivator.php
 */
function deactivate_wpage_lcr() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpage-lcr-deactivator.php';
	Wpage_Lcr_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpage_lcr' );
register_deactivation_hook( __FILE__, 'deactivate_wpage_lcr' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpage-lcr.php';

/**
 * @since    1.0.0
 */
function run_wpage_lcr() {

	$plugin = new Wpage_Lcr();
	$plugin->run();

}
run_wpage_lcr();
