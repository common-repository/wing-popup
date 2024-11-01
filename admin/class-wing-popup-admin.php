<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wingdevs.com
 * @since      1.0.0
 *
 * @package    Wing_Popup
 * @subpackage Wing_Popup/admin
 * @author     WingDevs <admin@wingdevs.com>
 */


class WDPOP_Admin {
	
	/**
	 * The prefix for the ID of this plugin settings.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $prefix    The prefix for the ID of this plugin settings.
	 */
	private $prefix = 'wdpops';
	
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		if(class_exists('WDPOP_CORE')){
			$this->register_popup_setttings();
        }
		
	}
	/**
     *  Register popup settings
     *  
     *  @since 1.0.0
     *
     * @return void
     */
	function register_popup_setttings(){
        // Create options
        WDPOP_CORE::createOptions( $this->prefix, array(
            'menu_title' => 'Wing Popup',
            'menu_icon' => 'dashicons-format-gallery',
            'menu_slug'  => 'wing-popup',
            'theme'  => 'light',
            'show_all_options' => false,
            'framework_class' => 'wing-popup--options',
            'framework_title' => 'Manage Wing Popups',
            'footer_text' => 'Thank you for choosing Wing Popup. A product of <a target="_blank" href="https://wingdevs.com">Wingdevs</a>',
        ) );
        
        $page_load_fieldset =     array(
            'id'     => 'page_load_events',
            'type'   => 'fieldset',
            'fields' => array(
                array(
                    'type'    => 'subheading',
                    'content' => '<i class="fas fa-sync me-1"></i> Page Load',
                ),
                array(
                    'id'    => 'page_load_status',
                    'type'  => 'switcher',
                    'title' => 'Enable Display Popup on Page Load',
                    'default' => true,
                ),
                array(
                    'id'    => 'popup_delay',
                    'type'  => 'number',
                    'title' => 'Popup Delay in Seconds',
                    'unit'    => 'Second(s)',
                    'default' => 3,
                    'dependency' => array( 'page_load_status', '==', 'true' ),
                ),
                array(
                    'id'          => 'popup_show_again',
                    'type'        => 'dimensions',
                    'title'       => 'Show Popup again after',
                    'width_icon'  => '<i class="fas fa-clock"></i>',
                    'width_placeholder'  => '',
                    'height' => false,
                    'units'       => array( 'Minute(s)', 'Hour(s)', 'Day(s)', 'Week(s)', 'Month(s)'),
                    'default'     => array(
                        'width'     => '3',
                        'unit'      => 'Hour(s)',
                    ),
                    'dependency' => array( 'page_load_status', '==', 'true' ),
                ),
                
            ),
        );
        $on_click_fieldset =     array(
            'id'     => 'on_click_events',
            'type'   => 'fieldset',
            'fields' => array(
                array(
                    'type'    => 'subheading',
                    'content' => '<i class="fas fa-hand-pointer me-1"></i> On Click',
                ),
                array(
                    'id'    => 'on_click_status',
                    'type'  => 'switcher',
                    'title' => 'Enable Display Popup on Click',
                    'default' => false,
                ),
                array(
                    'type'    => 'notice',
                    'style'   => 'info',
                    'content' => 'Separte Selectors with comma for multiple CSS Selectors',
                    'dependency' => array( 'on_click_status', '==', 'true' ),
                ),
                array(
                    'id'    => 'popup_css_selectors',
                    'type'  => 'text',
                    'title' => 'CSS Selectors',
                    'dependency' => array( 'on_click_status', '==', 'true' ),
                ),
                array(
                    'id'    => 'prevent_default',
                    'type'  => 'switcher',
                    'dependency' => array( 'on_click_status', '==', 'true' ),
                    'title' => 'Enable the prevent default click functionality.',
                    'default' => true,
                ),
                
            ),

        );
        
        // Trigger Events
        $trigger_events = array(
            'title'  => 'Trigger Events',
            'icon'   => 'fas fa-star',
            'fields' => array(
                array(
                    'type'    => 'heading',
                    'content' => 'Configure actions/events to trigger the popup display.',
                ),
                $page_load_fieldset,
                $on_click_fieldset,
            ),
        );
        
        $popup_contents = array(

            array(
                'id'         => 'popup_type',
                'type'       => 'button_set',
                'title'      => 'Popup Type',
                'options'    => array(
                    'image'  => 'Only Image',
                    'image_and_content' => 'Image and Content',
                ),
                'default'    => 'image',
            ),
            array(
                'type'    => 'notice',
                'style'   => 'info',
                'content' => '<strong>Note: To disable a field, leave it empty.</strong>',
                'dependency' => array( 'popup_type', '==', 'image_and_content' ),
            ),
            array(
                'id'           => 'popup_image',
                'type'         => 'upload',
                'title'        => 'Upload image for the popup',
                'library'      => 'image',
                'button_title' => 'Upload Image',
                'preview' => true,
                'dependency' => array( 'popup_type', 'any', 'image,image_and_content' ),
            ),
            array(
                'id'    => 'popup_image_link',
                'type'  => 'link',
                'title' => 'Image Wrapper Link',
                'add_title'    => 'Add Image Wrapper Link',
                'edit_title'   => 'Edit Image Wrapper Link',
                'remove_title' => 'Remove Image Wrapper Link',
                'dependency' => array( 'popup_type', 'any', 'image,image_and_content' ),
            ),
            array(
                'id'    => 'popup_title',
                'type'  => 'text',
                'title' => 'Popup Title',
                'dependency' => array( 'popup_type', '==', 'image_and_content' ),
            ),
            array(
                'id'    => 'Popup_content',
                'type'  => 'wp_editor',
                'title' => 'Popup Content',
                'dependency' => array( 'popup_type', '==', 'image_and_content' ),
            ),
            array(
                'id'    => 'popup_close_text',
                'type'  => 'text',
                'title' => 'Popup Close Text',
                'dependency' => array( 'popup_type', '==', 'image_and_content' ),
            ),
            array(
                'id'    => 'cta_link',
                'type'  => 'link',
                'title' => 'CTA Button',
                'dependency' => array( 'popup_type', '==', 'image_and_content' ),
                'add_title'    => 'Add CTA Button',
                'edit_title'   => 'Edit CTA Button',
                'remove_title' => 'Remove CTA Button',
            )

        );
        $popup_settings = [
            array(
                'id'          => 'popup_width',
                'type'        => 'select',
                'title'       => 'Select Popup Width',
                'options'     => array(
                    'modal-sm'     => 'Small (300px)',
                    'modal-md'     => 'Medium (500px)',
                    'modal-lg'     => 'Large (800px)',
                    'modal-xl'     => 'Extra large (1140px)',
                    'modal-custom-width'     => 'Custom Width',
                ),
                'default'     => 'modal-md'
            ),
            array(
                'id'     => 'popup_custom_width',
                'type'   => 'dimensions',
                'title'  => 'Popup Custom Width',
                'height' => false,
                'dependency' => array( 'popup_width', '==', 'modal-custom-width' ),
            ),
            array(
                'id'          => 'fullscreen_popup',
                'type'        => 'select',
                'title'       => 'Make Popup Fullscreen',
                'options'     => array(
                    'modal-fullscreen-no'     => 'No',
                    'modal-fullscreen'     => 'Always',
                    'modal-fullscreen-sm-down'     => 'On Small Devices (576px and below)',
                    'modal-fullscreen-md-down'     => 'On Medium Devices (768px and below)',
                    'modal-fullscreen-lg-down'     => 'On Large Devices ( 992px and below)',
                    'modal-fullscreen-xl-down'     => 'On Extra large Devices ( 1200px and below)',
                    'modal-fullscreen-xxl-down'     => 'On Extra Extra large Devices ( 1400px and below)',
                ),
                'default'     => 'modal-fullscreen-no'
            ),
            array(
                'id'    => 'force_interaction',
                'type'  => 'switcher',
                'title' => 'Force Inreraction',
                'label' => 'When ebnabled, the Popup will not close when clicking outside of it and also will not if the Esc key is pressed.',
            ),
            array(
                'id'          => 'popup_position',
                'type'        => 'select',
                'title'       => 'Make Popup Position',
                'options'     => array(
                    'wdpop_core_top_left'     => 'Top left',
                    'wdpop_core_top_center'     => 'Top Center',
                    'wdpop_core_top_right'     => 'Top Right',
                    'wdpop_core_middle_left'     => 'Middle left',
                    'wdpop_core_middle_center'     => 'Middle Center',
                    'wdpop_core_middle_right'     => 'Middle Right',
                    'wdpop_core_bottom_left'     => 'Bottom left',
                    'wdpop_core_bottom_center'     => 'Bottom Center',
                    'wdpop_core_bottom_right'     => 'Bottom Right',
                ),
                'default'     => 'wdpop_core_middle_center'
            ),
    
        ];
        $popup_styles = [
            array(
                'id'         => 'popup_style_accordions',
                'type'       => 'accordion',
                'accordions' => array(

                    array(
                        'title'  => 'Close Button Styles',
                        'fields' => array(
                            array(
                                'id'      => 'close_btn_color',
                                'type'    => 'link_color',
                                'title'   => 'Close Button Color',
                                'default' => array(
                                    'color' => '#212529',
                                    'hover' => '#EB1E1E',
                                ),
                            ),
                            array(
                                'id'      => 'close_btn_bg_color',
                                'type'    => 'link_color',
                                'title'   => 'Close Button Background Color',
                                'default' => array(
                                    'color' => 'transparent',
                                    'hover' => 'transparent',
                                ),
                            ),
                            array(
                                'id'      => 'close_btn_border_color',
                                'type'    => 'link_color',
                                'title'   => 'Close Button Border Color',
                                'default' => array(
                                    'color' => '#212529',
                                    'hover' => '#EB1E1E',
                                ),
                            ),

                        )
                    ),

                    

                )
            ),

        ];
        $display_options_fields = WDPOP_Helper::get_display_options_fields();
        
        $Settings_tab = array(
            'id'    => 'wdpop_item_tab',
            'type'  => 'tabbed',
            'tabs'  => array(
                array(
                    'title'  => 'Popup Content',
                    'icon'   => 'fas fa-photo-video',
                    'fields' => $popup_contents,
                ),
                $trigger_events,
                array(
                    'title'  => 'Display Options',
                    'icon'   => 'fas fa-eye',
                    'fields' => array(
                        array(
                            'id'          => 'popup_display_type',
                            'type'        => 'select',
                            'title'       => 'Display the popup on the page',
                            'options'     => array(
                                'everywhere'     => 'Everywhere',
                                'custom_selection'     => 'Custom Selection',
                            ),
                            'default'     => 'everywhere'
                        ),
                        array(
                            'id'         => 'popup_display_accordions',
                            'type'       => 'accordion',
                            'dependency' => array( 'popup_display_type', '==', 'custom_selection' ),
                            'accordions' => array(

                                array(
                                    'title'  => 'Include Pages: Click to Select where you want to show the popup',
                                    'icon'   => 'fas fa-check',
                                    'fields' => array(
                                        array(
                                            'id'     => 'popup_display_includes',
                                            'type'   => 'repeater',
                                            'button_title' => '<i class="fas fa-plus-circle"></i> Include Another',
                                            'fields' => $display_options_fields,
                                            'class'  => 'popup_display_includes',
                                        ),
                                    )
                                ),

                                array(
                                    'title'  => 'Exclude Pages: Click to Select where you do not want to show the popup',
                                    'icon'   => 'fas fa-times',
                                    'fields' => array(
                                        array(
                                            'id'     => 'popup_display_excludes',
                                            'type'   => 'repeater',
                                            'button_title' => '<i class="fas fa-plus-circle"></i> Exclude Another',
                                            'fields' => $display_options_fields,
                                            'class'  => 'popup_display_excludes',
                                        ),
                                    )
                                ),

                            )
                        ),
                    ),
                ),
                array(
                    'title'  => 'Popup Settings',
                    'icon'   => 'fas fa-cog',
                    'fields' => $popup_settings,
                ),
                array(
                    'title'  => 'Popup Styles',
                    'icon'   => 'fas fa-paint-brush',
                    'fields' => $popup_styles,
                ),

            ),
        );
        
        $list_group_fields = array(
            array(
                'id'       => 'wdpop_item',
                'type'     => 'group',
                'accordion_title_by' => array( 'popup_name'),
                'accordion_title_prefix' => 'Popup:',
                'button_title' => '<i class="fas fa-plus-circle"></i> Add New Popup',
                'min' => 1,
                'accordion_title_number' => true,
                'class' => 'wing-popup--items',
                'fields' => array(
                    array(
                        'id'    => 'popup_name',
                        'type'  => 'text',
                        'title' => 'Popup Name',
                        'placeholder' => 'Provide a name for your Popup',
                        'default' => 'Popup Name',
                    ),
                    array(
                        'id'    => 'popup_status',
                        'type'  => 'switcher',
                        'title' => 'Popup Status',
                        'default' => true,
                    ),
                    $Settings_tab
                ),
            )
        );
        
        WDPOP_CORE::createSection( $this->prefix, array(
            'title'  => 'Manage Wing Popups',
            'icon'   => 'fas fa-rocket',
            'fields' => $list_group_fields,
        ) );
        
    }
	
	/**
	 * Register the stylesheets for the admin area.
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

        WDPOP_Helper::wing_add_dynamic_style($this->plugin_name, 'admin/css/wing-popup-admin.css');
	}

	/**
	 * Register the JavaScript for the admin area.
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

        WDPOP_Helper::wing_add_dynamic_script($this->plugin_name, 'admin/js/wing-popup-admin.js');

	}
}
