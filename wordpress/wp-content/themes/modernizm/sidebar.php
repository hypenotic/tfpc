<div id="sidebar" class="columns col2">

	<?php if ( !dynamic_sidebar( 'Right Sidebar' ) ) : ?>
	<div class="column col2 social">
		<h6>Follow Us</h6>
	  <ul>
	    <li><a class="twitter" href="<?php if (get_option("colabs_social_twitter")!=''){ echo get_option("colabs_social_twitter"); }else{ echo 'http://twitter.com/colorlabs'; }?>">Twitter</a></li>
	    <li><a class="facebook" href="<?php if (get_option("colabs_social_facebook")!=''){ echo get_option("colabs_social_facebook"); }else{ echo 'http://www.facebook.com/colorlabs'; }?>">Facebook</a></li>
	    <li><a class="linkedin" href="<?php if (get_option("colabs_social_linkedin")!=''){ echo get_option("colabs_social_linkedin"); }else{ echo 'http://www.linkedin.com/colorlabs/'; }?>">Linkedin</a></li>
	    <li><a class="rss" href="<?php if(get_option("colabs_feed_url") != ''){ echo 'http://feeds.feedburner.com/'.get_option("colabs_feed_url");	}else{ bloginfo("rss2_url"); }?>">RSS</a></li>
	  </ul>
	</div><!-- .social -->
	
	<div class="column col2 widget">
		<h6>Recommended Links</h6>
	  <ul>
	    <li><a href="http://colorlabsproject.com" target="_blank">Storefront</a></li>
	    <li><a href="http://colorlabsproject.com/blog" target="_blank">Company Blog</a></li>
	    <li><a href="http://colorlabsproject.com/themes" target="_blank">Themes</a></li>
	    <li><a href="http://colorlabsproject.com/showcase" target="_blank">Showcase</a></li>
	    <li><a href="http://colorlabsproject.com/team" target="_blank">The Team</a></li>
	    <li><a href="http://colorlabsproject.com/jobs" target="_blank">Grow With Us</a></li>
	  </ul>
	</div>

	<?php endif; ?>

</div><!-- #sidebar -->
