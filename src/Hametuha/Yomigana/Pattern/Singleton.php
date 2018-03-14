<?php

namespace Hametuha\Yomigana\Pattern;

/**
 * Singleton
 *
 * @package Hametuha\Yomigana
 */
abstract class Singleton {

	/**
	 * Instance holder.
	 *
	 * @var array
	 */
	protected static $instances = [];

	/**
	 * Singleton constructor.
	 */
	protected function __construct() {
	}

	/**
	 * Get instance
	 *
	 * @return static
	 */
	public static function get_instance() {
		$class_name = get_called_class();
		if ( ! isset( self::$instances[ $class_name ] ) ) {
			self::$instances[ $class_name ] = new $class_name();
		}
		return self::$instances[ $class_name ];
	}

}
