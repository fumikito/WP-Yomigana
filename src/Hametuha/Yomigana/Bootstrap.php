<?php

namespace Hametuha\Yomigana;

use Hametuha\Yomigana\Pattern\Application;

class Bootstrap extends Application
{



	/**
	 * Constructor
	 */
	protected function __construct() {
		// Regsiter admin menu
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		// Add TinyMCE plugins
		add_filter( "mce_external_plugins", array( $this, 'register_plugins' ) );
		// Register buttons
		foreach( array('', '_2', '_3', '_4') as $index => $filter){
			add_filter( 'mce_buttons'.$filter, array( $this, 'register_buttons_'.($index + 1) ), 10000 );
		}
		// Customize TinyMCE setting
		add_filter( "tiny_mce_before_init", array( $this, "mce_init" ), 1000 );
		// Editor helper script
		add_action( 'wp_enqueue_editor', array($this, 'mce_helper'));
		// Add admin css for non-ruby browsers
		add_filter( "mce_css", array( $this, 'css_admin' ) );
		// Avoid ruby tag to be deleted.
		add_filter( 'wp_kses_allowed_html', array( $this, 'kses_allowed_html' ), 10, 2 );
		// Add JS snippet to wp_head to detect non-ruby browsers
		add_action( 'wp_head', array( $this, 'add_noruby' ), 99);
	}

	/**
	 * Get specified tag's display row index
	 *
	 * @param string $tag
	 *
	 * @return int
	 */
	protected function get_row_index( $tag ) {
		if ( is_string( $tag ) && isset( $this->option[ $tag ] ) && is_array( $this->option[ $tag ] ) && isset( $this->option[ $tag ][0] ) ) {
			return (int) $this->option[ $tag ][0];
		} else {
			return 0;
		}
	}

	/**
	 * Get specified tag's display index
	 *
	 * @param string $tag
	 *
	 * @return int 0 means hidden.
	 */
	protected function get_column_index( $tag ) {
		if ( is_string( $tag ) && isset( $this->option[ $tag ] ) && is_array( $this->option[ $tag ] ) && isset( $this->option[ $tag ][1] ) ) {
			return (int) $this->option[ $tag ][1];
		} else {
			return 0;
		}
	}


	/**
	 * Add menu page
	 */
	public function admin_menu() {
		add_options_page( $this->_s('WP-Yomigana 設定'), $this->_s('ルビ設定'), 'manage_options', 'ruby-options', array( $this, 'menu_page' ) );
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
	 * Render admin screen
	 */
	public function menu_page() {
		$this->get_template('setting');
	}

	/**
	 * Register buttons
	 *
	 * @param array $buttons
	 * @param int $index
	 *
	 * @return array
	 */
	private function register_button( $buttons, $index ) {
		foreach ( $this->option as $tag => $array ) {
			if( $this->get_row_index( $tag ) != $index ){
				continue;
			}
			$insert_index = $this->get_column_index( $tag );
			if ( $insert_index == 0 ) {
				$new_buttons = array_merge( array( $tag ), $buttons );
			} elseif ( $insert_index >= count( $buttons ) ) {
				$new_buttons = array_merge( $buttons, array( $tag ) );
			} else {
				$new_buttons = array();
				$counter     = 1;
				foreach ( $buttons as $button ) {
					if ( $counter == $insert_index ) {
						$new_buttons[] = $tag;
					}
					$new_buttons[] = $button;
					$counter ++;
				}
			}
			$buttons = $new_buttons;
		}
		return $buttons;
	}

	/**
	 * Register TinyMCE plugins
	 *
	 * @param array $plugin_array
	 *
	 * @return array
	 */
	function register_plugins( $plugin_array ) {
		$plugin_array['yomigana'] = $this->assets.'/js/dist/editor_plugin.js';
		return $plugin_array;
	}

	/**
	 * Register helper script
	 *
	 * @param $setting
	 */
	public function mce_helper($setting){
		if( $setting['tinymce'] ){
			wp_enqueue_style('jquery-ui-dialog');
			wp_enqueue_script('wp-yomigana-editor-helper', $this->assets.'/js/dist/editor-helper.js', array('jquery-ui-dialog'), static::VERSION, true);
			wp_localize_script('wp-yomigana-editor-helper', 'WpYomigana', array(
				'dl'    => $this->_s('定義リスト'),
				'dlToggle' => $this->_s('DL切替'),
				'dtToggle' => $this->_s('dt/dd切替'),
				'q'     => $this->_s('インライン引用'),
				'qDesc' => $this->_s('出典を入力してください。空白の場合は設定されません。'),
				'small' => $this->_s('注釈'),
				'cite'  => $this->_s('引用元'),
				'ruby'  => $this->_s('ルビ'),
				'imageBase' => $this->assets.'/img/dist/',
			));
		}
	}

	/**
	 * TinyMCE initialization
	 *
	 * @param array $init_arr
	 *
	 * @return string
	 */
	public function mce_init( $init_arr ) {
		if ( isset( $init_arr["extended_valid_elements"] ) && ! empty( $init_arr["extended_valid_elements"] ) ) {
			$init_arr["extended_valid_elements"] .= ",ruby[id|class],rt,rp";
		} else {
			$init_arr["extended_valid_elements"] = "ruby[id|class],rt,rp";
		}
		return $init_arr;
	}

	/**
	 * Add valid elements to kses filter
	 *
	 * @param string $tags
	 * @param string $context
	 *
	 * @return string
	 */
	public function kses_allowed_html( $tags, $context ) {
		foreach( array(
			'ruby' => array(
				'id' => true,
				'name' => true,
				'class' => true,
				'title' => true,
			),
			'rt' => true,
			'rb' => true,
			'rp' => true,
		) as $tag_name => $setting ){
			if( !isset($tags[$tag_name]) ){
				$tags[$tag_name] = $setting;
			}
		}
		return $tags;
	}

	/**
	 * Add non-ruby compatible css to admin panel
	 *
	 * @param string $css
	 *
	 * @return string
	 */
	public function css_admin( $css ) {
		if( $this->is_ruby_disabled() ) {
			if ( ! empty( $css ) ) {
				$css .= ',';
			}
			$css .= $this->assets . "/css/noruby_admin.css?v=" . static::VERSION;
		}
		return $css;
	}

	/**
	 * Add JS snippet to wp_head
	 *
	 * This snippets append `no-ruby` class to html element
	 * only if browser doesn't support ruby.
	 *
	 * @link http://stackoverflow.com/questions/9847580/how-to-detect-safari-chrome-ie-firefox-and-opera-browser
	 * @return array
	 */
	public function add_noruby() {
		echo <<<HTML
<script>//<!-- Generated by WP-Yomigana
(function(){
	var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0, // Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
		isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
	if( isOpera || (isFirefox && parseFloat(navigator.userAgent.replace(/.*rv:([0-9\.]+).*/, "$1")) < 38 ) ){
		document.documentElement.className += ' no-ruby';
	}
})();
//--></script>
HTML;
	}

	/**
	 * Magic method
	 *
	 * @param string $name
	 * @param array $arguments
	 *
	 * @return array
	 */
	public function __call($name, $arguments){
		if( preg_match('/register_buttons_([0-9])/', $name, $match) ){
			return $this->register_button($arguments[0], $match[1]);
		}else{
			// Do nothing
		}
	}
}
