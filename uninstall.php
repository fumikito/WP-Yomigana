<?php
/**
 * Executed when WordPress tries to delete this plugin.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! WP_UNINSTALL_PLUGIN ||
	dirname( WP_UNINSTALL_PLUGIN ) != dirname( plugin_basename( __FILE__ ) ) ) {
	exit;
}

delete_option( 'wp_yomigana_options' );