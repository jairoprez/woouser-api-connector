<?php
defined( 'ABSPATH' ) || exit;

$user_id = get_current_user_id();
$elements = get_user_meta( $user_id, 'api_elements', true );
$elements_array = array_filter( array_map( 'trim', explode( ',', $elements ) ) ); // Remove empty elements
?>
<h2><?php _e( 'API Settings', 'woouser-api-connector' ); ?></h2>

<form method="post" action="">
    <?php wp_nonce_field( 'save_api_settings', 'woouser_api_nonce' ); ?>
    <p>
        <label for="api_elements"><?php _e( 'Enter Elements (comma separated)', 'woouser-api-connector' ); ?></label><br>
        <input type="text" id="api_elements" name="api_elements" value="<?php echo esc_attr( $elements ); ?>" class="woocommerce-Input woocommerce-Input--text input-text" style="width: 100%;" placeholder="<?php _e( 'e.g., element1, element2, element3', 'woouser-api-connector' ); ?>" />
    </p>
    <p>
        <button type="submit" class="woocommerce-Button button"><?php _e( 'Save Settings', 'woouser-api-connector' ); ?></button>
    </p>
</form>

<?php if ( ! empty( $elements_array ) ) : // Only display API Data if elements are set ?>
    <hr>
    <?php
    $api_handler = API_Handler::get_instance();
    $data = $api_handler->fetch_api_data( $elements_array );
    ?>

    <h2><?php _e( 'Your API Data', 'woouser-api-connector' ); ?></h2>
    <?php
    if ( isset( $data['error'] ) ) {
        echo '<p>' . esc_html( $data['error'] ) . '</p>';
    } elseif ( ! empty( $data['headers'] ) ) {
        echo '<ul class="api-data-list">';
        foreach ( $data['headers'] as $key => $value ) {
            echo '<li><strong>' . esc_html( $key ) . ':</strong> ' . esc_html( $value ) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>' . __( 'No headers available.', 'woouser-api-connector' ) . '</p>';
    }
    ?>
<?php endif; ?>
