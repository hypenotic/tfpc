<?php get_header(); ?>

<div id="content" class="recent-list columns col10">
<div class="column col10">
 <h3><?php dynamictitles();?></h3>
</div>
<?php if ( have_posts() ) {?>
<?php
	$i=1;$count=1;
	while ( have_posts() ) : the_post(); 
	if ($i==1)echo '<div class="row">';
	?>

	<div class="column col2">
		<?php colabs_image('width=138&height=91&play=true'); ?>
		<h4 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'colabsthemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
		<?php the_excerpt(); ?>
		<a class="more-link" title="<?php printf( esc_attr__( 'Permalink to %s', 'colabsthemes' ), the_title_attribute( 'echo=0' ) ); ?>" href="<?php the_permalink();?>"><?php _e('Reading mawr','colabsthemes')?> &rarr;</a>
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
