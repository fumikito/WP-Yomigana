<?php
/*
Plugin Name: WP-Yomigana
Plugin URI: https://wordpress.org/plugins/wp-yomigana/
Description: TinyMCEでルビを入力できるようにします。
Version: 1.3.0
Author: Takahashi Fumiki
Author URI: http://takahashifumiki.com
Lisence: MIT

This plugins owes a lot to TinyMCE Advanced, a WordPress plugin(http://wordpress.org/extend/plugins/tinymce-advanced/).

*/

if( version_compare(phpversion(), '5.3', '>=') ){
	require __DIR__.'/vendor/autoload.php';
	call_user_func(array('Hametuha\\Yomigana\\Bootstrap', 'get_instance'));
}else{
	die('WP-Yomigana requires PHP 5.3 and over.');
}
