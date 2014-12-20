<?php

/*
Plugin Name: WP-Yomigana
Plugin URI: http://github.com/fumikito/WP-Yomigana
Description: TinyMCEでルビを入力できるようにします。
Version: 1.2.3
Author: Takahashi Fumiki
Author URI: http://takahashifumiki.com

This plugins owes a lot to TinyMCE Advanced, a WordPress plugin(http://wordpress.org/extend/plugins/tinymce-advanced/).

Released under the GPL v.2, http://www.gnu.org/copyleft/gpl.html

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

class WP_Yomigana {

	/**
	 * @var string
	 */
	const VERSION = '1.2.3';

	/**
	 * @var array
	 */
	private $option = array(
		'ruby'  => false,
		'small' => false,
		'dl'    => false,
		'q'     => false
	);

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		//保存されていた設定で上書き
		$saved_option = get_option( 'wp_yomigana_options' );
		if ( $saved_option && is_array( $saved_option ) ) {
			foreach ( $saved_option as $key => $val ) {
				if ( isset( $this->option[ $key ] ) ) {
					$this->option[ $key ] = $val;
				}
			}
		}
		// 設定画面の登録
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		// TinyMCEの設定
		add_action( 'init', array( $this, 'init' ) );
		// rubyタグを有効にする
		add_filter( 'wp_kses_allowed_html', array( $this, 'kses_allowed_html' ), 10, 2 );
		// Rubyタグをサポートしないブラウザ向けにCSSとJSを書き込む
		add_filter( 'wp_head', array( $this, 'wp_head' ), 99);
	}

	/**
	 * 指定されたタグが何列目に表示されるかを返す
	 *
	 * @param string $tag
	 *
	 * @return int
	 */
	private function get_row_index( $tag ) {
		if ( is_string( $tag ) && isset( $this->option[ $tag ] ) && is_array( $this->option[ $tag ] ) && isset( $this->option[ $tag ][0] ) ) {
			return (int) $this->option[ $tag ][0];
		} else {
			return 0;
		}
	}

	/**
	 * 指定されたタグが何番目に表示されるかを返す
	 *
	 * @param string $tag
	 *
	 * @return int 表示されない場合は0
	 */
	private function get_column_index( $tag ) {
		if ( is_string( $tag ) && isset( $this->option[ $tag ] ) && is_array( $this->option[ $tag ] ) && isset( $this->option[ $tag ][1] ) ) {
			return (int) $this->option[ $tag ][1];
		} else {
			return 0;
		}
	}


	/**
	 * 管理画面にページを追加する
	 */
	public function admin_menu() {
		add_options_page( 'ルビ設定', 'ルビ設定', 'manage_options', 'ruby-options', array( $this, 'menu_page' ) );
	}

	/**
	 * 管理画面を描画する
	 */
	public function menu_page() {
		?>
		<div class="wrap">
			<h2>ルビ設定</h2>
			<?php
			if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'ruby_setting' ) ) {
				foreach ( $this->option as $tag => $array ) {
					$index        = isset( $_REQUEST[ $tag . '_index' ] ) ? abs( $_REQUEST[ $tag . '_index' ] ) : 0;
					$column_index = isset( $_REQUEST[ $tag . '_column_index' ] ) ? abs( $_REQUEST[ $tag . '_column_index' ] ) : 0;
					if ( $index ) {
						$this->option[ $tag ] = array( min( $index, 4 ), $column_index );
					} else {
						$this->option[ $tag ] = false;
					}
					update_option( 'wp_yomigana_options', $this->option );
				}
				?>
				<div class="updated">
					<p>設定を更新しました</p>
				</div>
			<?php
			}
			?>
			<form method="post">
				<?php wp_nonce_field( 'ruby_setting' );?>
				<table class="form-table">
					<tbody>
					<?php foreach ( $this->option as $tag => $setting ): ?>
						<tr>
							<th><?php echo $tag; ?>タグのボタン</th>
							<td>
								<?php $index = $this->get_row_index( $tag );
								global $is_IE; ?>
								<select name="<?php echo $tag; ?>_index">
									<option value="0"<?php if ( ! $index ) {
										echo ' selected="selected"';
									} ?>>表示しない
									</option>
									<?php for ( $i = 1; $i < 5; $i ++ ): ?>
										<option value="<?php echo $i; ?>"<?php if ( $index == $i ) {
											echo ' selected="selected"';
										} ?>><?php echo $i; ?>行目に表示する
										</option>
									<?php endfor; ?>
								</select>
								表示順位: <input type="<?php echo $is_IE ? 'text' : 'number" step="1'; ?>"
								             name="<?php echo $tag ?>_column_index"
								             value="<?php echo $this->get_column_index( $tag ); ?>"/>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php submit_button();?>
			</form>
			<h3>ルビをサポートしないブラウザに対して</h3>

			<p>
				ルビをサポートしないブラウザ（Opera, Firefox）に対してはhtmlタグにno-rubyクラスを付与します。
				お使いのテーマのstyle.cssに下記のコードを記入すれば、インラインで表示されることを防げます。
			</p>
			<p class="description">
				<strong>Notice: </strong>Microsoft Wordなどからコピー＆ペーストした場合は自動でrpタグが入ります。
				括弧が2重になるのを防ぐために、rpタグの表示をオフにした方がいいかもしれません。
			</p>
<pre>
.no-ruby rt:before{
	content: '（';
}
.no-ruby rt:after{
	content: '）';
}
.no-ruby rp{
	display:none;
}
</pre>
		</div>
	<?php
	}

	/**
	 * iniフックで実行
	 */
	public function init() {
		// リッチエディターが有効なときだけ登録
		add_filter( "mce_external_plugins", array( $this, 'activate' ) );
		add_filter( 'mce_buttons', array( $this, 'register_buttons_1' ), 10000 );
		add_filter( 'mce_buttons_2', array( $this, 'register_buttons_2' ), 10000 );
		add_filter( 'mce_buttons_3', array( $this, 'register_buttons_3' ), 10000 );
		add_filter( 'mce_buttons_4', array( $this, 'register_buttons_4' ), 10000 );

		// TinyMCEで実行される
		add_filter( "tiny_mce_before_init", array( $this, "mce_init" ), 1000 );

		// ルビタグをサポートしないブラウザ向けに管理画面用CSSを読み込む
		if( $this->is_ruby_disabled() ){
			add_filter( "mce_css", array( $this, 'css_admin' ) );
		}
	}

	/**
	 * ボタンを登録する
	 *
	 * @param array $buttons
	 * @param int $index
	 *
	 * @return array
	 */
	private function register_button( $buttons, $index ) {
		foreach ( $this->option as $tag => $array ) {
			//表示すべき列か否か
			if ( $this->get_row_index( $tag ) == $index ) {
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
		}

		return $buttons;
	}

	public function register_buttons_1( $buttons ) {
		return $this->register_button( $buttons, 1 );
	}

	public function register_buttons_2( $buttons ) {
		return $this->register_button( $buttons, 2 );
	}

	public function register_buttons_3( $buttons ) {
		return $this->register_button( $buttons, 3 );
	}

	public function register_buttons_4( $buttons ) {
		return $this->register_button( $buttons, 4 );
	}

	/**
	 * TinyMCE用のプラグインを登録する
	 *
	 * @param array $plugin_array
	 *
	 * @return array
	 */
	function activate( $plugin_array ) {
		$plugin_array['yomigana'] = plugin_dir_url( __FILE__ ) . 'mce/' . (WP_DEBUG ? 'editor_plugin.js' : 'editor_plugin.min.js');
		return $plugin_array;
	}

	/**
	 * TinyMCEを初期化するときに実行される
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
	 * 有効なタグのリストを追加する
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
	 * ルビタグをサポートしないブラウザ向けに、管理画面専用のCSSを読み込む
	 *
	 * @param string $css
	 *
	 * @return string
	 */
	public function css_admin( $css ) {
		if ( ! empty( $css ) ) {
			$css .= ',';
		}
		$css .= plugin_dir_url( __FILE__ ) . "mce/css/noruby_admin.css?v=" . self::VERSION;
		return $css;
	}

	/**
	 * ルビをサポートしないブラウザ(Firefox, Opera)向けにhtmlにクラスを書き込み
	 *
	 * @link http://stackoverflow.com/questions/9847580/how-to-detect-safari-chrome-ie-firefox-and-opera-browser
	 * @return array
	 */
	public function wp_head() {
		echo "
<script>//<!-- Generated by WP-Yomigana
(function(){
	var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0, // Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
		isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
	if( isOpera || isFirefox ){
		document.documentElement.className += ' no-ruby';
	}
})();
//--></script>
";
	}

	/**
	 * ルビタグをサポートしないブラウザ（Opera, FF）か否か
	 * @global string $is_gecko
	 * @global string $is_opera
	 * @return boolean
	 */
	private function is_ruby_disabled() {
		// Opera, FFはサポートしていない
		global $is_gecko, $is_opera;
		if ( $is_opera || $is_gecko ) {
			return true;
		} else {
			return false;
		}
	}
}

new WP_Yomigana();
