<?php
/*---------------------------------------------------------------------------------*/
/* Embed Widget */
/*---------------------------------------------------------------------------------*/


class CoLabs_EmbedWidget extends WP_Widget {

	function CoLabs_EmbedWidget() {
		$widget_ops = array('description' => 'Display the Embed code from posts, use in Sidebar only.' );
		parent::WP_Widget(false, __('ColorLabs - Embed/Video', 'colabsthemes'),$widget_ops);      
	}

	function widget($args, $instance) { 
		extract( $args ); 
		$title = apply_filters('widget_title', $instance['title'] );
		$limit = 1;
		
		$cat_id = $instance['cat_id'];
		$tag = $instance['tag'];
		
		$width = $instance['width'];
		$height = $instance['height'];
		wp_reset_query();		
		if(!empty($tag))
			$myposts = get_posts("numberposts=$limit&tag=$tag");
		else
			$myposts = get_posts("numberposts=$limit&cat=$cat_id");

		

        echo $before_widget; ?>
       
        <?php

			echo $before_title .$title. $after_title; ?>
           
            <?php    
		
			if(isset($myposts)) {
			
				foreach($myposts as $mypost) {
					
					$embed = colabs_get_embed('colabs_embed',$width,$height,'widget_video',$mypost->ID);

					if($embed) {
						
						?>
						<div class="widget-video-unit"  >
						<?php
							
							
							echo $embed;
							echo '<h4><a href="'.get_permalink( $mypost->ID ).'">' . get_the_title($mypost->ID)  . "</a></h4>\n";
							
						?>
						</div>
						<?php
					}
				}
			}
		?>
       

        <?php
			
		echo $after_widget;

	}

	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {        
		$title = esc_attr($instance['title']);
		
		$cat_id = esc_attr($instance['cat_id']);
		$tag = esc_attr($instance['tag']);

		$width = esc_attr($instance['width']);
		$height = esc_attr($instance['height']);
		
		
		if(empty($width)) $width = 300;
		if(empty($height)) $height = 220;

		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
        </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id('cat_id'); ?>"><?php _e('Category:','colabsthemes'); ?></label>
	       <?php $cats = get_categories(); ?>
	       <select name="<?php echo $this->get_field_name('cat_id'); ?>" class="widefat" id="<?php echo $this->get_field_id('cat_id'); ?>">
           <option value="">Disabled</option>
			<?php
			
           	foreach ($cats as $cat){
           	?><option value="<?php echo $cat->cat_ID; ?>" <?php if($cat_id == $cat->cat_ID){ echo "selected='selected'";} ?>><?php echo $cat->cat_name . ' (' . $cat->category_count . ')'; ?></option><?php
           	}
           ?>
           </select>
       </p>
        <p>
            <label for="<?php echo $this->get_field_id('tag'); ?>">Or <?php _e('Tag:','colabsthemes'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('tag'); ?>" value="<?php echo $tag; ?>" class="widefat" id="<?php echo $this->get_field_id('tag'); ?>" />
        </p>

         <p>
            <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Size:','colabsthemes'); ?></label>
            <input type="text" size="2" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $width; ?>" class="" id="<?php echo $this->get_field_id('width'); ?>" /> W
            <input type="text" size="2" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $height; ?>" class="" id="<?php echo $this->get_field_id('height'); ?>" /> H

        </p>
        
         

        <?php
	}
}

register_widget('CoLabs_EmbedWidget');



?>