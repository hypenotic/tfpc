<?php
/*
Template Name: Sitemap
*/
 ?>
<?php get_header();?>
<div id="content" class="columns col10">
<?php while ( have_posts() ) : the_post(); ?>

	<div class="post columns col10">
		<div class="entry-content column col10">
			<div class="entry-text">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			<!-- category sitemap -->
            <div class="archive archive-column">
            <h3><?php _e('Pages','colabsthemes'); ?></h3>
                <ul> <?php wp_list_pages("title_li=" ); ?> </ul>
            </div>
            <!-- /category sitemap -->

            <!-- feeds sitemap -->
            <div class="archive archive-column">
                <h3><?php _e('Feeds','colabsthemes'); ?></h3>
                <ul>
                    <li><a title="Full content" href="feed:<?php bloginfo('rss2_url'); ?>"><?php _e('Main RSS','colabsthemes'); ?></a></li>
                    <li><a title="Comment Feed" href="feed:<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comment Feed','colabsthemes'); ?></a></li>
                </ul>
            </div>
            <!-- /feeds sitemap -->

            <!-- category sitemap -->
           <div class="archive archive-column">
                <h3><?php _e('Categories','colabsthemes'); ?></h3>
                <ul> <?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?> </ul>
            </div>
            <!-- /category sitemap -->

            <!-- Blog post -->
            <div class="archive">
                <h3><?php _e('All Blog Posts','colabsthemes'); ?></h3>
                <ul>
                    <?php 
                    $archive_query = new WP_Query('cat=-8');
                    while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
                        <li> 
                            <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
                                <?php the_title(); ?>
                            </a>
                            ( <?php comments_number('0 comment', '1 Comment', '% Comments'); ?> )
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <!-- /Blog post -->
			
			<!-- Archives -->
			<div class="archive">
			<h3><?php _e('Archives','colabsthemes'); ?></h3>
			<ul><?php wp_get_archives('type=monthly&show_post_count=true'); ?></ul>
			</div>
			<!-- /Archives -->
</div>
		</div><!-- .entry-content -->
	</div>

<?php endwhile; ?>
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
