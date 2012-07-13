<?php
/*-----------------------------------------------------------------------------------*/
/* SEO - colabsthemes_seo_page */
/*-----------------------------------------------------------------------------------*/

function colabsthemes_seo_page(){

    $themename =  get_option( 'colabs_themename' );
    $manualurl =  get_option( 'colabs_manual' );
	$shortname =  'seo_colabs';

    //Framework Version in Backend Head
    $colabs_framework_version = get_option( 'colabs_framework_version' );

    //Version in Backend Head
    $theme_data = get_theme_data( get_template_directory() . '/style.css' );
    $local_version = $theme_data['Version'];

    //GET themes update RSS feed and do magic
	include_once(ABSPATH . WPINC . '/feed.php' );

	$pos = strpos($manualurl, 'documentation' );
	$theme_slug = str_replace( "/", "", substr($manualurl, ($pos + 13))); //13 for the word documentation

    //add filter to make the rss read cache clear every 4 hours
    add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 14400;' ) );

	$inner_pages = array(	'b' => 'Page title;',
                            'a' => 'Page title; Blog title',
							'd' => 'Page title; Blog description',
                            'f' => 'Page title; Blog title; Blog description',
							'c' => 'Blog title; Page title;',
							'e' => 'Blog title; Page title; Blog description'
						);

	$seo_options = array();

	$seo_options[] = array( "name" => "Page Title",
					"icon" => "misc",
					"type" => "heading" );

	$seo_options[] = array( "name" => "Blog Title",
					"desc" => "NOTE: This value corresponds to that in the Settings > General tab in the WordPress Dashboard.",
					"id" => "blogname",
					"std" => "",
					"type" => "text" );

	$seo_options[] = array( "name" => "Blog Description",
					"desc" => "NOTE: This value corresponds to that in the Settings > General tab in the WordPress Dashboard.",
					"id" => "blogdescription",
					"std" => "",
					"type" => "text" );

	$seo_options[] = array( "name" => "Separator",
					"desc" => "Set a character that separates elements of your page titles ( eg. |, -, or &raquo; ).",
					"id" => $shortname."_separator",
					"std" => "|",
					"type" => "text" );

	$seo_options[] = array( "name" => "Custom Page Titles",
					"desc" => "Check this box to gain control over the elements of the page titles (highly recommended).",
					"id" => $shortname."_wp_title",
					"std" => "true",
					"class" => "collapsed",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => "Homepage Title Layout",
					"desc" => "Define the order of the title elements.",
					"id" => $shortname."_home_layout",
					"std" => "a",
					"class" => "hidden",
					"options" => array(	'b' => 'Blog title',
                                        'a' => 'Blog title; Blog description',
										'c' => 'Blog description',
                                        'd' => 'Blog description; Blog title'
                                        ),
					"type" => "select2" );

	$seo_options[] = array( "name" => "Single Title Layout",
					"desc" => "Define the order of the title elements.",
					"id" => $shortname."_single_layout",
					"std" => "f",
					"class" => "hidden",
					"options" => $inner_pages,
					"type" => "select2" );

	$seo_options[] = array( "name" => "Page Title Layout",
					"desc" => "Define the order of the title elements.",
					"id" => $shortname."_page_layout",
					"std" => "f",
					"class" => "hidden",
					"options" => $inner_pages,
					"type" => "select2" );

	$seo_options[] = array( "name" => "Archive Title Layout",
					"desc" => "Define the order of the title elements.",
					"id" => $shortname."_archive_layout",
					"std" => "f",
					"class" => "hidden",
					"options" => $inner_pages,
					"type" => "select2" );

	$seo_options[] = array( "name" => "Page Number",
					"desc" => "Define a text string that precedes page number in page titles.",
					"id" => $shortname."_paged_var",
					"std" => "Page",
					"class" => "hidden",
					"type" => "text" );

	$seo_options[] = array( "name" => "Page Number Position",
					"desc" => "Define the position of page number in page titles.",
					"id" => $shortname."_paged_var_pos",
					"std" => "before",
					"class" => "hidden",
					"options" => array(	'before' => 'Before Title',
										'after' => 'After Title'),
					"type" => "select2" );

	$seo_options[] = array( "name" => "Disable Custom Titles",
					"desc" => "If you prefer to have uniform titles across you theme. Alternatively they will be generated from custom fields and/or plugin data.",
					"id" => $shortname."_wp_custom_field_title",
					"std" => "false",
					"class" => "hidden hide",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => "Description Meta",
					"icon" => "misc",
					"type" => "heading" );

	$seo_options[] = array( "name" => "Homepage Description",
					"desc" => "Choose where to populate the homepage meta description from.",
					"id" => $shortname."_meta_home_desc",
					"std" => "b",
                    "class" => "collapsed",
					"options" => array(	"a" => "Off",
										"b" => "From Site Description",
										"c" => "From Custom Homepage Description"),
					"type" => "radio" );

	$seo_options[] = array( "name" => "Custom Homepage Description",
					"desc" => "Add a custom meta description to your homepage.",
					"id" => $shortname."_meta_home_desc_custom",
					"std" => "",
                    "class" => "hidden last",
					"type" => "textarea" );

	$seo_options[] = array( "name" => "Single Page/Post Description",
					"desc" => "Choose where to populate the single Page/Post meta description from.",
					"id" => $shortname."_meta_single_desc",
					"std" => "c",
					"options" => array(	"a" => "Off *",
										"b" => "From Custom Field and/or Plugin Data",
										"c" => "Automatically from Post/Page Content",
										),
					"type" => "radio" );

	$seo_options[] = array( "name" => "Global Post/Page Description",
					"desc" => "Add a custom meta description to your posts and pages. This will only show if no other data is available from the selection above. This will still be added even if setting above is set to \"Off\".",
					"id" => $shortname."_meta_single_desc_sitewide",
					"std" => "",
					"class" => "collapsed",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => "Global Post/Page Description",
					"desc" => "Add a global post/page description.",
					"id" => $shortname."_meta_single_desc_custom",
					"std" => "",
					"class" => "hidden",
					"type" => "textarea" );

	$seo_options[] = array( "name" => "Keyword Meta",
					"icon" => "misc",
					"type" => "heading" );

	$seo_options[] = array( "name" => "Homepage Keywords",
					"desc" => "Choose where to populate the homepage meta keywords from.",
					"id" => $shortname."_meta_home_key",
					"std" => "a",
                    "class" => "collapsed",
					"options" => array(	"a" => "Off",
										"c" => "From Custom Homepage Keywords"),
					"type" => "radio" );

	$seo_options[] = array( "name" => "Custom Homepage Keywords",
					"desc" => "Add a comma-separated list of keywords to your homepage.",
					"id" => $shortname."_meta_home_key_custom",
					"std" => "",
                    "class" => "hidden last",
					"type" => "textarea" );

	$seo_options[] = array( "name" => "Single Page/Post Keywords",
					"desc" => "Choose where to populate the single page/post meta keywords from.",
					"id" => $shortname."_meta_single_key",
					"std" => "c",
					"options" => array(	"a" => "Off *",
										"b" => "From Custom Fields and/or Plugins",
										"c" => "Automatically from Post Tags &amp; Categories"),
					"type" => "radio" );

	$seo_options[] = array( "name" => "Global Post/Page Keywords",
					"desc" => "Add custom meta keywords to your posts and pages. These will only show if no other data is available from the selection above. These will still be added even if setting above is set to \"Off\".",
					"id" => $shortname."_meta_single_key_sitewide",
					"std" => "",
					"class" => "collapsed",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => "Global Post/Page Keywords",
					"desc" => "Add a comma-separated list of keywords to your posts and pages.",
					"id" => $shortname."_meta_single_key_custom",
					"std" => "",
					"class" => "hidden",
					"type" => "textarea" );

	$seo_options[] = array( "name" => "Indexing Options",
					"icon" => "misc",
					"type" => "heading" );

	/*$seo_options[] = array( "name" => "Add Indexing Meta",
					"desc" => "Add links to the header telling the search engine what the start, next, previous and home urls are.",
					"id" => $shortname."_meta_basics",
					"std" => "false",
					"type" => "checkbox" ); */

	$seo_options[] = array( "name" => "Archive Pages to Index",
					"desc" => "Select which archive pages to be indexed. Indexing archive pages may result in duplicate entries in search engines and cause content dilution.",
					"id" => $shortname."_meta_indexing",
					"std" => "category",
					"type" => "multicheck",
					"options" => array(	'category' => 'Category Archives',
										'tag' => 'Tag Archives',
										'author' => 'Author Pages',
										'search' => 'Search Results',
										'date' => 'Date Archives'));

	$seo_options[] = array( "name" => "Add 'follow' Meta to Posts and Pages",
					"desc" => "Check this box to add 'follow' meta to all posts and pages. This means that all links on these pages will be crawled by search engines, including those leading away from your site.",
					"id" => $shortname."_meta_single_follow",
					"std" => "",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => "Advanced Settings",
					"icon" => "general",
					"type" => "heading" );

	$seo_options[] = array( "name" => "Please Read",
					"type" => "info",
					"std" => "Data from 3rd party plugin such as All-in-One SEO Pack, Headspace 2 and WordPress SEO By Yoast can also be used where applicable. Use the checkbox below to use 3rd party plugin data.</span>" );

	$seo_options[] = array( "name" => "Use 3rd Party Plugin Data",
					"desc" => "Meta data added to <strong>custom fields in posts and pages</strong> will be extracted and used where applicable. This typically does not include home page and archive pages and only single post/pages.",
					"id" => $shortname."_use_third_party_data",
					"std" => "false",
					"type" => "checkbox" );

	$seo_options[] = array( "name" => "Hide ColorLabs SEO Settings",
					"desc" => "Check this box to hide the ColorLabs SEO Settings box in the post and page editing screens.",
					"id" => $shortname."_hide_fields",
					"std" => "false",
					"type" => "checkbox" );


	update_option( 'colabs_seo_template',$seo_options);


	?>
    <?php

    	if(
    		class_exists( 'All_in_One_SEO_Pack') ||
    		class_exists( 'Headspace_Plugin') ||
    		class_exists( 'WPSEO_Admin' ) ||
    		class_exists( 'WPSEO_Frontend' )
    	  ) {

			//echo "<div id='colabs-seo-notice' class='colabs-notice'><p><strong>3rd Party SEO Plugin(s) Detected</strong> - Some ".$themename." SEO functionality has been disabled.</p></div>";
			echo "<div id='' class='update-nag'><strong>3rd Party SEO Plugin(s) Detected</strong> - Some ".$themename." SEO functionality has been disabled.</div>";
		}

    ?>
    <?php

    	if ( get_option( 'blog_public') == 0 ) {

			//echo "<div id='colabs-seo-notice-privacy' class='colabs-notice update-nag'><p><strong>This site is set to Private</strong> - SEO is disabled, change settings <a href='". admin_url( 'options-privacy.php' ) . "'>here</a>.</p></div>";
            echo "<div id='' class='update-nag'><strong>This site is set to Private</strong> - SEO is disabled, change settings <a href='". admin_url( 'options-privacy.php' ) . "'>here</a>.</div>";

		}

    ?>
    <div class="wrap colabs_container">

        <form action="" enctype="multipart/form-data" id="colabsform">
        <?php
	    	// Add nonce for added security.
	    	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-seo-options-update' ); } // End IF Statement

	    	$colabs_nonce = '';

	    	if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-seo-options-update' ); } // End IF Statement

	    	if ( $colabs_nonce == '' ) {} else {

	    ?>
	    	<input type="hidden" name="_ajax_nonce" value="<?php echo $colabs_nonce; ?>" />
	    <?php

	    	} // End IF Statement
	    ?>
        <div class="themever left">
           <div id="icon-colabs" class="icon32"><br /></div>
            <h2><?php echo $themename; ?> <?php echo $local_version; ?>&nbsp;<?php _e( 'SEO' ) //your admin panel title ?></h2>
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
            <?php $return = colabsthemes_machine($seo_options); ?>
            <div id="main" class="menu-item-settings metabox-holder">
                <div id="colabs-nav">
                    <ul>
                        <?php echo $return[1]; ?>
                    </ul>
                </div>
                <div id="content">
                <?php echo $return[0]; ?>
                </div>
                <div class="clear"></div>

            </div>
            <div class="clear"></div>
            <div class="save_bar_down right">
            <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/ajax-loading.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
            <input type="submit" value="Save All Changes" class="button submit-button button-primary" />
            </form>

            <form action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" method="post" style="display:inline" id="colabsform-reset">
            <?php
		    	// Add nonce for added security.
		    	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'colabsframework-seo-options-reset' ); } // End IF Statement

		    	$colabs_nonce = '';

		    	if ( function_exists( 'wp_create_nonce' ) ) { $colabs_nonce = wp_create_nonce( 'colabsframework-seo-options-reset' ); } // End IF Statement

		    	if ( $colabs_nonce == '' ) {} else {

		    ?>
		    	<input type="hidden" name="_ajax_nonce" value="<?php echo $colabs_nonce; ?>" />
		    <?php

		    	} // End IF Statement
		    ?>
            <span class="submit-footer-reset">
            <input name="reset" type="submit" value="Reset Options" class="button submit-button reset-button button-highlighted" onclick="return confirm( 'Click OK to reset. Any settings will be lost!' );" />
            <input type="hidden" name="colabs_save" value="reset" />
            </span>
        	</form>

            </div>
            
    <div style="clear:both;"></div>
    </div><!--wrap-->

<?php } ?>