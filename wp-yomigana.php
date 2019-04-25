<?php
/**
Plugin Name: WP-Yomigana
Plugin URI: https://wordpress.org/plugins/wp-yomigana/
Description: You can enter ruby tag in visual editor.
Version: 2.0.3
PHP Version: 5.4
Author: Takahashi Fumiki
Author URI: https://takahashifumiki.com
License: GPL 3.0 or later
Text Domain: wp-yomigana
Domain Path: /languages

This plugins owes a lot to TinyMCE Advanced, a WordPress plugin(http://wordpress.org/extend/plugins/tinymce-advanced/).

*/

// Do not load directly.
defined( 'ABSPATH' ) || die();

/**
 * Initialize plugin
 *
 * @ignore
 */
function yomigana_init() {
	// Register i18n.
	load_plugin_textdomain( 'wp-yomigana', false, basename( dirname( __FILE__ ) ) . '/languages' );
	// Check error.
	$auto_loader = yomigana_error();
	if ( is_wp_error( $auto_loader ) ) {
		add_action( 'admin_notices', 'yomigana_notice' );
	} else {
		require $auto_loader;
		call_user_func( array( 'Hametuha\\Yomigana\\Bootstrap', 'get_instance' ) );
	}
}
add_action( 'plugins_loaded', 'yomigana_init' );

/**
 * Check error.
 *
 * @return WP_Error|string
 */
function yomigana_error() {
	$required = '5.4';
	$current  = phpversion();
	$path = dirname( __FILE__ ) . '/vendor/autoload.php';
	if ( version_compare( $current, $required, '<' ) ) {
		// translators: %1$s is requires PHP version, %2$s is current version.
		return new WP_Error( 'invalid_php_version', sprintf( __( 'WP-Yomigana requires PHP %1$s and later, but your PHP is %2$s', 'wp-yomigana' ), '5.4', phpversion() ) );
	} elseif ( ! file_exists( $path ) ) {
		// translators: %s is file path.
		return new WP_Error( 'no_composer', sprintf( __( 'WP-Yomigana\'s auto load file %s is not found.', 'wp-yomigana' ), $path ) );
	} else {
		return $path;
	}
}

/**
 * Show error message on admin screen.
 *
 * @ignore
 */
function yomigana_notice() {
	$error = yomigana_error();
	printf( '<div class="error"><p>%s</p></div>', esc_html( $error->get_error_message() ) );
}
