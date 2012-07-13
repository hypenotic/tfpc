<?php
// CoLabsThemes Admin Interface
/*-----------------------------------------------------------------------------------
TABLE OF CONTENTS
- CoLabsThemes Admin Interface - colabsthemes_add_admin
- CoLabsThemes Reset Function - colabs_reset_options
- Framework options panel - colabsthemes_options_page
- colabs_load_only
- Ajax Save Action - colabs_ajax_callback
- Generates The Options - colabsthemes_machine
- CoLabsThemes Uploader - colabsthemes_uploader_function
-----------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Admin Interface - colabsthemes_add_admin */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabsthemes_add_admin')) {
function colabsthemes_add_admin() {
    global $query_string;
    global $current_user;
    $current_user_id = $current_user->user_login;
    $super_user = get_option( 'framework_colabs_super_user' );
    $themename =  get_option( 'colabs_themename' );
    $shortname =  get_option( 'colabs_shortname' );
   	// Reset the settings, sanitizing the various requests made.
   	// Use a SWITCH to determine which settings to update.
   	/* Make sure we're making a request.
   	------------------------------------------------------------*/
   	if ( isset( $_REQUEST['page'] ) ) {
   		// Sanitize page being requested.
   		$_page = '';
		$_page = mysql_real_escape_string( strtolower( trim( strip_tags( $_REQUEST['page'] ) ) ) );
		// Sanitize action being requested.
   		$_action = '';
		if ( isset( $_REQUEST['colabs_save'] ) ) {
			$_action = mysql_real_escape_string( strtolower( trim( strip_tags( $_REQUEST['colabs_save'] ) ) ) );
		} // End IF Statement
		// If the action is "reset", run the SWITCH.
		/* Perform settings reset.
  		------------------------------------------------------------*/
		if ( $_action == 'reset' ) {
			// Add nonce security check.
			if ( function_exists( 'check_ajax_referer' ) ) { check_ajax_referer( 'colabsframework-theme-options-reset', '_ajax_nonce' ); } // End IF Statement
			switch ( $_page ) {
				case 'colabsthemes':
					$options =  get_option( 'colabs_template' ); 
					colabs_reset_options($options,'colabsthemes' );
					header( "Location: admin.php?page=colabsthemes&reset=true" );
					die;
				break;
				case 'colabsthemes_seo':
					$options = get_option( 'colabs_seo_template' ); 
					colabs_reset_options($options);
					header( "Location: admin.php?page=colabsthemes_seo&reset=true" );
					die;
				break;
			} // End SWITCH Statement
		} // End IF Statement
   	} // End IF Statement
    // Check all the Options, then if the no options are created for a relative sub-page... it's not created.
    $icon = trailingslashit( get_template_directory_uri() ) . 'images/logo16.png';
    if(function_exists( 'add_object_page'))
    {
        add_object_page ( 'Page Title', $themename, 'manage_options','colabsthemes', 'colabsthemes_options_page', $icon);
    }
    else
    {
        add_menu_page ( 'Page Title', $themename, 'manage_options','colabsthemes', 'colabsthemes_options_page', $icon);
    }
    $colabspage = add_submenu_page( 'colabsthemes', $themename, 'Theme Options', 'manage_options', 'colabsthemes','colabsthemes_options_page' ); // Default
	// Add SEO Menu Item
	$colabsseo = '';
	$colabsseo = add_submenu_page( 'colabsthemes', 'SEO', 'SEO', 'manage_options', 'colabsthemes_seo', 'colabsthemes_seo_page' );
/*	
	// CoLabsthemes Content Builder
	if (function_exists( 'colabsthemes_content_builder_menu')) {
		colabsthemes_content_builder_menu();
	}
	// Custom Navigation Menu Item
	if (function_exists( 'colabs_custom_navigation_menu')) {
		colabs_custom_navigation_menu();
	}	
	// Buy Themes Menu Item
    $colabsthemepage = add_submenu_page( 'colabsthemes', 'Available CoLabsThemes', 'Buy Themes', 'manage_options', 'colabsthemes_themes', 'colabsthemes_more_themes_page' );    
   	add_action( "admin_print_scripts-$colabsthemepage", 'colabs_load_only' );
*/
	// Add README.txt file submenu, if it exists
    $colabsthemes_readme_menu_pagehook = file_exists( get_template_directory() . '/README.txt' ) ? add_submenu_page('colabsthemes', __('Readme', 'colabsthemes'), __('Readme', 'colabsthemes'), 'edit_theme_options', 'readme', 'colabsthemes_readme_menu_admin') : null;
	// Add framework functionaily to the head individually
	add_action( "admin_print_scripts-$colabspage", 'colabs_load_only' );
	add_action( "admin_print_scripts-$colabsseo", 'colabs_load_only' );
}
} 
add_action( 'admin_menu', 'colabsthemes_add_admin' );
/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Reset Function - colabs_reset_options */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_reset_options')) {
function colabs_reset_options($options,$page = ''){
	$excludes = array( 'blogname' , 'blogdescription' );
	foreach($options as $option){
		if(isset($option['id'])){ 
			$option_id = $option['id'];
			$option_type = $option['type'];
			//Skip assigned id's
			if(in_array($option_id,$excludes)) { continue; }
			if($option_type == 'multicheck'){
				foreach($option['options'] as $option_key => $option_option){
					$del = $option_id . "_" . $option_key;
					delete_option($del);
				}
			} else if(is_array($option_type)) {
				foreach($option_type as $inner_option){
					$option_id = $inner_option['id'];
					$del = $option_id;
					delete_option($option_id);
				}
			} else {
				delete_option($option_id);
			}
		}		
	}	
	//When Theme Options page is reset - Add the colabs_options option
	if($page == 'colabsthemes'){
		delete_option( 'colabs_options' );
	}
}
}
/*-----------------------------------------------------------------------------------*/
/* Framework options panel - colabsthemes_options_page */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabsthemes_options_page')) {
function colabsthemes_options_page(){
    $options =  get_option( 'colabs_template' );      
    $themename =  get_option( 'colabs_themename' );
    $shortname =  get_option( 'colabs_shortname' );
    $manualurl =  get_option( 'colabs_manual' ); 
    //Version in Backend Header
    $theme_data = get_theme_data( get_template_directory() . '/style.css' );
    $local_version = $theme_data['Version'];
    //GET themes update RSS feed and do magic
	include_once(ABSPATH . WPINC . '/feed.php' );
	$pos = strpos($manualurl, 'documentation' );
	$theme_slug = str_replace( "/", "", substr($manualurl, ($pos + 13))); //13 for the word documentation
	//add filter to make the rss read cache clear every 4 hours
	//add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 14400;' ) );
?>
<div class="wrap colabs_container">
    <form action="" enctype="multipart/form-data" id="colabsform">
    <?php
    	// Add nonce for added security.
    	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-theme-options-update' ); } // End IF Statement
    	$colabs_nonce = '';
    	if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-theme-options-update' ); } // End IF Statement
    	if ( $colabs_nonce == '' ) {} else {
    ?>
    	<input type="hidden" name="_ajax_nonce" value="<?php echo $colabs_nonce; ?>" />
    <?php
    	} // End IF Statement 
    ?>
        <div class="themever left">
           <div id="icon-colabs" class="icon32"><br /></div>
            <h2><?php echo $themename; ?> <?php echo $local_version; ?>&nbsp;<?php _e( 'Theme Options' ) //your admin panel title ?></h2>
        </div>
		<div class="logocolabs right">
            <a href="http://colorlabsproject.com" title="Visit Our Website"><img src="<?php echo bloginfo( 'template_url' ); ?>/functions/images/colorlabs.png" /></a>
		</div>
        <div class="clear"></div>
		<div id="colabs-popup-save" class="colabs-save-popup"><div class="colabs-save-save">Options Updated</div></div>
		<div id="colabs-popup-reset" class="colabs-save-popup"><div class="colabs-save-reset">Options Reset</div></div>
		<div style="width:100%;padding-top:15px;">
		<div id="support-links" class="left">
			<ul>
				<li class="docs"><a title="Theme Documentation" href="<?php echo $manualurl; ?>/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_blank" >View Documentation</a></li>
                <span>&#124;</span>
				<li class="forum"><a href="http://colorlabsproject.com/resolve/" target="_blank">Submit a Support Ticket</a></li>
				<span>&#124;</span>
				<li class="idea"><a href="http://ideas.colorlabsproject.com/" target="_blank">Suggest a Feature</a></li>
			</ul>
		</div>
        <div class="save_bar_top right">
            <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/ajax-loading.gif" class="ajax-loading-img ajax-loading-img-top left" alt="Working..." />        
			<input type="submit" value="Save All Changes" class="button submit-button button-primary" />
		</div>
		</div>
        <div class="clear"></div>
        <?php 
		// Rev up the Options Machine
        $return = colabsthemes_machine($options);
        ?>        
        <div id="main" class="menu-item-settings metabox-holder">
	        <div id="colabs-nav">
				<ul>
					<?php echo $return[1] ?>
				</ul>		
			</div>
			<div id="content">
	         <?php echo $return[0]; /* Settings */ ?>
	        </div>
	        <div class="clear"></div>
        </div>
        <div class="save_bar_down right">
        <img style="display:none" src="<?php echo bloginfo( 'template_url' ); ?>/functions/images/ajax-loading.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
        <input type="submit" value="Save All Changes" class="button submit-button button-primary" />        
        </form>
        <form action="<?php /*echo wp_specialchars( $_SERVER['REQUEST_URI'] )*/ ?>" method="post" style="display:inline" id="colabsform-reset">
        <?php
	    	// Add nonce for added security.
	    	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-theme-options-reset' ); } // End IF Statement
	    	$colabs_nonce = '';
	    	if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-theme-options-reset' ); } // End IF Statement
	    	if ( $colabs_nonce == '' ) {} else {
	    ?>
	    	<input type="hidden" name="_ajax_nonce" value="<?php echo $colabs_nonce; ?>" />
	    <?php
	    	} // End IF Statement
	    ?>
            <span class="submit-footer-reset">
            <input name="reset" type="submit" value="Reset All Options" class="button submit-button reset-button button-highlighted" onclick="return confirm( 'Click OK to reset all options. All settings will be lost!' );" />
            <input type="hidden" name="colabs_save" value="reset" /> 
            </span>
        </form>
        </div>
        <?php  if (!empty($update_message)) echo $update_message; ?>    
<div style="clear:both;"></div>    
</div><!--wrap-->
 <?php
}
}
/*-----------------------------------------------------------------------------------*/
/* colabs_load_only */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_load_only')) {
function colabs_load_only() {
	add_action( 'admin_head', 'colabs_admin_head' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_register_script( 'jquery-ui-datepicker', get_bloginfo( 'template_directory').'/functions/js/ui.datepicker.js', array( 'jquery-ui-core' ));
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_register_script( 'jquery-input-mask', get_bloginfo( 'template_directory').'/functions/js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script( 'jquery-input-mask' );
	wp_enqueue_script( 'colabs-scripts', get_bloginfo( 'template_directory').'/functions/js/colabs-scripts.js', array( 'jquery' ));	
	/* colabs_admin_head()
	--------------------------------------------------------------------------------*/
	function colabs_admin_head() {
		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo( 'template_directory').'/functions/admin-style.css" media="screen" />';
		echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo( 'template_directory') . '/functions/css/jquery-ui-datepicker.css" />';
    /* To change the CSS stylesheet depending on the chosen color */
    global $_wp_admin_css_colors;
    ?>  
    <link rel="stylesheet" type="text/css"  href="<?php bloginfo('template_directory'); ?>/<?php echo $_wp_admin_css_colors['name']; ?>.css" />   
    <?php
		 // COLOR Picker ?>
		<link rel="stylesheet" media="screen" type="text/css" href="<?php echo get_bloginfo( 'template_directory' ); ?>/functions/css/colorpicker.css" />
		<script type="text/javascript" src="<?php echo get_bloginfo( 'template_directory' ); ?>/functions/js/colorpicker.js"></script>
		<script type="text/javascript" language="javascript">
		jQuery(document).ready(function(){
			//JQUERY DATEPICKER
			jQuery( '.colabs-input-calendar').each(function (){
				jQuery( '#' + jQuery(this).attr( 'id')).datepicker({showOn: 'button', buttonImage: '<?php echo get_bloginfo( 'template_directory' );?>/functions/images/calendar.gif', buttonImageOnly: true});
			});
			//JQUERY TIME INPUT MASK
			jQuery( '.colabs-input-time').each(function (){
				jQuery( '#' + jQuery(this).attr( 'id')).mask( "99:99" );
			});
			//Color Picker
			<?php $options = get_option( 'colabs_template' );
			foreach($options as $option){ 
			if($option['type'] == 'color' OR $option['type'] == 'typography' OR $option['type'] == 'border'){
				if($option['type'] == 'typography' OR $option['type'] == 'border'){
					$option_id = $option['id'];
					$temp_color = get_option($option_id);
					$option_id = $option['id'] . '_color';
					$color = $temp_color['color'];
				}
				else {
					$option_id = $option['id'];
					$color = get_option($option_id);
				}
				?>
				 jQuery( '#<?php echo $option_id; ?>_picker').children( 'div').css( 'backgroundColor', '<?php echo $color; ?>' );    
				 jQuery( '#<?php echo $option_id; ?>_picker').ColorPicker({
					color: '<?php echo $color; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						//jQuery(this).css( 'border','1px solid red' );
						jQuery( '#<?php echo $option_id; ?>_picker').children( 'div').css( 'backgroundColor', '#' + hex);
						jQuery( '#<?php echo $option_id; ?>_picker').next( 'input').attr( 'value','#' + hex);
					}
				  });
			  <?php } } ?>
		});
		</script> 
		<?php
		//AJAX Upload
		?>
		<script type="text/javascript" src="<?php echo get_bloginfo( 'template_directory' ); ?>/functions/js/ajaxupload.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
			var flip = 0;
			jQuery( '#expand_options').click(function(){
				if(flip == 0){
					flip = 1;
					jQuery( '.colabs_container #colabs-nav').hide();
					jQuery( '.colabs_container #content').width(785);
					jQuery( '.colabs_container .group').add( '.colabs_container .group h2').show();
					jQuery(this).text( '[-]' );
				} else {
					flip = 0;
					jQuery( '.colabs_container #colabs-nav').show();
					jQuery( '.colabs_container #content').width(595);
					jQuery( '.colabs_container .group').add( '.colabs_container .group h2').hide();
					jQuery( '.colabs_container .group:first').show();
					jQuery( '.colabs_container #colabs-nav li').removeClass( 'current' );
					jQuery( '.colabs_container #colabs-nav li:first').addClass( 'current' );
					jQuery(this).text( '[+]' );
				}
			});
				jQuery( '.group').hide();
				jQuery( '.group:first').fadeIn();

                jQuery.fn.removeHidden = function () {
                    jQuery(this).parents('.collapsed').nextAll().each( 
                    function(){
                        if (jQuery(this).hasClass( 'last')) {
                            jQuery(this).removeClass( 'hidden' );
                            return false;
                        }
           			jQuery(this).removeClass( 'hidden' );
                    });
                }

                jQuery.fn.addHidden = function () {
                    jQuery(this).parents('.collapsed').nextAll().each( 
                    function(){
                        if (jQuery(this).hasClass( 'last')) {
                            jQuery(this).addClass( 'hidden' );
                            return false;
           				}
                    jQuery(this).addClass( 'hidden' );
                    });                    
                }
                
                //CHECKBOX Element
				jQuery( '.group .collapsed').each(function(){
                    jQuery(this).find( 'input:checked').removeHidden();
           		});
                
				jQuery( '.group .collapsed input:checkbox').click(
				function (){
					if (jQuery(this).attr( 'checked')) {
						jQuery(this).removeHidden();
					} else {
                        jQuery(this).addHidden();
					}
				});
				
                //SELECT Element
				jQuery( '.group .collapsed select').each(function(){
				    //if ( jQuery(this).val() != '0' || jQuery(this).val() != '' ){
                    if ( jQuery(this).val() == '' ){
                        jQuery(this).removeHidden();
                    }
           		});

                jQuery( '.group .collapsed select').change(function(){
                    //if ( jQuery(this).val() != '0' || jQuery(this).val() != '' ){
                    if ( jQuery(this).val() == '' ){
                            jQuery(this).removeHidden();
                        }else{
                            jQuery(this).addHidden();
                        }
                });
                
                //RADIOBUTTON Element
				jQuery( '.group .collapsed').each(function(){
                    jQuery(this).find( 'input:radio').removeHidden();
           		});
                                   
                jQuery(".group .collapsed input:radio").click(function(){
                    if ( jQuery(this).attr('value') == 'c' ){
                        jQuery(this).parent().parent().parent().nextAll().removeClass( 'hidden' );
                    } else {
						jQuery(this).parent().parent().parent().nextAll().each( 
							function(){
           						if (jQuery(this).filter( '.last').length) {
           							jQuery(this).addClass( 'hidden' );
									return false;
           						}
           						jQuery(this).addClass( 'hidden' );
           					});                        
                    }
                });
                
				jQuery( '.colabs-radio-img-img').click(function(){
					jQuery(this).parent().parent().find( '.colabs-radio-img-img').removeClass( 'colabs-radio-img-selected' );
					jQuery(this).addClass( 'colabs-radio-img-selected' );
				});
				jQuery( '.colabs-radio-img-label').hide();
				jQuery( '.colabs-radio-img-img').show();
				jQuery( '.colabs-radio-img-radio').hide();
				jQuery( '#colabs-nav li:first').addClass( 'current' );
				jQuery( '#colabs-nav li a').click(function(evt){
						jQuery( '#colabs-nav li').removeClass( 'current' );
						jQuery(this).parent().addClass( 'current' );
						var clicked_group = jQuery(this).attr( 'href' );
						jQuery( '.group').hide();
							jQuery(clicked_group).fadeIn();
						evt.preventDefault();
					});
				jQuery( 'select.colabs-typography-unit').change(function(){
					var val = jQuery(this).val();
					var parent = jQuery(this).parent();
					var name = parent.find( '.colabs-typography-size-px').attr( 'name' );
					if(name == ''){ var name = parent.find( '.colabs-typography-size-em').attr( 'name' ); } 
					if(val == 'px'){ 
						parent.find( '.colabs-typography-size-em').hide().removeAttr( 'name' );
						parent.find( '.colabs-typography-size-px').show().attr( 'name',name);
					}
					else if(val == 'em'){
						parent.find( '.colabs-typography-size-em').show().attr( 'name',name);
						parent.find( '.colabs-typography-size-px').hide().removeAttr( 'name' );
					}
				});
				// Create sanitary variable for use in the JavaScript conditional.
				<?php
				$is_reset = 'false';
				if( isset( $_REQUEST['reset'] ) ) {
					$is_reset = $_REQUEST['reset'];
					$is_reset = strtolower( strip_tags( trim( $is_reset ) ) );
				} else {
					$is_reset = 'false';
				} // End IF Statement
				?>
				if( '<?php echo $is_reset; ?>' == 'true'){
					var reset_popup = jQuery( '#colabs-popup-reset' );
					reset_popup.fadeIn();
					window.setTimeout(function(){
						   reset_popup.fadeOut();                        
						}, 2000);
						//alert(response);
				}
			//Update Message popup
			jQuery.fn.center = function () {
				this.animate({"top":( jQuery(window).height() - this.height() - 200 ) / 2+jQuery(window).scrollTop() + "px"},100);
				this.css( "left", 250 );
				return this;
			}
			/* //jQuery( '#colabs-popup-save').center();
			//jQuery( '#colabs-popup-reset').center(); */
			jQuery(window).scroll(function() { 
				jQuery( '#colabs-popup-save').center();
				jQuery( '#colabs-popup-reset').center();
			});
			//String Builder Details
			jQuery( '.string_builder_return').each(function(){
				var top_object = jQuery(this);
				if(jQuery(this).children( '.string_option').length == 0){
					top_object.find( '.string_builder_empty').show();
				}
			})
			//AJAX Upload
			jQuery( '.image_upload_button').each(function(){
			var clickedObject = jQuery(this);
			var clickedID = jQuery(this).attr( 'id' );	
			new AjaxUpload(clickedID, {
				  action: '<?php echo admin_url( "admin-ajax.php" ); ?>',
				  name: clickedID, // File upload name
				  data: { // Additional data to send
						action: 'colabs_ajax_post_action',
						type: 'upload',
						data: clickedID },
				  autoSubmit: true, // Submit file after selection
				  responseType: false,
				  onChange: function(file, extension){},
				  onSubmit: function(file, extension){
						clickedObject.text( 'Uploading' ); // change button text, when user selects file	
						this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
						interval = window.setInterval(function(){
							var text = clickedObject.text();
							if (text.length < 13){	clickedObject.text(text + '.' ); }
							else { clickedObject.text( 'Uploading' ); } 
						}, 200);
				  },
				  onComplete: function(file, response) {
					window.clearInterval(interval);
					clickedObject.text( 'Upload Image' );	
					this.enable(); // enable upload button
					// If there was an error
					if(response.search( 'Upload Error') > -1){
						var buildReturn = '<span class="upload-error">' + response + '</span>';
						jQuery( ".upload-error").remove();
						clickedObject.parent().after(buildReturn);
					}
					else{
						var buildReturn = '<img class="hide colabs-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';
						jQuery( ".upload-error").remove();
						jQuery( "#image_" + clickedID).remove();	
						clickedObject.parent().after(buildReturn);
						jQuery( 'img#image_'+clickedID).fadeIn();
						clickedObject.next( 'span').fadeIn();
						clickedObject.parent().prev( 'input').val(response);
					}
				  }
				});
			});
			//AJAX Remove (clear option value)
			jQuery( '.image_reset_button').click(function(){
					var clickedObject = jQuery(this);
					var clickedID = jQuery(this).attr( 'id' );
					var theID = jQuery(this).attr( 'title' );	
					var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
					var data = {
						action: 'colabs_ajax_post_action',
						type: 'image_reset',
						data: theID
					};
					jQuery.post(ajax_url, data, function(response) {
						var image_to_remove = jQuery( '#image_' + theID);
						var button_to_hide = jQuery( '#reset_' + theID);
						image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
						button_to_hide.fadeOut();
						clickedObject.parent().prev( 'input').val( '' );
					});
					return false; 
				});
			//Adding string builder add
			jQuery( '.string_builder_add').click(function(){
					<?php // Nonce Security ?>
					<?php if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-theme-options-update' ); } // End IF Statement ?>
					var clickedObject = jQuery(this);
					var id = jQuery(this).attr( 'id' );
					var name = jQuery( '#'+id+'_name').val();
					var value = jQuery( '#'+id+'_value').val();
					if(name == '' || value == ''){ alert( 'Please add a value to one of the fields.' ); return false;}
					var data = 'id='+id+'&name=' + name + '&value=' + value;
					var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
					var data = {
						action: 'colabs_ajax_post_action',
						type: 'string_builder_add',
						data: data, 
						_ajax_nonce: '<?php echo $colabs_nonce; ?>'
					};
					jQuery.post(ajax_url, data, function(response) {
						var response = response.split( '|' );
						var id = response[0];
						var name = response[1]
						var value = response[2];
						var html = '';
						html += '<div class="string_option" id="'+name+'"><span>'+name+':</span> '+value+'</div>';
						jQuery( '#'+id+'_return').find( '.string_builder_empty').hide();
						jQuery( '#'+id+'_return').append(html);
					});
					return false; 
				});
						//AJAX Remove (clear option value)
			jQuery( '.string_option .delete').click(function(){
					<?php // Nonce Security ?>
					<?php if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-theme-options-update' ); } // End IF Statement ?>
					var id = jQuery(this).parent().parent().parent().attr( 'id' );
					var name = jQuery(this).attr( 'rel' );
					var data = 'id='+id+'&name='+name;
					var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
					var data = {
						action: 'colabs_ajax_post_action',
						type: 'string_builder_delete',
						data: data, 
						_ajax_nonce: '<?php echo $colabs_nonce; ?>'
					};
					jQuery.post(ajax_url, data, function(response) {
						jQuery( '#string_builer_option_'+response).fadeOut( 'slow',function(){jQuery(this).remove();});
					});
					return false; 
				});	
			//Save everything else
			jQuery( '#colabsform').submit(function(){
					function newValues() {
					  var serializedValues = jQuery( "#colabsform *").not( '.colabs-ignore').serialize();
					  return serializedValues;
					}
					jQuery( ":checkbox, :radio").click(newValues);
					jQuery( "select").change(newValues);
					jQuery( '.ajax-loading-img').fadeIn().css('display','inline');
					var serializedReturn = newValues();
					var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
					 //var data = {data : serializedReturn};
					var data = {
						<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'colabsthemes'){ ?>
						type: 'options',
						<?php } ?>
						<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'colabsthemes_framework_settings'){ ?>
						type: 'framework',
						<?php } ?>
						<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'colabsthemes_seo'){ ?>
						type: 'seo',
						<?php } ?>
						<?php /*if(isset($_REQUEST['page']) && $_REQUEST['page'] == 'colabsthemes_tumblog'){ ?>
						type: 'tumblog',
						<?php }*/ ?>
						action: 'colabs_ajax_post_action',
						data: serializedReturn, 
						<?php // Nonce Security ?>
						<?php if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-theme-options-update' ); } // End IF Statement ?>
						_ajax_nonce: '<?php echo $colabs_nonce; ?>'
					};
					jQuery.post(ajax_url, data, function(response) {
						var success = jQuery( '#colabs-popup-save' );
						var loading = jQuery( '.ajax-loading-img' );
						loading.fadeOut();  
						success.fadeIn();
						window.setTimeout(function(){
						   success.fadeOut(); 
						}, 2000);
					});
					return false; 
				});   	 	
			});
		</script>
	<?php }
}
}
/*-----------------------------------------------------------------------------------*/
/* Ajax Save Action - colabs_ajax_callback */
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_ajax_colabs_ajax_post_action', 'colabs_ajax_callback' );
if (!function_exists( 'colabs_ajax_callback')) {
function colabs_ajax_callback() {
	// check security with nonce.
	if ( function_exists( 'check_ajax_referer' ) ) { check_ajax_referer( 'colabsframework-theme-options-update', '_ajax_nonce' ); } // End IF Statement
	global $wpdb; // this is how you get access to the database
	$save_type = $_POST['type'];
	//Uploads
	if($save_type == 'upload'){
		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
       	$filename['name'] = preg_replace( '/[^a-zA-Z0-9._\-]/', '', $filename['name']); 
       	//print_r($filename);
		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';    
		$uploaded_file = wp_handle_upload($filename,$override);
				$upload_tracking[] = $clickedID;
				update_option( $clickedID , $uploaded_file['url'] );								
		 if(!empty($uploaded_file['error'])) {echo 'Upload Error: ' . $uploaded_file['error']; }	
		 else { echo $uploaded_file['url']; } // Is the Response
	}
	elseif($save_type == 'image_reset'){
			$id = $_POST['data']; // Acts as the name
			delete_option($id);	
	}
	elseif($save_type == 'string_builder_add'){
		$data = $_POST['data'];
		parse_str($data,$output);
		$id = $output['id'];
		$name = $output['name'];
		$name = preg_replace( '/[^a-zA-Z0-9-_ ]/i','',$name);
		$value = stripslashes($output['value']);
		$value = stripslashes($value);
		$return = "$id|$name|$value";
		echo $return;	
		$option_temp = get_option($id);
		$option_temp[$name] = $value;
		update_option($id,$option_temp);
	}
	elseif($save_type == 'string_builder_delete'){
		$data = $_POST['data'];
		parse_str($data,$output);
		$id = $output['id'];
		$name = $output['name'];
		echo str_replace( " ","_",$name);
		$option_temp = get_option($id);
		unset($option_temp[$name]);
		update_option($id,$option_temp);
	}
	elseif ($save_type == 'options' OR $save_type == 'seo' OR $save_type == 'tumblog' OR $save_type == 'framework') {
		$data = $_POST['data'];
		parse_str($data,$output);
		//Pull options
 	       $options = get_option( 'colabs_template' );

        if($save_type == 'seo'){ 
			$options = get_option( 'colabs_seo_template' ); } // Use SEO template on SEO page
/*		if($save_type == 'tumblog'){ 
			$options = get_option( 'colabs_tumblog_template' ); } // Use Tumblog template on Tumblog page
		if($save_type == 'framework'){ 
			$options = get_option( 'colabs_framework_template' ); } // Use Framework template on Framework Settings page
*/
		foreach($options as $option_array){
			if(isset($option_array['id'])){
				$id = $option_array['id'];
			} else { $id = null;}
			$old_value = get_option($id);
			$new_value = '';
            $multicheck_arr = array();
			if(isset($output[$id])){
				$new_value = $output[$option_array['id']];
			}
			if(isset($option_array['id'])) { // Non - Headings...
				//Import of prior saved options
				if($id == 'framework_colabs_import_options'){
					//Decode and over write options.
					$new_import = base64_decode($new_value);
					$new_import = unserialize($new_import);
					//echo '<pre>';
					//print_r($new_import);
					//echo '</pre>';
					if(!empty($new_import)) {
						foreach($new_import as $id2 => $value2){
							if(is_serialized($value2)) {
								update_option($id2,unserialize($value2));
							} else {
								update_option($id2,$value2);
							}
						}
					}
				} else {
					$type = $option_array['type'];
					if ( is_array($type)){
						foreach($type as $array){
							if($array['type'] == 'text'){
								$id = $array['id'];
								$std = $array['std'];
								$new_value = $output[$id];
								if($new_value == ''){ $new_value = $std; }
								update_option( $id, stripslashes($new_value));
							}
						}                 
					}
					elseif($new_value == '' && $type == 'checkbox'){ // Checkbox Save
						update_option($id,'false' );
					}
					elseif ($new_value == 'true' && $type == 'checkbox'){ // Checkbox Save
						update_option($id,'true' );
					}
                    elseif($type == 'multicheck' || $type == 'multicheck2'){ // Multi Check Save
						$option_options = $option_array['options'];
						foreach ($option_options as $options_id => $options_value){
							$multicheck_id = $id . "_" . $options_id;
							if(!isset($output[$multicheck_id])){
							  update_option($multicheck_id,'false' );
							}
							else{
							   update_option($multicheck_id,'true' );
                               $multicheck_arr[] = $options_id; //added
							}
						}
                        update_option($id,$multicheck_arr ); //added
					} 
					elseif($type == 'typography'){
						$typography_array = array();	
						$typography_array['size'] = $output[$option_array['id'] . '_size'];
						$typography_array['unit'] = $output[$option_array['id'] . '_unit'];
						$typography_array['face'] = stripslashes($output[$option_array['id'] . '_face']);
						$typography_array['style'] = $output[$option_array['id'] . '_style'];
						$typography_array['color'] = $output[$option_array['id'] . '_color'];
						update_option($id,$typography_array);
					}
					elseif($type == 'border'){
						$border_array = array();	
						$border_array['width'] = $output[$option_array['id'] . '_width'];
						$border_array['style'] = $output[$option_array['id'] . '_style'];
						$border_array['color'] = $output[$option_array['id'] . '_color'];
						update_option($id,$border_array);
					}
					elseif($type != 'upload_min'){
						update_option($id,stripslashes($new_value));
					}
				}
			}	
		}
	}
	if( $save_type == 'options' OR $save_type == 'framework' ){
		/* Create, Encrypt and Update the Saved Settings */
		$colabs_options = array();
		$data = array();
		if($save_type == 'framework' ){
			$options = get_option( 'colabs_template' );
		}
		foreach($options as $option){
			if(isset($option['id'])){ 
				$count++;
				$option_id = $option['id'];
				$option_type = $option['type'];
				if(is_array($option_type)) {
					$type_array_count = 0;
					foreach($option_type as $inner_option){
						$option_id = $inner_option['id'];
						$data[$option_id] .= get_option($option_id);
					}
				}
				else {
					$data[$option_id] = get_option($option_id);					
				}
			}
		}
		$output = "<ul>";
		foreach ($data as $name => $value){
				if(is_serialized($value)) {
					$value = unserialize($value);
					$colabs_array_option = $value;
					$temp_options = '';
					foreach($value as $v){
						if(isset($v))
							$temp_options .= $v . ',';
					}	
					$value = $temp_options;
					$colabs_array[$name] = $colabs_array_option;
				} else {
					$colabs_array[$name] = $value;
				}
				$output .= '<li><strong>' . $name . '</strong> - ' . $value . '</li>';
		}
		$output .= "</ul>";
		$output = base64_encode($output);
		update_option( 'colabs_options',$colabs_array);
		update_option( 'colabs_settings_encode',$output);
	}
  die();
}
}
/*-----------------------------------------------------------------------------------*/
/* Generates The Options - colabsthemes_machine */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabsthemes_machine')) {
function colabsthemes_machine($options) {
    $counter = 0;
	$menu = '';
	$output = '';
	foreach ($options as $value) {
		$counter++;
		$val = '';
		//Start Heading
		 if ( $value['type'] != "heading" )
		 {
		 	$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
			//$output .= '<div class="section section-'. $value['type'] .'">'."\n".'<div class="option-inner">'."\n";
			$output .= '<div class="section section-'.$value['type'].' '. $class .'">'."\n";
			$output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
			$output .= '<div class="option">'."\n" . '<span class="controls">'."\n";
		 } 
		 //End Heading
		$select_value = '';                                   
		switch ( $value['type'] ) {
		case 'text':
			$val = $value['std'];
			$std = get_option($value['id']);
			if ( $std != "") { $val = $std; }
			$output .= '<input class="regular-text" name="'. $value['id'] .'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $val .'" />';
		break;
		case 'select':
			$output .= '<div class="select_wrapper"><select class="colabs-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';			
            //$output .= '<select name="'. $value['id'] .'" id="'. $value['id'] .'">';
			$select_value = stripslashes(get_option($value['id']));
			foreach ($value['options'] as $option) {
				$selected = '';
				 if($select_value != '') {
					 if ( $select_value == $option) { $selected = ' selected="selected"';} 
			     } else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $option) { $selected = ' selected="selected"'; }
				 }
				 $output .= '<option'. $selected .'>';
				 $output .= $option;
				 $output .= '</option>';
			 } 
			 $output .= '</select></div>';
		break;
		case 'select2':
			//$output .= '<select name="'. $value['id'] .'" id="'. $value['id'] .'">';
            $output .= '<div class="select_wrapper"><select class="colabs-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';                        
			$select_value = stripslashes(get_option($value['id']));
			foreach ($value['options'] as $option => $name) {
				$selected = '';
				 if($select_value != '') {
					 if ( $select_value == $option) { $selected = ' selected="selected"';} 
			     } else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $option) { $selected = ' selected="selected"'; }
				 }
				 $output .= '<option'. $selected .' value="'.$option.'">';
				 $output .= $name;
				 $output .= '</option>';
			 } 
			 $output .= '</select></div>';
		break;
		case 'calendar':
			$val = $value['std'];
			$std = get_option($value['id']);
			if ( $std != "") { $val = $std; }
            $output .= '<input class="colabs-input-calendar" type="text" name="'.$value['id'].'" id="'.$value['id'].'" value="'.$val.'">';
		break;
		case 'time':
			$val = $value['std'];
			$std = get_option($value['id']);
			if ( $std != "") { $val = $std; }
			$output .= '<input class="colabs-input-time" name="'. $value['id'] .'" id="'. $value['id'] .'" type="text" value="'. $val .'" />';
		break;
		case 'textarea':
			$cols = '8';
			$ta_value = '';
			if(isset($value['std'])) {
				$ta_value = $value['std']; 
				if(isset($value['options'])){
					$ta_options = $value['options'];
					if(isset($ta_options['cols'])){
					$cols = $ta_options['cols'];
					} else { $cols = '8'; }
				}
			}
				$std = get_option($value['id']);
				if( $std != "") { $ta_value = stripslashes( $std ); }
				$output .= '<textarea class="colabs-input" name="'. $value['id'] .'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';
		break;
		case "radio":
			 $select_value = get_option( $value['id']);
			 foreach ($value['options'] as $key => $option) 
			 { 
				 $checked = '';
				   if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; } 
				   } else {
					if ($value['std'] == $key) { $checked = ' checked'; }
				   }
				$output .= '<input class="colabs-input colabs-radio" type="radio" name="'. $value['id'] .'" value="'. $key .'" '. $checked .' />' . $option .'<br />';
			}
		break;
		case "checkbox": 
		   $std = $value['std'];  
		   $saved_std = get_option($value['id']);
		   $checked = '';
			if(!empty($saved_std)) {
				if($saved_std == 'true') {
				$checked = 'checked="checked"';
				}
				else{
				   $checked = '';
				}
			}
			elseif( $std == 'true') {
			   $checked = 'checked="checked"';
			}
			else {
				$checked = '';
			}
			$output .= '<input type="checkbox" class="checkbox colabs-input" name="'.  $value['id'] .'" id="'. $value['id'] .'" value="true" '. $checked .' />';
		break;
		case "multicheck":
			$std =  $value['std'];         
			foreach ($value['options'] as $key => $option) {
			$colabs_key = $value['id'] . '_' . $key;
			$saved_std = get_option($colabs_key);
			if(!empty($saved_std)) 
			{ 
				  if($saved_std == 'true'){
					 $checked = 'checked="checked"';  
				  } 
				  else{
					  $checked = '';     
				  }    
			} 
			elseif( $std == $key) {
			   $checked = 'checked="checked"';
			}
			else {
				$checked = '';                                                                                    }
			$output .= '<input type="checkbox" class="checkbox colabs-input" name="'. $colabs_key .'" id="'. $colabs_key .'" value="true" '. $checked .' /><label for="'. $colabs_key .'">'. $option .'</label><br />';
			}
		break;
		case "multicheck2":
			$std =  explode( ',',$value['std']);         
			foreach ($value['options'] as $key => $option) {
			$colabs_key = $value['id'] . '_' . $key;
			$saved_std = get_option($colabs_key);
			if(!empty($saved_std)) 
			{ 
				  if($saved_std == 'true'){
					 $checked = 'checked="checked"';  
				  } 
				  else{
					  $checked = '';     
				  }    
			} 
			elseif( in_array($key,$std)) {
			   $checked = 'checked="checked"';
			}
			else {
				$checked = '';                                                                                    }
			$output .= '<input type="checkbox" class="checkbox colabs-input" name="'. $colabs_key .'" id="'. $colabs_key .'" value="true" '. $checked .' /><label for="'. $colabs_key .'">'. $option .'</label><br />';
			}
		break;
		case "upload":
			if ( function_exists( 'colabsthemes_medialibrary_uploader' ) ) {
				$output .= colabsthemes_medialibrary_uploader( $value['id'], $value['std'], null ); // New AJAX Uploader using Media Library
			} else {
				$output .= colabsthemes_uploader_function($value['id'],$value['std'],null); // Original AJAX Uploader
			} // End IF Statement
		break;
		case "upload_min":
			if ( function_exists( 'colabsthemes_medialibrary_uploader' ) ) {
				$output .= colabsthemes_medialibrary_uploader( $value['id'], $value['std'], 'min' ); // New AJAX Uploader using Media Library
			} else {
				$output .= colabsthemes_uploader_function($value['id'],$value['std'],'min' ); // Original AJAX Uploader
			} // End IF Statement
			// $output .= colabsthemes_uploader_function($value['id'],$value['std'],'min' );
		break;
		case "color":
			$val = $value['std'];
			$stored  = get_option( $value['id'] );
			if ( $stored != "") { $val = $stored; }
			$output .= '<div id="' . $value['id'] . '_picker" class="colorSelector"><div></div></div>';
			$output .= '<input class="colabs-color" name="'. $value['id'] .'" id="'. $value['id'] .'" type="text" value="'. $val .'" />';
		break;   
		case "typography":
			$default = $value['std'];
			$typography_stored = get_option($value['id']);
			/* Font Size */
			$val = $default['size'];
			if ( $typography_stored['size'] != "") { $val = $typography_stored['size']; }
			if ( $typography_stored['unit'] == 'px'){ $show_px = ''; $show_em = ' style="display:none" '; $name_px = ' name="'. $value['id'].'_size" '; $name_em = ''; }
			else if ( $typography_stored['unit'] == 'em'){ $show_em = ''; $show_px = 'style="display:none"'; $name_em = ' name="'. $value['id'].'_size" '; $name_px = ''; }
			else { $show_px = ''; $show_em = ' style="display:none" '; $name_px = ' name="'. $value['id'].'_size" '; $name_em = ''; }
			$output .= '<select class="colabs-typography colabs-typography-size colabs-typography-size-px"  id="'. $value['id'].'_size" '. $name_px . $show_px .'>';
				for ($i = 9; $i < 71; $i++){ 
					if($val == strval($i)){ $active = 'selected="selected"'; } else { $active = ''; }
					$output .= '<option value="'. $i .'" ' . $active . '>'. $i .'</option>'; }
			$output .= '</select>';
			$output .= '<select class="colabs-typography colabs-typography-size colabs-typography-size-em" id="'. $value['id'].'_size" '. $name_em . $show_em.'>';
				$em = 0.5;
				for ($i = 0; $i < 39; $i++){
					if ($i <= 24)			// up to 2.0em in 0.1 increments
						$em = $em + 0.1;
					elseif ($i >= 14 && $i <= 24)		// Above 2.0em to 3.0em in 0.2 increments
						$em = $em + 0.2;
					elseif ($i >= 24)		// Above 3.0em in 0.5 increments
						$em = $em + 0.5;
					if($val == strval($em)){ $active = 'selected="selected"'; } else { $active = ''; }
					//echo ' '. $value['id'] .' val:'.floatval($val). ' -> ' . floatval($em) . ' $<br />' ;
					$output .= '<option value="'. $em .'" ' . $active . '>'. $em .'</option>'; }
			$output .= '</select>';
			/* Font Unit */
			$val = $default['unit'];
			if ( $typography_stored['unit'] != "") { $val = $typography_stored['unit']; }
				$em = ''; $px = '';
			if($val == 'em'){ $em = 'selected="selected"'; }
			if($val == 'px'){ $px = 'selected="selected"'; }
			$output .= '<select class="colabs-typography colabs-typography-unit" name="'. $value['id'].'_unit" id="'. $value['id'].'_unit">';
			$output .= '<option value="px" '. $px .'">px</option>';
			$output .= '<option value="em" '. $em .'>em</option>';
			$output .= '</select>';
			/* Font Face */
			$val = $default['face'];
			if ( $typography_stored['face'] != "") 
				$val = $typography_stored['face']; 
			$font01 = ''; 
			$font02 = ''; 
			$font03 = ''; 
			$font04 = ''; 
			$font05 = ''; 
			$font06 = ''; 
			$font07 = ''; 
			$font08 = '';
			$font09 = ''; 
			$font10 = '';
			$font11 = '';
			$font12 = '';
			$font13 = '';
			$font14 = '';
			$font15 = '';
			if (strpos($val, 'Arial, sans-serif') !== false){ $font01 = 'selected="selected"'; }
			if (strpos($val, 'Verdana, Geneva') !== false){ $font02 = 'selected="selected"'; }
			if (strpos($val, 'Trebuchet') !== false){ $font03 = 'selected="selected"'; }
			if (strpos($val, 'Georgia') !== false){ $font04 = 'selected="selected"'; }
			if (strpos($val, 'Times New Roman') !== false){ $font05 = 'selected="selected"'; }
			if (strpos($val, 'Tahoma, Geneva') !== false){ $font06 = 'selected="selected"'; }
			if (strpos($val, 'Palatino') !== false){ $font07 = 'selected="selected"'; }
			if (strpos($val, 'Helvetica') !== false){ $font08 = 'selected="selected"'; }
			if (strpos($val, 'Calibri') !== false){ $font09 = 'selected="selected"'; }
			if (strpos($val, 'Myriad') !== false){ $font10 = 'selected="selected"'; }
			if (strpos($val, 'Lucida') !== false){ $font11 = 'selected="selected"'; }
			if (strpos($val, 'Arial Black') !== false){ $font12 = 'selected="selected"'; }
			if (strpos($val, 'Gill') !== false){ $font13 = 'selected="selected"'; }
			if (strpos($val, 'Geneva, Tahoma') !== false){ $font14 = 'selected="selected"'; }
			if (strpos($val, 'Impact') !== false){ $font15 = 'selected="selected"'; }
			$output .= '<select class="colabs-typography colabs-typography-face" name="'. $value['id'].'_face" id="'. $value['id'].'_face">';
			$output .= '<option value="Arial, sans-serif" '. $font01 .'>Arial</option>';
			$output .= '<option value="Verdana, Geneva, sans-serif" '. $font02 .'>Verdana</option>';
			$output .= '<option value="&quot;Trebuchet MS&quot;, Tahoma, sans-serif"'. $font03 .'>Trebuchet</option>';
			$output .= '<option value="Georgia, serif" '. $font04 .'>Georgia</option>';
			$output .= '<option value="&quot;Times New Roman&quot;, serif"'. $font05 .'>Times New Roman</option>';
			$output .= '<option value="Tahoma, Geneva, Verdana, sans-serif"'. $font06 .'>Tahoma</option>';
			$output .= '<option value="Palatino, &quot;Palatino Linotype&quot;, serif"'. $font07 .'>Palatino</option>';
			$output .= '<option value="&quot;Helvetica Neue&quot;, Helvetica, sans-serif" '. $font08 .'>Helvetica*</option>';
			$output .= '<option value="Calibri, Candara, Segoe, Optima, sans-serif"'. $font09 .'>Calibri*</option>';
			$output .= '<option value="&quot;Myriad Pro&quot;, Myriad, sans-serif"'. $font10 .'>Myriad Pro*</option>';
			$output .= '<option value="&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, sans-serif"'. $font11 .'>Lucida</option>';
			$output .= '<option value="&quot;Arial Black&quot;, sans-serif" '. $font12 .'>Arial Black</option>';
			$output .= '<option value="&quot;Gill Sans&quot;, &quot;Gill Sans MT&quot;, Calibri, sans-serif" '. $font13 .'>Gill Sans*</option>';
			$output .= '<option value="Geneva, Tahoma, Verdana, sans-serif" '. $font14 .'>Geneva*</option>';
			$output .= '<option value="Impact, Charcoal, sans-serif" '. $font15 .'>Impact</option>';
			// Google webfonts			
		 	global $google_fonts;
			sort ($google_fonts);
			$output .= '<option value="">-- Google Fonts --</option>';
			foreach ( $google_fonts as $key => $gfont ) :
		 		$font[$key] = '';
				if ($val == $gfont['name']){ $font[$key] = 'selected="selected"'; }
				$name = $gfont['name'];
				$output .= '<option value="'.$name.'" '. $font[$key] .'>'.$name.'</option>';
			endforeach;			
			// Custom Font stack
			$new_stacks = get_option( 'framework_colabs_font_stack' );
			if(!empty($new_stacks)){
				$output .= '<option value="">-- Custom Font Stacks --</option>';
				foreach($new_stacks as $name => $stack){
					if (strpos($val, $stack) !== false){ $fontstack = 'selected="selected"'; } else { $fontstack = ''; }
					$output .= '<option value="'. stripslashes(htmlentities($stack)) .'" '.$fontstack.'>'. str_replace( '_',' ',$name).'</option>';
				}
			}
			$output .= '</select>';
			/* Font Weight */
			$val = $default['style'];
			if ( $typography_stored['style'] != "") { $val = $typography_stored['style']; }
				$normal = ''; $italic = ''; $bold = ''; $bolditalic = '';
			if($val == 'normal'){ $normal = 'selected="selected"'; }
			if($val == 'italic'){ $italic = 'selected="selected"'; }
			if($val == 'bold'){ $bold = 'selected="selected"'; }
			if($val == 'bold italic'){ $bolditalic = 'selected="selected"'; }
			$output .= '<select class="colabs-typography colabs-typography-style" name="'. $value['id'].'_style" id="'. $value['id'].'_style">';
			$output .= '<option value="normal" '. $normal .'>Normal</option>';
			$output .= '<option value="italic" '. $italic .'>Italic</option>';
			$output .= '<option value="bold" '. $bold .'>Bold</option>';
			$output .= '<option value="bold italic" '. $bolditalic .'>Bold/Italic</option>';
			$output .= '</select>';
			/* Font Color */
			$val = $default['color'];
			if ( $typography_stored['color'] != "") { $val = $typography_stored['color']; }			
			$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div></div></div>';
			$output .= '<input class="colabs-color colabs-typography colabs-typography-color" name="'. $value['id'] .'_color" id="'. $value['id'] .'_color" type="text" value="'. $val .'" />';
		break;  
		case "border":
			$default = $value['std'];
			$border_stored = get_option( $value['id'] );
			/* Border Width */
			$val = $default['width'];
			if ( $border_stored['width'] != "") { $val = $border_stored['width']; }
			$output .= '<select class="colabs-border colabs-border-width" name="'. $value['id'].'_width" id="'. $value['id'].'_width">';
				for ($i = 0; $i < 21; $i++){ 
					if($val == $i){ $active = 'selected="selected"'; } else { $active = ''; }
					$output .= '<option value="'. $i .'" ' . $active . '>'. $i .'px</option>'; }
			$output .= '</select>';
			/* Border Style */
			$val = $default['style'];
			if ( $border_stored['style'] != "") { $val = $border_stored['style']; }
				$solid = ''; $dashed = ''; $dotted = '';
			if($val == 'solid'){ $solid = 'selected="selected"'; }
			if($val == 'dashed'){ $dashed = 'selected="selected"'; }
			if($val == 'dotted'){ $dotted = 'selected="selected"'; }
			$output .= '<select class="colabs-border colabs-border-style" name="'. $value['id'].'_style" id="'. $value['id'].'_style">';
			$output .= '<option value="solid" '. $solid .'>Solid</option>';
			$output .= '<option value="dashed" '. $dashed .'>Dashed</option>';
			$output .= '<option value="dotted" '. $dotted .'>Dotted</option>';
			$output .= '</select>';
			/* Border Color */
			$val = $default['color'];
			if ( $border_stored['color'] != "") { $val = $border_stored['color']; }			
			$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div></div></div>';
			$output .= '<input class="colabs-color colabs-border colabs-border-color" name="'. $value['id'] .'_color" id="'. $value['id'] .'_color" type="text" value="'. $val .'" />';
		break;   
		case "images":
			$i = 0;
			$select_value = get_option( $value['id']);
			foreach ($value['options'] as $key => $option) 
			 { 
			 $i++;
				 $checked = '';
				 $selected = '';
				   if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; $selected = 'colabs-radio-img-selected'; } 
				    } else {
						if ($value['std'] == $key) { $checked = ' checked'; $selected = 'colabs-radio-img-selected'; }
						elseif ($i == 1  && !isset($select_value)) { $checked = ' checked'; $selected = 'colabs-radio-img-selected'; }
						elseif ($i == 1  && $value['std'] == '') { $checked = ' checked'; $selected = 'colabs-radio-img-selected'; }
						else { $checked = ''; }
					}	
				$output .= '<span>';
				$output .= '<input type="radio" id="colabs-radio-img-' . $value['id'] . $i . '" class="checkbox colabs-radio-img-radio" value="'.$key.'" name="'. $value['id'].'" '.$checked.' />';
				$output .= '<div class="colabs-radio-img-label">'. $key .'</div>';
				$output .= '<img src="'.$option.'" alt="" class="colabs-radio-img-img '. $selected .'" onClick="document.getElementById(\'colabs-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
				$output .= '</span>';
			}
		break; 
		case "info":
			$default = $value['std'];
			$output .= $default;
		break; 
		case "string_builder":
			$desc = $value['std'];
			$output .= '<div id="'.$value['id'].'">';
			$output .= 'Name<input class="colabs-input colabs-ignore" name="name" id="'. $value['id'] .'_name" type="text" />';
			$output .= 'Font Stack<input class="colabs-input colabs-ignore" name="value" id="'. $value['id'] .'_value" type="text" />';
			$output .= '<div class="add_button"><a class="button string_builder_add" href="#" class="string_builder" id="'.$value['id'].'">Add</a></div>';
			$output .= '<div id="'.$value['id'].'_return" class="string_builder_return">';
			$output .= '<h3>'.$desc.'</h3>';
			$saved_data = get_option($value['id']);
			if(!empty($saved_data)){
				foreach($saved_data as $name => $data){
					$data = stripslashes($data);	
					$output .= '<div class="string_option" id="string_builer_option_'.str_replace( ' ','_',$name).'"><a class="delete" rel="'.$name.'" href="#"><img src="'.get_bloginfo( 'template_url').'/functions/images/ico-close.png" /></a><span>'.str_replace( '_',' ',$name) .':</span> '. $data .'</div>';
				}
			}
			$output .= '<div style="display:none" class="string_builder_empty">Nothing added yet.</div>';			
			$output .= '</div>';
			$output .= '</div>';
		break;                               
		case "heading":
			if($counter >= 2){
			   $output .= '</div>'."\n";
			}
			$jquery_click_hook = ereg_replace( "[^A-Za-z0-9]", "", strtolower($value['name']) );
			$jquery_click_hook = "colabs-option-" . $jquery_click_hook;
//			$jquery_click_hook = "colabs-option-" . str_replace( "&","",str_replace( "/","",str_replace( ".","",str_replace( ")","",str_replace( "( ","",str_replace( " ","",strtolower($value['name'])))))));
			$menu .= '<li class="'.$value['icon'].'"><a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">'.  $value['name'] .'</a></li>';
			$output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
		break;                                  
		} 
		// if TYPE is an array, formatted into smaller inputs... ie smaller values
		if ( is_array($value['type'])) {
			foreach($value['type'] as $array){
					$id = $array['id']; 
					$std = $array['std'];
					$saved_std = get_option($id);
					if($saved_std != $std){$std = $saved_std;} 
					$meta = $array['meta'];
					if($array['type'] == 'text') { // Only text at this point
						 $output .= '<input class="input-text-small colabs-input" name="'. $id .'" id="'. $id .'" type="text" value="'. $std .'" />';  
						 $output .= '<span class="meta-two">'.$meta.'</span>';
					}
				}
		}
		if ( $value['type'] != "heading" ) { 
			if ( $value['type'] != "checkbox" ) 
				{ 
				$output .= '<br/>';
				}
			if(!isset($value['desc'])){ $explain_value = ''; } else{ $explain_value = $value['desc']; } 
			$output .= '</span><span class="description"><label for="'.$value['id'].'">'. $explain_value .'</label></span>'."\n";
			$output .= '<div class="clear"> </div></div></div>'."\n";
			}
	}
    //Checks if is not the Content Builder page
    if ( isset($_REQUEST['page']) && $_REQUEST['page'] != 'colabsthemes_content_builder' ) {
		$output .= '</div>';
	}
    return array($output,$menu);
}
}
/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Uploader - colabsthemes_uploader_function */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabsthemes_uploader_function')) {
function colabsthemes_uploader_function($id,$std,$mod){
    //$uploader .= '<input type="file" id="attachement_'.$id.'" name="attachement_'.$id.'" class="upload_input"></input>';
    //$uploader .= '<span class="submit"><input name="save" type="submit" value="Upload" class="button upload_save" /></span>';
	$uploader = '';
    $upload = get_option($id);
	if($mod != 'min') { 
			$val = $std;
            if ( get_option( $id ) != "") { $val = get_option($id); }
            $uploader .= '<input class="colabs-input" name="'. $id .'" id="'. $id .'_upload" type="text" value="'. $val .'" />';
	}
	$uploader .= '<div class="upload_button_div"><span class="button image_upload_button" id="'.$id.'">Upload Image</span>';
	if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
	$uploader .= '<span class="button image_reset_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">Remove</span>';
	$uploader .='</div>' . "\n";
    $uploader .= '<div class="clear"></div>' . "\n";
	if(!empty($upload)){
		//$upload = cleanSource($upload); // Removed since V.2.3.7 it's not showing up
    	$uploader .= '<a class="colabs-uploaded-image" href="'. $upload . '">';
    	$uploader .= '<img class="colabs-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
    	$uploader .= '</a>';
		}
	$uploader .= '<div class="clear"></div>' . "\n"; 
return $uploader;
}
}
?>