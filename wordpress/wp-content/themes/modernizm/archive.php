<?php get_header(); ?>

<div id="content" class="recent-list columns col10">
<div class="column col10">
	<?php
	$cat_id =  get_query_var('cat');
	if(get_cat_name($cat_id) == "Introduction"){
		echo '<h3>Introduction</h3>';
		echo '<p>Food security, food sovereignty and the history of food movements and networks in the GTA.</p>'
	}
 	elseif(get_cat_name($cat_id) == "Availability"){
 		echo '<h3>Availability</h3>';
		echo '<p>Food production, processing and distribution.</p>'
	}
	elseif(get_cat_name($cat_id) == "Accessibility"){
		echo '<h3>Accessibility</h3>';
		echo '<p>The changing food environment: purchasing, procurement, income and inclusion.</p>'
	}
	elseif(get_cat_name($cat_id) == "Acceptability"){
		echo '<h3>Acceptability</h3>';
		echo '<p>Diversity leading change.</p>'
	}
	elseif(get_cat_name($cat_id) == "Adequacy"){
		echo '<h3>Adequacy</h3>';
		echo '<p>Envisioning a sustainable food system.</p>'
	}
	elseif(get_cat_name($cat_id) == "Agency"){
		echo '<h3>Agency</h3>';
		echo '<p>Taking action for a better food system.</p>'
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
