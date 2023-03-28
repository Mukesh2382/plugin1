<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mukesh.com
 * @since      1.0.0
 *
 * @package    My_Plugin
 * @subpackage My_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    My_Plugin
 * @subpackage My_Plugin/admin
 * @author     Mukesh <mukesh@mukesh.com>
 */
class My_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in My_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The My_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/my-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in My_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The My_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/my-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}
	
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




function my_add_menu_pages()
{
	add_menu_page(
		__('Content Calendar', 'my-plugin'),
		'Content Calendar',
		'manage_options',
		'my-plugin',
		array($this,'content_calendar_callback'),
		'dashicons-calendar-alt',
		6
	);
}



function content_calendar_callback()
{
	include('partials/my-plugin-admin-display.php');
	
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

}
