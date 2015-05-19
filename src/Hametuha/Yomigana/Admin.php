<?php

namespace Hametuha\Yomigana;

use Hametuha\Yomigana\Pattern\Application;

/**
 * Admin class
 *
 * @package Hametuha\Yomigana
 */
class Admin extends Application
{
	public function __construct( $autoloader = null, array $values = array() ) {
		// Register admin menu
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		// Create plugin link
		add_filter('plugin_action_links', array($this, 'plugin_page_link'), 10, 2);
		add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 4);

	}

	/**
	 * Admin action
	 */
	public function admin_init(){
		if( (!defined('DOING_AJAX') || !DOING_AJAX) && isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'ruby_setting')){
			$new_option = array();
			foreach ( $this->option as $tag => $array ) {
				$index        = isset( $_REQUEST[ $tag . '_index' ] ) ? absint( $_REQUEST[ $tag . '_index' ] ) : 0;
				$column_index = isset( $_REQUEST[ $tag . '_column_index' ] ) ? absint( $_REQUEST[ $tag . '_column_index' ] ) : 0;
				if ( $index ) {
					$new_option[ $tag ] = array( min( $index, 4 ), $column_index );
				} else {
					$new_option[ $tag ] = false;
				}
			}
			update_option( 'wp_yomigana_options', $new_option );
			$message = $this->_s('設定が更新されました。');
			add_action('admin_notices', function() use ($message){
				printf('<div class="updated"><p>%s</p></div>', $message);
			});
		}
	}


	/**
	 * Add menu page
	 */
	public function admin_menu() {
		add_options_page( $this->_s('WP-Yomigana 設定'), $this->_s('ルビ設定'), 'manage_options', 'ruby-options', array( $this, 'menu_page' ) );
	}


	/**
	 * Render admin screen
	 */
	public function menu_page() {
		$this->get_template('setting');
	}



	/**
	 * Setup plugin links
	 *
	 * @param array $links
	 * @param string $file
	 * @return array
	 */
	public function plugin_page_link($links, $file){
		if(false !== strpos($file, 'wp-yomigana')){
			array_unshift($links, sprintf('<a href="%s">%s</a>', admin_url('options-general.php?page=ruby-options'), $this->_s('設定')));
		}
		return $links;
	}


	/**
	 * Plugin row meta
	 *
	 * @param array $plugin_meta
	 * @param string $plugin_file
	 * @param array $plugin_data
	 * @param string $status
	 * @return mixed
	 */
	public function plugin_row_meta($plugin_meta, $plugin_file, $plugin_data, $status){
		if( false !== strpos($plugin_file, 'wp-yomigana') ){
			$plugin_meta[] = sprintf('<a href="https://github.com/fumikito/WP-Yomigana">Github</a>');
		}
		return $plugin_meta;
	}
}
