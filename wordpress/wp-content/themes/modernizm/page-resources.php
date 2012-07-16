<?php
/*
Template Name: Resources
*/
 ?>
 <?php get_header();?>
 <div id="content" class="recent-list columns col12">
 	<?php while ( have_posts() ) : the_post(); ?>
 	<?php the_content();?>
 	<?php endwhile; ?>
 	<div class="recent-entry columns col12">
 		<div class="column col2"><h4 class="entry-title">Main Category</h4></div>
 		<div class="column col2"><h4 class="entry-title">Main Category</h4></div>
 		<div class="column col2"><h4 class="entry-title">Main Category</h4></div>
 		<div class="column col2"><h4 class="entry-title">Main Category</h4></div>
 		<div class="column col2"><h4 class="entry-title">Main Category</h4></div>
 		<div class="column col2"><h4 class="entry-title">Main Category</h4></div>
 	</div>	
 </div>
 <?php get_footer(); ?>