<?php
/*
Template Name: Resource Category
*/
 ?>
 <?php get_header();?>
 <div id="content" class="recent-list columns col10">
 	<?php while ( have_posts() ) : the_post(); ?>
 	<div class="entry-content column col10">
 		<h3 class="headline-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
 		<p><?php excerpt();?></p>
 	</div>
	<?php endwhile; ?>
<?php get_sidebar(); ?>
 <?php get_footer(); ?>