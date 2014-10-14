<?php
/**
 * Helper functions
 *
 * @since       1.0.0
 * @package     WPTallyConnect\Functions
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Retrieve a users data from the API
 *
 * @since       1.0.0
 * @param       string $username The username to lookup
 * @param       array $args Other arguments to pass to the API
 * @return      array $tally The data retrieved from the API
 */
function wptallyconnect_get_data( $username = false, $args = array() ) {
    if( $username ) {
        $params = array(
            'timeout'   => 10,
            'sslverify' => false
        );

        $raw = wp_remote_retrieve_body( wp_remote_get( 'http://wptally.com/api/' . $username, $params ) );
        $raw = json_decode( $raw, true );

        if( array_key_exists( 'error', $raw ) ) {
            $tally = array(
                'error' => $raw['error']
            );
        } else {
            $tally = $raw;
        }
    } else {
        $tally = array(
            'error' => __( 'No username has been specified!', 'wp-tally-connect' )
        );
    }

    return apply_filters( 'wptallyconnect_get_data', $tally );
}
