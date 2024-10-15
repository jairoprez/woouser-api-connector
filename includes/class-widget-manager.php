<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Widget_Manager {

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
        add_action( 'widgets_init', array( $this, 'register_widget' ) );
    }

    /**
     * Register the API Data Widget.
     */
    public function register_widget() {
        register_widget( 'API_Data_Widget' );
    }
}
