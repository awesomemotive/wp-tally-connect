<?php
/**
 * Widget
 *
 * @package     WPTallyConnect\Widget
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Tally Widget
 *
 * @since       1.0.0
 */
class wptallyconnect_widget extends WP_Widget {

    /**
     * Constructor
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function wptallyconnect_widget() {
        parent::WP_Widget(
            false,
            __( 'WP Tally', 'wp-tally-connect' ),
            array(
                'description'  => __( 'Display a tally of your WordPress plugin downloads.', 'wp-tally-connect' )
            )
        );
    }


    /**
     * Widget definition
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::widget
     * @param       array $args Arguments to pass to the widget
     * @param       array $instance A given widget instance
     * @return      void
     */
    public function widget( $args, $instance ) {
        if( ! isset( $args['id'] ) ) {
            $args['id'] = 'wp_tally_widget';
        }

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );

        echo $args['before_widget'];

        if( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        do_action( 'wp_tally_before_widget' );

        if( $instance['username'] ) {
            $data = wptallyconnect_get_data( $instance['username'] );

            if( array_key_exists( 'error', $data['plugins'] ) ) {
                echo $data['plugins']['error'];
            } else {
                // Do stuff
                var_dump( $data );
            }
        } else {
            _e( 'No username has been specified!', 'wp-tally-connect' );
        }

        do_action( 'wp_tally_after_widget' );
        
        echo $args['after_widget'];
    }


    /**
     * Update widget options
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::update
     * @param       array $new_instance The updated options
     * @param       array $old_instance The old options
     * @return      array $instance The updated instance options
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']      = strip_tags( $new_instance['title'] );
        $instance['username']   = strip_tags( $new_instance['username'] );
        $instance['style']      = $new_instance['style'];

        return $instance;
    }


    /**
     * Display widget form on dashboard
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::form
     * @param       array $instance A given widget instance
     * @return      void
     */
    public function form( $instance ) {
        $defaults = array(
            'title'     => '',
            'username'  => '',
            'style'     => 'standard'
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'wp-tally-connect' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php _e( 'WordPress.org Username:', 'wp-tally-connect' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo $instance['username']; ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php _e( 'Widget Style:', 'wp-tally-connect' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
                <option value="standard" <?php selected( $instance['style'], 'standard' ); ?>><?php _e( 'Standard', 'wp-tally-connect' ); ?></option>
                <option value="goal" <?php selected( $instance['style'], 'goal' ); ?>><?php _e( 'Goal', 'wp-tally-connect' ); ?></option>
                <option value="countup" <?php selected( $instance['style'], 'countup' ); ?>><?php _e( 'Count Up', 'wp-tally-connect' ); ?></option>
            </select>
        </p>
        <?php
    }
}


/**
 * Register the new widget
 *
 * @since       1.0.0
 * @return      void
 */
function wptallyconnect_register_widget() {
    register_widget( 'wptallyconnect_widget' );
}
add_action( 'widgets_init', 'wptallyconnect_register_widget' );
