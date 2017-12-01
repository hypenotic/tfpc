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

	  <?php 
		  query_posts('showposts=3&cat='.$cat_featured);
		  if ( have_posts() ) : ?>
	  <div class="featured columns col10">
	    <!-- Pake Loop dulu biar ga pusing bacanya -->
	    <?php while ( have_posts() ) : the_post();?>
	      <div class="column col5"> 
	        <?php colabs_image('width=400&height=204&play=true'); ?>
	        <h3 class="headline-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
	        <p><?php excerpt();?></p>
	        <a class="more-link" href="<?php the_permalink();?>"><?php _e('Continue Reading','colabsthemes');?> &rarr;</a>
	      </div>
	    <?php endwhile; ?>
	  </div><!-- .featured -->
	  <?php endif; ?>

	<?php colabs_latest_post(5,'col10');?><!-- .recent-entry -->


	
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
