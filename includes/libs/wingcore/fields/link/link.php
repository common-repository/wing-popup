<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: link
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WDPOP_CORE_Field_link' ) ) {
  class WDPOP_CORE_Field_link extends WDPOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'add_title'    => esc_html__( 'Add Link', 'wing-popup' ),
        'edit_title'   => esc_html__( 'Edit Link', 'wing-popup' ),
        'remove_title' => esc_html__( 'Remove Link', 'wing-popup' ),
      ) );

      $default_values = array(
        'url'    => '',
        'text'  => '',
        'target' => '',
      );

      $value = wp_parse_args( $this->value, $default_values );

      $hidden = ( ! empty( $value['url'] ) || ! empty( $value['url'] ) || ! empty( $value['url'] ) ) ? ' hidden' : '';

      $maybe_hidden = ( empty( $hidden ) ) ? ' hidden' : '';

      echo wp_kses_post($this->field_before());

      echo '<textarea readonly="readonly" class="wdpop_core--link hidden"></textarea>';

      echo '<div class="'. esc_attr( $maybe_hidden ) .'"><div class="wdpop_core--result">'. wp_kses_post(sprintf( '{url:"%s", text:"%s", target:"%s"}', $value['url'], $value['text'], $value['target'] )) .'</div></div>';

      echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[url]' ) ) .'" value="'. esc_attr( $value['url'] ) .'"'. wp_kses_post($this->field_attributes( array( 'class' => 'wdpop_core--url' ) )) .' />';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[text]' ) ) .'" value="'. esc_attr( $value['text'] ) .'" class="wdpop_core--text" />';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name( '[target]' ) ) .'" value="'. esc_attr( $value['target'] ) .'" class="wdpop_core--target" />';

      echo '<a href="#" class="button button-primary wdpop_core--add'. esc_attr( $hidden ) .'">'. wp_kses_post($args['add_title']) .'</a> ';
      echo '<a href="#" class="button wdpop_core--edit'. esc_attr( $maybe_hidden ) .'">'. wp_kses_post($args['edit_title']) .'</a> ';
      echo '<a href="#" class="button wdpop_core-warning-primary wdpop_core--remove'. esc_attr( $maybe_hidden ) .'">'. wp_kses_post($args['remove_title']) .'</a>';

      echo wp_kses_post($this->field_after());

    }

    public function enqueue() {

      if ( ! wp_script_is( 'wplink' ) ) {
        wp_enqueue_script( 'wplink' );
      }

      if ( ! wp_script_is( 'jquery-ui-autocomplete' ) ) {
        wp_enqueue_script( 'jquery-ui-autocomplete' );
      }

      add_action( 'admin_print_footer_scripts', array( $this, 'add_wp_link_dialog' ) );

    }

    public function add_wp_link_dialog() {

      if ( ! class_exists( '_WP_Editors' ) ) {
        require_once ABSPATH . WPINC .'/class-wp-editor.php';
      }

      wp_print_styles( 'editor-buttons' );

      _WP_Editors::wp_link_dialog();

    }

  }
}
