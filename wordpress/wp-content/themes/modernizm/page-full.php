<?php
/*
Template Name: Full-width
*/
 ?>
<?php get_header(); ?>

<div id="content" class="columns col12">
<?php while ( have_posts() ) : the_post(); ?>

	<div class="post columns col12">
		<div class="entry-content column col12">
			<div class="entry-text">
				<h3 class="entry-title"><?php the_title(); ?></h3>
				<?php the_content(); ?>
			</div>
		</div><!-- .entry-content -->
	</div>

<?php endwhile; ?>
</div><!-- #content -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
