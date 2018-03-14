<?php
/** @var Hametuha\Yomigana\Admin $this */
?>
<div class="wrap">

	<h2><?php esc_html_e( 'Ruby Setting', 'wp-yomigana' ) ?></h2>

	<form method="post">
		<?php wp_nonce_field( 'ruby_setting' );?>
		<table class="form-table">
			<tbody>
			<?php foreach ( $this->option as $tag => $setting ): ?>
				<tr>
					<th><?php echo esc_html( sprintf( __( 'Button of %s tag', 'wp-yomigana' ), $tag ) ); ?></th>
					<td>
						<?php
							$index = $this->get_row_index( $tag );
						?>
						<select name="<?php echo $tag; ?>_index">
							<option value="0"<?php if ( ! $index ) {
								echo ' selected="selected"';
							} ?>>
								<?php esc_html_e( 'Do not display', 'wp-yomigana' ) ?>
							</option>
							<?php for ( $i = 1; $i < 5; $i ++ ): ?>
								<option value="<?php echo $i; ?>"<?php if ( $index == $i ) {
									echo ' selected="selected"';
								} ?>>
									<?php printf( esc_html__( 'Display in row #%d', 'wp-yomigana' ), $i ) ?>
								</option>
							<?php endfor; ?>
						</select>
						<label>
						<?php esc_html_e( 'Priority', 'wp-yomigana' ) ?> <input type="number" step="1"
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

	<p class="description">
		WP-Yomigana 1.4より、<code>ruby</code>タグは十分に普及したと判断し、ルビをサポートしないブラウザの判別を行わないようになりました。
		<code>ruby</code>タグのサポート状況は <a href="https://caniuse.com/#search=ruby" target="_blank">Can I Use</a>を参考にしています。
	</p>

	<h3 class="yomigana-example-header"><?php esc_html_e( 'Example', 'wp-yomigana' ) ?></h3>

	<p class="description">
		<?php esc_html_e( 'Below are examples of extended tags. Style are depends on your theme.', 'wp-yomigana' ) ?>
	</p>

	<?php foreach ( [
		'ruby'  => '<ruby>鏖<rt>みなごろし</rt></ruby>のような難しい漢字にはルビを振ります。<ruby>聖剣<rt>エクスカリバー</rt></ruby>のようなグループルビも可能です。',
		'small' => 'smallタグは注釈として機能します。<small>※HTML5までは単に「小さい」という意味でしたが、注釈が追加されました。</small>',
		'q,cite'     => 'qタグはインライン引用として、citeタグは引用元を示すために使います。<cite>ウィトゲンシュタイン『論理哲学論考』</cite>によれば、<q>語り得ぬものについては沈黙せねばならない</q>そうです。',
	] as $tag => $html ) : ?>
		<div class="yomigana-example">
			<h4 class="yomigana-example-title"><?php echo esc_html( $tag ) ?></h4>
			<p class="yomigana-example-html">
				<?php echo $html ?>
			</p>
			<pre class="yomigana-example-code"><?php echo esc_html( $html ) ?></pre>
		</div>

	<?php endforeach; ?>
</div>
