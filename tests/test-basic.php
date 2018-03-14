<?php
/**
 * Function test
 *
 * @package Gianism
 */

/**
 * Sample test case.
 */
class Gianism_Basic_Test extends WP_UnitTestCase {

	/**
	 * A single example test
	 *
	 */
	function test_auto_loader() {
		// Check class exists.
		$this->assertTrue( class_exists( 'Hametuha\\Yomigana\\Bootstrap' ) );
	}

}
