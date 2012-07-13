<?php
/*
Template Name: Gallery
*/
 ?>
<?php get_header();?>
<div id="content" class="recent-list columns col10">
<div class="column col10">
 <h3><?php dynamictitles();?></h3>
</div>
<?php 
	wp_reset_query();
	$limit=get_option('colabs_limit_gallery');
	if($limit=='')$limit=15;
	query_posts('posts_per_page=15&paged='.$paged);
	if ( have_posts() ) {?>
<?php
	$i=1;$count=1;
	while ( have_posts() ) : the_post(); 
	if ($i==1)echo '<div class="row">';
	?>

	<div class="column col2">
		<?php 
		if(colabs_image('width=138&height=91&return=true')){
		colabs_image('width=138&height=91&play=true'); 
		}else{
		colabs_image('width=138&height=91&src='.get_option('colabs_custom_noimage'));
		}
		?>
		
		
	</div>
	<?php if ($i==5){echo '</div>'; $i=0;}
	$i++;$count++;
	?>
<?php endwhile; ?>
<?php 
if($count%5!= 1){echo '</div>';}?>
<?php }else{ ?>
<div class="column col2">
<?php colabs_no_result();?>
</div>
<?php }?>
<?php the_pagination();?>
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
