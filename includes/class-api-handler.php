<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class API_Handler {

    /**
     * Instance of API_Handler.
     *
     * @var API_Handler
     */
    private static $instance = null;

    /**
     * API URL.
     *
     * @var string
     */
    private $api_url;

    /**
     * Cache duration in seconds.
     *
     * @var int
     */
    private $cache_duration;

    /**
     * Private constructor to prevent multiple instances.
     *
     * @param int $cache_duration Cache duration in seconds. Default is 3600 (1 hour).
     */
    private function __construct( $cache_duration = HOUR_IN_SECONDS ) {
        $this->cache_duration = $cache_duration;
        $this->api_url = apply_filters( 'wou_api_handler_api_url', 'https://httpbin.org/post' );
    }

    /**
     * Get the singleton instance of API_Handler.
     *
     * @return API_Handler
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Fetch API data based on elements.
     *
     * @param array $elements List of elements to fetch.
     * 
     * @return array API response data or error.
     */
    public function fetch_api_data( $elements ) {
        $user_id = get_current_user_id();
        $transient_key = $this->get_transient_key( $user_id );

        // Check if data is cached
        $cached_data = get_transient( $transient_key );
        if ( false !== $cached_data ) {
            return $cached_data;
        }

        // Prepare API request
        $response = wp_remote_post( $this->api_url, array(
            'body'    => json_encode( array( 'elements' => $elements ) ),
            'headers' => array( 'Content-Type' => 'application/json' ),
            'timeout' => 15,
        ) );

        // Handle response
        if ( is_wp_error( $response ) ) {
            return array( 'error' => $response->get_error_message() );
        }

        $body = wp_remote_retrieve_body( $response );
        $decoded_body = json_decode( $body, true );

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            return array( 'error' => 'Invalid JSON response from API.' );
        }

        // Extract headers
        $api_headers = isset( $decoded_body['headers'] ) && is_array( $decoded_body['headers'] ) ? $decoded_body['headers'] : array();

        $data = array(
            'body'    => $decoded_body,
            'headers' => $api_headers,
        );

        // Cache the data
        set_transient( $transient_key, $data, $this->cache_duration );

        return $data;
    }

    /**
     * Get transient key based on user ID.
     *
     * @param int $user_id User ID.
     * 
     * @return string Transient key.
     */
    private function get_transient_key( $user_id ) {
        return 'wou_api_data_' . $user_id;
    }

    /**
     * Clear cached data for a user.
     *
     * @param int $user_id User ID.
     * 
     * @return bool True if transient deleted, false otherwise.
     */
    public function clear_cached_data( $user_id ) {
        $transient_key = $this->get_transient_key( $user_id );
        return delete_transient( $transient_key );
    }
}
