<?php
/*
Plugin Name: WP-Yomigana
Plugin URI: http://hametuha.co.jp
Description: TinyMCEでルビを入力できるようにします。
Version: 0.8
Author: Takahashi Fumiki
Author URI: http://hametuha.co.jp

This plugins owes a lot to TinyMCE Advanced, a WordPress plugin(http://wordpress.org/extend/plugins/tinymce-advanced/).

Released under the GPL v.2, http://www.gnu.org/copyleft/gpl.html

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

/**
 * バージョンを設定する
 * @static WP_YOMIGANA_VERSION string
 */
define("WP_YOMIGANA_VERSION", "0.8");

/**
 * WordPress初期化時にWP-Yomiganaを有効にする
 * @return void
 */
function wp_yomigana()
{
	//ルビをサポートしないブラウザ向けにcssを読み込む
	if(!wp_yomigana_ruby_support() && !is_admin()){
		//ユーザCSSがあるかどうかを確認する
		if(is_file(TEMPLATEPATH.DIRECTORY_SEPARATOR."noruby.css")){
			wp_enqueue_style(
				"noruby",
				get_bloginfo("template_directory")."/noruby.css",
				array(),
				WP_YOMIGANA_VERSION
			);
		}
		//ユーザCSSがなければデフォルトを読み込む
		else{
			wp_enqueue_style(
				"noruby",
				get_bloginfo("url")."/wp-content/plugins/wp-yomigana/mce/css/noruby.css",
				array(),
				WP_YOMIGANA_VERSION
			);
		}
	}
	
	// 許可がなければtinyMCEには何もしない
	if(!current_user_can('edit_posts') && !current_user_can('edit_pages'))
		return;

	//リッチエディターが有効なときだけ登録
	add_filter("mce_external_plugins", "wp_yomigana_activate", 100);
	add_filter("mce_external_alanguages", "wp_yomigana_language");
	add_filter('mce_buttons_3', 'wp_yomigana_register_button');
	if(!wp_yomigana_ruby_support()){
		add_filter("mce_css", "wp_yomigana_css_admin");
	}
}
add_action('init', 'wp_yomigana');


/**
 * ボタンを登録する
 * @param array $buttons
 * @return array
 */
function wp_yomigana_register_button($buttons)
{
	array_unshift($buttons, "yomigana", "separator");
	return $buttons;
}


/**
 * TinyMCE用のプラグインを登録する
 * @param array $plugin_array
 * @return array
 */
function wp_yomigana_activate($plugin_array)
{
	$plugin_array['yomigana'] = get_bloginfo("url").'/wp-content/plugins/wp-yomigana/mce/editor_plugin.js';
	return $plugin_array;
}

/**
 * プラグインの言語ファイルを読み込む
 * @param array $languages
 * @return array
 */
function wp_yomigana_language($languages){
	$languages["yomigana"] = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."mce".DIRECTORY_SEPARATOR."langs".DIRECTORY_SEPARATOR."langs.php");
	return $languages;
}

/**
 * ルビタグをサポートしないブラウザ向けに、管理画面専用のCSSを読み込む
 * 
 * ※CSSが必要なのはTinyMCEが置換するiframeの中なので、ifram内にrubyタグを挿入する
 * 
 * @param string $css
 * @return string
 */
function wp_yomigana_css_admin($css)
{
	$url = plugin_dir_url(__FILE__)."mce/css/noruby_admin.css?v=".WP_YOMIGANA_VERSION;
	?>
<script type="text/javascript">
//<![CDATA[
jQuery(function(event){
	//Loop untile tinyMCE has been ready.
	var timer = setInterval(function(){
		if(jQuery("#content_ifr").length > 0){
			clearInterval(timer);
			jQuery("#content_ifr").contents().find("head").append(
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
 * ブラウザがルビをサポートしているかどうかを返す
 * @return boolean
 */
function wp_yomigana_ruby_support(){
	/*
	 * -------OK
	 * IE 6~
	 * Mac Safari 5.0~
	 * Mac Chrome 11.0~
	 * iPad 4.3~
	 * 
	 * -------NG
	 * Mac FireFox ~5
	 * Mac Opera ~11.50
	 * iPhone ~4.2(3G)
	 */
	global $is_gecko, $is_opera, $is_iphone;
	//return true;
	if($is_opera || $is_gecko || $is_iphone){
		return false;
	}else{
		return true;
	}
}

/**
 * デフォルトでは削除されてしまうrubyタグを有効にする
 * @param array $init_arr
 * @return array
 */
function wp_yomigana_init($init_arr)
{
	if(!empty($init_arr["extended_valid_elements"])){
		$init_arr["extended_valid_elements"] .= ",ruby,rb,rt";
	}else{
		$init_arr["extended_valid_elements"] .= "ruby,rb,rt";
	}
	return $init_arr;
}
add_filter("tiny_mce_before_init", "wp_yomigana_init");