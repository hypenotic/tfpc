<?php
/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Theme Version */
/*-----------------------------------------------------------------------------------*/
function colabs_version(){
    $theme_data = get_theme_data( get_template_directory() . '/style.css' );
    $theme_version = $theme_data['Version'];
	echo "\n<!-- Theme version -->\n";
    echo '<meta name="generator" content="'. get_option( 'colabs_themename').' '. $theme_version .'" />' ."\n";
}
// Add Generator meta tags
    add_action( 'wp_head', 'colabs_version' );
/*-----------------------------------------------------------------------------------*/
/* Load the required Admin Panel Files */
/*-----------------------------------------------------------------------------------*/
$functions_path = get_template_directory() . '/functions/';
require_once ($functions_path . 'admin-functions.php');				// Custom functions and plugins
require_once ($functions_path . 'admin-setup.php');					// Options panel variables and functions
require_once ($functions_path . 'admin-custom.php');                // Custom fields 
require_once ($functions_path . 'admin-interface.php');				// Admin Interfaces (options,framework, seo)
require_once ($functions_path . 'admin-seo.php');					// Admin Panel SEO controls
require_once ($functions_path . 'admin-medialibrary-uploader.php'); // Admin Panel Media Library Uploader Functions // 2011-28-05.
require_once ($functions_path . 'admin-readme.php');				// Admin Panel Readme Function
require_once ($functions_path . 'admin-hooks.php');					// Definition of CoLabsHooks
/*
if (get_option('framework_colabs_colabsnav') == "true")
	require_once ($functions_path . 'admin-custom-nav.php');		// CoLabs Custom Navigation
*/
require_once ($functions_path . 'admin-shortcodes.php');			// CoLabs Shortcodes
require_once ($functions_path . 'admin-shortcode-generator.php'); 	// Admin Panel Shortcode generator // 2011-05-27.
?>