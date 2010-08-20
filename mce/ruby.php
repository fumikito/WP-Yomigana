<?php

//WordPressの初期化
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).DIRECTORY_SEPARATOR."wp-load.php");

$tiny_mce_inc_dir = is_ssl() ? preg_replace("/^http:/", "https:", get_bloginfo("url")) : get_bloginfo("url");
$tiny_mce_inc_dir .= "/wp-includes/js/tinymce/";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#yomigana_dlg.title}</title>
	<script type="text/javascript" src="<?php echo $tiny_mce_inc_dir."tiny_mce_popup.js?v=".WP_YOMIGANA_VERSION; ?>"></script>
	<script type="text/javascript" src="<?php echo $tiny_mce_inc_dir."utils/form_utils.js?v=".WP_YOMIGANA_VERSION; ?>"></script>
	<script type="text/javascript" src="js/ruby.js?v=<?php echo WP_YOMIGANA_VERSION; ?>"></script>
	<link href="css/ruby.css?v=<?php echo WP_YOMIGANA_VERSION; ?>" rel="stylesheet" type="text/css" />
</head>
<body onload="yomiganaManager.onLoad(yomiganaManager);">
<form onsubmit="yomiganaManager.onSubmit(yomiganaManager);return false;" action="#">
	<div class="tabs">
		<ul>
			<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;">{#yomigana_dlg.desc}</a></span></li>
		</ul>
	</div>

	<div class="panel_wrapper">
		<div id="general_panel" class="panel current">
			<table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><label for="moji">{#yomigana_dlg.label_parent}</label></td>
                        <td class="nowrap">
                            <input id="moji" name="moji" type="text" value="" class="mceFocus" readonly="readonly" />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="yomi">{#yomigana_dlg.label_child}</label></td>
                        <td>
                        	<input id="yomi" name="yomi" type="text" value="" class="mceFocus" />
                        </td>
                    </tr>
                    <tr style="text-align:right">
                    	<td colspan="2"><input type="button" name="delete" id="delete" value="{#yomigana_dlg.label_delete}" onclick="yomiganaManager.onDelete(yomiganaManager);" disabled="disabled" /></td>
                    </tr>
            </table>
		</div>
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="submit" id="insert" name="insert" value="{#insert}" />
		</div>

		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>
	</div>
</form>
</body>
</html>
