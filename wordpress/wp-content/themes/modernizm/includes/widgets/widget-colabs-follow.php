<?php
/*---------------------------------------------------------------------------------*/
/* Follow widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_Follow extends WP_Widget {

   function CoLabs_Follow() {
	   $widget_ops = array('description' => 'Follow widget.' );
       parent::WP_Widget(false, __('ColorLabs - Follow', 'colabsthemes'),$widget_ops);      
   }

   function widget($args, $instance) {  
    extract( $args );
   	$title = apply_filters('widget_title', $instance['title'] );
	?>
		<?php echo $before_widget; ?>
        <?php if ($title) { echo $before_title . $title . $after_title; }else {echo $before_title .__('Follow Us','colabsthemes'). $after_title;} ?>
        <ul>
	    <li><a class="twitter" href="<?php if (get_option("colabs_social_twitter")!=''){ echo get_option("colabs_social_twitter"); }else{ echo 'http://twitter.com/colorlabs'; }?>">Twitter</a></li>
	    <li><a class="facebook" href="<?php if (get_option("colabs_social_facebook")!=''){ echo get_option("colabs_social_facebook"); }else{ echo 'http://www.facebook.com/colorlabs'; }?>">Facebook</a></li>
	    <li><a class="rss" href="<?php if(get_option("colabs_feed_url") != ''){ echo 'http://feeds.feedburner.com/'.get_option("colabs_feed_url");	}else{ bloginfo("rss2_url"); }?>">RSS</a></li>
		</ul>
		<?php echo $after_widget; ?>   
   <?php
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
   
       $title = esc_attr($instance['title']);

       ?>
		<p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
      <?php
   }
} 

register_widget('CoLabs_Follow');
?>