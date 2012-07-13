<?php

/*---------------------------------------------------------------------------------*/
/* Loads all the .php files found in /includes/widgets/ directory */
/*---------------------------------------------------------------------------------*/


include( TEMPLATEPATH . '/includes/widgets/widget-colabs-ad-sidebar.php' );
include( TEMPLATEPATH . '/includes/widgets/widget-colabs-adspace125.php' );
include( TEMPLATEPATH . '/includes/widgets/widget-colabs-embed.php' );
include( TEMPLATEPATH . '/includes/widgets/widget-colabs-flickr.php' );
include( TEMPLATEPATH . '/includes/widgets/widget-colabs-search.php' );
include( TEMPLATEPATH . '/includes/widgets/widget-colabs-twitter.php' );
include( TEMPLATEPATH . '/includes/widgets/widget-colabs-popular-post.php' );
include( TEMPLATEPATH . '/includes/widgets/widget-colabs-about.php' );
include( TEMPLATEPATH . '/includes/widgets/widget-colabs-recent-comments.php' );
include( TEMPLATEPATH . '/includes/widgets/widget-colabs-follow.php' );


/*---------------------------------------------------------------------------------*/
/* Deregister Default Widgets */
/*---------------------------------------------------------------------------------*/
if (!function_exists('colabs_deregister_widgets')) {
	function colabs_deregister_widgets(){
	    unregister_widget('WP_Widget_Search');    
		unregister_widget('WP_Widget_Recent_Comments');
	}
}
add_action('widgets_init', 'colabs_deregister_widgets');  


?>