<?php get_header(); ?>

<div id="content" class="columns col10">
	<?php
		$cat_headline=get_option('colabs_cat_headline');
		if($cat_headline=='')$cat_headline=1;
		$cat_featured=get_option('colabs_cat_featured');
		if($cat_featured=='')$cat_featured=1;
		query_posts('cat='.$cat_headline);
		$i=1;
		if ( have_posts() ) :
		?>
	<div id="featuredContent" class="headline columns col10 flexslider">
		<ul class="slides">
			<?php while ( have_posts() ) : the_post(); ?>
			<li>
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('banner-image'); ?>
					<div class="slider-description">
						<h3><?php the_title(); ?></h3>
						<?php  the_excerpt(); ?>
					</div>
				</a>
			</li>
		<?php endwhile; ?>
		</ul>		
	</div><!-- .headline -->
	<?php endif; ?>

	<?php colabs_latest_post(5,'col10');?><!-- .recent-entry -->


	
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
