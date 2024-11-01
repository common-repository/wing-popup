<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wdpop_get_icons' ) ) {
  function wdpop_get_icons() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'wdpop_core_icon_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'wing-popup' ) ) );
    }

    ob_start();

    WDPOP_CORE::include_plugin_file( 'fields/icon/fa5-icons.php' );

    $icon_lists = apply_filters( 'wdpop_core_field_icon_add_icons', wdpop_get_default_icons() );

    if ( ! empty( $icon_lists ) ) {

      foreach ( $icon_lists as $list ) {

        echo ( count( $icon_lists ) >= 2 ) ? '<div class="wdpop_core-icon-title">'. esc_attr( $list['title'] ) .'</div>' : '';

        foreach ( $list['icons'] as $icon ) {
          echo '<i title="'. esc_attr( $icon ) .'" class="'. esc_attr( $icon ) .'"></i>';
        }

      }

    } else {

      echo '<div class="wdpop_core-error-text">'. esc_html__( 'No data available.', 'wing-popup' ) .'</div>';

    }

    $content = ob_get_clean();

    wp_send_json_success( array( 'content' => $content ) );

  }
  add_action( 'wp_ajax_wdpop_core-get-icons', 'wdpop_get_icons' );
}

/**
 *
 * Export
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wdpop_export' ) ) {
  function wdpop_export() {

    $nonce  = ( ! empty( $_GET[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_GET[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'wdpop_core_backup_nonce' ) ) {
      die( esc_html__( 'Error: Invalid nonce verification.', 'wing-popup' ) );
    }

    if ( empty( $unique ) ) {
      die( esc_html__( 'Error: Invalid key.', 'wing-popup' ) );
    }

    // Export
    header('Content-Type: application/json');
    header('Content-disposition: attachment; filename=backup-'. gmdate( 'd-m-Y' ) .'.json');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo wp_json_encode( get_option( $unique ) );

    die();

  }
  add_action( 'wp_ajax_wdpop_core-export', 'wdpop_export' );
}

/**
 *
 * Import Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wdpop_import_ajax' ) ) {
  function wdpop_import_ajax() {
    $wdpop_core__nonce = wp_create_nonce( 'wdpop_core_global' );
    if(!wp_verify_nonce( $wdpop_core__nonce, 'wdpop_core_global' )){}
    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';
    $data = ( ! empty( $_POST['data'] ) ) ? wp_kses_post_deep( json_decode( sanitize_text_field( wp_unslash( $_POST['data'] ) ), true ) ) : array();
    if ( ! wp_verify_nonce( $nonce, 'wdpop_core_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'wing-popup' ) ) );
    }

    if ( empty( $unique ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid key.', 'wing-popup' ) ) );
    }

    if ( empty( $data ) || ! is_array( $data ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: The response is not a valid JSON response.', 'wing-popup' ) ) );
    }

    // Success
    update_option( $unique, $data );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_wdpop_core-import', 'wdpop_import_ajax' );
}

/**
 *
 * Reset Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wdpop_reset_ajax' ) ) {
  function wdpop_reset_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'wdpop_core_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'wing-popup' ) ) );
    }

    // Success
    delete_option( $unique );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_wdpop_core-reset', 'wdpop_reset_ajax' );
}

/**
 *
 * Chosen Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'wdpop_chosen_ajax' ) ) {
  function wdpop_chosen_ajax() {
    $wdpop_core__nonce = wp_create_nonce( 'wdpop_core_global' );
    if(!wp_verify_nonce( $wdpop_core__nonce, 'wdpop_core_global' )){}
    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $type  = ( ! empty( $_POST[ 'type' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'type' ] ) ) : '';
    $term  = ( ! empty( $_POST[ 'term' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'term' ] ) ) : '';
    $query = ( ! empty( $_POST['query_args'] ) ) ? wp_kses_post_deep( sanitize_text_field( wp_unslash( $_POST['query_args'] ) ) ) : array();
    
    if ( ! wp_verify_nonce( $nonce, 'wdpop_chosen_ajax_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'wing-popup' ) ) );
    }

    if ( empty( $type ) || empty( $term ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid term ID.', 'wing-popup' ) ) );
    }

    $capability = apply_filters( 'wdpop_chosen_ajax_capability', 'manage_options' );

    if ( ! current_user_can( $capability ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to do that.', 'wing-popup' ) ) );
    }

    // Success
    $options = WDPOP_Fields::field_data( $type, $term, $query );

    wp_send_json_success( $options );

  }
  add_action( 'wp_ajax_wdpop_core-chosen', 'wdpop_chosen_ajax' );
}
