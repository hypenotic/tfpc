<?php

/*-----------------------------------------------------------------------------------*/
/* Start ColorLabs Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/
error_reporting(0);

// Set path to ColorLabs Framework and theme specific functions
$functions_path = TEMPLATEPATH . '/functions/';
$includes_path = TEMPLATEPATH . '/includes/';

// ColorLabs Admin
require_once ($functions_path . 'admin-init.php');			// Admin Init

// Theme specific functionality
require_once ($includes_path . 'theme-functions.php'); 		// Custom theme functions
require_once ($includes_path . 'theme-options.php'); 		// Custom theme options
require_once ($includes_path . 'theme-plugins.php');		// Theme specific plugins integrated in a theme
require_once ($includes_path . 'theme-actions.php');		// Theme actions & user defined hooks
require_once ($includes_path . 'theme-comments.php'); 		// Custom comments/pingback loop
require_once ($includes_path . 'theme-js.php');				// Load javascript in wp_head
require_once ($includes_path . 'theme-sidebar-init.php');   // Initialize widgetized areas
require_once ($includes_path . 'theme-widgets.php');		// Theme widgets


/*-----------------------------------------------------------------------------------*/
/* Custom */
/*-----------------------------------------------------------------------------------*/
if ( ! isset( $content_width ) )
	$content_width = 600;

function wpse_287931_register_categories_names_field() {
	register_rest_field( 'project',
		'categories_names',
		array(
			'get_callback'    => 'wpse_287931_get_categories_names',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}

add_action( 'rest_api_init', 'wpse_287931_register_categories_names_field' );

function wpse_287931_get_categories_names( $object, $field_name, $request ) {

	$formatted_categories = array();
	$categories = get_the_category( $object['id'] );
	foreach ($categories as $category) {
		$formatted_categories[] = $category->name;
	}
	return $formatted_categories;
}

function add_taxonomies_to_pages() {
	register_taxonomy_for_object_type('post_tag', 'page');
	register_taxonomy_for_object_type('category', 'page');
}
add_action('init', 'add_taxonomies_to_pages');

if (!is_admin()) {
	add_action('pre_get_posts', 'category_and_tag_archives');
}

function category_and_tag_archives($wp_query) {
	$my_post_array = array('post', 'page');

	if ($wp_query -> get('category_name') || $wp_query -> get('cat'))
		$wp_query -> set('post_type', $my_post_array);

	if ($wp_query -> get('tag'))
		$wp_query -> set('post_type', $my_post_array);
}

?>
