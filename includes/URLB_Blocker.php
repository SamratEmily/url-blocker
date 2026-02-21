<?php
/**
 * Blocker class: hooks into template_redirect and performs the 302 redirect
 * when the current request path matches a blocked URL.
 *
 * @package URLBlocker
 */

namespace URLBlocker;

defined( 'ABSPATH' ) || exit;

/**
 * Checks every frontend request against the blocked URL list and redirects matches.
 */
class URLB_Blocker {

	/**
	 * Wire up WordPress hooks.
	 */
	public function __construct() {
		\add_action( 'template_redirect', array( $this, 'maybe_redirect' ) );
	}

	/**
	 * Redirect the visitor if the current path is on the block list.
	 */
	public function maybe_redirect(): void {
		if ( '1' === \get_option( 'urlb_exclude_admins', '1' ) && \current_user_can( 'manage_options' ) ) {
			return;
		}

		$blocked_raw = \get_option( 'urlb_blocked_urls', '' );

		$blocked_urls = array_filter(
			array_map( 'trim', explode( "\n", $blocked_raw ) )
		);

		if ( empty( $blocked_urls ) ) {
			return;
		}

		// Sanitize the raw server variable before use.
		$raw_uri      = isset( $_SERVER['REQUEST_URI'] ) ? \sanitize_text_field( \wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$current_path = (string) \wp_parse_url( $raw_uri, PHP_URL_PATH );

		// Decode percent-encoded characters so /%73ecret-page/ matches /secret-page/.
		$current_path = rawurldecode( $current_path );

		// Normalise trailing slash so /page and /page/ both match.
		$current_path_normalised = \trailingslashit( $current_path );

		foreach ( $blocked_urls as $blocked ) {
			$blocked_normalised = \trailingslashit( $blocked );

			if ( $current_path === $blocked || $current_path_normalised === $blocked_normalised ) {
				$this->do_redirect();
			}
		}
	}

	/**
	 * Execute the configured redirect action: serve a 404 or issue a 302.
	 *
	 * Always ends with exit so a matched blocked URL is never served.
	 */
	private function do_redirect(): void {
		$redirect_type = \get_option( 'urlb_redirect_type', 'custom' );
		$redirect_to   = \get_option( 'urlb_redirect_url', '' );

		if ( 'custom' === $redirect_type && ! empty( $redirect_to ) ) {
			\wp_safe_redirect( $redirect_to, 302 );
			exit;
		}

		// '404' type, OR custom type with no URL configured — serve the theme 404 page.
		global $wp_query;
		$wp_query->set_404();
		\status_header( 404 );
		\nocache_headers();
		include \get_404_template();
		exit;
	}
}
