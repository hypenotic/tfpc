<?php 

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

1. Add specific IE hacks to HEAD
1. Add custom styling to HEAD
2. Add custom typograhpy to HEAD

-----------------------------------------------------------------------------------*/


add_action('wp_head','colabs_IE_head');					// Add specific IE styling/hacks to HEAD
add_action('colabs_head','colabs_custom_styling');			// Add custom styling to HEAD
//add_action('colabs_head','colabs_custom_typography');			// Add custom typography to HEAD


/*-----------------------------------------------------------------------------------*/
/* 1. Add specific IE hacks to HEAD */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_IE_head')) {
	function colabs_IE_head() {
?>
<!--[if IE 6]>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/pngfix.js"></script>
<![endif]-->	
<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* 2. Add Custom Styling to HEAD */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_custom_styling')) {
	function colabs_custom_styling() {
	
		global $colabs_options;
		
		$output = '';
		// Get options
		$body_color = $colabs_options['colabs_body_color'];
		$body_img = $colabs_options['colabs_body_img'];
		$body_repeat = $colabs_options['colabs_body_repeat'];
		$body_position = $colabs_options['colabs_body_pos'];
		$link = $colabs_options['colabs_link_color'];
		$hover = $colabs_options['colabs_link_hover_color'];
		$button = $colabs_options['colabs_button_color'];
			
		// Add CSS to output
		if ($body_color)
			$output .= 'body {background:'.$body_color.'}' . "\n";
			$output .= '#header {background:'.$body_color.'}'."\n";
			
		if ($body_img)
			$output .= 'body {background-image:url('.$body_img.')}' . "\n";

		if ($body_img && $body_repeat && $body_position)
			$output .= 'body {background-repeat:'.$body_repeat.'}' . "\n";

		if ($body_img && $body_position)
			$output .= 'body {background-position:'.$body_position.'}' . "\n";

		if ($link)
			$output .= 'a:link, a:visited {color:'.$link.'}' . "\n";

		if ($hover)
			$output .= 'a:hover, .post-more a:hover, .post-meta a:hover, .post p.tags a:hover, .nav a:hover, .nav li.current_page_item a, .nav li.current-menu-ancestor a, .nav li.current-menu-item a {color:'.$hover.'}' . "\n";

		if ($button) {
			$output .= 'a.button, a.comment-reply-link, #commentform #submit, #contact-page .submit, .searchform #submit {background:'.$button.';border-color:'.$button.'}' . "\n";
			$output .= 'a.button:hover, a.button.hover, a.button.active, a.comment-reply-link:hover, #commentform #submit:hover, #contact-page .submit:hover {background:'.$button.';opacity:0.9;}' . "\n";
		}
		
		// Output styles
		if (isset($output) && $output != '') {
			$output = strip_tags($output);
			$output = "<!-- ColorLabs Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
		}
			
	}
} 

/*-----------------------------------------------------------------------------------*/
/* 3. Add custom typograhpy to HEAD */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_custom_typography')) {
	function colabs_custom_typography() {
	
		// Get options
		global $colabs_options;
				
		// Reset	
		$output = '';
		
		// Add Text title and tagline if text title option is enabled
		if ( $colabs_options['colabs_texttitle'] == "true" ) {		
			
			if ( $colabs_options['colabs_font_site_title'] )
				$output .= '#logo .site-title a {'.colabs_generate_font_css($colabs_options['colabs_font_site_title']).'}' . "\n";	
			if ( $colabs_options['colabs_font_tagline'] )
				$output .= '#logo .site-description {'.colabs_generate_font_css($colabs_options['colabs_font_tagline']).'}' . "\n";	
		}

		if ( $colabs_options['colabs_typography'] == "true") {
			
			if ( $colabs_options['colabs_font_body'] )
				$output .= 'body { '.colabs_generate_font_css($colabs_options['colabs_font_body'], '1.5').' }' . "\n";	

			if ( $colabs_options['colabs_font_nav'] )
				$output .= '#navigation, #navigation .nav a { '.colabs_generate_font_css($colabs_options['colabs_font_nav'], '1').' }' . "\n";	

			if ( $colabs_options['colabs_font_post_title'] )
				$output .= '.post .title { '.colabs_generate_font_css($colabs_options['colabs_font_post_title']).' }' . "\n";	
		
			if ( $colabs_options['colabs_font_post_meta'] )
				$output .= '.post-meta { '.colabs_generate_font_css($colabs_options['colabs_font_post_meta']).' }' . "\n";	

			if ( $colabs_options['colabs_font_post_entry'] )
				$output .= '.entry, .entry p { '.colabs_generate_font_css($colabs_options['colabs_font_post_entry'], '1.5').' } h1, h2, h3, h4, h5, h6 { font-family:'.stripslashes($colabs_options['colabs_font_post_entry']['face']).'}'  . "\n";	

			if ( $colabs_options['colabs_font_widget_titles'] )
				$output .= '.widget h3 { '.colabs_generate_font_css($colabs_options['colabs_font_widget_titles']).' }'  . "\n";	
		}
		
		// Output styles
		if (isset($output) && $output != '') {
		
			// Enable Google Fonts stylesheet in HEAD
			if (function_exists('colabs_google_webfonts')) colabs_google_webfonts();
			
			$output = "\n<!-- ColorLabs Custom Typography -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
			
		}
			
	}
} 

if (!function_exists('colabs_generate_font_css')) {
	// Returns proper font css output
	function colabs_generate_font_css($option, $em = '1') {
		return 'font:'.$option["style"].' '.$option["size"].$option["unit"].'/'.$em.'em '.stripslashes($option["face"]).';color:'.$option["color"].';';
	}
}

// Output stylesheet and custom.css after custom styling
remove_action('wp_head', 'colabsthemes_wp_head');
add_action('colabs_head', 'colabsthemes_wp_head');

/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>