<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wingdevs.com
 * @since      1.0.0
 *
 * @package    Wing_Popup
 * @subpackage Wing_Popup/public
 * @author     WingDevs <admin@wingdevs.com>
 */


class WDPOP_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	public function add_popup_markup(){
		include_once WDPOP_DIR . 'public/partials/popup-default.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in WDPOP_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WDPOP_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		if(!WDPOP_Helper::if_popups_active()) return;
		
		WDPOP_Helper::wing_add_dynamic_style($this->plugin_name, 'public/css/wing-styles.css');
		
		$this->add_popup_inline_styles();

	}
	
	function add_popup_inline_styles(){
		$wdpops = WDPOP_Helper::get_all_active_popups();
		$styles ='';
		
		foreach ($wdpops as $key => $this_popup) {
			$acc = $this_popup['wdpop_item_tab']['popup_style_accordions'];
			
			$color = $acc['close_btn_color']['color'];
			$hover = $acc['close_btn_color']['hover'];
			$bg_color = $acc['close_btn_bg_color']['color'];
			$bg_hover_color = $acc['close_btn_bg_color']['hover'];
			$border_color = $acc['close_btn_border_color']['color'];
			$border_hover_color = $acc['close_btn_border_color']['hover'];
			
			$styles .= "
			.wing-modal--$key .wing--popup-close-btn {
				color: {$color};
				background-color: {$bg_color};
				border-color: {$border_color};
			}
			.wing-modal--$key .wing--popup-close-btn:hover {
				color: {$hover};
				background-color: {$bg_hover_color};
				border-color: {$border_hover_color};
			}
			";
		}
		wp_add_inline_style( $this->plugin_name, $styles );
		
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in WDPOP_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WDPOP_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		if(!WDPOP_Helper::if_popups_active()) return;
		
		// Helper, public and bs scripts are in this file
		WDPOP_Helper::wing_add_dynamic_script($this->plugin_name.'-scripts', 'public/js/wing-popup-scripts.js');
		
		$dataFront = array(
			'popups' => $this->get_public_data_for_popups(),
			'site_url' => get_option('siteurl'),
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce( 'wing__nonce' ),
		);
		wp_localize_script($this->plugin_name.'-scripts', 'wing__cred', $dataFront );
		
		
	}
	function get_public_data_for_popups(){
		$wdpops = WDPOP_Helper::get_all_active_popups();
		$items = [];
		foreach ($wdpops as $key => $this_popup) {
			$items[] = array(
				'id' => 	$key,
				'on_click_events' => 	$this_popup['wdpop_item_tab']['on_click_events'],
				'page_load_events' => 	$this_popup['wdpop_item_tab']['page_load_events'],
			);
			
		}
		return $items;
	}
	
}
