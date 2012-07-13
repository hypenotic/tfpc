<?php 
//PLEASE UPDATE TABLE OF CONTENTS ON FUNCTIONS ADDED / REMOVED
/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Excerpt
- Page navigation
- CoLabsTabs - Popular Posts
- CoLabsTabs - Latest Posts
- CoLabsTabs - Latest Comments
- Post Meta
- Dynamic Titles
- WordPress 3.0 New Features Support
- using_ie - Check IE
- post-thumbnail - WP 3.0 post thumbnails compatibility
- automatic-feed-links Features
- Twitter button - twitter
- Facebook Like Button - fblike
- Facebook Share Button - fbshare
- Google +1 Button - [google_plusone]
-- Load Javascript for Google +1 Button
- colabs_link - Alternate Link & RSS URL
- Open Graph Meta Function
- colabs_share - Twitter, FB & Google +1
- Post meta Portfolio

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* SET GLOBAL CoLabs VARIABLES
/*-----------------------------------------------------------------------------------*/

// Slider Tags
	$GLOBALS['slide_tags_array'] = array();
// Duplicate posts 
	$GLOBALS['shownposts'] = array();

/*-----------------------------------------------------------------------------------*/
/* Excerpt
/*-----------------------------------------------------------------------------------*/

//Add excerpt on pages
if(function_exists('add_post_type_support'))
add_post_type_support('page', 'excerpt');

/** Excerpt character limit */
/* Excerpt length */
function colabs_excerpt_length($length) {
if( get_option('colabs_excerpt_length') != '' ){
        return get_option('colabs_excerpt_length');
    }else{
        return 45;
    }
}
add_filter('excerpt_length', 'colabs_excerpt_length');

/** Remove [..] in excerpt */
function colabs_trim_excerpt($text) {
  return rtrim($text,'[...]');
}
add_filter('get_the_excerpt', 'colabs_trim_excerpt');

/** Add excerpt more */
function colabs_excerpt_more($more) {
    global $post;
	//return '<span class="more"><a href="'. get_permalink($post->ID) . '">'. __( 'Read more', 'colabsthemes' ) . '&hellip;</a></span>';
}
add_filter('excerpt_more', 'colabs_excerpt_more');

// Shorten Excerpt text for use in theme
function colabs_excerpt($text, $chars = 120) {
	$text = $text." ";
	$text = substr($text,0,$chars);
	$text = substr($text,0,strrpos($text,' '));
	$text = $text."...";
	return $text;
}

//Custom Excerpt Function
function excerpt($limit,$text) {
	global $post;
	if($limit=='')$limit=30;
	$output = $post->post_excerpt;
	if ($output!=''){
	$excerpt = $output;
	}else{
	$excerpt = explode(' ',get_the_excerpt(), $limit);
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt).$text;
	}
	echo $excerpt;
}

// get_the_excerpt filter
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'custom_trim_excerpt');

function custom_trim_excerpt($text) { // Fakes an excerpt if needed
global $post;
	if ( '' == $text ) {
		$text = get_the_content('');

		$text = strip_shortcodes( $text );

		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text);
		$excerpt_length = apply_filters('excerpt_length', 45);
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words) > $excerpt_length) {
			array_pop($words);
            $excerpt_more = apply_filters('excerpt_more', '...');
			array_push($words, '...');
            array_push($words, $excerpt_more);
			$text = implode(' ', $words);
		}
	}
	return $text;
}

/*-----------------------------------------------------------------------------------*/
/* Breadcrumbs */
/*-----------------------------------------------------------------------------------*/
if(!function_exists('the_breadcrumb')){
function the_breadcrumb() {
     echo 'You are here: ';
     if (!is_home()) {
         echo '<a href="';
         echo get_option('home');
         echo '">';
         echo 'Home';
         echo "</a>  ";
		
         if (is_single()) {    
               echo the_title();
         } elseif (is_page()) {
            echo the_title();
         }
         elseif (is_tag()) {
            single_tag_title();
         }
         elseif (is_day()) {
            echo "Archive for "; the_time('F jS, Y');
         }
         elseif (is_month()) {
            echo "Archive for "; the_time('F, Y');
         }
         elseif (is_year()) {
            echo "Archive for "; the_time('Y');
         }
         elseif (is_author()) {
            echo "Author Archive";
         }
         elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
            echo "Blog Archives";
         }
         elseif (is_search()) {
            echo "Search Results : ";
			the_search_query();
         }
         elseif (is_404()) {
            echo "404 Error";
         }
		  elseif (is_category()) {
           $category = get_the_category();
		   echo $category[0]->cat_name;
         }
		 elseif (is_tax()) {
          $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		  echo $term->name;
         }
     }else{
        echo '<a href="';
        echo get_option('home');
        echo '">';
        echo 'Home';
        echo "</a>";
     }
}}

/*End of Breadcrumbs*/

/*-----------------------------------------------------------------------------------*/
/* Paost navigation */
/*-----------------------------------------------------------------------------------*/


if (!function_exists('colabs_postnav')) {
	function colabs_postnav() {
		?>
    <div class="navigation">
        <div class="navleft floatleft"><?php next_post_link('%link',__('&laquo; Prev','colabsthemes'));?></div>
		<div class="navcenter gohome"><a href="<?php echo get_option('home');?>"><?php _e('Back to home','colabsthemes');?></a></div>
        <div class="navright floatright"><?php previous_post_link('%link',__('Next &raquo;','colabsthemes')); ?></div>
        
    </div><!--/.navigation-->
		<?php 
	}
}

/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - No Results */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_no_result')) {
	function colabs_no_result(){
?>
        
                
		<p>It seems that page you were looking for doesn't exist.
Try searching the site.</p>
           

	    
<?php	   
	}
}
/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - Popular Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_tabs_popular')) {
	function colabs_tabs_popular( $posts = 5, $size = 35 ) {
		global $post;
		$popular = get_posts('caller_get_posts=1&orderby=comment_count&showposts='.$posts);
		foreach($popular as $post) :
			setup_postdata($post);
	?>
	<li>
		<span class="floatleft" style="padding-right:10px"><?php if ($size <> 0) colabs_image('height='.$size.'&width='.$size.'&class=thumbnail&single=true'); ?></span>
		<div class="desc">
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a><br/>
		<span class="meta"> <?php the_time( get_option( 'date_format' ) ); ?></span>
		</div>
	</li>
	<?php endforeach;
	}
}

/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - Latest Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_latest_post')) {
	function colabs_latest_post( $posts = 5, $class = '' ) {
		global $post,$wpdb;
			
			wp_reset_query();
			if(is_home() || is_front_page()){
			$cat_headline=get_option('colabs_cat_headline');
			if($cat_headline==''){$cat_headline=1;}
			$cat_featured=get_option('colabs_cat_featured');
			if($cat_featured==''){$cat_featured=1;}	
			}
			$where = apply_filters( 'getarchives_where', "WHERE post_type = 'post' AND post_status = 'publish'", $r );
			$join = apply_filters( 'getarchives_join', '', $r );
			$query = "SELECT YEAR(post_date) AS `year`, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date) ORDER BY post_date DESC";
			$key = md5($query);
			$cache = wp_cache_get( 'wp_get_archives' , 'general');
			if ( !isset( $cache[ $key ] ) ) {
				$arcresults = $wpdb->get_results($query);
				$cache[ $key ] = $arcresults;
				wp_cache_set( 'wp_get_archives', $cache, 'general' );
			} else {
				$arcresults = $cache[ $key ];
			}
			foreach ( (array) $arcresults as $arcresult) {
				$url[] = get_year_link($arcresult->year);
			}
	?>
	<div class="recent-entry columns <?php echo $class;?>">
		<div class="recent-title column <?php echo $class;?>">
			<h6 class="floatleft"><?php _e('Recent Entries','colabsthemes');?></h6>
			<a href="<?php  echo $url[0];?>" class="link-button floatright"><?php _e('Go To Archive','colabsthemes');?></a>
		</div>
		<div class="recent-list columns">	
	<?php
		query_posts(array(
			'category__not_in' => array($cat_headline,$cat_featured),
			'showposts' => $posts,
			));
		if ( have_posts() ) :
		while( have_posts() ) : the_post() ;		
	?>
		<div class="column col2">
			<?php colabs_image('width=138&height=91&play=true'); ?>
			<h4 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'colabsthemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
			<p><?php excerpt(); ?></p>
			<a class="more-link" title="<?php printf( esc_attr__( 'Permalink to %s', 'colabsthemes' ), the_title_attribute( 'echo=0' ) ); ?>" href="<?php the_permalink();?>"><?php _e('Continue Reading','colabsthemes');?> &rarr;</a>
		</div>
	<?php endwhile; endif; ?>
		</div>
	</div>
	<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* CoLabsTabs - Latest Comments */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('colabs_tabs_comments')) {
	function colabs_tabs_comments( $posts = 5, $size = 45 ) {
		global $wpdb;
		$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
		comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved,
		comment_type,comment_author_url,
		SUBSTRING(comment_content,1,50) AS com_excerpt
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
		$wpdb->posts.ID)
		WHERE comment_approved = '1' AND comment_type = '' AND
		post_password = ''
		ORDER BY comment_date_gmt DESC LIMIT ".$posts;
		
		$comments = $wpdb->get_results($sql);
		
		foreach ($comments as $comment) {
		?>
		<li>
			<?php echo get_avatar( $comment, $size ); ?>
			<div class="desc">
			<a href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php _e('on ', 'colabsthemes'); ?> <?php echo $comment->post_title; ?>">
                <span class="author"><?php echo strip_tags($comment->comment_author); ?></span></a>: <span class="comment"><?php echo strip_tags($comment->com_excerpt); ?>...</span>
			
			</div>
		</li>
		<?php 
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Post Meta */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('colabs_post_meta')) {
	function colabs_post_meta( ) {
?>
    <div class="date"> 
		Author :<a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author(); ?></a> &nbsp; &nbsp; 
		<?php if(is_single() || is_category()){?>
        Category : <?php the_category(', ') ?>  &nbsp; &nbsp; 
		<?php }?>
        <span class="date-t">
            <?php the_time('j M ') ?> | <a href="<?php the_permalink(); ?>#comments">
            <?php comments_number('No Comments', '1 Comment', '% Comments'); ?></a>   
            <?php edit_post_link('Edit', '', '  '); ?>
        </span>
    </div>
<?php 
	}
}

/*-----------------------------------------------------------------------------------*/
/* Dynamic Titles */
/*-----------------------------------------------------------------------------------*/
// This sets your <title> depending on what page you're on, for better formatting and for SEO

function dynamictitles() {
	
if ( is_author() ) {
      
      wp_title(__('Articles by','colabsthemes').'');	 
	  echo '<span class="topic-cat">';
	  the_author_posts_link();
	  echo '</span>';
	  
} else if ( is_category() ) {
      echo (__('Topic','colabsthemes').'');
	  echo '<span class="topic-cat">';
      wp_title('');
	  echo '</span>';	

} else if ( is_tag() ) {
     
      echo (__('Tag archive for','colabsthemes').'');
	  echo '<span class="topic-cat">';
      wp_title('');
	  echo '</span>';
} else if ( is_tax() ) {
      echo (__('Format Post for ','colabsthemes'));
	  echo '<span class="topic-cat">';
	  if(get_query_var('filter') != "standard") {
	  echo ucwords(get_post_format());
	  }else{
	  echo (__('Note','colabsthemes').'');
	  }
	  echo '</span>';
} else if ( is_archive() ) {
      
      echo (__('Archive for','colabsthemes').'');
	  echo '<span class="topic-cat">';
      wp_title('');
	  echo '</span>';
} else if ( is_search() ) {
      
      echo (__('Search Results for ','colabsthemes'));
	  echo '<span class="topic-cat">';
	  the_search_query();
	  echo '</span>';
} else if ( is_404() ) {
      
      echo (__('404 Error (Page Not Found)','colabsthemes').'');
	  
} 
}

/*-----------------------------------------------------------------------------------*/
/* WordPress 3.0 New Features Support */
/*-----------------------------------------------------------------------------------*/

if ( function_exists('register_nav_menus') ) {
	add_theme_support( 'nav-menus' );
    register_nav_menus( array(
	'topmenu'		=>	'Top Menu',
	'bottommenu'=>	'Footer Menu'
) );    
}

/* CallBack functions for menus in case of earlier than 3.0 Wordpress version or if no menu is set yet*/
function primarymenu(){ ?>
    <div id="top-menu" class="ddsmoothmenu">
		<ul class="menu" id="menu-menu">
        <li><div> Go to Admin > Appearance > Menus to set up the menu. </div></li>
        </ul>
	</div>
<?php }
if (!function_exists('colabs_nav')) {

function colabs_nav($div_id) {

    if ( function_exists( 'wp_nav_menu' ) )

        if ( $div_id == 'bottommenu' )

        wp_nav_menu('menu_id=&fallback_cb=colabs_nav_fallback&theme_location='.$div_id.'&depth=1&after=');

        else

        wp_nav_menu('menu_class=&menu_id=&fallback_cb=colabs_nav_fallback&theme_location='.$div_id);

    else

        colabs_nav_fallback($div_id);

}}

function add_menuid ($page_markup) {
preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
$divclass = $matches[1];
$toreplace = array('<div class="'.$divclass.'">', '</div>');
$new_markup = str_replace($toreplace, '', $page_markup);
$new_markup = preg_replace('/^<ul>/i', '<ul>', $new_markup);
return $new_markup; }

add_filter('wp_page_menu', 'add_menuid');

if (!function_exists('colabs_nav_fallback')) {
function colabs_nav_fallback($div_id){
    if (is_array($div_id)){ $div_id = $div_id['theme_location']; }
    if ( $div_id == 'main-menu' ){
        wp_page_menu('&depth=0&title_li=');
    };
    if ( $div_id == 'bottommenu' || $div_id == 'topmenu' ){
        wp_page_menu('show_home='.__('Home','colabsthemes').'&depth=1&title_li=');
        };
}}

/*-----------------------------------------------------------------------------------*/
/* using_ie - Check IE */
/*-----------------------------------------------------------------------------------*/
//check IE
function using_ie()
{
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;    
}

/*-----------------------------------------------------------------------------------*/
/*  WP 3.0 post thumbnails compatibility */
/*-----------------------------------------------------------------------------------*/
if(function_exists( 'add_theme_support')){
	//if(get_option( 'colabs_post_image_support') == 'true'){
    if( get_option('colabs_post_image_support') ){
        add_theme_support( 'post-thumbnails' );		
		// set height, width and crop if dynamic resize functionality isn't enabled
		if ( get_option( 'colabs_pis_resize') <> "true" ) {
			$hard_crop = get_option( 'colabs_pis_hard_crop' );
			if($hard_crop == 'true') {$hard_crop = true; } else { $hard_crop = false;} 
			add_image_size('single_featured',474,318, $hard_crop);
			add_image_size('small_thumb',138,91, $hard_crop);
			
		}
	}
} 

/*-----------------------------------------------------------------------------------*/
/*  automatic-feed-links Features  */
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'add_theme_support' ) && get_option('colabs_feedlinkurl') == '' ) {
add_theme_support( 'automatic-feed-links' );
}

/*-----------------------------------------------------------------------------------*/
/* Twitter button - twitter
/*-----------------------------------------------------------------------------------*/
/*

Source: http://twitter.com/goodies/tweetbutton

Optional arguments:
 - style: vertical, horizontal, none ( default: vertical )
 - url: specify URL directly 
 - source: username to mention in tweet
 - related: related account 
 - text: optional tweet text (default: title of page)
 - float: none, left, right (default: left)
 - lang: fr, de, es, js (default: english)
*/
function colabs_shortcode_twitter($atts, $content = null) {
   	extract(shortcode_atts(array(	'url' => '',
   									'style' => 'vertical',
   									'source' => '',
   									'text' => '',
   									'related' => '',
   									'lang' => '',
   									'float' => 'floatleft'), $atts));
	$output = '';

	if ( $url )
		$output .= ' data-url="'.$url.'"';
		
	if ( $source )
		$output .= ' data-via="'.$source.'"';
	
	if ( $text ) 
		$output .= ' data-text="'.$text.'"';

	if ( $related ) 			
		$output .= ' data-related="'.$related.'"';

	if ( $lang ) 			
		$output .= ' data-lang="'.$lang.'"';
	
	$output = '<div class="colabs-sc-twitter '.$float.'"><a href="http://twitter.com/share" class="twitter-share-button"'.$output.' data-count="'.$style.'">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>';	
	return $output;

}
//add_shortcode( 'twitter', 'colabs_shortcode_twitter' );


/*-----------------------------------------------------------------------------------*/
/* Facebook Like Button - fblike
/*-----------------------------------------------------------------------------------*/
/*

Source: http://developers.facebook.com/docs/reference/plugins/like

Optional arguments:
 - float: none (default), left, right
 - url: link you want to share (default: current post ID)
 - style: standard (default), button
 - showfaces: true or false (default)
 - width: 450
 - verb: like (default) or recommend
 - colorscheme: light (default), dark
 - font: arial (default), lucida grande, segoe ui, tahoma, trebuchet ms, verdana

*/
function colabs_shortcode_fblike($atts, $content = null) {
   	extract(shortcode_atts(array(	'float' => 'none',
   									'url' => '',
   									'style' => 'standard',
   									'showfaces' => 'false',
   									'width' => '450',
                                    'height' => '60',
   									'verb' => 'like',
   									'colorscheme' => 'light',
   									'font' => 'arial'), $atts));

	global $post;

	if ( ! $post ) {

		$post = new stdClass();
		$post->ID = 0;

	} // End IF Statement

	$allowed_styles = array( 'standard', 'button_count', 'box_count' );

	if ( ! in_array( $style, $allowed_styles ) ) { $style = 'standard'; } // End IF Statement

	if ( !$url )
		$url = get_permalink($post->ID);

	if ( ! $height || ! is_numeric( $height ) ) { $height = '60'; } // End IF Statement
	if ( $showfaces == 'true')
		$height = '100';

	if ( ! $width || ! is_numeric( $width ) ) { $width = 450; } // End IF Statement

	switch ( $float ) {

		case 'left':

			$float = 'floatleft';

		break;

		case 'right':

			$float = 'floatright';

		break;

		default:
		break;

	} // End SWITCH Statement

	$output = '
<div class="colabs-fblike '.$float.'">
<iframe src="http://www.facebook.com/plugins/like.php?href='.$url.'&amp;layout='.$style.'&amp;show_faces='.$showfaces.'&amp;width='.$width.'&amp;action='.$verb.'&amp;colorscheme='.$colorscheme.'&amp;font=' . $font . '" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:'.$width.'px; height:'.$height.'px"></iframe>
</div>
	';
	return $output;

}
//add_shortcode( 'fblike', 'colabs_shortcode_fblike' );

/*-----------------------------------------------------------------------------------*/
/* Google +1 Button - [google_plusone]
/*-----------------------------------------------------------------------------------*/

function colabs_shortcode_google_plusone ( $atts, $content = null ) {

	global $post;

	$defaults = array(
						'size' => '',
						'language' => '',
						'count' => '',
						'href' => '',
						'callback' => '',
						'float' => 'none'
					);

	$atts = shortcode_atts( $defaults, $atts );

	extract( $atts );

	$allowed_floats = array( 'left' => ' floatleft', 'right' => ' floatright', 'none' => '' );
	if ( ! in_array( $float, array_keys( $allowed_floats ) ) ) { $float = 'none'; }

	$output = '';
	$tag_atts = '';

	// Make sure we only have Google +1 attributes in our array, after parsing the "float" parameter.
	unset( $atts['float'] );

	if ( $atts['href'] == '' & isset( $post->ID ) ) {
		$atts['href'] = get_permalink( $post->ID );
	}

	foreach ( $atts as $k => $v ) {
		if ( ${$k} != '' ) {
			$tag_atts .= ' ' . $k . '="' . ${$k} . '"';
		}
	}

	$output = '<div class="shortcode-google-plusone' . $allowed_floats[$float] . '"><g:plusone' . $tag_atts . '></g:plusone>';

	// Enqueue the Google +1 button JavaScript from their API.
	add_action( 'wp_footer', 'colabs_shortcode_google_plusone_js' );
	//add_action( 'colabs_shortcode_generator_preview_footer', 'colabs_shortcode_google_plusone_js' );
    //do_action ( 'colabs_shortcode_generator_preview_footer' );
    
    //$output .= 	'<script src="https://apis.google.com/js/plusone.js" type="text/javascript"></script>' . "\n" . '<script type="text/javascript">gapi.plusone.go();</script>' . "\n";    
    
    $output .= '</div><!--/.shortcode-google-plusone-->' . "\n";
                    
	return $output . "\n";

} // End colabs_shortcode_google_plusone()

//add_shortcode( 'google_plusone', 'colabs_shortcode_google_plusone' );

/*-----------------------------------------------------------------------------------*/
/* Load Javascript for Google +1 Button
/*-----------------------------------------------------------------------------------*/

function colabs_shortcode_google_plusone_js () {
	echo '<script src="https://apis.google.com/js/plusone.js" type="text/javascript"></script>' . "\n";
	echo '<script type="text/javascript">gapi.plusone.go();</script>' . "\n";
} // End colabs_shortcode_google_plusone_js()

/*-----------------------------------------------------------------------------------*/
/* colabs_link - Alternate Link & RSS URL */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_head', 'colabs_link' );
if (!function_exists('colabs_link')) {
function colabs_link(){ 
?>	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('colabs_feedlinkurl') ) { echo get_option('colabs_feedlinkurl'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	
<?php 
}}

/*-----------------------------------------------------------------------------------*/
/*  Open Graph Meta Function    */
/*-----------------------------------------------------------------------------------*/
function colabs_meta_head(){
    do_action( 'colabs_meta' );
}
add_action( 'colabs_meta', 'og_meta' );  

if (!function_exists('og_meta')) {
function og_meta(){ ?>
	<?php if ( is_home() && get_option( 'colabs_og_enable' ) == '' ) { ?>
	<meta property="og:title" content="<?php echo bloginfo('name');; ?>" />
	<meta property="og:type" content="author" />
	<meta property="og:url" content="<?php echo get_option('home'); ?>" />
	<meta property="og:image" content="<?php echo get_option('colabs_og_img'); ?>"/>
	<meta property="og:site_name" content="<?php echo get_option('colabs_og_sitename'); ?>" />
	<meta property="fb:admins" content="<?php echo get_option('colabs_og_admins'); ?>" />
	<meta property="og:description" content="<?php echo get_option('blogdescription '); ?>" />
	<?php } ?>
	
	<?php if ( ( is_page() || is_single() ) && get_option( 'colabs_og_enable' ) == '' ) { ?>
	<meta property="og:title" content="<?php the_title(); ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo get_post_meta($post->ID, 'yourls_shorturl', true) ?>" />
	<meta property="og:image" content="<?php $values = get_post_custom_values("Image"); ?><?php echo get_option('home'); ?>/<?php echo $values[0]; ?>"/>
	<meta property="og:site_name" content="<?php echo get_option('colabs_og_sitename'); ?>" />
	<meta property="fb:admins" content="<?php echo get_option('colabs_og_admins'); ?>" />
	<?php } ?>
    
    <meta name="viewport" content="width=1024,maximum-scale=1.0" />
<?php
}}

/*-----------------------------------------------------------------------------------*/
/*  colabs_share - Twitter, FB & Google +1    */
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'colabs_share' ) ) {
function colabs_share() {
    
$return = '';


$colabs_share_twitter = get_option('colabs_single_share_twitter');
$colabs_share_fblike = get_option('colabs_single_share_fblike');
$colabs_share_fb = get_option('colabs_single_share_fb');
$colabs_share_google_plusone = get_option('colabs_single_share_google_plusone');


    //Share Button Functions 
    global $colabs_options;
    $url = get_permalink();
    $share = '';
    
    //Twitter Share Button
    if(function_exists('colabs_shortcode_twitter') && $colabs_share_twitter == "true"){
        $tweet_args = array(  'url' => $url,
   							'style' => 'horizontal',
   							'source' => ( $colabs_options['colabs_twitter_username'] )? $colabs_options['colabs_twitter_username'] : '',
   							'text' => '',
   							'related' => '',
   							'lang' => '',
   							'float' => 'left'
                        );

        $share .= colabs_shortcode_twitter($tweet_args);
    }
    
    //Google +1 Share Button
    if( function_exists('colabs_shortcode_google_plusone') && $colabs_share_google_plusone == "true"){
        $google_args = array(
						'size' => 'medium',
						'language' => '',
						'count' => '',
						'href' => $url,
						'callback' => '',
						'float' => 'left'
					);        

        $share .= colabs_shortcode_google_plusone($google_args);       
    }
	
	//Facebook Like Button
    if(function_exists('colabs_shortcode_fblike') && $colabs_share_fblike == "true"){
    $fblike_args = 
    array(	
        'float' => 'left',
        'url' => '',
        'style' => 'button_count',
        'showfaces' => 'false',
        'width' => '74',
        'height' => '35',
        'verb' => 'like',
        'colorscheme' => 'light',
        'font' => 'arial'
        );
        $share .= colabs_shortcode_fblike($fblike_args);    
    }
        
    
    
    $return .= '<div class="social_share clearfloat">'.$share.'</div>';
    
    return $return;
}
}
/*Search widget*/
function custom_search( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </form>';

    return $form;
}

add_filter( 'get_search_form', 'custom_search' );
function the_pagination($pages = '', $range = 2)
{
     $showitems = ($range * 2)+1;
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }
	 
 
     if(1 != $pages)
     {
         echo "<div id='pagination'>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a class='link-button' href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a class='link-button' href='".get_pagenum_link($paged - 1)."'>Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='link-button current'>".$i."</span>":"<a class='link-button' href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a class='link-button' href='".get_pagenum_link($paged + 1)."'>Next</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a class='link-button' href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</div>\n";
     }
}
function modernizm_comments($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;
?>

	<li <?php comment_class(); ?>>
		<div id="comment-<?php comment_ID(); ?>">
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'colabsthemes' ); ?></em>
				<br />
			<?php endif; ?>

			<?php comment_text() ?>

			<div class="comment-author">
				<?php echo get_comment_author_link(); ?>
				<small>on</small>
				<span class="meta"><?php printf( __( '%1$s', 'colabsthemes' ), get_comment_date() ) ?>				
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => __( 'Reply', 'colabsthemes' ),
					'depth' => $depth,
					'max_depth' => $args['max_depth']
				) ) ); ?>
			</div>
		</div>
  
<?php } 
/*-----------------------------------------------------------------------------------*/

/* CoLabsTabs - Flickr */

/*-----------------------------------------------------------------------------------*/    

require_once ($includes_path . 'flickrimages.php');



function getmyflickr($account,$count){

$flickr_url= 'http://api.flickr.com/services/feeds/photos_public.gne?id=';

$flickr_url.= $account ;

$flickr_url.= '&display=latest&lang=en-us&format=rss_200';

$flickr = new FlickrImages( $flickr_url );

	$title = $flickr->getTitle();

	$url = $flickr->getProfileLink();

	$images = $flickr->getImages();

	$i=1;$j=1;	

	

	$output = '<div class="flickr"><ul >';

	foreach( $images as $img ) {

		if ($i<=$count){

		$output .= '<li>';

		$output .= '<a href="' . $img[ 'link' ] . '">';

		$output .=  $img[ 'thumb' ] ;

		$output .= '</a></li>';

		}

		$i++;$j++;

	}

	$output .= '</ul></div>';

	echo $output;



}
/*-----------------------------------------------------------------------------------*/

/* CoLabsTabs - User Meta */

/*-----------------------------------------------------------------------------------*/ 
function new_user_meta( $contactmethods ) {

$contactmethods['twitter'] = 'Twitter';

$contactmethods['facebook'] = 'Facebook';

return $contactmethods;
}
add_filter('user_contactmethods','new_user_meta',10,1);

/*-----------------------------------------------------------------------------------*/
/* Custom Dropdown Menu */
/*-----------------------------------------------------------------------------------*/
class dropdown_walker extends Walker {
	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "&nbsp;&nbsp;", $depth ) : '';
		$class_names = $value = '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		$value = ' value="'. esc_attr( $item->url        ).'" ';
		$output .= '<option' . $id . $value . $class_names .'>' . $indent;
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	function end_el(&$output, $item, $depth) {
		$output .= "</option>\n";
	}
}
function colabs_dropdown_menu( $args = array() ) {
	static $menu_id_slugs = array();
	$defaults = array( 'menu' => '', 'container' => '', 'container_class' => '', 'container_id' => '', 'menu_class' => 'menu', 'menu_id' => '',
	'echo' => true, 'fallback_cb' => 'wp_page_menu', 'before' => '', 'after' => '', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<select id="%1$s" class="%2$s select">%3$s</select>','depth' => 0, 'walker' => '', 'theme_location' => '', 'show_option_none' => '' );
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'wp_nav_menu_args', $args );
	$args = (object) $args;
	// Get the nav menu based on the requested menu
	$menu = wp_get_nav_menu_object( $args->menu );
	// Get the nav menu based on the theme_location
	if ( ! $menu && $args->theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $args->theme_location ] ) )
		$menu = wp_get_nav_menu_object( $locations[ $args->theme_location ] );
	// get the first menu that has items if we still can't find a menu
	if ( ! $menu && !$args->theme_location ) {
		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu_maybe ) {
			if ( $menu_items = wp_get_nav_menu_items($menu_maybe->term_id) ) {
				$menu = $menu_maybe;
				break;
			}
		}
	}
	// If the menu exists, get its items.
	if ( $menu && ! is_wp_error($menu) && !isset($menu_items) )
		$menu_items = wp_get_nav_menu_items( $menu->term_id );
	// If no menu was found or if the menu has no items and no location was requested, call the fallback_cb if it exists
	if ( ( !$menu || is_wp_error($menu) || ( isset($menu_items) && empty($menu_items) && !$args->theme_location ) )
		&& $args->fallback_cb && is_callable( $args->fallback_cb ) )
			return call_user_func( $args->fallback_cb, (array) $args );
	// If no fallback function was specified and the menu doesn't exists, bail.
	if ( !$menu || is_wp_error($menu) )
		return false;
	$nav_menu = $items = '';
	$show_container = false;
	if ( $args->container ) {
		$allowed_tags = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );
		if ( in_array( $args->container, $allowed_tags ) ) {
			$show_container = true;
			$class = $args->container_class ? ' class="' . esc_attr( $args->container_class ) . '"' : ' class="menu-'. $menu->slug .'-container"';
			$id = $args->container_id ? ' id="' . esc_attr( $args->container_id ) . '"' : '';
			$nav_menu .= '<'. $args->container . $id . $class . '>';
		}
	}
	// Set up the $menu_item variables
	_wp_menu_item_classes_by_context( $menu_items );
	$sorted_menu_items = array();
	foreach ( (array) $menu_items as $key => $menu_item )
		$sorted_menu_items[$menu_item->menu_order] = $menu_item;
	unset($menu_items);
	$sorted_menu_items = apply_filters( 'wp_nav_menu_objects', $sorted_menu_items, $args );
	if ( $args->show_option_none )
			$items .= "\t<option value=\"-1\">".$args->show_option_none."</option>\n";
	$items .= walk_nav_menu_tree2( $sorted_menu_items, $args->depth, $args );
	unset($sorted_menu_items);
	// Attributes
	if ( ! empty( $args->menu_id ) ) {
		$wrap_id = $args->menu_id;
	} else {
		$wrap_id = 'menu-' . $menu->slug;
		while ( in_array( $wrap_id, $menu_id_slugs ) ) {
			if ( preg_match( '#-(\d+)$#', $wrap_id, $matches ) )
				$wrap_id = preg_replace('#-(\d+)$#', '-' . ++$matches[1], $wrap_id );
			else
				$wrap_id = $wrap_id . '-1';
		}
	}
	$menu_id_slugs[] = $wrap_id;
	$wrap_class = $args->menu_class ? $args->menu_class : '';
	// Allow plugins to hook into the menu to add their own <li>'s
	$items = apply_filters( 'wp_nav_menu_items', $items, $args );
	$items = apply_filters( "wp_nav_menu_{$menu->slug}_items", $items, $args );
	$nav_menu .= sprintf( $args->items_wrap, esc_attr( $wrap_id ), esc_attr( $wrap_class ), $items );
	unset( $items );
	if ( $show_container )
		$nav_menu .= '</' . $args->container . '>';
	$nav_menu = apply_filters( 'wp_nav_menu', $nav_menu, $args );
	if ( $args->echo )
		echo $nav_menu;
	else
		return $nav_menu;
}
function walk_nav_menu_tree2( $items, $depth, $r ) {
	$walker = ( empty($r->walker) ) ? new dropdown_walker : $r->walker;
	$args = array( $items, $depth, $r );
	return call_user_func_array( array(&$walker, 'walk'), $args );
}
function colabs_dropdown_pages($args = '') {
	$defaults = array(
		'depth' => 0, 'child_of' => 0,
		'selected' => 0, 'echo' => 1,
		'name' => 'page_id', 'id' => '',
		'show_option_none' => '', 'show_option_no_change' => '',
		'option_none_value' => '',
		'class' => 'page-dropdown-menu'
	);
	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );
	$pages = get_pages($r);
	$output = '';
	$name = esc_attr($name);
	$class = esc_attr($class);
	// Back-compat with old system where both id and name were based on $name argument
	if ( empty($id) )
		$id = $name;
	if ( ! empty($pages) ) {
		$output = "<select name=\"$name\" id=\"$id\" class=\"$class\">\n";
		if ( $show_option_no_change )
			$output .= "\t<option value=\"-1\">$show_option_no_change</option>";
		if ( $show_option_none )
			$output .= "\t<option value=\"-1\">$show_option_none</option>\n";
		$output .= walk_page_dropdown_tree($pages, $depth, $r);
		$output .= "</select>\n";
	}
	$output = apply_filters('colabs_dropdown_pages', $output);
	if ( $echo )
		echo $output;
	return $output;
}
?>
