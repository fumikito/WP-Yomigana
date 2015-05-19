<?php
/** @var \Hametuha\Yomigana\Bootstrap $this */
?>
<div>
	<p class="description"><?php $this->_e('引用文の出典を入力してください。空白の場合は設定されません。') ?></p>
	<table class="form-table">
		<tr>
			<td><label for="rubyBody"><?php $this->_e('親文字') ?></label></td>
			<td>
				<input id="rubyBody" name="rubyBody" type="text" value="" class="" readonly />
			</td>
		</tr>
		<tr>
			<td><label for="rubyText"><?php $this->_e('ルビ') ?></label></td>
			<td>
				<input id="rubyText" name="rubyText" type="text" value="" class="" />
			</td>
		</tr>
	</table>
</div>
