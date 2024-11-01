<?php

/**
 * @link              https://wingdevs.com
 * @since             1.0.0
 * @package           Wing_Popup
 *
 * @wordpress-plugin
 * Plugin Name:       Wing Popup
 * Plugin URI:        https://wingdevs.com/wing-popup
 * Description:       Lightweight and powerful popup builder plugin for WordPress.
 * Version:           1.0.0
 * Author:            WingDevs
 * Author URI:        https://wingdevs.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wing-popup
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WDPOP_VER', '1.0.0' );
define( 'WINGPOP_VER', '1.0.0' );

/**
 * Define necessary constants
 */
define( 'WDPOP_URL', plugin_dir_url( __FILE__ ) );
define( 'WDPOP_DIR', plugin_dir_path( __FILE__ ));
define( 'WDPOP_NAME', 'wing-popup');

/**
 * The code that runs during plugin activation.
 */
function wdpop_activate() {
	require_once WDPOP_DIR . 'includes/class-wing-popup-activator.php';
	WDPOP_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function wdpop_deactivate() {
	require_once WDPOP_DIR . 'includes/class-wing-popup-deactivator.php';
	WDPOP_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'wdpop_activate' );
register_deactivation_hook( __FILE__, 'wdpop_deactivate' );

/**
 * Load Core Framework for Wingdevs Plugin
 */
function wdpop_load_core_framework(){
	require_once WDPOP_DIR . 'includes/libs/wingcore/wingcore.php';
}
add_action('plugins_loaded', 'wdpop_load_core_framework');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WDPOP_DIR . 'includes/class-wing-popup.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wdpop_run() {

	$plugin = new WDPOP();
	$plugin->run();

}
add_action( 'init', 'wdpop_run' );
