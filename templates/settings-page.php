<?php
/**
 * Template: Settings > URL Blocker page.
 *
 * Variables made available by urlb_render_settings_page():
 *   string $blocked_urls   Newline-separated list of blocked relative paths.
 *   string $redirect_type  'custom' = 302 to a URL, '404' = serve the WP 404 template.
 *   string $redirect_url   Destination URL when redirect_type is 'custom'.
 *   string $exclude_admins '1' = admins are exempt, '0' = admins are blocked too.
 *
 * @package URLBlocker
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
	<h1><?php esc_html_e( 'PathGuard Redirects Settings', 'pathguard-redirects' ); ?></h1>

	<?php if ( '1' === filter_input( INPUT_GET, 'updated', FILTER_SANITIZE_NUMBER_INT ) ) : ?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Settings saved.', 'pathguard-redirects' ); ?></p>
		</div>
	<?php endif; ?>

	<form method="post" action="">
		<?php wp_nonce_field( 'urlb_save_settings', 'urlb_nonce' ); ?>

		<table class="form-table" role="presentation">

			<tr>
				<th scope="row">
					<label for="urlb_blocked_urls">
						<?php esc_html_e( 'Blocked URLs', 'pathguard-redirects' ); ?>
					</label>
				</th>
				<td>
					<textarea
						id="urlb_blocked_urls"
						name="urlb_blocked_urls"
						rows="10"
						cols="60"
						class="large-text code"
						placeholder="/secret-page/
/members-only/
/private/"
					><?php echo esc_textarea( $blocked_urls ); ?></textarea>
					<p class="description">
						<?php esc_html_e( 'Enter one relative URL per line (e.g. /secret-page/). The path is matched exactly, with or without a trailing slash.', 'pathguard-redirects' ); ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="urlb_redirect_type">
						<?php esc_html_e( 'Redirect Action', 'pathguard-redirects' ); ?>
					</label>
				</th>
				<td>
					<select id="urlb_redirect_type" name="urlb_redirect_type">
						<option value="custom" <?php selected( $redirect_type, 'custom' ); ?>>
							<?php esc_html_e( 'Custom URL (302 redirect)', 'pathguard-redirects' ); ?>
						</option>
						<option value="404" <?php selected( $redirect_type, '404' ); ?>>
							<?php esc_html_e( 'Not Found (404 page)', 'pathguard-redirects' ); ?>
						</option>
					</select>
					<p class="description">
						<?php esc_html_e( 'Choose what happens when a blocked URL is requested.', 'pathguard-redirects' ); ?>
					</p>
				</td>
			</tr>

			<tr id="urlb_custom_url_row">
				<th scope="row">
					<label for="urlb_redirect_url">
						<?php esc_html_e( 'Redirect Destination URL', 'pathguard-redirects' ); ?>
					</label>
				</th>
				<td>
					<input
						type="url"
						id="urlb_redirect_url"
						name="urlb_redirect_url"
						value="<?php echo esc_attr( $redirect_url ); ?>"
						class="regular-text"
						placeholder="https://example.com or /home"
					/>
					<p class="description">
						<?php esc_html_e( 'Visitors will be sent here with a 302 redirect. Only used when "Custom URL" is selected above.', 'pathguard-redirects' ); ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php esc_html_e( 'Exclude Admins', 'pathguard-redirects' ); ?>
				</th>
				<td>
					<label for="urlb_exclude_admins">
						<input
							type="checkbox"
							id="urlb_exclude_admins"
							name="urlb_exclude_admins"
							value="1"
							<?php checked( '1', $exclude_admins ); ?>
						/>
						<?php esc_html_e( 'Do not block logged-in administrators (users with the "manage_options" capability).', 'pathguard-redirects' ); ?>
					</label>
				</td>
			</tr>

		</table>

		<?php submit_button( __( 'Save Settings', 'pathguard-redirects' ), 'primary', 'urlb_save' ); ?>
	</form>
</div>

<script>
( function () {
	var select    = document.getElementById( 'urlb_redirect_type' );
	var customRow = document.getElementById( 'urlb_custom_url_row' );

	function toggleCustomRow() {
		customRow.style.display = ( 'custom' === select.value ) ? '' : 'none';
	}

	select.addEventListener( 'change', toggleCustomRow );
	toggleCustomRow();
}() );
</script>
