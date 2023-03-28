<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://mukesh.com
 * @since             1.0.0
 * @package           My_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       my-plugin
 * Plugin URI:        https://my-plugin.com
 * Description:       This is a demo plugin to accept and display data.

 * Version:           1.0.0
 * Author:            Mukesh
 * Author URI:        https://mukesh.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       my-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('MY_PLUGIN_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-my-plugin-activator.php
 */
function activate_my_plugin()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-my-plugin-activator.php';
	My_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-my-plugin-deactivator.php
 */
function deactivate_my_plugin()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-my-plugin-deactivator.php';
	My_Plugin_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_my_plugin');
register_deactivation_hook(__FILE__, 'deactivate_my_plugin');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-my-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */


function create_my_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'demo_data';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) AUTO_INCREMENT,
		date date NOT NULL,
		occasion varchar(255) NOT NULL,
		post_title varchar(255) NOT NULL,
		author int(11) NOT NULL,
		reviewer varchar(255) NOT NULL,
		PRIMARY KEY  (id)
	  ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_my_table');



function run_my_plugin()
{

	$plugin = new My_Plugin();
	$plugin->run();
}
run_my_plugin();
