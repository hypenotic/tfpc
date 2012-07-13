<?php query_posts('caller_get_posts=1&post_type=any&meta_key=_ttrust_in_slideshow_value&meta_value=true&posts_per_page=10'); ?>
<?php if(have_posts()) :?>
<?php global $more; $more=0; ?>
<div id="slideshow">	
	<div id="slides">			
		
		<?php $i = 1; while (have_posts()) : the_post(); ?>				
		<?php if(has_post_thumbnail()) : ?>				
		
		<div id="slide<?php echo $i; ?>" class="clearfix slide" <?php if($i > 1) echo('style="display: none;"'); ?> >		
			<?php $slideLink = get_permalink(); ?>				
			<div class="slideImage">										
		    	<a href="<?php the_permalink() ?>" rel="bookmark" ><?php the_post_thumbnail('ttrust_slideshow', array('alt' => ''.get_the_title().'', 'title' => ''.get_the_title().'')); ?></a>				
			</div>				
				
			<div class="slideText">
				<?php if ($post->post_type == 'post') :?>
					<div class="metaCat"><?php _e('In', 'themetrust'); ?> <?php the_category(', ') ?></div>
				<?php endif; ?>
				<h3><a href="<?php the_permalink() ?>" rel="bookmark" ><?php the_title(); ?></a></h3>							
				<?php $content = get_the_content(''); ?>
				<?php $content = apply_filters('the_content', $content); ?>
				<?php echo wpt_strip_content_tags($content); ?>
				<?php more_link(); ?>				
			</div>	
		</div>
		<?php $i++; ?>
		<?php endif; ?>		
		
		<?php endwhile; ?>
		<?php wp_reset_query();?>		
	</div>
	
	<?php if($i > 2) : ?>
	<div id="slideshowControls">	
		<div id="slideshowNav">	 			
			<div id="slideshowNavPager"></div>			
		</div>	
	</div>	
	<?php endif; ?>
	
</div>
<?php endif; ?>