<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- colabs_image - Get Image from custom field
    - vt_resize - Resize post thumbnail
    - getVimeoInfo() - get vimeo video thumbnail
    - get_redirect_url() - Get Redirect Link -- get Daily Motion thumbnail
    - colabs_get_youtube_video_image - Get thumbnail from YouTube
- colabs_get_embed - Get Video
- CoLabs Show Page Menu
- Get the style path currently selected
- Get page ID
- Tidy up the image source url
- Show image in RSS feed
- Show analytics code footer
- Browser detection body_class() output
- Twitter's Blogger.js output for Twitter widgets
- Template Detector
- CoLabs URL shortener
- SEO - colabs_title()
- SEO - Strip slashes from the display of the website/page title
- SEO - colabs_meta()
      - seo_add_custom() - Add Post Custom Settings
- CoLabs Text Trimmer
- Enable Home link in WP Menus
- Buy Themes page
- Detects the Charset of String and Converts it to UTF-8
- colabs_pagination()
- colabs_breadcrumbs()
-- colabs_breadcrumbs_get_parents()
-- colabs_breadcrumbs_get_term_parents()
- WordPress Admin Bar-related
-- Disable WordPress Admin Bar
-- Enhancements to the WordPress Admin Bar
- colabs_prepare_category_ids_from_option()
- Move tracking code from footer to header

//temporary function below
- get_admin_color

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* colabs_image - Get Image from custom field  */
/*-----------------------------------------------------------------------------------*/

/*
This function retrieves/resizes the image to be used with the post in this order:

1. Image passed through parameter 'src'
2. WP Post Thumbnail (if option activated)
3. Custom field
4. First attached image in post (if option activated)
5. First inline image in post (if option activated)

Resize options (enabled in options panel):
- vt_resize() is used to natively resize #2 and #4
- Thumb.php is used to resize #1, #3, #4 (only if vt_resize is disabled) and #5

Parameters: 
        $key = Custom field key eg. "image"
        $width = Set width manually without using $type
        $height = Set height manually without using $type
        $class = CSS class to use on the img tag eg. "alignleft". Default is "thumbnail"
        $quality = Enter a quality between 80-100. Default is 90
        $id = Assign a custom ID, if alternative is required.
        $link = Echo with anchor ( 'src'), without anchor ( 'img') or original image URL ( 'url').
        $repeat = Auto Img Function. Adjust amount of images to return for the post attachments.
        $offset = Auto Img Function. Offset the $repeat with assigned amount of objects.
        $before = Auto Img Function. Add Syntax before image output.
        $after = Auto Img Function. Add Syntax after image output.
        $single = (true/false) Force thumbnail to link to the post instead of the image.
        $force = Force smaller images to not be effected with image width and height dimentions (proportions fix)
        $return = Return results instead of echoing out.
		$src = A parameter that accepts a img url for resizing. (No anchor)
		$meta = Add a custom meta text to the image and anchor of the image.
		$alignment = Crop alignment for thumb.php (l, r, t, b)
		$size = Custom pre-defined size for WP Thumbnail (string)
*/

function colabs_image($args) {

	/* ------------------------------------------------------------------------- */
	/* SET VARIABLES */
	/* ------------------------------------------------------------------------- */

	global $post;
	global $colabs_options;
	
	//Defaults
	$key = 'image';
	$width = null;
	$height = null;
	$class = '';
	$quality = 90;
	$id = null;
	$link = 'src';
	$repeat = 1;
	$offset = 0;
	$before = '';
	$after = '';
	$single = false;
	$force = false;
	$return = false;
	$is_auto_image = false;
	$src = '';
	$meta = '';
	$alignment = '';
	$size = '';	
	$play = false;
	$alt = '';
	$img_link = '';
    $wrapper_class = '';
    
	$playicon = '';
    
	$attachment_id = array();
	$attachment_src = array();
		
	if ( !is_array($args) ) 
		parse_str( $args, $args );
	
	extract($args);
	
    // Set post ID
    if ( empty($id) ) {
		$id = $post->ID;
    }

	$thumb_id = get_post_meta($id,'_thumbnail_id',true);
    
	// Set alignment 
	if ( $alignment == '') 
		$alignment = get_post_meta($id, '_image_alignment', true);

	// Get standard sizes
	if ( !$width && !$height ) {
		$width = '100';
		$height = '100';
	}
    
    if($wrapper_class !== ''){$wrap_class = 'class="'.$wrapper_class.'"'; }

	/* ------------------------------------------------------------------------- */
	/* FIND IMAGE TO USE */
	/* ------------------------------------------------------------------------- */

	// When a custom image is sent through
	if ( $src != '' ) { 
		$custom_field = $src;
		$link = 'img';
	
	// WP 2.9 Post Thumbnail support	
	} elseif ( get_option( 'colabs_post_image_support') == 'true' AND !empty($thumb_id) ) {

		if ( get_option( 'colabs_pis_resize') == "true") {
		
			// Dynamically resize the post thumbnail 
			$vt_crop = get_option( 'colabs_pis_hard_crop' );
			if ($vt_crop == "true") $vt_crop = true; else $vt_crop = false;
			$vt_image = vt_resize( $thumb_id, '', $width, $height, $vt_crop );
			
			// Set fields for output
			$custom_field = $vt_image['url'];		
			$width = $vt_image['width'];
			$height = $vt_image['height'];
			
		} else {
			// Use predefined size string
			if ( $size ) 
				$thumb_size = $size;
			else 
				$thumb_size = array($width,$height);
				
			$img_link = get_the_post_thumbnail($id,$thumb_size,array( 'class' => 'colabs-image ' . $class));
		}		
		
	// Grab the image from custom field
	} else {
    	$custom_field = get_post_meta($id, $key, true);
	} 

	// Automatic Image Thumbs - get first image from post attachment
	if ( empty($custom_field) && get_option( 'colabs_auto_img') == 'true' && empty($img_link) && !(is_singular() AND in_the_loop() AND $link == "src") ) { 
	        
        if( $offset >= 1 ) 
			$repeat = $repeat + $offset;
    
        $attachments = get_children( array(	'post_parent' => $id,
											'numberposts' => $repeat,
											'post_type' => 'attachment',
											'post_mime_type' => 'image',
											'order' => 'DESC', 
											'orderby' => 'menu_order date')
											);

		// Search for and get the post attachment
		if ( !empty($attachments) ) { 
       
			$counter = -1;
			$size = 'large';
			foreach ( $attachments as $att_id => $attachment ) {            
				$counter++;
				if ( $counter < $offset ) 
					continue;
			
				if ( get_option( 'colabs_pis_resize') == "true") {
				
					// Dynamically resize the post thumbnail 
					$vt_crop = get_option( 'colabs_pis_hard_crop' );
					if ($vt_crop == "true") $vt_crop = true; else $vt_crop = false;
					$vt_image = vt_resize( $att_id, '', $width, $height, $vt_crop );
					
					// Set fields for output
					$custom_field = $vt_image['url'];		
					$width = $vt_image['width'];
					$height = $vt_image['height'];
				
				} else {

					$src = wp_get_attachment_image_src($att_id, $size, true);
					$custom_field = $src[0];
					$attachment_id[] = $att_id;
					$src_arr[] = $custom_field;
						
				}
				$thumb_id = $att_id;
				$is_auto_image = true;
			}

		// Get the first img tag from content
		} else { 

			$first_img = '';
			$post = get_post($id); 
			ob_start();
			ob_end_clean();
			$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			if ( !empty($matches[1][0]) ) {
				
				// Save Image URL
				$custom_field = $matches[1][0];
				
				// Search for ALT tag
				$output = preg_match_all( '/<img.+alt=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
				if ( !empty($matches[1][0]) ) {
					$alt = $matches[1][0];
				}
			}

		}
		
	} 
	
	// Check if there is Video embed
	if ( empty($custom_field) && empty($img_link) ) {
		$embed = get_post_meta($id, "colabs_embed", true);
		if ( $embed ) {
	    	$custom_field = colabs_get_video_image($embed);
            // Set Play Icon
            if($play == true){ $playicon = '<span class="playicon">Play</span>'; }else{ $playicon = ''; }
			}
	}
				
	// Return if there is no attachment or custom field set
	if ( empty($custom_field) && empty($img_link) ) {
		
		// Check if default placeholder image is uploaded
		$placeholder = get_option( 'framework_colabs_default_image' );
		if ( $placeholder && !(is_singular() AND in_the_loop()) ) {
			$custom_field = $placeholder;	

			// Resize the placeholder if
			if ( get_option( 'colabs_pis_resize') == "true") {

				// Dynamically resize the post thumbnail 
				$vt_crop = get_option( 'colabs_pis_hard_crop' );
				if ($vt_crop == "true") $vt_crop = true; else $vt_crop = false;
				$vt_image = vt_resize( '', $placeholder, $width, $height, $vt_crop );
				
				// Set fields for output
				$custom_field = $vt_image['url'];		
				$width = $vt_image['width'];
				$height = $vt_image['height'];
			
			}			
			
		} else {
	       return;
		
	    }
	
	}
	
	if(empty($src_arr) && empty($img_link)){ $src_arr[] = $custom_field; }
	
	/* ------------------------------------------------------------------------- */
	/* BEGIN OUTPUT */
	/* ------------------------------------------------------------------------- */

    $output = '';
	
    // Set output height and width
    $set_width = ' width="' . $width .'" ';
    $set_height = ' height="' . $height .'" '; 
    if($height == null OR $height == '') $set_height = '';
		
	// Set standard class
	if ( $class ) $class = 'colabs-image ' . $class; else $class = 'colabs-image';

	// Do check to verify if images are smaller then specified.
	if($force == true){ $set_width = ''; $set_height = ''; }

	// WP Post Thumbnail
	if(!empty($img_link) ){
			
		if( $link == 'img' ) {  // Output the image without anchors
			$output .= $before; 
			$output .= $img_link;
            $output .= $playicon;
			$output .= $after;  
			
		} elseif( $link == 'url' ) {  // Output the large image

			$src = wp_get_attachment_image_src($thumb_id, 'large', true);
			$custom_field = $src[0];
			$output .= $custom_field;

		} else {  // Default - output with link				

			if ( ( is_single() OR is_page() ) AND $single == false ) {
				$rel = 'rel="lightbox"';
				$href = false;  
			} else { 
				$href = get_permalink($id);
				$rel = '';
			}
			
			$title = 'title="' . get_the_title($id) .'"';
		
			$output .= $before; 
			if($href == false){
				$output .= $img_link;
			} else {
				$output .= '<a '.$title.' href="' . $href .'" '.$rel.' '.$wrap_class.'>' . $img_link . $playicon . '</a>';
			}
			
			$output .= $after;  
		}	
	}
	
	// Use thumb.php to resize. Skip if image has been natively resized with vt_resize.
	elseif ( get_option( 'colabs_resize') == 'true' && empty($vt_image['url']) ) { 
		
		foreach($src_arr as $key => $custom_field){
	
			// Clean the image URL
			$href = $custom_field; 		
			$custom_field = cleanSource( $custom_field );

			// Check if WPMU and set correct path AND that image isn't external
			if ( function_exists( 'get_current_site') && strpos($custom_field,"http://") !== 0 ) {
				get_current_site();
				//global $blog_id; Breaks with WP3 MS
				if ( !$blog_id ) {
					global $current_blog;
					$blog_id = $current_blog->blog_id;				
				}
				if ( isset($blog_id) && $blog_id > 0 ) {
					$imageParts = explode( 'files/', $custom_field );
					if ( isset($imageParts[1]) ) 
						$custom_field = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
				}
			}
			
			//Set the ID to the Attachment's ID if it is an attachment
			if($is_auto_image == true){	
				$quick_id = $attachment_id[$key];
			} else {
			 	$quick_id = $id;
			}
			
			//Set custom meta 
			if ($meta) { 
				$alt = $meta;
				$title = 'title="'. $meta .'"';
			} else { 
				if ($alt == '' AND get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ) 
					$alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
				else
					$alt = get_the_title($quick_id);
				$title = 'title="'. get_the_title($quick_id) .'"';
			}
			
			// Set alignment parameter
			if ($alignment <> '')
				$alignment = '&amp;a='.$alignment;
											
			$img_link = '<img src="'. get_bloginfo( 'template_url'). '/scripts/timthumb.php?src='. $custom_field .'&amp;w='. $width .'&amp;h='. $height .'&amp;zc=1&amp;q='. $quality . $alignment . '" alt="'.$alt.'" class="'. stripslashes($class) .'" '. $set_width . $set_height . ' />';
			
			if( $link == 'img' ) {  // Just output the image
				$output .= $before; 
				$output .= $img_link;
				$output .= $playicon;
				$output .= $after;  
				
			} elseif( $link == 'url' ) {  // Output the image without anchors
	
				if($is_auto_image == true){	
					$src = wp_get_attachment_image_src($thumb_id, 'large', true);
					$custom_field = $src[0];
				}
				$output .= $custom_field;
				
			} else {  // Default - output with link				

				if ( ( is_single() OR is_page() ) AND $single == false ) {
					$rel = 'rel="lightbox"';
				} else { 
					$href = get_permalink($id);
					$rel = '';
				}
			
				$output .= $before; 
				$output .= '<a '.$title.' href="' . $href .'" '.$rel.' '.$wrap_class.'>' . $img_link . $playicon . '</a>';
				$output .= $after;  
			}
		}
		
	// No dynamic resizing
	} else {  
		
		foreach($src_arr as $key => $custom_field){
				
			//Set the ID to the Attachment's ID if it is an attachment
			if($is_auto_image == true AND isset($attachment_id[$key])){	
				$quick_id = $attachment_id[$key];
			} else {
			 	$quick_id = $id;
			}
			
			//Set custom meta 
			if ($meta) { 
				$alt = $meta;
				$title = 'title="'. $meta .'"';
			} else { 
				if ($alt == '') $alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
				$title = 'title="'. get_the_title($quick_id) .'"';
			}
		
			$img_link =  '<img src="'. $custom_field .'" alt="'. $alt .'" '. $set_width . $set_height . ' class="'. stripslashes($class) .'" />';
		
			if ( $link == 'img' ) {  // Just output the image 
				$output .= $before;                   
				$output .= $img_link;
                $output .= $playicon;
				$output .= $after;  
				
			} elseif( $link == 'url' ) {  // Output the URL to original image
				if ( $vt_image['url'] || $is_auto_image ) { 
					$src = wp_get_attachment_image_src($thumb_id, 'full', true);
					$custom_field = $src[0];
				}
				$output .= $custom_field;

			} else {  // Default - output with link
			
				if ( ( is_single() OR is_page() ) AND $single == false ) { 

					// Link to the large image if single post
					if ( $vt_image['url'] || $is_auto_image ) { 
						$src = wp_get_attachment_image_src($thumb_id, 'full', true);
						$custom_field = $src[0];
					}
					
					$href = $custom_field;
					$rel = 'rel="lightbox"';
				} else { 
					$href = get_permalink($id);
					$rel = '';
				}
				 
				$output .= $before;   
				$output .= '<a href="' . $href .'" '. $rel . $title . $wrap_class .'>' . $img_link  . $playicon . '</a>';
				$output .= $after;   
			}
		}
	}

	// Return or echo the output
	if ( $return == TRUE )
		return $output;
	else 
		echo $output; // Done  

}

/* Get thumbnail from Video Embed code */

/* // Get Vimeo Thumbnail */
function getVimeoInfo($id, $info = 'thumbnail_large') {
  
		$output = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));  
		$output = $output[0][$info];
		return $output;
   
};

/* Get Redirect Link -- get Daily Motion thumbnail */
function get_redirect_url($url){
	$redirect_url = null; 
 
	$url_parts = @parse_url($url);
	if (!$url_parts) return false;
	if (!isset($url_parts['host'])) return false; //can't process relative URLs
	if (!isset($url_parts['path'])) $url_parts['path'] = '/';
 
	$sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
	if (!$sock) return false;
 
	$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n"; 
	$request .= 'Host: ' . $url_parts['host'] . "\r\n"; 
	$request .= "Connection: Close\r\n\r\n"; 
	fwrite($sock, $request);
	$response = '';
	while(!feof($sock)) $response .= fread($sock, 8192);
	fclose($sock);
 
	if (preg_match('/^Location: (.+?)$/m', $response, $matches)){
		if ( substr($matches[1], 0, 1) == "/" )
			return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
		else
			return trim($matches[1]);
 
	} else {
		return false;
	}
 
}

if (!function_exists( 'colabs_get_video_image')) { 
	function colabs_get_video_image($embed) { 
	
		// YouTube - get the video code if this is an embed code (old embed)
		preg_match( '/youtube\.com\/v\/([\w\-]+)/', $embed, $match);
	 
		// YouTube - if old embed returned an empty ID, try capuring the ID from the new iframe embed
		if($match[1] == '')
			preg_match( '/youtube\.com\/embed\/([\w\-]+)/', $embed, $match);
	 
		// YouTube - if it is not an embed code, get the video code from the youtube URL	
		if($match[1] == '')
			preg_match( '/v\=(.+)&/',$embed ,$match);
	 
		// YouTube - get the corresponding thumbnail images	
		if($match[1] != '')
			$video_thumb = "http://img.youtube.com/vi/".$match[1]."/0.jpg";
			
		/* Vimeo Thumbnail */
		 if($match[1] == '')
			{
			preg_match( '/vimeo\.com\/video\/([\w\-]+)/', $embed, $match2);
			$vimeo=getVimeoInfo($match2[1],'thumbnail_large');
			}
			
		if($vimeo != '')
			$video_thumb =$vimeo; 

		/* Metacafe Thumbnail */
		if (($match[1] == '')and($vimeo == '')){
			preg_match( '/metacafe\.com\/fplayer\/([\w\-]+)/', $embed, $metacafe);
			$video_thumb ="http://s3.mcstatic.com/thumb/".$metacafe[1].".jpg";
			}
		
		/* Dailymotion */
		if (($match[1] == '')and($vimeo == '')and($metacafe[1]== '')){
			preg_match( '/dailymotion\.com\/embed\/video\/([\w\-]+)/', $embed, $daily);
			$daily_redirect ="http://www.dailymotion.com/thumbnail/video/".$daily[1];
			$video_thumb=get_redirect_url($daily_redirect);
			}
            
		// return whichever thumbnail image you would like to retrieve
		return $video_thumb;		
	}
}


/*-----------------------------------------------------------------------------------*/
/* vt_resize - Resize images dynamically using wp built in functions
/*-----------------------------------------------------------------------------------*/
/*
 * Resize images dynamically using wp built in functions
 * Victor Teixeira
 *
 * php 5.2+
 *
 * Exemplo de uso:
 * 
 * <?php 
 * $thumb = get_post_thumbnail_id(); 
 * $image = vt_resize( $thumb, '', 140, 110, true );
 * ?>
 * <img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" />
 *
 * @param int $attach_id
 * @param string $img_url
 * @param int $width
 * @param int $height
 * @param bool $crop
 * @return array
 */
if ( !function_exists( 'vt_resize') ) {
	function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
	
		// this is an attachment, so we have the ID
		if ( $attach_id ) {
		
			$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
			$file_path = get_attached_file( $attach_id );
		
		// this is not an attachment, let's use the image url
		} else if ( $img_url ) {
			
			$file_path = parse_url( $img_url );
			$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
			
			//$file_path = ltrim( $file_path['path'], '/' );
			//$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];
			
			$orig_size = getimagesize( $file_path );
			
			$image_src[0] = $img_url;
			$image_src[1] = $orig_size[0];
			$image_src[2] = $orig_size[1];
		}
		
		$file_info = pathinfo( $file_path );
	
		// check if file exists
		$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
		if ( !file_exists($base_file) )
		 return;
		 
		$extension = '.'. $file_info['extension'];
	
		// the image path without the extension
		$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
		
		$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
	
		// checking if the file size is larger than the target size
		// if it is smaller or the same size, stop right here and return
		if ( $image_src[1] > $width AND $image_src[2] > $height ) {
	
			// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
			if ( file_exists( $cropped_img_path ) ) {
	
				$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
				
				$vt_image = array (
					'url' => $cropped_img_url,
					'width' => $width,
					'height' => $height
				);
				
				return $vt_image;
			}
	
			// $crop = false
			if ( $crop == false ) {
			
				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
	
				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {
				
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
	
					$vt_image = array (
						'url' => $resized_img_url,
						'width' => $proportional_size[0],
						'height' => $proportional_size[1]
					);
					
					return $vt_image;
				}
			}
	
			// check if image width is smaller than set width
			$img_size = getimagesize( $file_path );
			if ( $img_size[0] <= $width ) $width = $img_size[0];		
			
			// no cache files - let's finally resize it
			$new_img_path = image_resize( $file_path, $width, $height, $crop );
			$new_img_size = getimagesize( $new_img_path );
			$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
	
			// resized output
			$vt_image = array (
				'url' => $new_img,
				'width' => $new_img_size[0],
				'height' => $new_img_size[1]
			);
			
			return $vt_image;
		}
	
		// default output - without resizing
		$vt_image = array (
			'url' => $image_src[0],
			'width' => $image_src[1],
			'height' => $image_src[2]
		);
		
		return $vt_image;
	}
}


/*-----------------------------------------------------------------------------------*/
/* Depreciated - colabs_get_image - Get Image from custom field */
/*-----------------------------------------------------------------------------------*/

// Depreciated
function colabs_get_image($key = 'image', $width = null, $height = null, $class = "thumbnail", $quality = 90,$id = null,$link = 'src',$repeat = 1,$offset = 0,$before = '', $after = '',$single = false, $force = false, $return = false) {
	// Run new function
	colabs_image( 'key='.$key.'&width='.$width.'&height='.$height.'&class='.$class.'&quality='.$quality.'&id='.$id.'&link='.$link.'&repeat='.$repeat.'&offset='.$offset.'&before='.$before.'&after='.$after.'&single='.$single.'&fore='.$force.'&return='.$return );
	return;

}



/*-----------------------------------------------------------------------------------*/
/* colabs_embed - Get Video embed code from custom field */
/*-----------------------------------------------------------------------------------*/

/*
Get Video
This function gets the embed code from the custom field
Parameters: 
        $key = Custom field key eg. "embed"
        $width = Set width manually without using $type
        $height = Set height manually without using $type
		$class = Custom class to apply to wrapping div
		$id = ID from post to pull custom field from
*/

function colabs_embed($args) {

	//Defaults
	$key = 'embed';
	$width = null;
	$height = null;
	$class = 'video';
	$id = null;	
	
	if ( !is_array($args) ) 
		parse_str( $args, $args );
	
	extract($args);

  if(empty($id))
    {
    global $post;
    $id = $post->ID;
    } 
    

$custom_field = get_post_meta($id, $key, true);

if ($custom_field) : 

	$custom_field = html_entity_decode( $custom_field ); // Decode HTML entities.

    $org_width = $width;
    $org_height = $height;
    $calculated_height = '';
    
    // Get custom width and height
    $custom_width = get_post_meta($id, 'width', true);
    $custom_height = get_post_meta($id, 'height', true);    
    
    //Dynamic Height Calculation
    if ($org_height == '' && $org_width != '') {
    	$raw_values = explode( " ", $custom_field);
    
    	foreach ($raw_values as $raw) {
    		$embed_params = explode( "=",$raw);
    		if ($embed_params[0] == 'width') {
   		 		$embed_width = ereg_replace( "[^0-9]", "", $embed_params[1]);
    		}
    		elseif ($embed_params[0] == 'height') {
    			$embed_height = ereg_replace( "[^0-9]", "", $embed_params[1]);
    		} 
    	}
    
    	$float_width = floatval($embed_width);
		$float_height = floatval($embed_height);
		$float_ratio = $float_height / $float_width;
		$calculated_height = intval($float_ratio * $width);
    }
    
    // Set values: width="XXX", height="XXX"
    if ( !$custom_width ) $width = 'width="'.$width.'"'; else $width = 'width="'.$custom_width.'"';
    if ( $height == '' ) { $height = 'height="'.$calculated_height.'"'; } else { if ( !$custom_height ) { $height = 'height="'.$height.'"'; } else { $height = 'height="'.$custom_height.'"'; } }
    $custom_field = stripslashes($custom_field);
    $custom_field = preg_replace( '/width="([0-9]*)"/' , $width , $custom_field );
    $custom_field = preg_replace( '/height="([0-9]*)"/' , $height , $custom_field );    

    // Set values: width:XXXpx, height:XXXpx
    if ( !$custom_width ) $width = 'width:'.$org_width.'px'; else $width = 'width:'.$custom_width.'px';
    if (  $height == '' ) { $height = 'height:'.$calculated_height.'px'; } else { if ( !$custom_height ) { $height = 'height:'.$org_height.'px'; } else { $height = 'height:'.$custom_height.'px'; } }
    $custom_field = stripslashes($custom_field);
    $custom_field = preg_replace( '/width:([0-9]*)px/' , $width , $custom_field );
    $custom_field = preg_replace( '/height:([0-9]*)px/' , $height , $custom_field );     

	// Suckerfish menu hack
	$custom_field = str_replace( '<embed ','<param name="wmode" value="transparent"></param><embed wmode="transparent" ',$custom_field);

	$output = '';
    $output .= '<div class="'. $class .'">' . $custom_field . '</div>';
    
    return $output; 
	
else :

	return false;
    
endif;

}

/*-----------------------------------------------------------------------------------*/
/* Depreciated - colabs_get_embed - Get Video embed code from custom field */
/*-----------------------------------------------------------------------------------*/

// Depreciated
function colabs_get_embed($key = 'embed', $width, $height, $class = 'video', $id = null) {
	// Run new function
	return colabs_embed( 'key='.$key.'&width='.$width.'&height='.$height.'&class='.$class.'&id='.$id );

}



/*-----------------------------------------------------------------------------------*/
/* CoLabs Show Page Menu */
/*-----------------------------------------------------------------------------------*/

// Show menu in header.php
// Exlude the pages from the slider
function colabs_show_pagemenu( $exclude="" ) {
    // Split the featured pages from the options, and put in an array
    if ( get_option( 'colabs_ex_featpages') ) {
        $menupages = get_option( 'colabs_featpages' );
        $exclude = $menupages . ',' . $exclude;
    }
    
    $pages = wp_list_pages( 'sort_column=menu_order&title_li=&echo=0&depth=1&exclude='.$exclude);
    $pages = preg_replace( '%<a ([^>]+)>%U','<a $1><span>', $pages);
    $pages = str_replace( '</a>','</span></a>', $pages);
    echo $pages;
}



/*-----------------------------------------------------------------------------------*/
/* Get the style path currently selected */
/*-----------------------------------------------------------------------------------*/

function colabs_style_path() {
	
	$return = '';
	
	$style = $_REQUEST['style'];
	
	// Sanitize request input.
	$style = strtolower( trim( strip_tags( $style ) ) );
	
	if ( $style != '' ) {
	
		$style_path = $style;
	
	} else {
	
		$stylesheet = get_option( 'colabs_alt_stylesheet' );
		
		// Prevent against an empty return to $stylesheet.
		
		if ( $stylesheet == '' ) {
		
			$stylesheet = 'default.css';
		
		} // End IF Statement
		
		$style_path = str_replace( '.css', '', $stylesheet );
	
	} // End IF Statement
	
	if ( $style_path == 'default' ) {
	
		$return = 'images';
	
	} else {
	
		$return = 'styles/' . $style_path;
	
	} // End IF Statement
	
	echo $return;
	
} // End colabs_style_path()


/*-----------------------------------------------------------------------------------*/
/* Get page ID */
/*-----------------------------------------------------------------------------------*/
function get_page_id($page_slug){
	$page_id = get_page_by_path($page_slug);
    if ($page_id) {
        return $page_id->ID;
    } else {
        return null;
    }    
    
}

/*-----------------------------------------------------------------------------------*/
/* Tidy up the image source url */
/*-----------------------------------------------------------------------------------*/
function cleanSource($src) {

	// remove slash from start of string
	if(strpos($src, "/") == 0) {
		$src = substr($src, -(strlen($src) - 1));
	}

	// Check if same domain so it doesn't strip external sites
	$host = str_replace( 'www.', '', $_SERVER['HTTP_HOST']);
	if ( !strpos($src,$host) )
		return $src;


	$regex = "/^((ht|f)tp(s|):\/\/)(www\.|)" . $host . "/i";
	$src = preg_replace ($regex, '', $src);
	$src = htmlentities ($src);
    
    // remove slash from start of string
    if (strpos($src, '/') === 0) {
        $src = substr ($src, -(strlen($src) - 1));
    }
	
	return $src;
}



/*-----------------------------------------------------------------------------------*/
/* Show image in RSS feed */
/* Original code by Justin Tadlock http://justintadlock.com */
/*-----------------------------------------------------------------------------------*/
if (get_option( 'colabs_rss_thumb') == "true")
	add_filter( 'the_content', 'add_image_RSS' );
	
function add_image_RSS( $content ) {
	
	global $post, $id;
	$blog_key = substr( md5( get_bloginfo( 'url') ), 0, 16 );
	if ( ! is_feed() ) return $content;

	// Get the "image" from custom field
	$image = get_post_meta($post->ID, 'image', $single = true);
	$image_width = '240';

	// If there's an image, display the image with the content
	if($image !== '') {
		$content = '<p style="float:right; margin:0 0 10px 15px; width:'.$image_width.'px;">
		<img src="'.$image.'" width="'.$image_width.'" />
		</p>' . $content;
		return $content;
	} 

	// If there's not an image, just display the content
	else {
		$content = $content;
		return $content;
	}
} 



/*-----------------------------------------------------------------------------------*/
/* Show analytics code in footer */
/*-----------------------------------------------------------------------------------*/
function colabs_analytics(){
	$output = get_option( 'colabs_google_analytics' );
	if ( $output <> "" ) 
		echo stripslashes($output) . "\n";
}
add_action( 'wp_footer','colabs_analytics' );



/*-----------------------------------------------------------------------------------*/
/* Browser detection body_class() output */
/*-----------------------------------------------------------------------------------*/
add_filter( 'body_class','browser_body_class' );
function browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) {
		$browser = $_SERVER['HTTP_USER_AGENT']; 
		$browser = substr( "$browser", 25, 8); 
		if ($browser == "MSIE 7.0"  )
			$classes[] = 'ie7';
		elseif ($browser == "MSIE 6.0" )
			$classes[] = 'ie6'; 
		elseif ($browser == "MSIE 8.0" )
			$classes[] = 'ie8'; 
		else	
			$classes[] = 'ie';
	}
	else $classes[] = 'unknown';

	if($is_iphone) $classes[] = 'iphone';
	return $classes;
}

/*-----------------------------------------------------------------------------------*/
/* Twitter's Blogger.js output for Twitter widgets */
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'colabs_twitter_script') ) {
	function colabs_twitter_script($unique_id,$username,$limit,$exclude_replies='') {
	?>
	<script type="text/javascript">
	<!--//--><![CDATA[//><!--
	
	    function twitterCallback2(twitters) {
	    
	      var statusHTML = [];
	      for (var i=0; i<twitters.length; i++){
	        var username = twitters[i].user.screen_name;
	        var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
	          return '<a href="'+url+'">'+url+'</a>';
	        }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
	          return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
	        });
	        statusHTML.push( '<li><span class="content">'+status+'</span> <a style="font-size:85%" class="time" href="http://twitter.com/'+username+'/statuses/'+twitters[i].id_str+'">'+relative_time(twitters[i].created_at)+'</a></li>' );
	      }
	      document.getElementById( 'twitter_update_list_<?php echo $unique_id; ?>').innerHTML = statusHTML.join( '' );
	    }
	    
	    function relative_time(time_value) {
	      var values = time_value.split( " " );
	      time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
	      var parsed_date = Date.parse(time_value);
	      var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
	      var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
	      delta = delta + (relative_to.getTimezoneOffset() * 60);
	    
	      if (delta < 60) {
	        return 'less than a minute ago';
	      } else if(delta < 120) {
	        return 'about a minute ago';
	      } else if(delta < (60*60)) {
	        return (parseInt(delta / 60)).toString() + ' minutes ago';
	      } else if(delta < (120*60)) {
	        return 'about an hour ago';
	      } else if(delta < (24*60*60)) {
	        return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
	      } else if(delta < (48*60*60)) {
	        return '1 day ago';
	      } else {
	        return (parseInt(delta / 86400)).toString() + ' days ago';
	      }
	    }
	//-->!]]>
	</script>
    <?php
        if ( $exclude_replies != '' ){ $exclude_replies_str = '&amp;exclude_replies='.$exclude_replies; } else { $exclude_replies_str = ''; }
    ?>
	<script type="text/javascript" src="http://api.twitter.com/1/statuses/user_timeline/<?php echo $username; ?>.json?callback=twitterCallback2&amp;count=<?php echo $limit; ?>&amp;include_rts=t<?php echo $exclude_replies_str; ?>"></script>
	<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Template Detector */
/*-----------------------------------------------------------------------------------*/
function colabs_active_template($filename = null){

	if(isset($filename)){
		
		global $wpdb;
		$query = "SELECT *,count(*) AS used FROM $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = '$filename' GROUP BY meta_value";
		$results = $wpdb->get_row($wpdb->prepare($query),'ARRAY_A' ); // Select thrid coloumn accross
				
		if(empty($results))
			return false;
			
		$post_id = $results['post_id'];
		$trash = get_post_status($post_id); // Check for trash
		
		if($trash != 'trash')
			return true;
		else
	 		return false;
	
	} else {
		return false; // No $filename argument was set
	}

}

/*-----------------------------------------------------------------------------------*/
/* CoLabs URL shortener */
/*-----------------------------------------------------------------------------------*/

function colabs_short_url($url) {
	$service = get_option( 'colabs_url_shorten' );
	$bitlyapilogin = get_option( 'colabs_bitly_api_login' );;
	$bitlyapikey = get_option( 'colabs_bitly_api_key' );;
	if (isset($service)) {
		switch ($service) 
		{
    		case 'TinyURL':
    			$shorturl = getTinyUrl($url);
    			break;
    		case 'Bit.ly':
    			if (isset($bitlyapilogin) && isset($bitlyapikey) && ($bitlyapilogin != '') && ($bitlyapikey != '')) {
    				$shorturl = make_bitly_url($url,$bitlyapilogin,$bitlyapikey,'json' );
    			}
    			else {
    				$shorturl = getTinyUrl($url);
    			}
    			break;
    		case 'Off':
    			$shorturl = $url;
    			break;
    		default:
    			$shorturl = $url;
    			break;
    	}
	}
	else {
		$shorturl = $url;
	}
	return $shorturl;
}

//TinyURL
function getTinyUrl($url) {
	$tinyurl = file_get_contents_curl( "http://tinyurl.com/api-create.php?url=".$url);
	return $tinyurl;
}

//Bit.ly
function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1')
{
	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;
	
	//get the url
	//could also use cURL here
	$response = file_get_contents_curl($bitly);
	
	//parse depending on desired format
	if(strtolower($format) == 'json')
	{
		$json = @json_decode($response,true);
		return $json['results'][$url]['shortUrl'];
	}
	else //xml
	{
		$xml = simplexml_load_string($response);
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}

//Alternative CURL function
function file_get_contents_curl($url) {
	if (_iscurlinstalled()) {
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_URL, $url);
	
		$data = curl_exec($ch);
		
		if ($data === FALSE) {
			$data =  "cURL Error: " . curl_error($ch);
		}
	
		curl_close($ch);
	} else {
		$data = $url;
	}
	return $data;
}

// Checks for presence of the cURL extension.
function _iscurlinstalled() {
	if  (in_array  ( 'curl', get_loaded_extensions())) {
		if (function_exists( 'curl_init')) {
			return true;
		} else {
			return false;
		}
	}
	else{
		if (function_exists( 'curl_init')) {
			return true;
		} else {
			return false;
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* colabs_title() */
/*-----------------------------------------------------------------------------------*/

function colabs_title(){

	global $post;
	$layout = ''; 
	
	// Setup the variable that will, ultimately, hold the title value.
	$title = '';
	
	//Taxonomy Details WP 3.0 only
	if ( function_exists( 'get_taxonomies') ) :
		global $wp_query; 
		$taxonomy_obj = $wp_query->get_queried_object();
		if ( ! empty( $taxonomy_obj->name ) && function_exists( 'is_post_type_archive' ) && ! is_post_type_archive() ) :
			$taxonomy_nice_name = $taxonomy_obj->name;
			$term_id = $taxonomy_obj->term_taxonomy_id;
			$taxonomy_short_name = $taxonomy_obj->taxonomy;
			$taxonomy_top_level_items = get_taxonomies(array( 'name' => $taxonomy_short_name), 'objects' );
			$taxonomy_top_level_item = $taxonomy_top_level_items[$taxonomy_short_name]->label;
		elseif ( ! empty( $taxonomy_obj->name ) && function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) :
			$archive_name = $taxonomy_obj->label;
		endif;
	endif;
	
	//3rd Party Plugins
	$use_third_party_data = false;
	if(get_option( 'seo_colabs_use_third_party_data') == 'true'){
		$use_third_party_data = true;
	}
		
	if(
		(
			class_exists( 'All_in_One_SEO_Pack') || 
			class_exists( 'Headspace_Plugin') || 
			class_exists( 'WPSEO_Admin' ) || 
			class_exists( 'WPSEO_Frontend' )
    	)
	&& 
		( $use_third_party_data != true ) ) { wp_title(); return; }

	$sep = get_option( 'seo_colabs_separator' );	
	if(empty($sep)) { $sep = " | ";} else { $sep = ' ' . $sep . ' ';}
	$use_wp_title = get_option( 'seo_colabs_wp_title' );
	$home_layout = get_option( 'seo_colabs_home_layout' );
	$single_layout = get_option( 'seo_colabs_single_layout' );
	$page_layout = get_option( 'seo_colabs_page_layout' );
	$archive_layout = get_option( 'seo_colabs_archive_layout' );
	
	
	$output = '';
	if($use_wp_title == 'true'){
		
		if(is_home() OR is_front_page()){
			switch ($home_layout){
				case 'a': $output = get_bloginfo( 'name') . $sep . get_bloginfo( 'description' ); 
				break;
				case 'b': $output = get_bloginfo( 'name' ); 
				break;
				case 'c': $output = get_bloginfo( 'description' ); 
				break;
				case 'd': $output = get_bloginfo( 'description' ) . $sep . get_bloginfo( 'name'); 
				break;
				}
			if(is_paged()){
				$paged_var = get_query_var( 'paged' );
				if(get_option( 'seo_colabs_paged_var_pos') == 'after'){
				
					$output .= $sep . get_option( 'seo_colabs_paged_var') . ' ' . $paged_var;

				} else {
									
					$output = get_option( 'seo_colabs_paged_var') . ' ' . $paged_var . $sep . $output;

				}
				
			}
			$output = stripslashes($output);
			echo $output;
		}
		else {
		if (is_single()) { $layout = $single_layout; }
		elseif  (is_page()) { $layout = $page_layout; }
		elseif  (is_archive()) { $layout = $archive_layout; }
		elseif  (is_tax()) { $layout = $archive_layout; }
		elseif  (is_search()) { $layout = 'search'; }
		elseif  (is_404()) { $layout = $single_layout; }
		
		
		
		//Check if there is a custom value added to post meta
		$colabsseo_title = get_post_meta($post->ID,'seo_title',true); // CoLabsSEO
		$aio_title = get_post_meta($post->ID,'_aioseop_title',true); // All-in-One SEO Pack
		$headspace_title = get_post_meta($post->ID,'_headspace_page_title',true); // Headspace SEO
		$wpseo_title = get_post_meta( $post->ID,'_yoast_wpseo_title', true ); // WordPress SEO
		
		if( get_option( 'seo_colabs_wp_custom_field_title') != 'true' && is_singular() ) {
			if( ! empty($colabsseo_title ) ){
				$layout = 'colabsseo';
			} elseif(!empty($aio_title) AND $use_third_party_data) {
				$layout = 'aioseo';
			} elseif(!empty($headspace_title) AND $use_third_party_data) {
				$layout = 'headspace';
			} elseif(!empty($wpseo_title) AND $use_third_party_data) {
				$layout = 'wpseo';
			}
		}
			switch ( $layout ) {
				case 'a': $output = wp_title($sep,false,true) . get_bloginfo( 'name' );
				break;
				case 'b': $output = wp_title( '',false,false);
				break;
				case 'c': $output = get_bloginfo( 'name') . wp_title($sep,false,false);
				break;
				case 'd': $output = wp_title($sep,false,true) . get_bloginfo( 'description' );
				break;
				case 'e': $output = get_bloginfo( 'name') . $sep . wp_title($sep,false,true) . get_bloginfo( 'description' );
				break;
				case 'f': $output = wp_title($sep,false,true) . get_bloginfo( 'name'). $sep . get_bloginfo( 'description' );
				break;
				case 'search':  $output = get_bloginfo( 'name') . wp_title($sep,false,false); // Search is hardcoded
				break;
				case 'colabsseo':  $output = $colabsseo_title; // CoLabsSEO Title
				break;
				case 'aioseo':  $output = $aio_title; // All-in-One SEO Pack Title
				break;
				case 'headspace':  $output = $headspace_title; // Headspace Title
				break;
				case 'wpseo':  $output = $wpseo_title; // WordPress SEO Title
				break;
			}
			if(is_paged()){
				$paged_var = get_query_var( 'paged' );
				if(get_option( 'seo_colabs_paged_var_pos') == 'after'){
					$output .= $sep . get_option( 'seo_colabs_paged_var') . ' ' . $paged_var;
				} else {
					$output = get_option( 'seo_colabs_paged_var') . ' ' . $paged_var . $sep . $output;
				}
			}
			$output = stripslashes($output);
			
			if(empty($output)) {
				$title = wp_title( '&raquo;', false );
			} else {
				$title = $output;
			}
			
		}
	}
	else {

		if ( is_home() ) { $title = get_bloginfo( 'name') . $sep . get_bloginfo( 'description' ); } 
		elseif ( is_search() ) { $title = get_bloginfo( 'name') . $sep . __( 'Search Results', 'colabsthemes' );  }  
		elseif ( is_author() ) { $title = get_bloginfo( 'name') . $sep . __( 'Author Archives', 'colabsthemes' );  }  
		elseif ( is_single() ) { $title = wp_title( $sep, false, true ) . get_bloginfo( 'name' );  }
		elseif ( is_page() ) { $title = get_bloginfo( 'name' ) . wp_title( $sep, false, 'none' );  }
		elseif ( is_category() ) { $title = get_bloginfo( 'name') . $sep . __( 'Category Archive', 'colabsthemes' ) . $sep . single_cat_title( '',false );  }
		elseif ( is_tax() ) { $title = get_bloginfo( 'name') . $sep . $taxonomy_top_level_item . __( ' Archive', 'colabsthemes' ) . $sep . $taxonomy_nice_name;  }   
		elseif ( is_day() ) { $title = get_bloginfo( 'name') . $sep . __( 'Daily Archive', 'colabsthemes' ) . $sep . get_the_time( 'jS F, Y' );  }
		elseif ( is_month() ) { $title = get_bloginfo( 'name') . $sep . __( 'Monthly Archive', 'colabsthemes' ) . $sep . get_the_time( 'F' );  }
		elseif ( is_year() ) { $title = get_bloginfo( 'name') . $sep . __( 'Yearly Archive', 'colabsthemes' ) . $sep . get_the_time( 'Y' );  }
		elseif ( is_tag() ) {  $title = get_bloginfo( 'name') . $sep . __( 'Tag Archive', 'colabsthemes' ) . $sep . single_tag_title( '',false); }
		elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) { $title = get_bloginfo( 'name') . $sep . $archive_name . __( ' Archive', 'colabsthemes' );  }
	}
	
	// Allow child themes/plugins to filter the title value.
	$title = apply_filters( 'colabs_title', $title, $sep );
	
	// Display the formatted title.
	echo $title;
}

/*-----------------------------------------------------------------------------------*/
/* colabs_meta() */
/*-----------------------------------------------------------------------------------*/


function colabs_meta(){
		global $post;
		global $wpdb;
		if(!empty($post)){
			$post_id = $post->ID;
		}
		
		// Basic Output
		echo '<meta http-equiv="Content-Type" content="'. get_bloginfo( 'html_type') .'; charset='. get_bloginfo( 'charset') .'" />' . "\n";
		
		// Under SETTIGNS > PRIVACY in the WordPress backend
		if ( get_option( 'blog_public') == 0 ) { return; }
		
		//3rd Party Plugins
		$use_third_party_data = false;
		if(get_option( 'seo_colabs_use_third_party_data') == 'true'){
			$use_third_party_data = true;
		}
		
		if(
			(
			class_exists( 'All_in_One_SEO_Pack') || 
    		class_exists( 'Headspace_Plugin') || 
    		class_exists( 'WPSEO_Admin' ) || 
    		class_exists( 'WPSEO_Frontend' )
    		)
		&& ( $use_third_party_data == true ) ) { return; }
		
		// Robots
		if (
			! class_exists( 'All_in_One_SEO_Pack') && 
    		! class_exists( 'Headspace_Plugin') && 
    		! class_exists( 'WPSEO_Admin' ) && 
    		! class_exists( 'WPSEO_Frontend' )
		) {
			$index = 'index';
			$follow = 'nofollow';
			
			if ( is_category() && get_option( 'seo_colabs_meta_indexing_category') != 'true' ) { $index = 'noindex'; }  
			elseif ( is_tag() && get_option( 'seo_colabs_meta_indexing_tag') != 'true') { $index = 'noindex'; }
			elseif ( is_search() && get_option( 'seo_colabs_meta_indexing_search') != 'true' ) { $index = 'noindex'; }  
			elseif ( is_author() && get_option( 'seo_colabs_meta_indexing_author') != 'true') { $index = 'noindex'; }  
			elseif ( is_date() && get_option( 'seo_colabs_meta_indexing_date') != 'true') { $index = 'noindex'; }
			
			// Set default to follow			
			if ( get_option( 'seo_colabs_meta_single_follow') == 'true' )
				$follow = 'follow';  
	
			// Set individual post/page to follow/unfollow
			if ( is_singular() ) {
				if ( $follow == 'follow' AND get_post_meta($post->ID,'seo_follow',true) == 'true') 
					$follow = 'nofollow';  
				elseif ( $follow == 'nofollow' AND get_post_meta($post->ID,'seo_follow',true) == 'true') 
					$follow = 'follow';  
			}							
						
			if(is_singular() && get_post_meta($post->ID,'seo_noindex',true) == 'true') { $index = 'noindex';  }
			
			echo '<meta name="robots" content="'. $index .', '. $follow .'" />' . "\n";
		}
		
		/* Description */
		$description = '';
		
		$home_desc_option = get_option( 'seo_colabs_meta_home_desc' );
		$singular_desc_option = get_option( 'seo_colabs_meta_single_desc' );
		
		//Check if there is a custom value added to post meta
		$colabsseo_desc = get_post_meta($post->ID,'seo_description',true); // CoLabsSEO
		$aio_desc = get_post_meta($post->ID,'_aioseop_description',true); // All-in-One SEO Pack
		$headspace_desc = get_post_meta($post->ID,'_headspace_description',true); // Headspace SEO
		$wpseo_desc = get_post_meta($post->ID,'_yoast_wpseo_metadesc',true); // WordPress SEO
	
		//Singular setup
		if(!empty($aio_desc) AND $use_third_party_data) {
			$singular_desc_option = 'aioseo';
		} elseif(!empty($headspace_desc) AND $use_third_party_data) {
			$singular_desc_option = 'headspace';
		} elseif( ! empty( $wpseo_desc ) AND $use_third_party_data) {
			$singular_desc_option = 'wpseo';
		}

		
		if(is_home() OR is_front_page()){
			switch($home_desc_option){
				case 'a': $description = '';
				break;
				case 'b': $description = get_bloginfo( 'description' );
				break;
				case 'c': $description = get_option( 'seo_colabs_meta_home_desc_custom' );
				break;
			}
		}
		elseif(is_singular()){
			
			switch($singular_desc_option){
				case 'a': $description = '';
				break;
				case 'b': $description = trim(strip_tags($colabsseo_desc));
				break; 
				case 'c': 
	
    				if(is_single()){
    					 $posts = get_posts( "p=$post_id" );
    				}
    				if(is_page()){
    					 $posts = get_posts( "page_id=$post_id&post_type=page" );
    				}
					foreach($posts as $post){
   						setup_postdata($post);	
						$post_content =  get_the_excerpt();
						if(empty($post_content)){
							$post_content = get_the_content();
						}
					}
					// $post_content = htmlentities(trim(strip_tags(strip_shortcodes($post_content))), ENT_QUOTES, 'UTF-8' ); // Replaced with line below to accommodate special characters. // 2010-11-15.
					// $post_content = html_entity_decode(trim(strip_tags(strip_shortcodes($post_content))), ENT_QUOTES, 'UTF-8' ); // Replaced to fix PHP4 compatibility issue. // 2010-12-09.
					// $post_content = utf8_decode( trim( strip_tags( strip_shortcodes( $post_content ) ) ) );
					// $post_content = html_entity_decode( trim( strip_tags( strip_shortcodes( $post_content ) ) ) );
					// $post_content = esc_html( htmlspecialchars ( strip_shortcodes( $post_content ) ) );
					
					$post_content = esc_attr( strip_tags( strip_shortcodes( $post_content ) ) );
					
					$description = colabs_text_trim($post_content,30);
					
				break;
				case 'aioseo':  $description = $aio_desc; // All-in-One Description
				break;
				case 'headspace':  $description = $headspace_desc; // Headspace Description
				break;
				case 'wpseo':  $description = $wpseo_desc; // WordPress SEO Description
				break;
				
			}			
		}
		
		if(empty($description) AND get_option( 'seo_colabs_meta_single_desc_sitewide') == 'true'){
			$description = get_option( 'seo_colabs_meta_single_desc_custom' );
		}
		
		
		// $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8' ); // Replaced with line below to accommodate special characters. // 2010-11-15.
		$description = esc_attr( $description );
		$description = stripslashes($description);
		
		// Faux-htmlentities using an array of key => value pairs.
		// TO DO: Clean-up and move to a re-usable function.
		$faux_htmlentities = array(
								'& ' => '&amp; ', 
								'<' => '&lt;', 
								'>' => '&gt;'
							 );
		
		foreach ( $faux_htmlentities as $old => $new ) {
		
			$description = str_replace( $old, $new, $description );
		
		} // End FOREACH Loop
		
		if(!empty($description)){
			echo '<meta name="description" content="'.$description.'" />' . "\n";
		}
		
		/* Keywords */
		$keywords = '';
		
		$home_key_option = get_option( 'seo_colabs_meta_home_key' );
		$singular_key_option = get_option( 'seo_colabs_meta_single_key' );
		
		//Check if there is a custom value added to post meta
		$colabsseo_keywords = get_post_meta($post->ID,'seo_keywords',true); // CoLabsSEO
		$aio_keywords = get_post_meta($post->ID,'_aioseop_keywords',true); // All-in-One SEO Pack
		$headspace_keywords = get_post_meta($post->ID,'_headspace_keywords',true); // Headspace SEO
		$wpseo_keywords = get_post_meta($post->ID,'_yoast_wpseo_focuskw',true); // WordPress SEO
		
		//Singular setup
		
		if(!empty($aio_keywords) AND $use_third_party_data) {
			$singular_key_option = 'aioseo';
		} elseif(!empty($headspace_keywords) AND $use_third_party_data) {
			$singular_key_option = 'headspace';
		} elseif( ! empty( $wpseo_keywords ) AND $use_third_party_data) {
			$singular_key_option = 'wpseo';
		}	
			
		if(is_home() OR is_front_page()){
			switch($home_key_option){
				case 'a': $keywords = '';
				break;
				case 'c': $keywords = get_option( 'seo_colabs_meta_home_key_custom' );
				break;
			}
		}
		elseif(is_singular()){
			
			switch($singular_key_option){
				case 'a': $keywords = '';
				break;
				case 'b': $keywords = $colabsseo_keywords;
				break;
				case 'c': 
					
					$the_keywords = array(); 
					//Tags
					if(get_the_tags($post->ID)){ 
						foreach(get_the_tags($post->ID) as $tag) {
							$tag_name = $tag->name; 
							$the_keywords[] = strtolower($tag_name);
						}
					}
					//Cats
					if(get_the_category($post->ID)){ 
						foreach(get_the_category($post->ID) as $cat) {
							$cat_name = $cat->name; 
							$the_keywords[] = strtolower($cat_name);
						}
					}
					//Other Taxonomies
					$all_taxonomies = get_taxonomies();
					$addon_taxonomies = array();
					if(!empty($all_taxonomies)){
						foreach($all_taxonomies as $key => $taxonomies){
							if(	$taxonomies != 'category' AND 
								$taxonomies != 'post_tag' AND 
								$taxonomies != 'nav_menu' AND
								$taxonomies != 'link_category'){
								$addon_taxonomies[] = $taxonomies;
							}
						}
					}
					$addon_terms = array();
					if(!empty($addon_taxonomies)){
						foreach($addon_taxonomies as $taxonomies){
							$addon_terms[] = get_the_terms($post->ID, $taxonomies);
						}
					}
					if(!empty($addon_terms)){
						 foreach($addon_terms as $addon){
						 	if(!empty($addon)){
						 		foreach($addon as $term){
						 			$the_keywords[] = strtolower($term->name);
						 		}
						 	}
						 }
					}
					$keywords = implode( ",",$the_keywords);
				break;
				case 'aioseo':  $keywords = $aio_keywords; // All-in-One Title
				break;
				case 'headspace':  $keywords = $headspace_keywords; // Headspace Title
				break;
				case 'wpseo':  $keywords = $wpseo_keywords; // Headspace Title
				break;
				}
		}
		
		if(empty($keywords) AND get_option( 'seo_colabs_meta_single_key_sitewide') == 'true'){
			$keywords = get_option( 'seo_colabs_meta_single_key_custom' );
		}
		
		$keywords = htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8' );
		$keywords = stripslashes($keywords);

		
		if(!empty($keywords)){
			echo '<meta name="keywords" content="'.$keywords.'" />' . "\n";
		}
		
}


//Add Post Custom Settings
add_action( 'admin_head','seo_add_custom' );

function seo_add_custom() {

		$seo_template = array();
		
		$seo_colabs_wp_title = get_option( 'seo_colabs_wp_title' );
		$seo_colabs_meta_single_desc = get_option( 'seo_colabs_meta_single_desc' );
		$seo_colabs_meta_single_key = get_option( 'seo_colabs_meta_single_key' );
		
		// a = off
		if( $seo_colabs_wp_title != 'true' OR $seo_colabs_meta_single_desc == 'a' OR $seo_colabs_meta_single_key == 'a') {
			
			$output = "";
			if ( $seo_colabs_wp_title != 'true' )
				$output .= "Custom Page Titles, ";
			if ( $seo_colabs_meta_single_desc == 'a' )
				$output .= "Custom Descriptions, ";
			if ( $seo_colabs_meta_single_key == 'a' )
				$output .= "Custom Keywords";			
				
			$output = rtrim($output, ", " );
			
			$desc = 'Additional SEO custom fields available: <strong>'.$output.'</strong>. Go to <a href="' . admin_url( 'admin.php?page=colabsthemes_seo' ) . '">SEO Settings</a> page to activate.';
			
		} else {
			$desc = 'Go to <a href="'.admin_url( 'admin.php?page=colabsthemes_seo').'">SEO Settings</a> page for more SEO options.';
		}
		
		$seo_template[] = array (	"name"  => "seo_info_1",
										"std" => "",
										"label" => "SEO ",
										"type" => "info",
										"desc" => $desc);

		// Change checkbox depending on "Add meta for Posts & Pages to 'follow' by default" checkbox value.
		
		$followstatus = get_option( 'seo_colabs_meta_single_follow' );

		if ( $followstatus != "true" ) { 

			$seo_template[] = array (	"name"  => "seo_follow", 
											"std" => 'false', 
											"label" => "Set follow",
											"type" => "checkbox",
											"desc" => "Let search engines <strong>follow</strong> links on this post/page." );
										
		} else {
		
			$seo_template[] = array (	"name"  => "seo_follow", 
											"std" => 'false', 
											"label" => "Set nofollow",
											"type" => "checkbox",
											"desc" => "Prevent search engines from following links on this post/page." );
		
		} // End IF Statement
		
		$seo_template[] = array (	"name"  => "seo_noindex",
										"std" => "false",
										"label" => "Set noindex",
										"type" => "checkbox",
										"desc" => "Prevent search engines from indexing this post/page." );

		if( get_option( 'seo_colabs_wp_title') == 'true'){
		$seo_template[] = array (	"name"  => "seo_title",
										"std" => "",
										"label" => "Custom Page Title",
										"type" => "text",
										"desc" => "Add a custom title for this post/page." );
		}
		
		if( get_option( 'seo_colabs_meta_single_desc') == 'b'){								
		$seo_template[] = array (	"name"  => "seo_description",
										"std" => "",
										"label" => "Custom Description",
										"type" => "textarea",
										"desc" => "Add a custom meta description for this post/page." );
		}
		
		if( get_option( 'seo_colabs_meta_single_key') == 'b'){			
		$seo_template[] = array (	"name"  => "seo_keywords",
										"std" => "",
										"label" => "Custom Keywords",
										"type" => "text",
										"desc" => "Add custom meta keywords for this post/page. (comma separated)" );	
		}
		
		//3rd Party Plugins
		if(get_option( 'seo_colabs_use_third_party_data') == 'true'){
			$use_third_party_data = true;
		} else {
			$use_third_party_data = false;
		}
		
		if( (
			class_exists( 'All_in_One_SEO_Pack') || 
    		class_exists( 'Headspace_Plugin') || 
    		class_exists( 'WPSEO_Admin' ) || 
    		class_exists( 'WPSEO_Frontend' )
			) AND 
		( $use_third_party_data == true )) { 
			delete_option( 'colabs_custom_seo_template' ); 
		}
		else {

			update_option( 'colabs_custom_seo_template',$seo_template);
			
		}	

}

/*-----------------------------------------------------------------------------------*/
/* CoLabs Text Trimmer */
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'colabs_text_trim') ) {
	function colabs_text_trim($text, $words = 50)
	{ 
		$matches = preg_split( "/\s+/", $text, $words + 1);
		$sz = count($matches);
		if ($sz > $words) 
		{
			unset($matches[$sz-1]);
			return implode( ' ',$matches)." ...";
		}
		return $text;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Enable Home link in WP Menus
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'colabs_home_page_menu_args') ) {
	function colabs_home_page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}
	add_filter( 'wp_page_menu_args', 'colabs_home_page_menu_args' );
}

/*-----------------------------------------------------------------------------------*/
/* Buy Themes page
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'colabsthemes_more_themes_page') ) {
	function colabsthemes_more_themes_page(){
        ?>
        <div class="wrap themes-page">
	        <h2>More CoLabsThemes</h2>
	        
			<?php // Get RSS Feed(s)
	        include_once(ABSPATH . WPINC . '/feed.php' );
	        $rss = fetch_feed( 'http://www.colorlabsproject.com/?feed=more_themes' );			
	        // If the RSS is failed somehow.
	        if ( is_wp_error($rss) ) {
	            $error = $rss->get_error_code();
	            if($error == 'simplepie-error') {
	                //Simplepie Error
	                echo "<div class='updated fade'><p>An error has occured with the RSS feed. (<code>". $error ."</code>)</p></div>";
	            }
	            return;
	         } 
	        ?>
	        <div class="info">
		        <a href="http://colorlabsproject.com/member/member.php">Member Area</a>
		        <a href="http://colorlabsproject.com/themes/">Themes Gallery</a>
		        <a href="http://colorlabsproject.com/showcase/">Theme Showcase</a>
	        </div>
	        
	        <?php
	        
	        $maxitems = $rss->get_item_quantity(10); 
	        $items = $rss->get_items(0, 10);
	        
	        ?>
	        <ul class="themes">
	        <?php if (empty($items)) echo '<li>No items</li>';
	        else
	        foreach ( $items as $item ) : ?>
	            <li class="theme">
	                <?php echo $item->get_description();?>
	            </li>
	        <?php 
	        endforeach; ?>
	        </ul>
        </div>
        
        <?php
	}
}

/*---------------------------------------------------------------------------------*/
/* Detects the Charset of String and Converts it to UTF-8 */
/*---------------------------------------------------------------------------------*/
if ( !function_exists( 'colabs_encoding_convert') ) {
	function colabs_encoding_convert($str_to_convert) {
		if ( function_exists( 'mb_detect_encoding') ) {
			$str_lang_encoding = mb_detect_encoding($str_to_convert);
			//if no encoding detected, assume UTF-8
			if (!$str_lang_encoding) {
				//UTF-8 assumed
				$str_lang_converted_utf = $str_to_convert;
			} else {
				//Convert to UTF-8
				$str_lang_converted_utf = mb_convert_encoding($str_to_convert, 'UTF-8', $str_lang_encoding);
			}
		} else {
			$str_lang_converted_utf = $str_to_convert;
		}
	
		return $str_lang_converted_utf;
	}
}

/*-----------------------------------------------------------------------------------*/
/* colabs_pagination() - Custom loop pagination function  */
/*-----------------------------------------------------------------------------------*/
/*
/* Additional documentation: http://codex.wordpress.org/Function_Reference/paginate_links
/*
/* Params:
/*
/* Arguments Array:
/*
/* 'base' (optional) 				- The query argument on which to determine the pagination (for advanced users)
/* 'format' (optional) 				- The format in which the query argument is formatted in it's raw format (for advanced users)
/* 'total' (optional) 				- The total amount of pages
/* 'current' (optional) 			- The current page number
/* 'prev_next' (optional) 			- Whether to include the previous and next links in the list or not.
/* 'prev_text' (optional) 			- The previous page text. Works only if 'prev_next' argument is set to true.
/* 'next_text' (optional) 			- The next page text. Works only if 'prev_next' argument is set to true.
/* 'show_all' (optional) 			- If set to True, then it will show all of the pages instead of a short list of the pages near the current page. By default, the 'show_all' is set to false and controlled by the 'end_size' and 'mid_size' arguments.
/* 'end_size' (optional) 			- How many numbers on either the start and the end list edges.
/* 'mid_size' (optional) 			- How many numbers to either side of current page, but not including current page.
/* 'add_fragment' (optional) 		- An array of query args to add using add_query_arg().
/* 'type' (optional) 				- Controls format of the returned value. Possible values are:
									  'plain' - A string with the links separated by a newline character.
									  'array' - An array of the paginated link list to offer full control of display.
									  'list' - Unordered HTML list.
/* 'before' (optional) 				- The HTML to display before the paginated links.
/* 'after' (optional) 				- The HTML to display after the paginated links.
/* 'echo' (optional) 				- Whether or not to display the paginated links (alternative is to "return").
/*
/* Query Parameter (optional) 		- Specify a custom query which you'd like to paginate.
/*
/*-----------------------------------------------------------------------------------*/
/**
 * colabs_pagination() is used for paginating the various archive pages created by WordPress. This is not
 * to be used on single.php or other single view pages.
 *
 * @since 3.7.0
 * @uses paginate_links() Creates a string of paginated links based on the arguments given.
 * @param array $args Arguments to customize how the page links are output.
 * @param object $query An optional custom query to paginate.
 */

if ( ! function_exists( 'colabs_pagination' ) ) {

	function colabs_pagination( $args = array(), $query = '' ) {
		global $wp_rewrite, $wp_query;
		
		do_action( 'colabs_pagination_start' );
		
		if ( $query ) {
		
			$wp_query = $query;
		
		} // End IF Statement
	
		/* If there's not more than one page, return nothing. */
		if ( 1 >= $wp_query->max_num_pages )
			return;
	
		/* Get the current page. */
		$current = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
	
		/* Get the max number of pages. */
		$max_num_pages = intval( $wp_query->max_num_pages );
	
		/* Set up some default arguments for the paginate_links() function. */
		$defaults = array(
			'base' => add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'total' => $max_num_pages,
			'current' => $current,
			'prev_next' => true,
			'prev_text' => __( '&laquo; Previous', 'colabsthemes' ), // Translate in WordPress. This is the default.
			'next_text' => __( 'Next &raquo;', 'colabsthemes' ), // Translate in WordPress. This is the default.
			'show_all' => false,
			'end_size' => 1,
			'mid_size' => 1,
			'add_fragment' => '',
			'type' => 'plain',
			'before' => '<div class="pagination colabs-pagination">', // Begin colabs_pagination() arguments.
			'after' => '</div>',
			'echo' => true,
		);
	
		/* Add the $base argument to the array if the user is using permalinks. */
		if( $wp_rewrite->using_permalinks() )
			$defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );
	
		/* If we're on a search results page, we need to change this up a bit. */
		if ( is_search() ) {
		/* If we're in BuddyPress, use the default "unpretty" URL structure. */
			if ( class_exists( 'BP_Core_User' ) ) {
				
				$search_query = get_query_var( 's' );
				$paged = get_query_var( 'paged' );
				
				$base = user_trailingslashit( home_url() ) . '?s=' . $search_query . '&paged=%#%';
				
				$defaults['base'] = $base;
			} else {
				$search_permastruct = $wp_rewrite->get_search_permastruct();
				if ( !empty( $search_permastruct ) )
					$defaults['base'] = user_trailingslashit( trailingslashit( get_search_link() ) . 'page/%#%' );
			}
		}
	
		/* Merge the arguments input with the defaults. */
		$args = wp_parse_args( $args, $defaults );
	
		/* Allow developers to overwrite the arguments with a filter. */
		$args = apply_filters( 'colabs_pagination_args', $args );
	
		/* Don't allow the user to set this to an array. */
		if ( 'array' == $args['type'] )
			$args['type'] = 'plain';
		
		/* Make sure raw querystrings are displayed at the end of the URL, if using pretty permalinks. */
		$pattern = '/\?(.*?)\//i';
		
		preg_match( $pattern, $args['base'], $raw_querystring );
		
		if( $wp_rewrite->using_permalinks() && $raw_querystring )
			$raw_querystring[0] = str_replace( '', '', $raw_querystring[0] );
			$args['base'] = str_replace( $raw_querystring[0], '', $args['base'] );
			$args['base'] .= substr( $raw_querystring[0], 0, -1 );
		
		/* Get the paginated links. */
		$page_links = paginate_links( $args );
	
		/* Remove 'page/1' from the entire output since it's not needed. */
		$page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'' ), '\'', $page_links );
	
		/* Wrap the paginated links with the $before and $after elements. */
		$page_links = $args['before'] . $page_links . $args['after'];
	
		/* Allow devs to completely overwrite the output. */
		$page_links = apply_filters( 'colabs_pagination', $page_links );
	
		do_action( 'colabs_pagination_end' );
		
		/* Return the paginated links for use in themes. */
		if ( $args['echo'] )
			echo $page_links;
		else
			return $page_links;
			
	} // End colabs_pagination()

} // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* colabs_breadcrumbs() - Custom breadcrumb generator function  */
/*
/* Params:
/*
/* Arguments Array:
/*
/* 'separator' 			- The character to display between the breadcrumbs.
/* 'before' 			- HTML to display before the breadcrumbs.
/* 'after' 				- HTML to display after the breadcrumbs.
/* 'front_page' 		- Include the front page at the beginning of the breadcrumbs.
/* 'show_home' 			- If $show_home is set and we're not on the front page of the site, link to the home page.
/* 'echo' 				- Specify whether or not to echo the breadcrumbs. Alternative is "return".
/*
/*-----------------------------------------------------------------------------------*/
/**
 * The code below is inspired by Justin Tadlock's Hybrid Core.
 *
 * colabs_breadcrumbs() shows a breadcrumb for all types of pages.  Themes and plugins can filter $args or input directly.  
 * Allow filtering of only the $args using get_the_breadcrumb_args.
 *
 * @since 3.7.0
 * @param array $args Mixed arguments for the menu.
 * @return string Output of the breadcrumb menu.
 */
function colabs_breadcrumbs( $args = array() ) {
	global $wp_query, $wp_rewrite;

	/* Get the textdomain. */
	$textdomain = 'colabsthemes';

	/* Create an empty variable for the breadcrumb. */
	$breadcrumb = '';

	/* Create an empty array for the trail. */
	$trail = array();
	$path = '';

	/* Set up the default arguments for the breadcrumb. */
	$defaults = array(
		'separator' => '&raquo;',
		'before' => '<span class="breadcrumb-title">' . __( 'You are here:', $textdomain ) . '</span>',
		'after' => false,
		'front_page' => true,
		'show_home' => __( 'Home', $textdomain ),
		'echo' => true
	);

	/* Allow singular post views to have a taxonomy's terms prefixing the trail. */
	if ( is_singular() )
		$defaults["singular_{$wp_query->post->post_type}_taxonomy"] = false;

	/* Apply filters to the arguments. */
	$args = apply_filters( 'colabs_breadcrumbs_args', $args );

	/* Parse the arguments and extract them for easy variable naming. */
	extract( wp_parse_args( $args, $defaults ) );

	/* If $show_home is set and we're not on the front page of the site, link to the home page. */
	if ( !is_front_page() && $show_home )
		$trail[] = '<a href="' . home_url() . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home" class="trail-begin">' . $show_home . '</a>';

	/* If viewing the front page of the site. */
	if ( is_front_page() ) {
		if ( !$front_page )
			$trail = false;
		elseif ( $show_home )
			$trail['trail_end'] = "{$show_home}";
	}

	/* If viewing the "home"/posts page. */
	elseif ( is_home() ) {
		$home_page = get_page( $wp_query->get_queried_object_id() );
		$trail = array_merge( $trail, colabs_breadcrumbs_get_parents( $home_page->post_parent, '' ) );
		$trail['trail_end'] = get_the_title( $home_page->ID );
	}

	/* If viewing a singular post (page, attachment, etc.). */
	elseif ( is_singular() ) {

		/* Get singular post variables needed. */
		$post = $wp_query->get_queried_object();
		$post_id = absint( $wp_query->get_queried_object_id() );
		$post_type = $post->post_type;
		$parent = $post->post_parent;

		/* If a custom post type, check if there are any pages in its hierarchy based on the slug. */
		if ( 'page' !== $post_type ) {

			$post_type_object = get_post_type_object( $post_type );

			/* If $front has been set, add it to the $path. */
			if ( 'post' == $post_type || 'attachment' == $post_type || ( $post_type_object->rewrite['with_front'] && $wp_rewrite->front ) )
				$path .= trailingslashit( $wp_rewrite->front );

			/* If there's a slug, add it to the $path. */
			if ( !empty( $post_type_object->rewrite['slug'] ) )
				$path .= $post_type_object->rewrite['slug'];

			/* If there's a path, check for parents. */
			if ( !empty( $path ) )
				$trail = array_merge( $trail, colabs_breadcrumbs_get_parents( '', $path ) );

			/* If there's an archive page, add it to the trail. */
			if ( !empty( $post_type_object->rewrite['archive'] ) && function_exists( 'get_post_type_archive_link' ) )
				$trail[] = '<a href="' . get_post_type_archive_link( $post_type ) . '" title="' . esc_attr( $post_type_object->labels->name ) . '">' . $post_type_object->labels->name . '</a>';
		}

		/* If the post type path returns nothing and there is a parent, get its parents. */
		if ( empty( $path ) && 0 !== $parent || 'attachment' == $post_type )
			$trail = array_merge( $trail, colabs_breadcrumbs_get_parents( $parent, '' ) );

		/* Display terms for specific post type taxonomy if requested. */
		if ( isset( $args["singular_{$post_type}_taxonomy"] ) && $terms = get_the_term_list( $post_id, $args["singular_{$post_type}_taxonomy"], '', ', ', '' ) )
			$trail[] = $terms;

		/* End with the post title. */
		$post_title = get_the_title( $post_id ); // Force the post_id to make sure we get the correct page title.
		if ( !empty( $post_title ) )
			$trail['trail_end'] = $post_title;
	}

	/* If we're viewing any type of archive. */
	elseif ( is_archive() ) {

		/* If viewing a taxonomy term archive. */
		if ( is_tax() || is_category() || is_tag() ) {

			/* Get some taxonomy and term variables. */
			$term = $wp_query->get_queried_object();
			$taxonomy = get_taxonomy( $term->taxonomy );

			/* Get the path to the term archive. Use this to determine if a page is present with it. */
			if ( is_category() )
				$path = get_option( 'category_base' );
			elseif ( is_tag() )
				$path = get_option( 'tag_base' );
			else {
				if ( $taxonomy->rewrite['with_front'] && $wp_rewrite->front )
					$path = trailingslashit( $wp_rewrite->front );
				$path .= $taxonomy->rewrite['slug'];
			}

			/* Get parent pages by path if they exist. */
			if ( $path )
				$trail = array_merge( $trail, colabs_breadcrumbs_get_parents( '', $path ) );

			/* If the taxonomy is hierarchical, list its parent terms. */
			if ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent )
				$trail = array_merge( $trail, colabs_breadcrumbs_get_term_parents( $term->parent, $term->taxonomy ) );

			/* Add the term name to the trail end. */
			$trail['trail_end'] = $term->name;
		}

		/* If viewing a post type archive. */
		elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {

			/* Get the post type object. */
			$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );

			/* If $front has been set, add it to the $path. */
			if ( $post_type_object->rewrite['with_front'] && $wp_rewrite->front )
				$path .= trailingslashit( $wp_rewrite->front );

			/* If there's a slug, add it to the $path. */
			if ( !empty( $post_type_object->rewrite['archive'] ) )
				$path .= $post_type_object->rewrite['archive'];

			/* If there's a path, check for parents. */
			if ( !empty( $path ) )
				$trail = array_merge( $trail, colabs_breadcrumbs_get_parents( '', $path ) );

			/* Add the post type [plural] name to the trail end. */
			$trail['trail_end'] = $post_type_object->labels->name;
		}

		/* If viewing an author archive. */
		elseif ( is_author() ) {

			/* If $front has been set, add it to $path. */
			if ( !empty( $wp_rewrite->front ) )
				$path .= trailingslashit( $wp_rewrite->front );

			/* If an $author_base exists, add it to $path. */
			if ( !empty( $wp_rewrite->author_base ) )
				$path .= $wp_rewrite->author_base;

			/* If $path exists, check for parent pages. */
			if ( !empty( $path ) )
				$trail = array_merge( $trail, colabs_breadcrumbs_get_parents( '', $path ) );

			/* Add the author's display name to the trail end. */
			$trail['trail_end'] = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
		}

		/* If viewing a time-based archive. */
		elseif ( is_time() ) {

			if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
				$trail['trail_end'] = get_the_time( __( 'g:i a', $textdomain ) );

			elseif ( get_query_var( 'minute' ) )
				$trail['trail_end'] = sprintf( __( 'Minute %1$s', $textdomain ), get_the_time( __( 'i', $textdomain ) ) );

			elseif ( get_query_var( 'hour' ) )
				$trail['trail_end'] = get_the_time( __( 'g a', $textdomain ) );
		}

		/* If viewing a date-based archive. */
		elseif ( is_date() ) {

			/* If $front has been set, check for parent pages. */
			if ( $wp_rewrite->front )
				$trail = array_merge( $trail, colabs_breadcrumbs_get_parents( '', $wp_rewrite->front ) );

			if ( is_day() ) {
				$trail[] = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', $textdomain ) ) . '">' . get_the_time( __( 'Y', $textdomain ) ) . '</a>';
				$trail[] = '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '" title="' . get_the_time( esc_attr__( 'F', $textdomain ) ) . '">' . get_the_time( __( 'F', $textdomain ) ) . '</a>';
				$trail['trail_end'] = get_the_time( __( 'j', $textdomain ) );
			}

			elseif ( get_query_var( 'w' ) ) {
				$trail[] = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', $textdomain ) ) . '">' . get_the_time( __( 'Y', $textdomain ) ) . '</a>';
				$trail['trail_end'] = sprintf( __( 'Week %1$s', $textdomain ), get_the_time( esc_attr__( 'W', $textdomain ) ) );
			}

			elseif ( is_month() ) {
				$trail[] = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', $textdomain ) ) . '">' . get_the_time( __( 'Y', $textdomain ) ) . '</a>';
				$trail['trail_end'] = get_the_time( __( 'F', $textdomain ) );
			}

			elseif ( is_year() ) {
				$trail['trail_end'] = get_the_time( __( 'Y', $textdomain ) );
			}
		}
	}

	/* If viewing search results. */
	elseif ( is_search() )
		$trail['trail_end'] = sprintf( __( 'Search results for &quot;%1$s&quot;', $textdomain ), esc_attr( get_search_query() ) );

	/* If viewing a 404 error page. */
	elseif ( is_404() )
		$trail['trail_end'] = __( '404 Not Found', $textdomain );

	/* Connect the breadcrumb trail if there are items in the trail. */
	if ( is_array( $trail ) ) {

		/* Open the breadcrumb trail containers. */
		$breadcrumb = '<div class="breadcrumb breadcrumbs colabs-breadcrumbs"><div class="breadcrumb-trail">';

		/* If $before was set, wrap it in a container. */
		if ( !empty( $before ) )
			$breadcrumb .= '<span class="trail-before">' . $before . '</span> ';

		/* Wrap the $trail['trail_end'] value in a container. */
		if ( !empty( $trail['trail_end'] ) )
			$trail['trail_end'] = '<span class="trail-end">' . $trail['trail_end'] . '</span>';

		/* Format the separator. */
		if ( !empty( $separator ) )
			$separator = '<span class="sep">' . $separator . '</span>';

		/* Join the individual trail items into a single string. */
		$breadcrumb .= join( " {$separator} ", $trail );

		/* If $after was set, wrap it in a container. */
		if ( !empty( $after ) )
			$breadcrumb .= ' <span class="trail-after">' . $after . '</span>';

		/* Close the breadcrumb trail containers. */
		$breadcrumb .= '</div></div>';
	}

	/* Allow developers to filter the breadcrumb trail HTML. */
	$breadcrumb = apply_filters( 'colabs_breadcrumbs', $breadcrumb );

	/* Output the breadcrumb. */
	if ( $echo )
		echo $breadcrumb;
	else
		return $breadcrumb;

} // End colabs_breadcrumbs()

/*-----------------------------------------------------------------------------------*/
/* colabs_breadcrumbs_get_parents() - Retrieve the parents of the current page/post */
/*-----------------------------------------------------------------------------------*/
/**
 * Gets parent pages of any post type or taxonomy by the ID or Path.  The goal of this function is to create 
 * a clear path back to home given what would normally be a "ghost" directory.  If any page matches the given 
 * path, it'll be added.  But, it's also just a way to check for a hierarchy with hierarchical post types.
 *
 * @since 3.7.0
 * @param int $post_id ID of the post whose parents we want.
 * @param string $path Path of a potential parent page.
 * @return array $trail Array of parent page links.
 */
function colabs_breadcrumbs_get_parents( $post_id = '', $path = '' ) {

	/* Set up an empty trail array. */
	$trail = array();

	/* If neither a post ID nor path set, return an empty array. */
	if ( empty( $post_id ) && empty( $path ) )
		return $trail;

	/* If the post ID is empty, use the path to get the ID. */
	if ( empty( $post_id ) ) {

		/* Get parent post by the path. */
		$parent_page = get_page_by_path( $path );

		/* If a parent post is found, set the $post_id variable to it. */
		if ( !empty( $parent_page ) )
			$post_id = $parent_page->ID;
	}

	/* If a post ID and path is set, search for a post by the given path. */
	if ( $post_id == 0 && !empty( $path ) ) {

		/* Separate post names into separate paths by '/'. */
		$path = trim( $path, '/' );
		preg_match_all( "/\/.*?\z/", $path, $matches );

		/* If matches are found for the path. */
		if ( isset( $matches ) ) {

			/* Reverse the array of matches to search for posts in the proper order. */
			$matches = array_reverse( $matches );

			/* Loop through each of the path matches. */
			foreach ( $matches as $match ) {

				/* If a match is found. */
				if ( isset( $match[0] ) ) {

					/* Get the parent post by the given path. */
					$path = str_replace( $match[0], '', $path );
					$parent_page = get_page_by_path( trim( $path, '/' ) );

					/* If a parent post is found, set the $post_id and break out of the loop. */
					if ( !empty( $parent_page ) && $parent_page->ID > 0 ) {
						$post_id = $parent_page->ID;
						break;
					}
				}
			}
		}
	}

	/* While there's a post ID, add the post link to the $parents array. */
	while ( $post_id ) {

		/* Get the post by ID. */
		$page = get_page( $post_id );

		/* Add the formatted post link to the array of parents. */
		$parents[]  = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . get_the_title( $post_id ) . '</a>';

		/* Set the parent post's parent to the post ID. */
		$post_id = $page->post_parent;
	}

	/* If we have parent posts, reverse the array to put them in the proper order for the trail. */
	if ( isset( $parents ) )
		$trail = array_reverse( $parents );

	/* Return the trail of parent posts. */
	return $trail;

} // End colabs_breadcrumbs_get_parents()

/*-----------------------------------------------------------------------------------*/
/* colabs_breadcrumbs_get_term_parents() - Retrieve the parents of the current term */
/*-----------------------------------------------------------------------------------*/
/**
 * Searches for term parents of hierarchical taxonomies.  This function is similar to the WordPress 
 * function get_category_parents() but handles any type of taxonomy.
 *
 * @since 3.7.0
 * @param int $parent_id The ID of the first parent.
 * @param object|string $taxonomy The taxonomy of the term whose parents we want.
 * @return array $trail Array of links to parent terms.
 */
function colabs_breadcrumbs_get_term_parents( $parent_id = '', $taxonomy = '' ) {

	/* Set up some default arrays. */
	$trail = array();
	$parents = array();

	/* If no term parent ID or taxonomy is given, return an empty array. */
	if ( empty( $parent_id ) || empty( $taxonomy ) )
		return $trail;

	/* While there is a parent ID, add the parent term link to the $parents array. */
	while ( $parent_id ) {

		/* Get the parent term. */
		$parent = get_term( $parent_id, $taxonomy );

		/* Add the formatted term link to the array of parent terms. */
		$parents[] = '<a href="' . get_term_link( $parent, $taxonomy ) . '" title="' . esc_attr( $parent->name ) . '">' . $parent->name . '</a>';

		/* Set the parent term's parent as the parent ID. */
		$parent_id = $parent->parent;
	}

	/* If we have parent terms, reverse the array to put them in the proper order for the trail. */
	if ( !empty( $parents ) )
		$trail = array_reverse( $parents );

	/* Return the trail of parent terms. */
	return $trail;
	
} // End colabs_breadcrumbs_get_term_parents()

/*-----------------------------------------------------------------------------------*/
/* get_admin_color() - Echo list of current wp admin css colors */
/*-----------------------------------------------------------------------------------*/
function get_admin_color(){
    
    //$_wp_admin_css_colors[$key] = (object) array('name' => $name, 'url' => $url, 'colors' => $colors);
    global $_wp_admin_css_colors;
    //get current colors[0] variable
    echo  $_wp_admin_css_colors[get_user_option('admin_color')] -> {'colors'}[0];
    echo '<br />';
    //get current admin color scheme
    echo get_user_option('admin_color'); 
    echo '<br />'; 
    print_r($_wp_admin_css_colors); 
}

/*-----------------------------------------------------------------------------------*/
/* WordPress Admin Bar-related */
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Disable WordPress Admin Bar */
/*-----------------------------------------------------------------------------------*/

$colabs_admin_bar_disable = get_option( 'framework_colabs_admin_bar_disable' );

if ( $colabs_admin_bar_disable == 'true' ) {
	add_filter( 'show_admin_bar', '__return_false' );

	add_action( 'admin_print_scripts-profile.php', 'colabs_hide_admin_bar_prefs' );

	function colabs_hide_admin_bar_prefs () { ?>
	<style type="text/css">
	    .show-admin-bar { display: none; }
	</style>
	<?php
	} // End colabs_hide_admin_bar_prefs()
}

/*-----------------------------------------------------------------------------------*/
/* Enhancements to the WordPress Admin Bar */
/*-----------------------------------------------------------------------------------*/

if ( $colabs_admin_bar_disable != 'true' && is_user_logged_in() && current_user_can( 'manage_options' ) ) {

	$colabs_admin_bar_enhancements = get_option( 'framework_colabs_admin_bar_enhancements' );

	if ( $colabs_admin_bar_enhancements == 'true' ) {

		add_action( 'admin_bar_menu', 'colabs_admin_bar_menu', 20 );

	}

} // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* colabs_admin_bar_menu() - Add menu items to the admin bar. */
/*-----------------------------------------------------------------------------------*/

function colabs_admin_bar_menu () {

	global $wp_admin_bar, $current_user;
    $current_user_id = $current_user->user_login;
    $super_user = get_option( 'framework_colabs_super_user' );

	$theme_data = get_theme_data( get_template_directory() . '/style.css' );

	$menu_label = __( 'ColorLabs Project', 'colabsthemes' );

	// Customise menu label to the child theme's name.
	if ( is_array( $theme_data ) && array_key_exists( 'Name', $theme_data ) ) {
		$menu_label = $theme_data['Name'];
	}

	// Main ColorLabs Menu Item
	$wp_admin_bar->add_menu( array( 'id' => 'colabsthemes', 'title' => $menu_label, 'href' => admin_url('admin.php?page=colabsthemes') ) );

	// Theme Options
	$wp_admin_bar->add_menu( array( 'parent' => 'colabsthemes', 'id' => 'colabsthemes-theme-options', 'title' => __( 'Theme Options', 'colabsthemes' ), 'href' => admin_url( 'admin.php?page=colabsthemes' ) ) );

	// Sidebar Manager
	if ( get_option( 'framework_colabs_sbm_disable') != 'true' ) {
		$wp_admin_bar->add_menu( array( 'parent' => 'colabsthemes', 'id' => 'colabsthemes-sbm', 'title' => __( 'Sidebar Manager', 'colabsthemes' ), 'href' => admin_url( 'admin.php?page=colabsthemes_sbm' ) ) );
	}

	if ( ( $super_user == $current_user_id ) || empty( $super_user ) ) {

		// Framework Settings
		$wp_admin_bar->add_menu( array( 'parent' => 'colabsthemes', 'id' => 'colabsthemes-framework-settings', 'title' => __( 'Framework Settings', 'colabsthemes' ), 'href' => admin_url( 'admin.php?page=colabsthemes_framework_settings' ) ) );

		// Update Framework
		$wp_admin_bar->add_menu( array( 'parent' => 'colabsthemes', 'id' => 'colabsthemes-update-framework', 'title' => __( 'Update Framework', 'colabsthemes' ), 'href' => admin_url( 'admin.php?page=colabsthemes_framework_update' ) ) );

	} // End IF Statement

} // End colabs_admin_bar_menu()

/*-----------------------------------------------------------------------------------*/
/* colabs_prepare_category_ids_from_option()
 *
 * Setup an array of category IDs, from a given theme option.
 * Attempt to transform category slugs into ID values as well.
 *
 * Params: String $option
 * Return: Array $cats
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'colabs_prepare_category_ids_from_option' ) ) {

	function colabs_prepare_category_ids_from_option ( $option ) {

		$cats = array();

		$stored_cats = get_option( $option );

		$cats_raw = explode( ',', $stored_cats );

		if ( is_array( $cats_raw ) && ( count( $cats_raw ) > 0 ) ) {
			foreach ( $cats_raw as $k => $v ) {
				$value = trim( $v );

				if ( is_numeric( $value ) ) {
					$cats_raw[$k] = $value;
				} else {
					$cat_obj = get_category_by_slug( $value );
					if ( isset( $cat_obj->term_id ) ) {
						$cats_raw[$k] = $cat_obj->term_id;
					}
				}

				$cats = $cats_raw;
			}
		}

		return $cats;

	} // End colabs_prepare_category_ids_from_option()

}

/*-----------------------------------------------------------------------------------*/
/* Move tracking code from footer to header */
/*-----------------------------------------------------------------------------------*/
	
	add_action( 'init', 'colabs_move_tracking_code', 20 );

	function colabs_move_tracking_code () {
		$move_code = get_option( 'framework_colabs_move_tracking_code' );
		
		if ( ! is_admin() && isset( $move_code ) && ( $move_code == 'true' ) ) {
			remove_action( 'wp_footer', 'colabs_analytics' );
			add_action( 'wp_head', 'colabs_analytics', 10 );
		}
	} // End colabs_move_tracking_code()

?>