<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: wp_editor
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WDPOP_CORE_Field_wp_editor' ) ) {
  class WDPOP_CORE_Field_wp_editor extends WDPOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'tinymce'       => true,
        'quicktags'     => true,
        'media_buttons' => true,
        'wpautop'       => false,
        'height'        => '',
      ) );

      $attributes = array(
        'rows'         => 10,
        'class'        => 'wp-editor-area',
        'autocomplete' => 'off',
      );

      $editor_height = ( ! empty( $args['height'] ) ) ? ' style="height:'. esc_attr( $args['height'] ) .';"' : '';

      $editor_settings  = array(
        'tinymce'       => $args['tinymce'],
        'quicktags'     => $args['quicktags'],
        'media_buttons' => $args['media_buttons'],
        'wpautop'       => $args['wpautop'],
      );

      echo wp_kses_post($this->field_before());

      echo ( wdpop_wp_editor_api() ) ? '<div class="wdpop_core-wp-editor" data-editor-settings="'. esc_attr( wp_json_encode( $editor_settings ) ) .'">' : '';

      echo wp_kses_post('<textarea name="'. esc_attr( $this->field_name() ) .'"'. $this->field_attributes( $attributes ) . $editor_height .'>'. $this->value .'</textarea>');

      echo ( wdpop_wp_editor_api() ) ? '</div>' : '';

      echo wp_kses_post($this->field_after());

    }

    public function enqueue() {

      if ( wdpop_wp_editor_api() && function_exists( 'wp_enqueue_editor' ) ) {

        wp_enqueue_editor();
        
        $this->setup_wp_editor_settings();
        
        $this->setup_wp_editor_media_buttons();
        
      }

    }
    /**
     *  Setup wp editor media buttons
     *
     *  Can hooked into `print_default_editor_scripts` action
     * 
     * @return void
     */
    public function setup_wp_editor_media_buttons() {
        if ( ! function_exists( 'media_buttons' ) ) {
          return;
        }
  
        ob_start();
          echo '<div class="wp-media-buttons wdpop_core-media-prio-button">';
            do_action( 'media_buttons' );
          echo '</div>';
        $media_buttons = ob_get_clean();
        
        $inline_script = 'var wdpop_core_media_buttons = ' . wp_json_encode( $media_buttons ) . ';';
        $this->add_inline_script_without_dependency($inline_script);
    }
    public function add_inline_script_without_dependency($script, $handle = 'wdpop_core-tiny-media-js-header') {
      wp_register_script( $handle, false, array(), '1.0', false );
      wp_enqueue_script( $handle );
      wp_add_inline_script( $handle, $script);

    }
    // Setup wp editor settings
    public function setup_wp_editor_settings() {

      if ( wdpop_wp_editor_api() && class_exists( '_WP_Editors') ) {

        $defaults = apply_filters( 'wdpop_core_wp_editor', array(
          'tinymce' => array(
            'wp_skip_init' => true
          ),
        ) );

        $setup = _WP_Editors::parse_settings( 'wdpop_core_wp_editor', $defaults );

        _WP_Editors::editor_settings( 'wdpop_core_wp_editor', $setup );

      }

    }

  }
}
