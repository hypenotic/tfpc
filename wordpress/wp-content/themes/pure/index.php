<?php get_header(); ?>	
		<div id="main" class="clearfix">				 
		<div id="content" class="full clearfix twoThird">
			<?php $c=0; $post_count = $wp_query->post_count; ?>			
			<?php  while (have_posts()) : the_post(); ?>
			    <?php $c++; ?>
			    <?php $postClass = has_post_thumbnail() ? $postClass = "withThumb" :  $postClass = ""; ?>
				<?php $postClass = $c == $post_count ? $postClass = $postClass." lastPost" :  $postClass = $postClass; ?>
				<div class="post clearfix <?php echo $postClass; ?>">						
											
						<?php if(has_post_thumbnail()) : ?>												
					    		<a href="<?php the_permalink() ?>" rel="bookmark" ><?php the_post_thumbnail('ttrust_small', array('class' => 'postThumb', 'alt' => ''.get_the_title().'', 'title' => ''.get_the_title().'')); ?></a>			    	
						<?php endif; ?>
						<div class="inside">
							<div class="metaCat"><?php _e('In', 'themetrust'); ?> <?php the_category(', ') ?></div>
							<h1><a href="<?php the_permalink() ?>" rel="bookmark" ><?php the_title(); ?></a></h1>
							<div class="meta clearfix">						
								<?php _e('Posted by:', 'themetrust'); ?> <?php the_author_posts_link(); ?> <?php _e('on', 'themetrust'); ?> <?php the_time( 'M j, Y' ) ?> | <a href="<?php comments_link(); ?>"><?php comments_number(__('No Comments', 'themetrust'), __('One Comment', 'themetrust'), __('% Comments', 'themetrust')); ?></a>
							</div>											
							<?php $content = get_the_content(''); ?>
							<?php $content = apply_filters('the_content', $content); ?>
							<?php echo wpt_strip_content_tags($content); ?>							
							<?php more_link(); ?>
						</div>																				
			    </div>				
			
			<?php endwhile; ?>
			
			<?php include( TEMPLATEPATH . '/includes/pagination.php'); ?>	    	
		</div> <!-- end content -->
		
		<?php get_sidebar(); ?>
		</div>
	
<?php get_footer(); ?>
