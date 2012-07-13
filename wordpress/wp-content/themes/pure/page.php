<?php get_header(); ?>	
	<?php if(!is_front_page()):?>
	<div id="pageHead">
		<h1><?php the_title(); ?></h1>
	</div>
	<?php endif; ?>
	<div id="main" class="clearfix page">			 
		<div id="content" class="twoThird clearfix">
			<?php while (have_posts()) : the_post(); ?>			    
			    <div class="post">
					<?php edit_post_link(__('Edit Page', 'themetrust'), '<p>', '</p>'); ?>							
					<?php the_content(); ?>				
				</div>				
				<?php comments_template('', true); ?>			
			<?php endwhile; ?>					    	
		</div>		
		<?php get_sidebar(); ?>
	</div>	
<?php get_footer(); ?>
