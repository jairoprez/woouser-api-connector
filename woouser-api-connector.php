<?php
/**
 * Plugin Name: WooUser API Connector
 * Plugin URI:  https://jairoprez.com/
 * Description: Connects WooCommerce users to an external API based on their preferences.
 * Version:     1.0.0
 * Author:      Jairo Perez
 * Author URI:  https://jairoprez.com/
 * License:     GPLv2 or later
 * Text Domain: woouser-api-connector
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define( 'WOU_API_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WOU_API_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WOU_API_PLUGIN_VERSION', '1.0.0' );

// Include the main plugin class
require_once WOU_API_PLUGIN_DIR . 'includes/class-wou-api-connector.php';

// Initialize the plugin
function initialize_wou_api_connector() {
    $plugin = new Woo_API_Connector();
    $plugin->run();
}
add_action( 'plugins_loaded', 'initialize_wou_api_connector' );

/**
 * Activation Hook
 */
register_activation_hook( __FILE__, array( 'Woo_API_Connector', 'activate' ) );

/**
 * Deactivation Hook
 */
register_deactivation_hook( __FILE__, array( 'Woo_API_Connector', 'deactivate' ) );
