<?php
/*
Template Name: MFPResource Category
*/
 ?>
<?php get_header(); ?>

<div id="content" class="recent-list columns col10">
<div class="column col10">
	<?php
	$cat_id =  get_query_var('cat');
	if(get_cat_name($cat_id) == "Municipal/Regional FPCs with strong government connection" || get_cat_name($cat_id) == "FPCs/Roundtables facilitated by government" || get_cat_name($cat_id) == "Regional initiatives not connected to an official FPC, but where governments are involved" || get_cat_name($cat_id) == "Local CSO based Food Networks Category A" || get_cat_name($cat_id) == "Local CSO based Food Networks Category B" || get_cat_name($cat_id) == "County, District, Regional FPCs"){
		echo '<h3>'.get_cat_name($cat_id).'</h3>';
		echo '<p>'.category_description( $cat_id ).'</p>';
		// echo category Table of Contents meta
		$cat_data = get_option("category_toc");
		if (isset($cat_data)){

			//$cat_data = html_entity_decode($cat_data, UTF-8);
			$cat_data = str_replace("\xc2\xa0", " ", $cat_data);

			echo '<div>'. apply_filters( 'meta_content', $cat_data ).'</div>';
		}
	}
 	else {
		echo '<h3>'.dynamictitles().'</h3>';
	}
	?>
</div>
<?php if ( have_posts() ) {?>
<?php
	$i=1;$count=1;
	while ( have_posts() ) : the_post(); 
	if ($i==1)echo '<div class="row">';
	?>

	<div class="column col2">
		<?php colabs_image('width=138&height=91&play=true') ?>
		<h4 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'colabsthemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
		<p><?php excerpt(); ?></p>
		<a class="more-link" title="<?php printf( esc_attr__( 'Permalink to %s', 'colabsthemes' ), the_title_attribute( 'echo=0' ) ); ?>" href="<?php the_permalink();?>"><?php _e('Continue Reading','colabsthemes');?>&rarr;</a>
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
