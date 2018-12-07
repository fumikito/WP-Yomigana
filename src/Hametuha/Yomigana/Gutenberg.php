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
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		add_action( 'init', [ $this, 'register_script' ], 10 );
		add_action( 'init', [ $this, 'register_block' ], 11 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_assets' ] );
	}

	/**
	 * Register scripts.
	 */
	public function register_script() {
		// Ruby, etc
		wp_register_script( 'wp-yomigana-gutenberg', $this->assets . '/js/dist/wp-yomigana-gutenberg.js', [
			'wp-element', 'wp-editor', 'wp-i18n', 'hamazon-editor',
			'wp-rich-text', 'wp-compose','wp-components',
		], self::VERSION, true );
		// Register DL.
		wp_register_script( 'wp-yomigana-dl', $this->assets . '/js/dist/definition-list.js', [ 'wp-blocks', 'wp-editor', 'wp-i18n' ], self::VERSION, true );
		wp_register_style( 'wp-yomigana-dl', $this->assets . '/css/editor-dl.css', [ 'wp-blocks' ], self::VERSION );
		// Register dt
		wp_register_script( 'wp-yomigana-dt', $this->assets . '/js/dist/definition-term.js', [ 'wp-yomigana-dl' ], self::VERSION, true );
		// Register dd
		wp_register_script( 'wp-yomigana-dd', $this->assets . '/js/dist/definition-description.js', [ 'wp-yomigana-dl' ], self::VERSION, true );
		// Register translation.
		if ( function_exists( 'wp_set_script_translations' ) ) {
			// For WordPress 5.0
			wp_set_script_translations( 'wp-yomigana-dl', 'wp-yomigana', plugin_dir_path( dirname( dirname( __DIR__ ) ) ) . 'languages' );
		} else if ( function_exists( 'gutenberg_get_jed_locale_data' ) ) {
			// For gutenberg only.
			$json = json_encode( gutenberg_get_jed_locale_data( 'wp-yomigana' ) );
			wp_add_inline_script(
				'wp-yomigana-dl',
				sprintf( 'wp.i18n.setLocaleData(  %s, "wp-yomigana" );', $json ),
				'before'
			);
		}
	}

	/**
	 * Register gutenberg block.
	 */
	public function register_block() {
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

	/**
	 * Enqueue assets for ruby, small, q, cite.
	 */
	public function block_editor_assets() {
		wp_enqueue_script( 'wp-yomigana-gutenberg' );
	}
}
