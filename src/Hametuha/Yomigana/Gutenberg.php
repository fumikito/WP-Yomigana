<?php

namespace Hametuha\Yomigana;


use Hametuha\Yomigana\Pattern\Application;

/**
 * Gutenberg helper
 *
 * @package wp-yomigana
 */
class Gutenberg extends Application {

	/**
	 * Constructor
	 */
	protected function __construct() {
		add_action( 'init', [ $this, 'register_script' ], 10 );
		add_action( 'init', [ $this, 'register_block' ], 11 );
	}

	/**
	 * Register scripts.
	 */
	public function register_script() {
		// Register DL.
		wp_register_script( 'wp-yomigana-dl', $this->assets . '/js/dist/definition-list.js', [ 'wp-blocks', 'wp-editor' ], self::VERSION, true );
		wp_localize_script( 'wp-yomigana-dl', 'YomiganaDl', [
			'label' => __( 'Definition List', 'wp-yomigana' ),
		] );
		wp_register_style( 'wp-yomigana-dl', $this->assets . '/css/editor-dl.css', [], self::VERSION );
		// Register dt
		wp_register_script( 'wp-yomigana-dt', $this->assets . '/js/dist/definition-term.js', [ 'wp-blocks', 'wp-editor' ], self::VERSION, true );
		wp_localize_script( 'wp-yomigana-dt', 'YomiganaDt', [
			'label' => __( 'Term', 'wp-yomigana' ),
		] );
		wp_register_script( 'wp-yomigana-dd', $this->assets . '/js/dist/definition-description.js', [ 'wp-blocks', 'wp-editor' ], self::VERSION, true );
		wp_localize_script( 'wp-yomigana-dd', 'YomiganaDd', [
			'label' => __( 'Description', 'wp-yomigana' ),
		] );
	}

	/**
	 * Register gutenberg block.
	 */
	public function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		register_block_type( 'wp-yomigana/dl', [
			'editor_style'  => 'wp-yomigana-dl',
			'editor_script' => 'wp-yomigana-dl',
		] );
		register_block_type( 'wp-yomigana/dt', [
			'editor_script' => 'wp-yomigana-dt',
		] );
		register_block_type( 'wp-yomigana/dd', [
			'editor_script' => 'wp-yomigana-dd',
		] );
	}
}
