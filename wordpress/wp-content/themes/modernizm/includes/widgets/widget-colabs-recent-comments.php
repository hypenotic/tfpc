<?php
/*---------------------------------------------------------------------------------*/
/* Comment widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_Comment extends WP_Widget {

   function CoLabs_Comment() {
	   $widget_ops = array('description' => 'Recent Comments.' );
       parent::WP_Widget(false, __('ColorLabs - Recent Comments', 'colabsthemes'),$widget_ops);      
   }

   function widget($args, $instance) {  
    extract( $args );
   	$title = $instance['title'];
	$number = $instance['number']; if ($number == '') $number = 5;
    $thumb_size = $instance['thumb_size']; if ($thumb_size == '') $thumb_size = 0;
	?>
		<?php echo $before_widget; ?>
        <?php if ($title) { echo $before_title . $title . $after_title; }else {echo $before_title .__('Recent Comments','colabsthemes'). $after_title;} ?>
		 <ul id="tab-pop" class="list">            
            <?php if ( function_exists('colabs_tabs_comments') ) colabs_tabs_comments($number, $thumb_size); ?>                    
         </ul>
		<?php echo $after_widget; ?>   
   <?php
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
   
       $title = esc_attr($instance['title']);
	   $number = esc_attr($instance['number']);
       $thumb_size = esc_attr($instance['thumb_size']);

       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
	   <p>
	       <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts:','colabsthemes'); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
	       </label>
       </p>  
       <p>
	       <label for="<?php echo $this->get_field_id('thumb_size'); ?>"><?php _e('Thumbnail Size (0=disable):','colabsthemes'); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id('thumb_size'); ?>" name="<?php echo $this->get_field_name('thumb_size'); ?>" type="text" value="<?php echo $thumb_size; ?>" />
	       </label>
       </p> 
      <?php
   }
} 

register_widget('CoLabs_Comment');
?>