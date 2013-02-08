<?php
if (!is_admin()) add_action( 'wp_print_scripts', 'colabsthemes_add_javascript' );

if (!function_exists('colabsthemes_add_javascript')) {

	function colabsthemes_add_javascript () {
		wp_deregister_script('jquery');
		wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js", false, null);
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'jtweetsanywhere', get_bloginfo('template_directory').'/includes/js/jquery.jtweetsanywhere.js', array('jquery'), false, true );
    wp_enqueue_script( 'splitter', get_bloginfo('template_directory').'/includes/js/jquery.easyListSplitter.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'placeholder', get_bloginfo('template_directory').'/includes/js/jquery.placeholder.js', array('jquery'), '1.0', true);
		wp_enqueue_script('flexslider', get_bloginfo('template_directory').'/includes/js/jquery.flexslider-min.js', array('jquery'), '1.0', true);
		wp_enqueue_script( 'script', get_bloginfo('template_directory').'/includes/js/script.js', array('jquery'), '1.0', true );

    	/* We add some JavaScript to pages with the comment form to support sites with threaded comments (when in use). */        
        if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
	} /* // End colabsthemes_add_javascript() */
	
} /* // End IF Statement */
?>
