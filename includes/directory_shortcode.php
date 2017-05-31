<?php

	if(!defined('ABSPATH')) exit; //Don't run if accessed directly


	/**
	 *
	 * @package Webmention directory
	 *
	 * create shortcode & output
	 *
	*/


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

	//add addresses to exclude from list (without http(s)://), include empty string
/*	$exclusions = array (
            'colinwalker.blog',
            'colinwalker.me.uk',
            'micro.blog',
            '' ); */

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

?>