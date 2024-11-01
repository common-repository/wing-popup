<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: notice
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WDPOP_CORE_Field_notice' ) ) {
  class WDPOP_CORE_Field_notice extends WDPOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $style = ( ! empty( $this->field['style'] ) ) ? $this->field['style'] : 'normal';

      echo ( ! empty( $this->field['content'] ) ) ? '<div class="wdpop_core-notice wdpop_core-notice-'. esc_attr( $style ) .'">'. wp_kses_post($this->field['content']) .'</div>' : '';

    }

  }
}
