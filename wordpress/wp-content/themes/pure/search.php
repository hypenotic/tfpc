<?php get_header(); ?>
	<div id="pageHead">
		<h1><?php _e('Search Results', 'themetrust'); ?></h1>
	</div>
	<div id="main" class="clearfix">				 
	<div id="content" class="twoThird clearfix">
		<?php $c=0; $post_count = $wp_query->post_count; ?>	
		<?php while (have_posts()) : the_post(); ?>
			<?php $c++; ?>
			<?php $postClass = has_post_thumbnail() ? $postClass = "withThumb" :  $postClass = ""; ?>
			<?php $postClass = $c == $post_count ? $postClass = $postClass." lastPost" :  $postClass = $postClass; ?>    
			<div class="post clearfix <?php echo $postClass; ?>">										
									
				<?php if(has_post_thumbnail()) : ?>												
					<a href="<?php the_permalink() ?>" rel="bookmark" ><?php the_post_thumbnail('ttrust_small', array('class' => 'postThumb', 'alt' => ''.get_the_title().'', 'title' => ''.get_the_title().'')); ?></a>			    	
				<?php endif; ?>																	
				<div class="inside">
					<h1><a href="<?php the_permalink() ?>" rel="bookmark" ><?php the_title(); ?></a></h1>	
					<?php the_excerpt('',TRUE); ?>
				</div>																									
			</div>				
			
		<?php endwhile; ?>
		<?php include( TEMPLATEPATH . '/includes/pagination.php'); ?>					    	
	</div>
		
	<?php get_sidebar(); ?>	
	</div>				
	
<?php get_footer(); ?>