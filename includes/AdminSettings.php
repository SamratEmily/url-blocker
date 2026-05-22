<?php
/**
 * Admin class: registers the settings page, handles form saves, and renders
 * the view by loading the template.
 *
 * @package URLBlocker
 */

namespace URLBlocker;

defined( 'ABSPATH' ) || exit;

/**
 * Handles all wp-admin integration: menu registration, form saving, and page rendering.
 */
class AdminSettings {

	/**
	 * Set default option values on plugin activation.
	 *
	 * Uses add_option() so existing values are never overwritten when the plugin
	 * is deactivated and re-activated.
	 */
	public static function activate(): void {
		\add_option( 'urlb_exclude_admins', '1' ); // admins are exempt by default
		\add_option( 'urlb_redirect_type', 'custom' );
		\add_option( 'urlb_blocked_urls', '' );
		\add_option( 'urlb_redirect_url', '' );
	}

	/**
	 * Remove all plugin options from the database on deactivation.
	 */
	public static function deactivate(): void {
		\delete_option( 'urlb_blocked_urls' );
		\delete_option( 'urlb_redirect_url' );
		\delete_option( 'urlb_redirect_type' );
		\delete_option( 'urlb_exclude_admins' );
	}

	/**
	 * Wire up WordPress hooks.
	 */
	public function __construct() {
		\add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		\add_action( 'admin_init', array( $this, 'handle_save' ) );
		\add_filter(
			'plugin_action_links_' . \plugin_basename( URLB_PATH . 'pathguard-redirects.php' ),
			array( $this, 'add_settings_link' )
		);
	}

	/**
	 * Prepend a "Settings" action link on the Plugins list page.
	 *
	 * @param array $links Existing action links for this plugin.
	 * @return array Modified links with Settings prepended.
	 */
	public function add_settings_link( array $links ): array {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			\esc_url( \admin_url( 'options-general.php?page=pathguard-redirects' ) ),
			\esc_html__( 'Settings', 'pathguard-redirects' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Register the Settings > URL Blocker submenu page.
	 */
	public function add_settings_page(): void {
		\add_options_page(
			\__( 'PathGuard Redirects', 'pathguard-redirects' ),
			\__( 'PathGuard Redirects', 'pathguard-redirects' ),
			'manage_options',
			'pathguard-redirects',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Process the settings form submission using the PRG pattern.
	 */
	public function handle_save(): void {
		if ( ! isset( $_POST['urlb_save'] ) ) {
			return;
		}

		// Capability check first — reject unauthorised users before touching the nonce.
		if ( ! \current_user_can( 'manage_options' ) ) {
			\wp_die( \esc_html__( 'You do not have permission to do that.', 'pathguard-redirects' ) );
		}

		\check_admin_referer( 'urlb_save_settings', 'urlb_nonce' );

		$blocked_urls = \sanitize_textarea_field( \wp_unslash( $_POST['urlb_blocked_urls'] ?? '' ) );

		$redirect_to = \esc_url_raw( \sanitize_text_field( \wp_unslash( $_POST['urlb_redirect_url'] ?? '' ) ) );

		$exclude_admins = isset( $_POST['urlb_exclude_admins'] ) ? '1' : '0';

		// Validate redirect type against allowed values.
		$allowed_types = array( '404', 'custom' );
		$raw_type      = isset( $_POST['urlb_redirect_type'] ) ? \sanitize_key( \wp_unslash( $_POST['urlb_redirect_type'] ) ) : 'custom';
		$redirect_type = in_array( $raw_type, $allowed_types, true ) ? $raw_type : 'custom';

		\update_option( 'urlb_blocked_urls', $blocked_urls );
		\update_option( 'urlb_redirect_url', $redirect_to );
		\update_option( 'urlb_redirect_type', $redirect_type );
		\update_option( 'urlb_exclude_admins', $exclude_admins );

		\wp_safe_redirect(
			\add_query_arg(
				array(
					'page'    => 'pathguard-redirects',
					'updated' => '1',
				),
				\admin_url( 'options-general.php' )
			)
		);
		exit;
	}

	/**
	 * Fetch saved values and load the settings page template.
	 */
	public function render_settings_page(): void {
		if ( ! \current_user_can( 'manage_options' ) ) {
			return;
		}

		$blocked_urls   = \get_option( 'urlb_blocked_urls', '' );
		$redirect_url   = \get_option( 'urlb_redirect_url', '' );
		$redirect_type  = \get_option( 'urlb_redirect_type', 'custom' );
		$exclude_admins = \get_option( 'urlb_exclude_admins', '1' );

		include URLB_PATH . 'templates/settings-page.php';
	}
}
