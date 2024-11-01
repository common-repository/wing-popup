<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * @package   Wingcore - WordPress Options Framework
 * @author    Wings <admin@wingdevs.com>
 * @link      https://wingdevs.com
 * @copyright 2023-2024 WingDevs
 *
 */


if( ! defined( 'WDPOP_CORE_VER' ) ) define( 'WDPOP_CORE_VER', '1.0.0' );
if( ! defined( 'WDPOP_CORE_URL' ) ) define( 'WDPOP_CORE_URL', plugin_dir_url( __FILE__ ) );
if( ! defined( 'WDPOP_CORE_DIR' ) ) define( 'WDPOP_CORE_DIR', plugin_dir_path( __FILE__ ) );

require_once WDPOP_CORE_DIR .'classes/setup.class.php';
