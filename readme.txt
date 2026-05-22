=== PathGuard Redirects ===
Contributors:      emily50
Tags:              redirect, url-blocker, access-control, security, block-pages
Requires at least: 5.8
Tested up to:      6.9
Stable tag:        1.0.0
Requires PHP:      7.4
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Block specific relative URLs on your site and redirect visitors to a custom destination or your theme's 404 page.

== Description ==

URL Blocker is a lightweight, developer-friendly plugin that lets site administrators block any relative URL on their WordPress site and control exactly what happens when a visitor tries to access it.

**How it works**

Add the relative paths you want to block (one per line) and choose what should happen when someone visits them:

* **Custom URL redirect** — send visitors to any destination URL with a 302 redirect.
* **404 Not Found** — serve your theme's native 404 page with a proper HTTP 404 status header (no redirect, the URL stays the same).

**Key features**

* Block any number of relative paths (e.g. `/secret-page/`, `/members-only/`).
* Choose the redirect action per-site: custom URL or 404 page.
* **Exclude Admins** — logged-in administrators are bypassed by default so they always have access. The option can be unchecked to restrict admins too.
* One-click access via the **Settings** link on the Plugins list page.
* All plugin data is removed from the database automatically when the plugin is deactivated.
* Paths matched with and without trailing slash — `/secret-page` and `/secret-page/` both work.
* URL-encoded paths are decoded before matching, preventing bypass attempts like `/%73ecret-page/`.

**Security**

* CSRF protection on every save using WordPress nonces.
* Strict capability check (`manage_options`) before processing any form data.
* All input is sanitised (`sanitize_textarea_field`, `esc_url_raw`, `sanitize_key`).
* All output is escaped (`esc_textarea`, `esc_attr`, `esc_html_e`).
* Uses `wp_safe_redirect` to prevent open-redirect abuse.

== Installation ==

1. Upload the `pathguard-redirects` folder to `/wp-content/plugins/`.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Go to **Settings → URL Blocker** (or click the **Settings** link on the Plugins page).
4. Enter the relative URLs you want to block, choose a redirect action, and click **Save Settings**.

== Frequently Asked Questions ==

= What URL format should I use in the blocked URLs list? =

Enter relative paths starting with `/`, one per line. Example:

`/secret-page/`
`/members-only/`
`/private-area/`

Do not include the domain name. Paths are matched with or without a trailing slash, so `/secret-page` and `/secret-page/` are treated as the same rule.

= Will administrators be blocked? =

No — by default the **Exclude Admins** option is enabled, which means logged-in users with the `manage_options` capability can always access blocked URLs. You can uncheck this option to apply blocking to administrators as well.

= What is the difference between the two redirect actions? =

* **Custom URL (302 redirect)** — the visitor's browser is redirected to the URL you specify. The blocked URL disappears from the address bar.
* **Not Found (404 page)** — the browser stays on the blocked URL but receives an HTTP 404 status and sees your theme's 404 template. No redirect occurs.

= What happens if I choose "Custom URL" but leave the destination field blank? =

The plugin falls back to serving the 404 page so the blocked URL is never accidentally left accessible.

= Does this plugin affect REST API or admin requests? =

No. The block logic runs on the `template_redirect` hook which only fires for standard frontend page requests. REST API, WP-CLI, and admin requests are unaffected.

= Will my settings be lost if I deactivate the plugin? =

Yes. The plugin removes all its stored options from the database on deactivation. This keeps your database clean. If you need to keep your settings, do not deactivate — simply disable blocking by leaving the blocked URLs list empty.

= Does the plugin block query strings? =

No. Only the path portion of the URL is compared (e.g. `/secret-page/`). Query strings like `?preview=true` are ignored, which means `/secret-page/?anything=value` is still blocked by the rule `/secret-page/`.

== Screenshots ==

1. The URL Blocker settings page showing the blocked URLs list, redirect action selector, and Exclude Admins option.
2. The Settings action link on the WordPress Plugins list page.

== Changelog ==

= 1.0.0 =
* Initial release.
* Block relative URLs with per-line textarea input.
* Redirect action: Custom URL (302) or 404 page.
* Exclude Admins option, pre-enabled on activation.
* Settings link on the Plugins list page.
* Automatic database cleanup on deactivation.
* URL-encoding bypass protection via `rawurldecode()`.
* CSRF, capability, and sanitisation hardening.

== Upgrade Notice ==

= 1.0.0 =
Initial release — no upgrade steps required.
