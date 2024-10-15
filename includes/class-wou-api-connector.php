<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Woo_API_Connector {

    /**
     * API Handler instance.
     *
     * @var API_Handler
     */
    private $api_handler;

    /**
     * Settings Manager instance.
     *
     * @var Settings_Manager
     */
    private $settings_manager;

    /**
     * Widget Manager instance.
     *
     * @var Widget_Manager
     */
    private $widget_manager;

    /**
     * Constructor.
     */
    public function __construct() {
        // Include necessary class files
        require_once WOU_API_PLUGIN_DIR . 'includes/class-api-handler.php';
        require_once WOU_API_PLUGIN_DIR . 'includes/class-settings-manager.php';
        require_once WOU_API_PLUGIN_DIR . 'includes/class-widget-manager.php';
        require_once WOU_API_PLUGIN_DIR . 'includes/class-api-data-widget.php';

        // Initialize API Handler
        $this->api_handler = API_Handler::get_instance();

        // Initialize Settings Manager
        $this->settings_manager = new Settings_Manager( $this->api_handler );

        // Initialize Widget Manager
        $this->widget_manager = new Widget_Manager( $this->api_handler );
    }

    /**
     * Initialize hooks.
     */
    public function run() {
        // Initialize Settings Manager hooks
        $this->settings_manager->init();

        // Initialize Widget Manager hooks
        $this->widget_manager->init();

        // Register API endpoint
        add_action( 'init', array( $this, 'register_endpoint' ) );

        // Enqueue assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    /**
     * Register the new endpoint for My Account.
     */
    public function register_endpoint() {
        add_rewrite_endpoint( 'api-settings', EP_PAGES );
    }

    /**
     * Enqueue necessary scripts and styles.
     */
    public function enqueue_assets() {
        // Enqueue CSS for styling the API data
        wp_enqueue_style( 'woouser-api-style', WOU_API_PLUGIN_URL . 'assets/css/style.css', array(), WOU_API_PLUGIN_VERSION );

        // Enqueue JS if needed
        // wp_enqueue_script( 'woouser-api-script', WOU_API_PLUGIN_URL . 'assets/js/script.js', array('jquery'), WOU_API_PLUGIN_VERSION, true );
    }

    /**
     * Flush rewrite rules upon plugin activation.
     */
    public static function activate() {
        // Instantiate the class to access register_endpoint method
        $plugin = new self();
        $plugin->register_endpoint();

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Flush rewrite rules upon plugin deactivation.
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}
