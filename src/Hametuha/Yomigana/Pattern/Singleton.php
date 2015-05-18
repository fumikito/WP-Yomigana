<?php

namespace Hametuha\Yomigana\Pattern;

/**
 * Singleton
 *
 * @package Hametuha\Yomigana
 */
abstract class Singleton
{


	protected static $instance;


	protected function __construct(){
		// Override this
	}

	/**
	 * Get instance
	 *
	 * @return static
	 */
	public static function get_instance(){
		if( !static::$instance ){
			static::$instance = new static();
		}
		return static::$instance;
	}

}
