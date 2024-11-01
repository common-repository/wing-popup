<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it's ready for translation.
 *
 * @link        https://wingdevs.com
 * @since       1.0.0
 *
 * @package     Wing_Popup
 * @subpackage  Wing_Popup/includes
 * @author      WingDevs <admin@wingdevs.com>
 */

class WDPOP_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wing-popup',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' // WDPOP_DIR
		);

	}



}
