<?php

// Register widgetized areas

if (!function_exists('the_widgets_init')) {
	function the_widgets_init() {
	    if ( !function_exists('register_sidebars') )
	        return;
    //Sidebar
register_sidebar( array(
	'name'					=> 'Right Sidebar',
	'description'		=> 'This widget will appear in right sidebar area',
	'before_title'	=> '<h6>',
	'after_title'		=> '</h6>',
	'before_widget'	=> '<div id="%1$s" class="widget %2$s column col2">',
	'after_widget'	=> '</div>'
) );
register_sidebar( array(
	'name'					=> 'Footer Sidebar',
	'description'		=> 'This widget will appear in footer widget area',
	'before_title'	=> '<h6>',
	'after_title'		=> '</h6>',
	'before_widget'	=> '<div id="%1$s" class="widget %2$s column col4">',
	'after_widget'	=> '</div>'
) );

    }
}

add_action( 'init', 'the_widgets_init' );


    
?>