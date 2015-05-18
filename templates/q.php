<?php
/** @var \Hametuha\Yomigana\Bootstrap $this */
?>
<div>
	<p class="description"><?php $this->_e('引用文の出典を入力してください。空白の場合は設定されません。') ?></p>
	<table class="form-table">
		<tr>
			<td><label for="citeText"><?php $this->_e('引用文') ?></label></td>
			<td>
				<input id="citeText" name="citeText" type="text" value="" class="" readonly />
			</td>
		</tr>
		<tr>
			<td><label for="citeFrom"><?php $this->_e('出典 / URL') ?></label></td>
			<td>
				<input id="citeFrom" name="citeFrom" type="text" value="" class="" />
			</td>
		</tr>
	</table>
</div>
