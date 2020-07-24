<?php

//Enable CoLabsSEO on these custom Post types
//$seo_post_types = array('post','page');
//define("SEOPOSTTYPES", serialize($seo_post_types));

//Global options setup
add_action('init','colabs_global_options');
function colabs_global_options(){
	// Populate CoLabsThemes option in array for use in theme
	global $colabs_options;
	$colabs_options = get_option('colabs_options');
}

add_action('admin_head','colabs_options');  
if (!function_exists('colabs_options')) {
function colabs_options(){
	
// VARIABLES
$themename = "Modernizm";
$manualurl = 'http://colorlabsproject.com';
$shortname = "colabs";

//Access the WordPress Categories via an Array
$colabs_categories = array();  
$colabs_categories_obj = get_categories('hide_empty=0');
foreach ($colabs_categories_obj as $colabs_cat) {
    $colabs_categories[$colabs_cat->cat_ID] = $colabs_cat->cat_name;}
//$categories_tmp = array_unshift($colabs_categories, "Select a category:");

//Access the WordPress Pages via an Array
$colabs_pages = array();
$colabs_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($colabs_pages_obj as $colabs_page) {
    $colabs_pages[$colabs_page->ID] = $colabs_page->post_name; }
//$colabs_pages_tmp = array_unshift($colabs_pages, "Select a page:");       

//Stylesheets Reader
$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();
if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) {
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }
    }
}

//Access the WordPress Categories via an Array
$colabs_portfolios = array();  
$colabs_portfolios_obj = get_categories('hide_empty=0&taxonomy=category_portfolio');
foreach ($colabs_portfolios_obj as $colabs_tax) {
    $colabs_portfolios[$colabs_tax->cat_ID] = $colabs_tax->cat_name;}
//$portfolios_tmp = array_unshift($colabs_portfolios, "Select a portfolio category:");

//More Options
$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");

$other_entries_10 = array("Select a number:","1","2","3","4","5","6","7","8","9","10");

$other_entries_4 = array("Select a number:","1","2","3","4");

// THIS IS THE DIFFERENT FIELDS
$options = array();

// General Settings
$options[] = array( "name" => "General Settings",
					"type" => "heading",
					"icon" => "general");

/*$options[] = array( "name" => "Custom Favicon",
					"desc" => "Upload a 16x16px ico image that will represent your website's favicon. Favicon/bookmark icon will be shown at the left of your blog's address in visitor's internet browsers.",
					"id" => $shortname."_custom_favicon",
					"std" => trailingslashit( get_bloginfo('template_url') ) . "images/favicon.png",
					"type" => "upload"); */
					
$options[] = array( "name" => "Custom Logo Header",
					"desc" => "Upload a logo for your theme at the header, or specify an image URL directly. Best image size in 153x51 px",
					"id" => $shortname."_logo_head",
					"std" => trailingslashit( get_bloginfo('template_url') ) . "images/logo.png",
					"type" => "upload");
					
/* // Styling Options */
$options[] = array( "name" => "Styling Options",
					"type" => "heading",
					"icon" => "styling");
                    
$options[] = array( "name" =>  "Background Color",
					"desc" => "Pick a custom color for background color of the theme e.g. #697e09",
					"id" => "colabs_body_color",
					"std" => "#EEEEEE",
					"type" => "color");
                    
// FrontPage Options
$options[] = array( "name" => "FrontPage Settings",
					"type" => "heading",
					"icon" => "home");
					
$options[] = array( "name" => "Front Page Headline Section",
					"desc" => "Select headline category.",
					"id" => $shortname."_cat_headline",
					"type" => "select2",
					"options" => $colabs_categories );
					
$options[] = array( "name" => "Front Page Featured Section",
					"desc" => "Select featured category.",
					"id" => $shortname."_cat_featured",
					"type" => "select2",
					"options" => $colabs_categories );


/* //Social Settings	 */				
$options[] = array( "name" => "Social Networking",
					"icon" => "misc",
					"type" => "heading");

$options[] = array( "name" => "Twitter",
					"desc" => "Enter your Twitter URL",
					"id" => $shortname."_social_twitter",
					"std" => "http://twitter.com/colorlabs",
					"type" => "text");

$options[] = array( "name" => "Facebook",
					"desc" => "Enter your Facebook profile URL",
					"id" => $shortname."_social_facebook",
					"std" => "http://www.facebook.com/colorlabs",
					"type" => "text");

$options[] = array( "name" => "LinkedIn",
					"desc" => "Enter your LinkedIn profile URL",
					"id" => $shortname."_social_linkedin",
					"std" => "http://www.linkedin.com/colorlabs",
					"type" => "text");   

$options[] = array( "name" => "Enable/Disable Social Share Button",
					"desc" => "Select which social share button you would like to enable on single post & pages.",
					"id" => $shortname."_single_share",
					"std" => array("fblike","twitter","google_plusone"),
					"type" => "multicheck2",
                    "class" => "",
					"options" => array(
                                    "fblike" => "Facebook Like Button",                                    
                                    "twitter" => "Twitter Share Button",
                                    "google_plusone" => "Google +1 Button",
                                )
                    ); 					

// Open Graph Settings
$options[] = array( "name" => "Open Graph Settings",
					"type" => "heading",
					"icon" => "graph");

$options[] = array( "name" => "Open Graph",
					"desc" => "Enable or disable Open Graph Meta tags.",
					"id" => $shortname."_og_enable",
					"type" => "select2",
                    "std" => "",
                    "class" => "collapsed",
					"options" => array("" => "Enable", "disable" => "Disable") );

$options[] = array( "name" => "Site Name",
					"desc" => "Open Graph Site Name ( og:site_name ).",
					"id" => $shortname."_og_sitename",
					"std" => "",
                    "class" => "hidden",
					"type" => "text");

$options[] = array( "name" => "Admin",
					"desc" => "Open Graph Admin ( fb:admins ).",
					"id" => $shortname."_og_admins",
					"std" => "",
                    "class" => "hidden",
					"type" => "text");

$options[] = array( "name" => "Image",
					"desc" => "You can put the url for your Open Graph Image ( og:image ).",
					"id" => $shortname."_og_img",
					"std" => "",
                    "class" => "hidden last",
					"type" => "text");

//Dynamic Images 					                   
$options[] = array( "name" => "Thumbnail Settings",
					"type" => "heading",
					"icon" => "image");
                    
$options[] = array( "name" => "WordPress Featured Image",
					"desc" => "Use WordPress Featured Image for post thumbnail.",
					"id" => $shortname."_post_image_support",
					"std" => "true",
					"class" => "collapsed",
					"type" => "checkbox");

$options[] = array( "name" => "WordPress Featured Image - Dynamic Resize",
					"desc" => "Resize post thumbnail dynamically using WordPress native functions (requires PHP 5.2+).",
					"id" => $shortname."_pis_resize",
					"std" => "true",
					"class" => "hidden",
					"type" => "checkbox");
                    
$options[] = array( "name" => "WordPress Featured Image - Hard Crop",
					"desc" => "Original image will be cropped to match the target aspect ratio.",
					"id" => $shortname."_pis_hard_crop",
					"std" => "true",
					"class" => "hidden last",
					"type" => "checkbox");
                    
$options[] = array( "name" => "TimThumb Image Resizer",
					"desc" => "Enable timthumb.php script which dynamically resizes images added thorugh post custom field.",
					"id" => $shortname."_resize",
					"std" => "true",
					"type" => "checkbox");
                    
$options[] = array( "name" => "Automatic Thumbnail",
					"desc" => "Generate post thumbnail from the first image uploaded in post (if there is no image specified through post custom field or WordPress Featured Image feature).",
					"id" => $shortname."_auto_img",
					"std" => "true",
					"type" => "checkbox");
                    
$options[] = array( "name" => "Thumbnail Image in RSS Feed",
					"desc" => "Add post thumbnail to RSS feed article.",
					"id" => $shortname."_rss_thumb",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => "Thumbnail Image Dimensions",
					"desc" => "Enter an integer value i.e. 250 for the desired size which will be used when dynamically creating the images.",
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array( 
									array(  'id' => $shortname. '_thumb_w',
											'type' => 'text',
											'std' => 100,
											'meta' => 'Width'),
									array(  'id' => $shortname. '_thumb_h',
											'type' => 'text',
											'std' => 100,
											'meta' => 'Height')
								  ));

// Analytics ID, RSS feed
$options[] = array( "name" => "Analytics ID, RSS feed",
					"type" => "heading",
					"icon" => "statistics");
                    
$options[] = array( "name" => "Google Analytics",
					"desc" => "Manage your website statistics with Google Analytics, put your Analytics Code here. ",
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea");

$options[] = array( "name" => "Feedburner URL",
					"desc" => "Feedburner URL. This will replace RSS feed link. Start with http://.",
					"id" => $shortname."_feedlinkurl",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Feedburner Comments URL",
					"desc" => "Feedburner URL. This will replace RSS comment feed link. Start with http://.",
					"id" => $shortname."_feedlinkcomments",
					"std" => "",
					"type" => "text");

// Footer Settings
$options[] = array( "name" => "Footer Settings",
					"type" => "heading",
					"icon" => "footer");    

$options[] = array( "name" => "Enable / Disable Custom Credit (Right)",
					"desc" => "Activate to add custom credit on footer area.",
					"id" => $shortname."_footer_credit",
					"class" => "collapsed",
					"std" => "false",
					"type" => "checkbox");    

$options[] = array( "name" => "Footer Credit",
                    "desc" => "You can customize footer credit on footer area here.",
                    "id" => $shortname."_footer_credit_txt",
                    "std" => "",
					"class" => "hidden last",                    
                    "type" => "textarea");
/* Contact Form */
$options[] = array( "name" => "Contact Form",
					"type" => "heading",
					"icon" => "contact");    
$options[] = array( "name" => "Destination Email Address",
					"desc" => "All inquiries made by your visitors through the Contact Form page will be sent to this email address.",
					"id" => $shortname."_contactform_email",
					"std" => "",
					"type" => "text"); 

// Gallery Page
$options[] = array( "name" => "Gallery Page",
					"type" => "heading",
					"icon" => "general");
					
$options[] = array( "name" => "Showpost limit",
					"desc" => "Enter the limit of post to show at your gallery page.",
					"id" => $shortname."_limit_gallery",
					"std" => "15",
					"type" => "text"); 
					
$options[] = array( "name" => "Custom No Image",
					"desc" => "Upload a no image for your theme at the gallery, or specify an image URL directly. Best image size in 138x91 px.",
					"id" => $shortname."_custom_noimage",
					"std" => trailingslashit( get_bloginfo('template_url') ) . "images/noimage.png",
					"type" => "upload"); 


// Add extra options through function
if ( function_exists("colabs_options_add") )
	$options = colabs_options_add($options);

if ( get_option('colabs_template') != $options) update_option('colabs_template',$options);      
if ( get_option('colabs_themename') != $themename) update_option('colabs_themename',$themename);   
if ( get_option('colabs_shortname') != $shortname) update_option('colabs_shortname',$shortname);
if ( get_option('colabs_manual') != $manualurl) update_option('colabs_manual',$manualurl);


// CoLabs Metabox Options
// Start name with underscore to hide custom key from the user
$colabs_metaboxes = array();
$colabs_metabox_settings = array();
global $post;

    //Metabox Settings
    $colabs_metabox_settings['post'] = array(
                                'id' => 'colabsthemes-settings',
								'title' => 'ColorLabs' . __( ' Image/Video Settings', 'colabsthemes' ),
								'callback' => 'colabsthemes_metabox_create',
								'page' => 'post',
								'context' => 'normal',
								'priority' => 'high',
                                'callback_args' => ''
								);
                                    

	

if ( ( get_post_type() == 'post') || ( !get_post_type() ) ) {
	$colabs_metaboxes[] = array (  "name"  => $shortname."_single_top",
					            "std"  => "Image",
					            "label" => "Item to Show",
					            "type" => "radio",
					            "desc" => "Choose Image/Embed Code to appear at the single top.",
								"options" => array(	"none" => "None",
													"single_image" => "Image",
													"single_video" => "Embed" ));
	$colabs_metaboxes[] = array (	"name" => "image",
								"label" => "Post Custom Image",
								"type" => "upload",
                                "class" => "single_image",
								"desc" => "Upload an image or enter an URL.");
	
	$colabs_metaboxes[] = array (  "name"  => $shortname."_embed",
					            "std"  => "",
					            "label" => "Video Embed Code",
					            "type" => "textarea",
                                "class" => "single_video",
					            "desc" => "Enter the video embed code for your video (YouTube, Vimeo or similar)");
					            
} // End post



// Add extra metaboxes through function
if ( function_exists("colabs_metaboxes_add") ){
	$colabs_metaboxes = colabs_metaboxes_add($colabs_metaboxes);
    }
if ( get_option('colabs_custom_template') != $colabs_metaboxes){
    update_option('colabs_custom_template',$colabs_metaboxes);
    }
if ( get_option('colabs_metabox_settings') != $colabs_metabox_settings){
    update_option('colabs_metabox_settings',$colabs_metabox_settings);
    }
     
}
}



?>