<?php
/*---------------------------------------------------------------------------------*/
/* About widget */
/*---------------------------------------------------------------------------------*/
class CoLabs_About extends WP_Widget {

   function CoLabs_About() {
	   $widget_ops = array('description' => 'About Page.' );
       parent::WP_Widget(false, __('ColorLabs - About Page', 'colabsthemes'),$widget_ops);      
   }

   function widget($args, $instance) {  
    extract( $args );
   	$title = $instance['title'];
	$page = $instance['page'];
	?>
		<?php echo $before_widget; ?>
        <?php echo $before_title . $title . $after_title;  ?>
        <?php 
		wp_reset_query();
		query_posts('page_id='.$page);
		while ( have_posts() ) : the_post();
		?>
		<div class="about-author">
		<div class="gravatar">
		<a href="<?php the_permalink();?>">
		<?php colabs_image('width=138&height=91&link=img');?>
		</a>
		</div>
		<h6><?php the_title();?></h6>
		<?php
		echo '<p>'.excerpt(25,'...').'</p>';	
		?>
		</div>
		<a class="more-link" href="<?php the_permalink();?>"><span>More &rarr;</span></a>
		<?php
		
		endwhile;
		?>
		<?php echo $after_widget; ?>   
   <?php
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
   
       $title = esc_attr($instance['title']);
	   $page = esc_attr($instance['page']);

       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','colabsthemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
	    <p>
	   	   <label for="<?php echo $this->get_field_id('page'); ?>"><?php _e('Page:','colabsthemes'); ?></label>
		   <select name="<?php echo $this->get_field_name('page'); ?>">
			<?php
			$colabs_pages = array();
			$colabs_pages_obj = get_pages('sort_column=post_parent,menu_order');    
			
			foreach ($colabs_pages_obj as $colabs_page) {
			//$colabs_pages[$colabs_page->ID] = $colabs_page->post_name; 
			if ($page==$colabs_page->ID)$selected='selected="true"';
			echo '<option value="'.$colabs_page->ID.'" '.$selected.'>'.$colabs_page->post_name.'</option>';
			}
			?>
			</select>
	       
       </p>
      <?php
   }
} 

register_widget('CoLabs_About');
?>