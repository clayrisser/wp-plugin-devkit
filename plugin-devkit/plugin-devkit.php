<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://clayrisser.com/
 * @since             0.0.1
 * @package           Plugin_Devkit
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Devkit
 * Plugin URI:        https://wordpress.org/plugins/plugin-devkit/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           0.0.1
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Clay Risser
 * Author URI:        https://clayrisser.com/
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       plugin-devkit
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Load the composer autoloader when dependencies have been installed.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-devkit-activator.php
 */
function plugin_devkit_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-devkit-activator.php';
	Plugin_Devkit_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-devkit-deactivator.php
 */
function plugin_devkit_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-devkit-deactivator.php';
	Plugin_Devkit_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'plugin_devkit_activate' );
register_deactivation_hook( __FILE__, 'plugin_devkit_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-devkit.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function plugin_devkit_run() {

	$plugin = new Plugin_Devkit();
	$plugin->run();
}
plugin_devkit_run();
