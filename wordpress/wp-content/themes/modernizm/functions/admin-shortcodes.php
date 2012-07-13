<?php
/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

1. CoLabs Shortcodes 
  1.1 Output shortcode JS in footer (in development)
2. PulledQuote

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* 1. CoLabs Shortcodes  */
/*-----------------------------------------------------------------------------------*/

// Enable shortcodes in widget areas
add_filter( 'widget_text', 'do_shortcode' );

// Add stylesheet for shortcodes to HEAD (added to HEAD in admin-setup.php)
if ( !function_exists( 'colabs_shortcode_stylesheet' ) ) {
	function colabs_shortcode_stylesheet() {
		echo "<!-- CoLabs Shortcodes CSS -->\n";
		echo '<link href="'. get_bloginfo( 'template_directory') .'/functions/css/shortcodes.css" rel="stylesheet" type="text/css" />'."\n\n";	
	}
}

// Replace WP autop formatting
if (!function_exists( "colabs_remove_wpautop")) {
	function colabs_remove_wpautop($content) { 
		$content = do_shortcode( shortcode_unautop( $content ) ); 
		$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content);
		return $content;
	}
}

/*-----------------------------------------------------------------------------------*/
/* 1.1 Output shortcode JS in footer */
/*-----------------------------------------------------------------------------------*/

// Enqueue shortcode JS file.

add_action( 'init', 'colabs_enqueue_shortcode_js' );

function colabs_enqueue_shortcode_js () {

	if ( is_admin() ) {} else {

		wp_enqueue_script( 'colabs-shortcodes', get_template_directory_uri() . '/functions/js/shortcodes.js', array( 'jquery', 'jquery-ui-tabs' ), true );
		
	} // End IF Statement

} // End colabs_enqueue_shortcode_js()

// Check if option to output shortcode JS is active
if (!function_exists( "colabs_check_shortcode_js")) {
	function colabs_check_shortcode_js($shortcode) {
	   	$js = get_option( "colabs_sc_js" );
	   	if ( !$js ) 
	   		colabs_add_shortcode_js($shortcode);
	   	else {
	   		if ( !in_array($shortcode, $js) ) {
		   		$js[] = $shortcode;
	   			update_option( "colabs_sc_js", $js);
	   		}
	   	}
	}
}

// Add option to handle JS output
if (!function_exists( "colabs_add_shortcode_js")) {
	function colabs_add_shortcode_js($shortcode) {
		$update = array();
		$update[] = $shortcode;
		update_option( "colabs_sc_js", $update);
	}
}

// Output queued shortcode JS in footer
if (!function_exists( "colabs_output_shortcode_js")) {
	function colabs_output_shortcode_js() {
		$option = get_option( 'colabs_sc_js' );
		if ( $option ) {
		
			// Toggle JS output
			if ( in_array( 'toggle', $option) ) {
			   	
			   	$output = '
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery( ".colabs-sc-toggle-box").hide();
		jQuery( ".colabs-sc-toggle-trigger").click(function() {
			jQuery(this).next( ".colabs-sc-toggle-box").slideToggle(400);
		});
	});
</script>
';
				echo $output;
			}
			
			// Reset option
			delete_option( 'colabs_sc_js' );
		}
	}
}
add_action( 'wp_footer', 'colabs_output_shortcode_js' );

/*-----------------------------------------------------------------------------------*/
/* 2. PulledQuote - [pulledquote float="left"][/pulledquote]
/*-----------------------------------------------------------------------------------*/
/*

Optional arguments:
 - style: boxed 
 - float: left, right
 
*/
function colabs_shortcode_pulledquote($atts, $content = null) {
   	extract(shortcode_atts(array(	'style' => '',
   									'float' => ''), $atts));
   $class = '';
   if ( $style )
   		$class .= ' '.$style;
   if ( $float ){
   		$class .= ' '.$float;
   }else{
   		$class .= ' '.'left';
   }
   
   return '<span class="colabs-sc-pulledquote' . $class . '">' . colabs_remove_wpautop($content) . '</span>';
}
add_shortcode( 'pulledquote', 'colabs_shortcode_pulledquote' );

/*-----------------------------------------------------------------------------------*/
/* THE END */
/*-----------------------------------------------------------------------------------*/
?>