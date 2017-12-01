<?php get_header(); ?>

<div id="content" class="columns col10">


	<div class="post columns col10">
		<div class="entry-content column col6">
			<?php while ( have_posts() ) : the_post(); ?>
			<?php 
			 
			  $single_top = get_post_custom_values("colabs_single_top");
			  if (($single_top[0]!='')||($single_top[0]=='none')){
			  ?>
			  <div class="singleimage">
				<?php 
				
				if ($single_top[0]=='single_video'){
					$embed = colabs_get_embed('colabs_embed',473,296,'widget_video',$post->ID);
					if ($embed!=''){
						echo $embed; 
						
					}
				}elseif($single_top[0]=='single_image'){
					colabs_image('width=473');
					
				}
					
				?>
			  </div>
			  <?php }?>
			<div class="entry-text column col5 floatright">
				<h3 class="entry-title"><?php the_title(); ?></h3>
				<div class="entry-meta">
					<?php printf( __('%1$s by %2$s', 'colabsthemes'), get_the_date(), get_the_author() ); ?>
					<?php
					$category = get_the_category(); 
					if($category[0]){
						echo ' | Filed Under: <a href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</a>';
					}
					?>
				</div>			
				<?php the_content(); ?>

				<div class="post-detail">
					<?php
					$posttags = get_the_tags();
					if ($posttags) {
						echo '<ul id="resource-tags">';
						foreach($posttags as $tag) {
							echo '<li><a href="'.get_settings('home').'/tag/'.$tag->slug.'">'.$tag->name.'</a></li>';
						}
						echo '</ul><br /><br />';
					}
					?>
				</div>
				<?php echo colabs_share();?>
			</div>
			<?php endwhile; ?>
			<?php colabs_postnav();?>
		</div><!-- .entry-content -->
		
		<?php //comments_template( '', true );?>
	</div>


</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
