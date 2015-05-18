<?php

namespace Hametuha\Yomigana\Pattern;

/**
 * Application base class
 *
 * @package Hametuha\Yomigana\Pattern
 * @property-read array $option
 * @property-read string $dir
 * @property-read string $assets
 */
abstract class Application extends Singleton
{


	/**
	 * @const string
	 */
	const VERSION = '1.3.0';

	/**
	 * @const string
	 */
	const DOMAIN = 'wp-yomigana';

	/**
	 * @var array
	 */
	private $default_option = array(
		'ruby'  => false,
		'small' => false,
		'dl'    => false,
		'q'     => false,
		'cite'  => false,
	);

	/**
	 * Return string
	 *
	 * @param string $string
	 *
	 * @return string|void
	 */
	protected function _s($string){
		if( count(func_get_args()) == 1 ){
			return __($string, self::DOMAIN);
		}else{
			return __(call_user_func_array('sprintf', func_get_args()), self::DOMAIN);
		}
	}

	/**
	 * Short hand for i18n
	 *
	 * @param $string
	 */
	protected function _e($string){
		echo call_user_func_array(array($this, '_s'), func_get_args());
	}

	/**
	 * Detect if browser supports ruby element.
	 *
	 * @global string $is_gecko
	 * @global string $is_opera
	 * @return boolean
	 */
	protected function is_ruby_disabled() {
		// Opera, FF < 38 don't support ruby.
		global $is_gecko, $is_opera;
		return (
			$is_opera
		|| ($is_gecko && floatval(preg_replace('/.*rv:([0-9\.]+).*/u', '$1', $_SERVER['HTTP_USER_AGENT']) ) < 38 )
		);
	}
	/**
	 * Load template
	 *
	 * @param string $template
	 * @param array $args
	 */
	protected function get_template($template, $args = array()){
		$path = $this->search_template($template);
		if( $path ){
			if( $args ){
				extract($args);
			}
			include $path;
		}
	}

	/**
	 * Load template and return string
	 *
	 * @param string $template
	 * @param array $args
	 * @return string
	 */
	protected function get_template_string($template, $args = array()) {
		$path = $this->search_template($template);
		if( $path ){
			if( $args ){
				extract($args);
			}
			ob_start();
			include $path;
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}else{
			return '';
		}
	}

	/**
	 * Search template
	 *
	 * @param string $template
	 *
	 * @return bool|string
	 */
	private function search_template($template){
		$path = $this->dir.'/templates/'.$template.'.php';
		return file_exists($path) ? $path : false;
	}

	/**
	 * @param string $name
	 *
	 * @return null
	 */
	public function __get($name){
		switch( $name ){
			case 'dir':
				return dirname(dirname(dirname(dirname(__DIR__))));
				break;
			case 'assets':
				return untrailingslashit(plugin_dir_url($this->dir.'/assets/hoge'));
				break;
			case 'option':
				// Override default with saved data.
				$saved_option = (array) get_option( 'wp_yomigana_options', array() );
				foreach ( $this->default_option as $key => $val ) {
					if ( !isset( $saved_option[ $key ] ) ) {
						$saved_option[ $key ] = $val;
					}
				}
				return $saved_option;
				break;
			default:
				return null;
				break;
		}
	}

}