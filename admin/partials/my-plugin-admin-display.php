<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://mukesh.com
 * @since      1.0.0
 *
 * @package    My_Plugin
 * @subpackage My_Plugin/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
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