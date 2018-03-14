<?php
/** @var \Hametuha\Yomigana\Bootstrap $this */

?>
<div>
	<p class="description"><?php esc_html_e( 'Enter ruby annotation.', 'wp-yomigana' ) ?></p>
	<table class="form-table">
		<tr>
			<td><label for="rubyBody"><?php esc_html_e( 'Parent String', 'wp-yomigana' ) ?></label></td>
			<td>
				<input id="rubyBody" name="rubyBody" type="text" value="" class="" readonly />
			</td>
		</tr>
		<tr>
			<td><label for="rubyText"><?php esc_html_e( 'Ruby', 'wp-yomigana' ) ?></label></td>
			<td>
				<input id="rubyText" name="rubyText" type="text" value="" class="" />
			</td>
		</tr>
	</table>
</div>
