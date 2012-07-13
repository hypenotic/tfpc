<?php get_header(); ?>

<div id="content" class="columns col10">
	<?php
		$cat_headline=get_option('colabs_cat_headline');
		if($cat_headline=='')$cat_headline=1;
		$cat_featured=get_option('colabs_cat_featured');
		if($cat_featured=='')$cat_featured=1;
		query_posts('showposts=2&cat='.$cat_headline);
		$i=1;
		if ( have_posts() ) :
		?>
	<div class="headline columns col10">
		<?php while ( have_posts() ) : the_post(); ?>
		<div class="featured column <?php if ($i==1){?>col6<?php }else{ ?>col4<?php }?>">
			<?php 
			if ($i==1){$image_headline_width=474;$image_headline_height=318;}else{$image_headline_width=306;$image_headline_height=215;}
			colabs_image('width='.$image_headline_width.'&height='.$image_headline_height.'&play=true'); 
			$i++;
			?>
			<h3 class="headline-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
			<p><?php excerpt();?></p>
			<a href="<?php the_permalink();?>" class="more-link"><?php _e('Continue Reading','colabsthemes');?> &rarr;</a>
		</div><!-- .featured1 -->
		<?php endwhile; ?>		
	</div><!-- .headline -->
	<?php endif; ?>

	<?php colabs_latest_post(5,'col10');?><!-- .recent-entry -->


	
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
