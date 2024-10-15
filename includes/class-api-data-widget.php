<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class API_Data_Widget extends WP_Widget {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'api_data_widget',
            __( 'API Data Widget', 'woouser-api-connector' ),
            array( 'description' => __( 'Displays API data based on user preferences.', 'woouser-api-connector' ) )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args Widget arguments.
     * 
     * @param array $instance Widget instance settings.
     */
    public function widget( $args, $instance ) {
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $elements = get_user_meta( $user_id, 'api_elements', true );
            $elements_array = array_filter( array_map( 'trim', explode( ',', $elements ) ) );

            if ( ! empty( $elements_array ) ) {
                $api_handler = API_Handler::get_instance();
                $data = $api_handler->fetch_api_data( $elements_array );

                echo $args['before_widget'];
                if ( ! empty( $instance['title'] ) ) {
                    echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
                }

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

                echo $args['after_widget'];
            }
        }
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values.
     * 
     * @return void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'API Data', 'woouser-api-connector' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'woouser-api-connector' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
                   type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance New values.
     * @param array $old_instance Old values.
     * 
     * @return array Sanitized values.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

        return $instance;
    }
}
