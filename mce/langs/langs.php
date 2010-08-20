<?php
//ロケールの設定
$yomigana_locale = ( '' == get_locale() ) ? 'en' : strtolower( substr(get_locale(), 0, 2) );

$lang_file = dirname(__FILE__) . '/' . $yomigana_locale . '_dlg.js';

if ( is_file($lang_file) && is_readable($lang_file) )
	$strings = file_get_contents($lang_file);
else {
	$strings = file_get_contents(dirname(__FILE__) . '/en_dlg.js');
	$strings = preg_replace( '/([\'"])en\./', '$1'.$yomigana_locale.'.', $strings, 1 );
}
