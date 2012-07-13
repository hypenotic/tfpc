<?php

// Load utility functions
require_once (TEMPLATEPATH . '/admin/utilities.php');
  
// Load main options panel file  
require_once (TEMPLATEPATH . '/admin/options.php');

// Enable translation
// Translations can be put in the /languages/ directory
load_theme_textdomain( 'themetrust', TEMPLATEPATH . '/languages' );

// Load custom widgets
require_once (TEMPLATEPATH . '/admin/widgets.php');



//////////////////////////////////////////////////////////////
// Get Options
/////////////////////////////////////////////////////////////
	
function ttrust_get_option($key) {	
	global $ttrust_options;	
	$ttrust_options = get_option('ttrust_options');
	
	$ttrust_defaults = array(		
		'ttrust_rss' => get_bloginfo('rss2_url'),		
		'ttrust_slideshow_transition' => 'fade',
		'ttrust_slideshow_speed' => 5,
		'ttrust_slideshow_enabled' => false			
	);
	
	foreach($ttrust_defaults as $k=>$v) {		
		if (!isset($ttrust_options[$k])  || $ttrust_options[$k] == NULL)
			$ttrust_options[$k] = $ttrust_defaults[$k];
	}	
	
	$ttrust_options['ttrust_logo'] = get_option('ttrust_logo');		
	
	
	if($key == 'all'){
		return $ttrust_options;
	}else{
		if(isset($ttrust_options[$key])){
			return $ttrust_options[$key];
		}else{
			return NULL;
		}
	}
}  



//////////////////////////////////////////////////////////////
// Theme Header
/////////////////////////////////////////////////////////////
	
add_action('wp_enqueue_scripts', 'ttrust_scripts');

function ttrust_scripts() {

	wp_enqueue_script('jquery');
	
	wp_enqueue_script('superfish', get_bloginfo('template_url').'/scripts/superfish/superfish.js', array('jquery'), '1.4.8', true);
	wp_enqueue_script('supersubs', get_bloginfo('template_url').'/scripts/superfish/supersubs.js', array('jquery'), '1.4.8', true);
	wp_enqueue_style('superfish', get_bloginfo('template_url').'/scripts/superfish/superfish.css', false, '1.4.8', 'all' );
	
	if(is_front_page()) :
		wp_enqueue_script('slideshow', get_bloginfo('template_url').'/scripts/slideshow/cycle.js', array('jquery'), '2.7.2', true);
	endif;
	
	if(is_active_widget(false,'','ttrust_flickr'))	
    	wp_enqueue_script('flickrfeed', get_bloginfo('template_url').'/scripts/jflickrfeed.js', array('jquery'), '0.8', true);

	wp_enqueue_script('fancybox', get_bloginfo('template_url').'/scripts/fancybox/jquery.fancybox-1.3.4.pack.js', array('jquery'), '1.3.4', true);
	wp_enqueue_style('fancybox', get_bloginfo('template_url').'/scripts/fancybox/jquery.fancybox-1.3.4.css', false, '1.3.4', 'all' );
	
	wp_enqueue_script('theme_trust', get_bloginfo('template_url').'/scripts/theme_trust.js', array('jquery'), '1.0', true);		

}

add_action('wp_head','ttrust_theme_head');

function ttrust_theme_head() { ?>
<meta name="generator" content="<?php global $ttrust_theme, $ttrust_version; echo $ttrust_theme.' '.$ttrust_version; ?>" />

<style type="text/css" media="screen">
<?php if(ttrust_get_option('ttrust_color_accent')) : ?>body {border-color: #<?php echo(ttrust_get_option('ttrust_color_accent')); ?>;} #mainNav li {border-color: #<?php echo(ttrust_get_option('ttrust_color_accent')); ?>!important;} .accent {color: #<?php echo(ttrust_get_option('ttrust_color_accent')); ?>;}<?php endif; ?>
<?php if(ttrust_get_option('ttrust_color_body_link')) : ?> a {color: #<?php echo(ttrust_get_option('ttrust_color_body_link')); ?>;}<?php endif; ?>
<?php if(ttrust_get_option('ttrust_color_body_link_hover')) : ?>a:hover {color: #<?php echo(ttrust_get_option('ttrust_color_body_link_hover')); ?>;}<?php endif; ?>
<?php echo(ttrust_get_option('ttrust_custom_css')); ?>
</style>

<!--[if IE]>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/ie.css" type="text/css" media="screen" />
<![endif]-->
<!--[if IE 7]>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/ie7.css" type="text/css" media="screen" />
<![endif]-->

<?php echo "\n".ttrust_get_option('ttrust_analytics')."\n"; ?>

<?php }


//////////////////////////////////////////////////////////////
// Custom Background Support
/////////////////////////////////////////////////////////////

if(function_exists('add_custom_background')) add_custom_background();



//////////////////////////////////////////////////////////////
// Theme Footer
/////////////////////////////////////////////////////////////

add_action('wp_footer','ttrust_footer');

function ttrust_footer() {
	if(is_front_page()) include(TEMPLATEPATH . '/scripts/slideshow/slideshow.php');
	echo "\n";	
}


//////////////////////////////////////////////////////////////
// Remove
/////////////////////////////////////////////////////////////

// #more from more-link
function ttrust_remove($content) {
	global $id;
	return str_replace('#more-'.$id.'"', '"', $content);
}
add_filter('the_content', 'ttrust_remove');


//////////////////////////////////////////////////////////////
// Custom Excerpt
/////////////////////////////////////////////////////////////
 
function wpt_strip_content_tags($content) {
    $content = strip_shortcodes($content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = preg_replace('/<img[^>]+./','',$content);
    $content = preg_replace('%<object.+?</object>%is', '', $content);
    return $content;
}


//////////////////////////////////////////////////////////////
// Pagination Styles
/////////////////////////////////////////////////////////////

add_action( 'wp_print_styles', 'ttrust_deregister_styles', 100 );
function ttrust_deregister_styles() {
	wp_deregister_style( 'wp-pagenavi' );
}
remove_action('wp_head', 'pagenavi_css');
remove_action('wp_print_styles', 'pagenavi_stylesheets');


//////////////////////////////////////////////////////////////
// Navigation Menus
/////////////////////////////////////////////////////////////

add_theme_support('menus');
register_nav_menu('main', 'Main Navigation Menu');

function default_nav() {
	require_once (TEMPLATEPATH . '/includes/default_nav.php');
}



//////////////////////////////////////////////////////////////
// Feature Images (Post Thumbnails)
/////////////////////////////////////////////////////////////

add_theme_support('post-thumbnails');

set_post_thumbnail_size(100, 100, true);
add_image_size('ttrust_small', 150, 150, true);
add_image_size('ttrust_med', 200, 130, true);
add_image_size('ttrust_slideshow', 580, 270);


//////////////////////////////////////////////////////////////
// Button Shortcode
/////////////////////////////////////////////////////////////

function ttrust_button($a) {
	extract(shortcode_atts(array(
		'label' 	=> 'Button Text',
		'id' 	=> '1',
		'url'	=> '',
		'target' => '_parent',		
		'size'	=> '',
		'ptag'	=> false
	), $a));
	
	$link = $url ? $url : get_permalink($id);	
	
	if($ptag) :
		return  wpautop('<a href="'.$link.'" target="'.$target.'" class="button '.$size.'">'.$label.'</a>');
	else :
		return '<a href="'.$link.'" target="'.$target.'" class="button '.$size.'">'.$label.'</a>';
	endif;
	
}

add_shortcode('button', 'ttrust_button');

//////////////////////////////////////////////////////////////
// Enable Shortcodes
/////////////////////////////////////////////////////////////
add_filter( 'slideshow_text', 'do_shortcode', 11 );


//////////////////////////////////////////////////////////////
// Custom More Link
/////////////////////////////////////////////////////////////

function more_link() {
	global $post;	
	if (strpos($post->post_content, '<!--more-->')) :
		$more_link = '<p class="moreLink"><a href="'.get_permalink().'" title="'.get_the_title().'">';
		$more_link .= '<span>'. __('Read More', 'themetrust') .'</span>';
		$more_link .= '</a></p>';
		echo $more_link;
	endif;
}



//////////////////////////////////////////////////////////////
// Meta Box
/////////////////////////////////////////////////////////////

$prefix = "_ttrust_";
$new_meta_boxes = array(
	
		"in_slideshow" => array(
    	"type" => "checkbox",
		"name" => $prefix."in_slideshow",
    	"std" => "",
    	"title" => __('Include in Slideshow','themetrust'),
    	"description" => __('Check this box to display in home page slideshow.','themetrust'))
);

function new_meta_boxes() {
global $post, $new_meta_boxes;

	foreach($new_meta_boxes as $meta_box) {
	
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'].'_value', true);
		if($meta_box_value == "") $meta_box_value = $meta_box['std'];
		
		echo'<div class="meta-field">';
		
		echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
		
		echo'<p><strong>'.$meta_box['title'].'</strong></p>';
		
		if(isset($meta_box['type']) && $meta_box['type'] == 'checkbox') {
		
			if($meta_box_value == 'true') {
				$checked = "checked=\"checked\"";
			} elseif($meta_box['std'] == "true") {	
					$checked = "checked=\"checked\"";	
			} else {
					$checked = "";
			}
		
			echo'<p class="clearfix"><input type="checkbox" class="meta-radio" name="'.$meta_box['name'].'_value" id="'.$meta_box['name'].'_value" value="true" '.$checked.' /> ';		
			echo'<label for="'.$meta_box['name'].'_value">'.$meta_box['description'].'</label></p><br />';		
		
		} elseif(isset($meta_box['type']) && $meta_box['type'] == 'textarea')  {			
			
			echo'<textarea rows="4" style="width:98%" name="'.$meta_box['name'].'_value" id="'.$meta_box['name'].'_value">'.$meta_box_value.'</textarea><br />';			
			echo'<p><label for="'.$meta_box['name'].'_value">'.$meta_box['description'].'</label></p><br />';			
		
		} else {
			
			echo'<input style="width:70%"type="text" name="'.$meta_box['name'].'_value" id="'.$meta_box['name'].'_value" value="'.$meta_box_value.'" /><br />';		
			echo'<p><label for="'.$meta_box['name'].'_value">'.$meta_box['description'].'</label></p><br />';			
		
		}
		
		echo'</div>';
		
	} // end foreach
		
	echo'<br style="clear:both" />';
	
} // end meta boxes

function create_meta_box() {

	global $ttrust_theme_name;
	
	if ( function_exists('add_meta_box') ) {
		add_meta_box( 'new-meta-boxes', $ttrust_theme_name. ' ' .__('Options','themetrust'), 'new_meta_boxes', 'post', 'normal', 'high' );
		add_meta_box( 'new-meta-boxes', $ttrust_theme_name. ' ' .__('Options','themetrust'), 'new_meta_boxes', 'page', 'normal', 'high' );		
	}
}

function save_postdata( $post_id ) {
global $post, $new_meta_boxes;

if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post->ID;
}

foreach($new_meta_boxes as $meta_box) {

	// Verify
	if(isset($_POST[$meta_box['name'].'_noncename'])){
		if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
	}

	if ( isset($_POST['post_type']) && 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ))
		return $post_id;
	} else {
	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;
	}

	$data = "";
	if(isset($_POST[$meta_box['name'].'_value'])){
		$data = $_POST[$meta_box['name'].'_value'];
	}


if(get_post_meta($post_id, $meta_box['name'].'_value') == "") 
	add_post_meta($post_id, $meta_box['name'].'_value', $data, true);
elseif($data != get_post_meta($post_id, $meta_box['name'].'_value', true))
	update_post_meta($post_id, $meta_box['name'].'_value', $data);
elseif($data == "" || $data == $meta_box['std'] )
	delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));
	
	} // end foreach
} // end save_postdata

add_action('admin_menu', 'create_meta_box');
add_action('save_post', 'save_postdata');



//////////////////////////////////////////////////////////////
// Comments
/////////////////////////////////////////////////////////////

function ttrust_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>		
	<li id="li-comment-<?php comment_ID() ?>">		
		
		<div class="comment <?php echo get_comment_type(); ?>" id="comment-<?php comment_ID() ?>">						
			
			<?php echo get_avatar($comment,'80',get_bloginfo('template_url').'/images/default_avatar.png'); ?>			
   	   			
   	   		<h5><?php comment_author_link(); ?></h5>
			<span class="date"><?php comment_date(); ?></span>
				
			<?php if ($comment->comment_approved == '0') : ?>
				<p><span class="message"><?php _e('Your comment is awaiting moderation.', 'themetrust'); ?></span></p>
			<?php endif; ?>
				
			<?php comment_text() ?>				
				
			<?php
			if(get_comment_type() != "trackback")
				comment_reply_link(array_merge( $args, array('add_below' => 'comment','reply_text' => '<span>'. __('Reply', 'themetrust') .'</span>', 'login_text' => '<span>'. __('Log in to reply', 'themetrust') .'</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'])))
			
			?>
				
		</div><!-- end comment -->
			
<?php
}

function ttrust_pings($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
		<li class="comment" id="comment-<?php comment_ID() ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?>
<?php
}
?>