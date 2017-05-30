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
   	 * Version: 0.2.0
   	 *
   	 * Author: Colin Walker
	*/


	add_action( 'admin_init', 'directory_settings' );
	add_action( 'admin_menu', 'directory_menu' );

    	// register settings

	function directory_settings() {
		register_setting( 'directory-settings-group', 'exclusions' );
	}

	// create menu/settings page

	function directory_menu() {
		add_menu_page('Webmention Directory Settings', 'Webmention Directory', 'administrator', 'directory-settings', 'directory_settings_page', 'dashicons-admin-generic', 4 );
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







function directory_shortcode() {

	global $wp_query;
	$args = array(
        	'status' => 'approve',
        	'order'   => 'DESC',
	/*	'type' 	=> 'webmention' */
	);

	$count = 0;		//initialise counter
	$check = 'no';		//set author check
	$people = array();	//initalise author array to check against
	$output = '';

	//get addresses to exclude
    
    $exclusions_str = get_option('exclusions');
    $exclusions_str = preg_replace( '/[, ]/', ',', $exclusions_str );
    $exclusions = explode( ',', $exclusions_str );

	$wp_query->comments = get_comments( $args );
    
	foreach ( $wp_query->comments as $comment ) {

		$wmreply = get_comment_meta( $comment->comment_ID, 'semantic_linkbacks_type', true );
		$author = get_comment_author( $comment->comment_ID );
		$author_url = get_comment_author_url( $comment->comment_ID );
		$parse = parse_url($author_url);
		$host = $parse['scheme'].'://'.$parse['host'];	//get just the domain portion of the URL

		//check if comment is either a webmention or webmention reply (via Semantic Linkbacks) but NOT from micro.blog
		if ( ($comment->comment_type == 'webmention' ) || ( $wmreply == 'reply' ) ) {

			//ensure author domain isn't excluded or name is not blank
			if (!in_array($parse['host'], $exclusions ) && ( '' != $author )) {

				//check if author has already been listed
				for ($x = 0; $x <= $count; $x++) {
    					if ( $author == $people[$x] ) {
						$check = 'yes';
					}
				} 
			
				//if author not yet listed add them to the array and display their link
				if ( $check != 'yes' ) {
					$people[$count] = $author;
					$output .= '<a class="directory-link" href="' . $host . '">'. $author . '</a><br/>';
				}
	
				$count += 1;
				$check = 'no';
			}

		}

	}


return $output;

}


add_shortcode( 'wmdirectory', 'directory_shortcode' );

?>