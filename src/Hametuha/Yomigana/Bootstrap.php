<?php

namespace Hametuha\Yomigana;

use Hametuha\Yomigana\Pattern\Application;

/**
 * Bootstrap Class
 *
 * @package Hametuha\Yomigana
 */
class Bootstrap extends Application {


	/**
	 * Constructor
	 */
	protected function __construct() {
		Admin::get_instance();
		Gutenberg::get_instance();
		// Add TinyMCE plugins.
		add_filter( 'mce_external_plugins', [ $this, 'register_plugins' ] );
		// Register buttons.
		foreach ( [ '', '_2', '_3', '_4' ] as $index => $filter ) {
			add_filter( 'mce_buttons' . $filter, [ $this, 'register_buttons_' . ( $index + 1 ) ], 10000 );
		}
		// Customize TinyMCE setting.
		add_filter( 'tiny_mce_before_init', [ $this, 'mce_init' ], 1000 );
		// Editor helper script.
		add_action( 'wp_enqueue_editor', [ $this, 'mce_helper' ] );
		// Avoid ruby tag to be deleted.
		add_filter( 'wp_kses_allowed_html', [ $this, 'kses_allowed_html' ], 10, 2 );
	}

	/**
	 * Register buttons
	 *
	 * @param array $buttons
	 * @param int   $index
	 *
	 * @return array
	 */
	private function register_button( $buttons, $index ) {
		foreach ( $this->option as $tag => $array ) {
			if ( $this->get_row_index( $tag ) != $index ) {
				continue;
			}
			$insert_index = $this->get_column_index( $tag );
			if ( 0 == $insert_index ) {
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
	 * @param array $plugin_array Plugins.
	 *
	 * @return array
	 */
	function register_plugins( $plugin_array ) {
		$plugin_array['yomigana'] = $this->assets . '/js/dist/editor_plugin.js';

		return $plugin_array;
	}

	/**
	 * Register helper script
	 *
	 * @param array $setting TinyMCE setting.
	 */
	public function mce_helper( $setting ) {
		if ( $setting['tinymce'] ) {
			wp_enqueue_style( 'jquery-ui-mp6', plugins_url( 'assets/css/jquery-ui.css', $this->dir . '/assets' ), [], '1.0.2' );
			wp_enqueue_script( 'wp-yomigana-editor-helper', $this->assets . '/js/dist/editor-helper.js', array( 'jquery-ui-dialog' ), static::VERSION, true );
			wp_localize_script( 'wp-yomigana-editor-helper', 'WpYomigana', array(
				'dl'        => __( 'Definition List', 'wp-yomigana' ),
				'dlToggle'  => __( 'Apply / Strip', 'wp-yomigana' ),
				'dtToggle'  => __( 'Switch term and definition', 'wp-yomigana' ),
				'q'         => __( 'Inline Quote', 'wp-yomigana' ),
				'qForm'     => $this->get_template_string( 'q' ),
				'small'     => __( 'Annotation', 'wp-yomigana' ),
				'cite'      => __( 'Cite', 'wp-yomigana' ),
				'ruby'      => __( 'Ruby', 'wp-yomigana' ),
				'rubyForm'  => $this->get_template_string( 'ruby' ),
				'imageBase' => $this->assets . '/img/dist/',
				'close'     => __( 'Cancel', 'wp-yomigana' ),
				'ok'        => __( 'OK', 'wp-yomigana' ),
				'unwrap'    => __( 'Delete', 'wp-yomigana' ),
			) );
		}
	}


	/**
	 * TinyMCE initialization
	 *
	 * @param array $init_arr TinyMCE setting array.
	 *
	 * @return array
	 */
	public function mce_init( $init_arr ) {
		if ( isset( $init_arr['extended_valid_elements'] ) && ! empty( $init_arr['extended_valid_elements'] ) ) {
			$init_arr['extended_valid_elements'] .= ',ruby[id|class],rt,rp';
		} else {
			$init_arr['extended_valid_elements'] = 'ruby[id|class],rt,rp';
		}

		return $init_arr;
	}

	/**
	 * Add valid elements to kses filter
	 *
	 * @param string $tags    Allowed tags.
	 * @param string $context Context.
	 *
	 * @return string
	 */
	public function kses_allowed_html( $tags, $context ) {
		foreach (
			array(
				'ruby' => array(
					'id'    => true,
					'name'  => true,
					'class' => true,
					'title' => true,
				),
				'rt'   => true,
			) as $tag_name => $setting
		) {
			if ( ! isset( $tags[ $tag_name ] ) ) {
				$tags[ $tag_name ] = $setting;
			}
		}

		return $tags;
	}

	/**
	 * Magic method
	 *
	 * @param string $name
	 * @param array $arguments
	 *
	 * @return array
	 */
	public function __call( $name, $arguments ) {
		if ( preg_match( '/register_buttons_([0-9])/', $name, $match ) ) {
			return $this->register_button( $arguments[0], $match[1] );
		}
	}
}
