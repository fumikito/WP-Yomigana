<?php
/** @var Hametuha\Yomigana\Bootstrap $this */
?>
<div class="wrap">
	<h2>ルビ設定</h2>
	<form method="post">
		<?php wp_nonce_field( 'ruby_setting' );?>
		<table class="form-table">
			<tbody>
			<?php foreach ( $this->option as $tag => $setting ): ?>
				<tr>
					<th><?php echo $tag; ?>タグのボタン</th>
					<td>
						<?php
							$index = $this->get_row_index( $tag );
						?>
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
						<label>
						表示順位: <input type="number" step="1"
						             name="<?php echo $tag ?>_column_index"
						             value="<?php echo $this->get_column_index( $tag ); ?>"/>
						</label>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php submit_button();?>
	</form>
	<h3>ルビをサポートしないブラウザに対して</h3>

	<p>
		ルビをサポートしないブラウザ（Opera, Firefox ver 38未満）に対してはhtmlタグにno-rubyクラスを付与します。
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