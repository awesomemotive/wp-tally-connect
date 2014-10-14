<?php
/**
 * Plugin Name:     WP Tally Connect
 * Plugin URI:      http://wptally.com
 * Description:     Track your total WordPress plugin downloads
 * Version:         1.0.0
 * Author:          Pippin Williamson, Daniel J Griffiths & Sean Davis
 * Author URI:      http://easydigitaldownloads.com
 * Text Domain:     wp-tally-connect
 *
 * @package         WPTallyConnect
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


if( ! class_exists( 'WPTallyConnect' ) ) {


    /**
     * Main WPTallyConnect class
     *
     * @since       1.0.0
     */
    class WPTallyConnect {


        /**
         * @access      private
         * @since       1.0.0
         * @var         WPTallyConnect $instance The one true WPTallyConnect
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      self::$instance The one true WPTallyConnect
         */
        public static function instance() {
            if( ! self::$instance ) {
                self::$instance = new WPTallyConnect();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin path
            define( 'WPTALLYCONNECT_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'WPTALLYCONNECT_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include required files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            require_once WPTALLYCONNECT_DIR . 'includes/functions.php';
            require_once WPTALLYCONNECT_DIR . 'includes/widget.php';
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $lang_dir = apply_filters( 'wptallyconnect_language_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), '' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'wp-tally-connect', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/wp-tally-connect/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/wp-tally-connect/ folder
                load_textdomain( 'wp-tally-connect', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/wp-tally-connect/languages/ folder
                load_textdomain( 'wp-tally-connect', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'wp-tally-connect', false, $lang_dir );
            }
        }
    }
}


/**
 * The main function responsible for returning the one true WPTallyConnect
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      WPTallyConnect The one true WPTallyConnect
 */
function wptallyconnect_load() {
    return WPTallyConnect::instance();
}
add_action( 'plugins_loaded', 'wptallyconnect_load' );
