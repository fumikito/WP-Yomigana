<?php
/*
Plugin Name: WP-Yomigana
Plugin URI: http://github.com/fumikito/WP-Yomigana
Description: TinyMCEでルビを入力できるようにします。
Version: 1.0
Author: Takahashi Fumiki
Author URI: http://takahashifumiki.com

This plugins owes a lot to TinyMCE Advanced, a WordPress plugin(http://wordpress.org/extend/plugins/tinymce-advanced/).

Released under the GPL v.2, http://www.gnu.org/copyleft/gpl.html

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

class WP_Yomigana{
	
	/**
	 * @var string
	 */
	const VERSION = '1.0';
	
	/**
	 * @var array
	 */
	private $option = array(
		'ruby' => false,
		'small' => false,
		'dl' => false,
		'q' => false
	);
	
	/**
	 * コンストラクタ 
	 */
	public function __construct(){
		//保存されていた設定で上書き
		$saved_option = get_option('wp_yomigana_options');
		if($saved_option && is_array($saved_option)){
			foreach($saved_option as $key => $val){
				if(isset($this->option[$key])){
					$this->option[$key] = $val;
				}
			}
		}
		//設定画面の登録
		add_action('admin_menu', array($this, 'admin_menu'));
		//TinyMCEの設定
		add_action('init', array($this, 'init'));
		//各種フィルター
		add_filter('', array($this, 'body_class'));
	}
	
	/**
	 * 指定されたタグが何列目に表示されるかを返す
	 * @param type $tag
	 * @return int 
	 */
	private function get_row_index($tag){
		if(is_string($tag) && isset($this->option[$tag]) && is_array($this->option[$tag]) && isset($this->option[$tag][0])){
			return (int)$this->option[$tag][0];
		}else{
			return 0;
		}
	}

	/**
	 * 指定されたタグが何番目に表示されるかを返す
	 * @param type $tag
	 * @return int 表示されない場合は0
	 */
	private function get_column_index($tag){
		if(is_string($tag) && isset($this->option[$tag]) && is_array($this->option[$tag]) && isset($this->option[$tag][1])){
			return (int)$this->option[$tag][1];
		}else{
			return 0;
		}
	}

	
	/**
	 * 管理画面にページを追加する 
	 */
	public function admin_menu(){
		add_options_page('ルビ設定', 'ルビ設定', 'manage_options', 'ruby-options', array($this, 'menu_page'));
	}
	
	/**
	 * 管理画面を描画する 
	 */
	public function menu_page(){
		?>
		<div class="wrap">
		<h2>ルビ設定</h2>
		<?php
			if(isset($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'ruby_setting')){
				foreach($this->option as $tag => $array){
					$index = isset($_REQUEST[$tag.'_index']) ? abs($_REQUEST[$tag.'_index']) : 0;
					$column_index = isset($_REQUEST[$tag.'_column_index']) ? abs($_REQUEST[$tag.'_column_index']) : 0;
					if($index){
						$this->option[$tag] = array(min($index, 4), $column_index);
					}else{
						$this->option[$tag] = false;
					}
					update_option('wp_yomigana_options', $this->option);
				}
				?>
				<div class="updated">
					<p>設定を更新しました</p>
				</div>
				<?php
			}
		?>
		<form method="post">
			<?php wp_nonce_field('ruby_setting');?>
			<table class="form-table">
				<tbody>
					<?php foreach($this->option as $tag => $setting): ?>
						<tr>
							<th><?php echo $tag; ?>タグのボタン</th>
							<td>
								<?php $index = $this->get_row_index($tag); global $is_IE;?>
								<select name="<?php echo $tag; ?>_index">
									<option value="0"<?php if(!$index) echo ' selected="selected"'; ?>>表示しない</option>
									<?php for($i = 1; $i < 5; $i++): ?>
									<option value="<?php echo $i; ?>"<?php if($index == $i) echo ' selected="selected"'; ?>><?php echo $i; ?>行目に表示する</option>
									<?php endfor; ?>
								</select>
								表示順位: <input type="<?php echo $is_IE ? 'text' : 'number" step="1'; ?>" name="<?php echo $tag?>_column_index" value="<?php echo $this->get_column_index($tag); ?>" />
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php submit_button();?>
		</form>
		</div>
		<?php
	}
	
	/**
	 * iniフックで実行
	 */
	public function init(){
		// 許可がなければtinyMCEには何もしない
		if(!current_user_can('edit_posts') && !current_user_can('edit_pages')){
			return;
		}
		//リッチエディターが有効なときだけ登録
		add_filter("mce_external_plugins", array($this, 'activate'));
		add_filter('mce_buttons', array($this, 'register_buttons_1'), 10000);
		add_filter('mce_buttons_2', array($this, 'register_buttons_2'), 10000);
		add_filter('mce_buttons_3', array($this, 'register_buttons_3'), 10000);
		add_filter('mce_buttons_4', array($this, 'register_buttons_4'), 10000);
	
		//TinyMCEで実行される
		add_filter("tiny_mce_before_init", array($this, "mce_init"), 1000);
		
		//ルビタグをサポートしないブラウザ向けにCSSを読み込む
		if($this->is_ruby_disabled()){
			add_filter("mce_css", array($this, 'css_admin'));
		}
	}
	
	/**
	 * ボタンを登録する
	 * @param array $buttons
	 * @param int $index 
	 * @return array
	 */
	private function register_button($buttons, $index){
		foreach($this->option as $tag => $array){
			//表示すべき列か否か
			if($this->get_row_index($tag) == $index){
				$insert_index = $this->get_column_index($tag);
				if($insert_index == 0){
					$new_buttons = array_merge(array($tag), $buttons);
				}elseif($insert_index >= count($buttons)){
					$new_buttons = array_merge($buttons, array($tag));
				}else{
					$new_buttons = array();
					$counter = 1;
					foreach($buttons as $button){
						if($counter == $insert_index){
							$new_buttons[] = $tag;
						}
						$new_buttons[] = $button;
						$counter++;
					}
				}
				$buttons = $new_buttons;
			}
		}
		return $buttons;
	}
	
	public function register_buttons_1($buttons){return $this->register_button($buttons, 1);}
	public function register_buttons_2($buttons){return $this->register_button($buttons, 2);}
	public function register_buttons_3($buttons){return $this->register_button($buttons, 3);}
	public function register_buttons_4($buttons){return $this->register_button($buttons, 4);}

	/**
	 * TinyMCE用のプラグインを登録する
	 * @param array $plugin_array
	 * @return array
	 */
	function activate($plugin_array)
	{
		$plugin_array['yomigana'] = plugin_dir_url(__FILE__).'/mce/editor_plugin.js';
		return $plugin_array;
	}
	
	/**
	 * TinyMCEを初期化するときに実行される
	 * @param type $init_arr
	 * @return string 
	 */
	public function mce_init($init_arr){
		if(!empty($init_arr["extended_valid_elements"])){
			$init_arr["extended_valid_elements"] .= ",ruby[id|class],rt";
		}else{
			$init_arr["extended_valid_elements"] .= "ruby[id|class],rt";
		}
		return $init_arr;
	}
	
	/**
	 * ルビタグをサポートしないブラウザ向けに、管理画面専用のCSSを読み込む
	 * 
	 * @param string $css
	 * @return string
	 */
	function css_admin($css)
	{
		$url = plugin_dir_url(__FILE__)."mce/css/noruby_admin.css?v=".self::VERSION;
		?>
			<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready(function($){
				//Loop untile tinyMCE has been ready.
				var timer = setInterval(function(){
					if($("#content_ifr").length > 0){
						clearInterval(timer);
						$("#content_ifr").contents().find("head").append(
							'<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>" />'
						);
					}
				}, 500);
			});
			//]]>
			</script>
		<?php
		return $css;
	}
	
	
	/**
	 * ルビタグをサポートしないブラウザ（Opera, FF）か否か
	 * @global type $is_gecko
	 * @global type $is_opera
	 * @return boolean 
	 */
	private function is_ruby_disabled(){
		// Opera, FFはサポートしていない
		global $is_gecko, $is_opera;
		if($is_opera || $is_gecko){
			return true;
		}else{
			return false;
		}
	}
}
new WP_Yomigana();