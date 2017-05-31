<?php

	if(!defined('ABSPATH')) exit; //Don't run if accessed directly


	/**
	 *
	 * @package Webmention directory
	 *
	 * activate, deactivate and set options defaults
	 *
	*/


	function directory_activate() {
		add_option('exclusions', '');
	}

	function directory_deactivate() {
		delete_option('exclusions');
	}



    // register settings

	function directory_settings() {
		register_setting( 'directory-settings-group', 'exclusions' );
	}



	// create menu/settings page

	function directory_menu() {
		add_menu_page('Webmention Directory Settings', 'Webmention Directory', 'administrator', 'directory-settings', 'directory_settings_page', 'dashicons-editor-ul', 4 );
	}

	function directory_settings_page() { ?>
		<div class="wrap">
		<h2>Webmention Directory</h2>
		<p>The domains added below will be excluded from the webmentions directory.</p>
		<p>Add multiple domains as comma separated values without http(s):// e.g. domain.name</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'directory-settings-group' ); ?>
			<p>Domains to exclude:</p>
			<input type="text" name="exclusions" value="<?php echo esc_attr( get_option('exclusions') ); ?>" size="35" />
			<br />
			<?php submit_button(); ?>
		</form>

<?php } 

?>