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

function handle_my_form()
{
	global $wpdb;
	if (isset($_POST['date']) && isset($_POST['occasion']) && isset($_POST['post_title']) && isset($_POST['author']) && isset($_POST['reviewer'])) {
		$table_name = $wpdb->prefix . 'demo_data';
		$date = sanitize_text_field($_POST['date']);
		$occasion = sanitize_text_field($_POST['occasion']);
		$post_title = sanitize_text_field($_POST['post_title']);
		$author = sanitize_text_field($_POST['author']);
		$reviewer = sanitize_text_field($_POST['reviewer']);
		$wpdb->insert(
			$table_name,
			array(
				'date' => $date,
				'occasion' => $occasion,
				'post_title' => $post_title,
				'author' => $author,
				'reviewer' => $reviewer
			)
		);
	}
}
add_action('init', 'handle_my_form');



function my_add_menu_pages()
{
	add_menu_page(
		__('Content Calendar', 'my-plugin'),
		'Content Calendar',
		'manage_options',
		'my-plugin',
		'content_calendar_callback',
		'dashicons-calendar-alt',
		6
	);
}
add_action('admin_menu', 'my_add_menu_pages');


function content_calendar_callback()
{
?>

	<!--Add Input fields on Schedule Content Page-->
	<div class="wrap">
		<h1>Schedule Content</h1>


		<form class="myform" method="post">
			<input type="hidden" name="action" value="cc_form">

			<label for="date">Date:</label>
			<input type="date" name="date" id="date" /><br />

			<label for="occasion">Occasion:</label>
			<input type="text" name="occasion" id="occasion" /><br />

			<label for="post_title">Post Title:</label>
			<input type="text" name="post_title" id="post_title" /><br />

			<label for="author">Author:</label>
			<select name="author" id="author" required>
				<?php
				$users = get_users(array(
					'role' => 'author',
					'fields' => array('ID', 'display_name')));
				foreach ($users as $user) {
					echo '<option value="' . $user->ID . '">' . $user->display_name . '</option>';
				}
				?>
			</select><br>

			<label for="reviewer">Reviewer:</label>
			<select name="reviewer" id="reviewer" required>
				<?php
				$admins = get_users(array(
					'role' => 'administrator',
					'fields' => array('ID', 'display_name')
				));
				foreach ($admins as $admin) {
					echo '<option value="' . $admin->ID . '">' . $admin->display_name . '</option>';
				}
				?>
			</select><br>

			<?php submit_button('Schedule Post'); ?>

		</form>
	</div>

<?php

	global $wpdb;
	$table_name = $wpdb->prefix . 'demo_data';

	$data = $wpdb->get_results("SELECT * FROM $table_name");
	echo '<div class="wrap">';
	echo '<table class="wp-list-table widefat fixed striped table-view-list">';
	echo '<thead><tr class="manage-column column-cb check-column"><th>ID</th><th>Date</th><th>Occasion</th><th>Post Title</th><th>Author</th><th>Reviewer</th></tr></thead>';
	foreach ($data as $row) {
		echo '<tr>';
		echo '<td>' . $row->id . '</td>';
		echo '<td>' . $row->date . '</td>';
		echo '<td>' . $row->occasion . '</td>';
		echo '<td>' . $row->post_title . '</td>';
		echo '<td>' . get_userdata($row->author)->display_name . '</td>';
		echo '<td>' . get_userdata($row->reviewer)->display_name . '</td>';
		echo '</tr>';
	}
	echo '</table>';
	echo '</div>';
}

function run_my_plugin()
{

	$plugin = new My_Plugin();
	$plugin->run();
}
run_my_plugin();
