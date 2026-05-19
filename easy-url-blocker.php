<?php
/**
 * Plugin Name:       Easy URL Blocker
 * Description:       Block specific relative URLs and redirect visitors to a custom destination. Admins are never redirected.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Samrat Hossen
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       easy-url-blocker
 *
 * @package EasyURLBlocker
 */

defined( 'ABSPATH' ) || exit;

/** Absolute path to this plugin's root directory (with trailing slash). */
define( 'URLB_PATH', plugin_dir_path( __FILE__ ) );

require_once URLB_PATH . 'includes/AdminSettings.php';
require_once URLB_PATH . 'includes/URLB_Blocker.php';

use URLBlocker\AdminSettings;
use URLBlocker\URLB_Blocker;

register_activation_hook( __FILE__, array( AdminSettings::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( AdminSettings::class, 'deactivate' ) );

new AdminSettings();
new URLB_Blocker();
