<?php


	if(!defined('ABSPATH')) exit; //Don't run if accessed directly


	/**
	 * Webmention directory
	 *
	 * @package Webmention directory
	 *
   	 * Plugin Name: Webmention Directory
   	 *
   	 * Description: Use a shortcode to display a list of authors who have engaged via webmentions
   	 *
   	 * Version: 0.5.0
   	 *
   	 * Author: Colin Walker
	*/


	add_action( 'plugins_loaded', 'directory_plugin' );

	function directory_plugin() {
		require_once('includes/directory_settings.php');
		require_once('includes/directory_shortcode.php');

		register_activation_hook( __FILE__, 'directory_activate' );
		register_deactivation_hook(__FILE__, 'directory_deactivate');
	}

	// add actions	

	add_action( 'admin_init', 'directory_settings' );
	add_action( 'admin_menu', 'directory_menu' );
	add_shortcode( 'wmdirectory', 'directory_shortcode' );

?>