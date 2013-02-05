<?php
if (!is_admin()) add_action( 'wp_print_scripts', 'colabsthemes_add_javascript' );

if (!function_exists('colabsthemes_add_javascript')) {

	function colabsthemes_add_javascript () {
	
    wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'splitter', get_bloginfo('template_directory').'/includes/js/jquery.easyListSplitter.js', array('jquery') );
		wp_enqueue_script( 'placeholder', get_bloginfo('template_directory').'/includes/js/jquery.placeholder.js', array('jquery') );
		wp_enqueue_script('modernizer', get_bloginfo('template_directory').'/functions/js/modernizr.foundation.js', array(''));
		wp_enqueue_script('foundation', get_bloginfo('template_directory') . '/includes/js/foundation.min.js', array('modernizer'));
		wp_enqueue_script( 'script', get_bloginfo('template_directory').'/includes/js/script.js', array('jquery') );

    	/* We add some JavaScript to pages with the comment form to support sites with threaded comments (when in use). */        
        if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
	} /* // End colabsthemes_add_javascript() */
	
} /* // End IF Statement */
?>
