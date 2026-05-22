# URL Blocker

A lightweight WordPress plugin that lets administrators block specific relative URLs and redirect visitors to a custom destination or a 404 page.

## Features

- **Block any relative path** — enter paths one per line (e.g. `/secret-page/`, `/members-only/`)
- **Two redirect actions** — 302 redirect to a custom URL, or serve the theme's native 404 page
- **Exclude Admins** — administrators are bypassed by default; can be unchecked to restrict them too
- **Settings link** — one-click access from the WordPress Plugins list page
- **Clean uninstall** — all plugin data is deleted from the database on deactivation
- **Trailing-slash normalisation** — `/secret-page` and `/secret-page/` both match the same rule
- **URL-encoding bypass protection** — `/%73ecret-page/` is decoded and matched correctly

## Requirements

| Requirement       | Version  |
|-------------------|----------|
| WordPress         | >= 5.8   |
| PHP               | >= 7.4   |

## Installation

1. Clone or download this repository into `/wp-content/plugins/pathguard-redirects/`.
2. Activate the plugin from **Plugins → Installed Plugins**.
3. Navigate to **Settings → PathGuard Redirects** (or click the **Settings** link on the Plugins page).
4. Add the paths to block, choose a redirect action, and click **Save Settings**.

## Directory Structure

```
pathguard-redirects/
├── pathguard-redirects.php          # Bootstrap: plugin header, constants, activation/deactivation hooks
├── includes/
│   ├── AdminSettings.php    # Admin menu, save handler, settings page renderer
│   └── URLB_Blocker.php     # Frontend redirect logic (template_redirect hook)
├── templates/
│   └── settings-page.php    # Settings page HTML template
├── README.md
└── readme.txt               # WordPress.org submission readme
```

## Settings

| Setting | Description |
|---|---|
| **Blocked URLs** | Newline-separated list of relative paths to block. |
| **Redirect Action** | `Custom URL` — 302 redirect to a URL you specify. `Not Found (404 page)` — serve the theme 404 template inline with a 404 status header. |
| **Redirect Destination URL** | The URL visitors are sent to when action is set to Custom URL. Falls back to 404 if left blank. |
| **Exclude Admins** | When checked (default), users with `manage_options` can always access blocked URLs. Uncheck to restrict admins too. |

## Security

| Measure | Implementation |
|---|---|
| CSRF protection | `wp_nonce_field` + `check_admin_referer` on every save |
| Authorisation | `current_user_can('manage_options')` checked before nonce |
| Input sanitisation | `sanitize_textarea_field`, `esc_url_raw`, `sanitize_key` |
| Output escaping | `esc_textarea`, `esc_attr`, `esc_html_e`, `selected`, `checked` |
| Safe redirect | `wp_safe_redirect` prevents open-redirect abuse |
| URL-encoding bypass | `rawurldecode()` applied to request path before comparison |
| Direct file access | `defined('ABSPATH') \|\| exit` in every PHP file |

## Changelog

### 1.0.0
- Initial release
- Block relative URLs via textarea input (one per line)
- Redirect action: Custom URL (302) or 404 page
- Exclude Admins toggle, pre-enabled on activation
- Settings link on the Plugins list page
- Automatic database cleanup on deactivation
- URL-encoding bypass protection
- CSRF, capability, and sanitisation hardening

## License

[GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html)
