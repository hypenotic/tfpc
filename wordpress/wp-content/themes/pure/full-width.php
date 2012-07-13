<?php /*
Template Name: Full Width
*/ ?>
<?php get_header(); ?>		
	<div id="pageHead">
		<h1><?php the_title(); ?></h1>
	</div>
	<div id="main" class="clearfix page full">					 
		<div id="content" class="clearfix">
			<?php while (have_posts()) : the_post(); ?>			    
				<div class="post">
					<?php edit_post_link(__('Edit Page', 'themetrust'), '<p>', '</p>'); ?>							
					<?php the_content(); ?>				
				</div>				
				<?php comments_template('', true); ?>			
			<?php endwhile; ?>					    	
		</div>
	</div>		
<?php get_footer(); ?>