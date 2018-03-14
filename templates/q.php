<?php
/** @var \Hametuha\Yomigana\Bootstrap $this */
?>
<div>
	<p class="description"><?php esc_html_e( 'Enter source of citation. If not set, cite attribute will be empty.', 'wp-yomigana' ) ?></p>
	<table class="form-table">
		<tr>
			<td><label for="citeText"><?php esc_html_e( 'Citation', 'wp-yomigana' ) ?></label></td>
			<td>
				<input id="citeText" name="citeText" type="text" value="" class="" readonly />
			</td>
		</tr>
		<tr>
			<td><label for="citeFrom"><?php esc_html_e( 'Source / URL', 'wp-yomigana' ) ?></label></td>
			<td>
				<input id="citeFrom" name="citeFrom" type="text" value="" class="" />
			</td>
		</tr>
	</table>
</div>
