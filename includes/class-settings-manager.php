<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Settings_Manager {

    /**
     * API Handler instance.
     *
     * @var API_Handler
     */
    private $api_handler;

    /**
     * Constructor.
     *
     * @param API_Handler $api_handler Instance of API_Handler.
     */
    public function __construct( $api_handler ) {
        $this->api_handler = $api_handler;
    }

    /**
     * Initialize hooks.
     */
    public function init() {
        // Save settings
        add_action( 'init', array( $this, 'save_settings' ) );

        // Add settings tab
        add_filter( 'woocommerce_account_menu_items', array( $this, 'add_settings_tab' ) );

        // Add content for the endpoint
        add_action( 'woocommerce_account_api-settings_endpoint', array( $this, 'render_settings_content' ) );
    }

    /**
     * Add new tab to My Account menu.
     *
     * @param array $items Existing menu items.
     * 
     * @return array Modified menu items.
     */
    public function add_settings_tab( $items ) {
        // Insert the new tab after 'Dashboard' or adjust as needed
        $items = array_slice( $items, 0, 1, true ) +
                 array( 'api-settings' => __( 'API Settings', 'woouser-api-connector' ) ) +
                 array_slice( $items, 1, NULL, true );

        return $items;
    }

    /**
     * Render the settings content in My Account tab.
     */
    public function render_settings_content() {
        include WOU_API_PLUGIN_DIR . 'templates/my-account-api-data.php';
    }

    /**
     * Save user settings when form is submitted.
     */
    public function save_settings() {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['woouser_api_nonce'] ) && wp_verify_nonce( $_POST['woouser_api_nonce'], 'save_api_settings' ) ) {
            if ( isset( $_POST['api_elements'] ) ) {
                $elements = sanitize_text_field( $_POST['api_elements'] );
                update_user_meta( get_current_user_id(), 'api_elements', $elements );

                // Clear cached data
                $this->api_handler->clear_cached_data( get_current_user_id() );

                wc_add_notice( __( 'Settings saved successfully.', 'woouser-api-connector' ), 'success' );
            }
        }
    }
}
