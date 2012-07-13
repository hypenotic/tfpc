<?php global $colabs_options; ?>
<div id="footer" class="columns col12">
  <?php 
  wp_reset_query();
  $cat_featured=get_option('colabs_cat_featured');
  if($cat_featured=='')$cat_featured=1;
  if (!is_home() || !is_front_page()){colabs_latest_post(6,'col12');}else{
  ?>
  <div class="featured-head column col12">
  &nbsp;
    <?php if(!is_home()): ?>
    <h6 class="floatleft"><?php _e('Featured','colabsthemes');?></h6>
    <a href="<?php echo get_category_link($cat_featured);?>" class="link-button floatright"><?php _e('All Featured','colabsthemes');?></a>
    <?php endif; ?>
  </div><!-- .featured-head -->
  <?php 
	  query_posts('showposts=3&cat='.$cat_featured);
	  if ( have_posts() ) : ?>
  <div class="featured columns col12">
    <!-- Pake Loop dulu biar ga pusing bacanya -->
    <?php while ( have_posts() ) : the_post();?>
      <div class="column col4"> 
        <?php colabs_image('width=307&height=204&play=true'); ?>
        <h3 class="headline-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
        <p><?php excerpt();?></p>
        <a class="more-link" href="<?php the_permalink();?>"><?php _e('Continue Reading','colabsthemes');?> &rarr;</a>
      </div>
    <?php endwhile; ?>
  </div><!-- .featured -->
  <?php endif; ?>
  <div class="footer-widget columns col12">
    <?php if ( !dynamic_sidebar( 'Footer Sidebar' ) ) : ?>
    
    <?php endif; ?>
  </div><!-- .footer-widget -->
  <?php }?>      
  <div class="footer-notice column col12">
    <p class="copyrights floatleft">
	<?php if( $colabs_options['colabs_footer_credit'] != 'true' ){ ?>
	Copyright &copy; 2011 <a href="http://colorlabsproject.com/themes/modernizm/" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">Modernizm</a> by <a href="http://colorlabsproject.com/" title="Colorlabs">ColorLabs</a>. All rights reserved.
	<?php }else{ echo stripslashes( $colabs_options['colabs_footer_credit_txt'] ); } ?>
	</p>
	<div class="floatright">
    <p>Designed by the friendly folks at <a href="http://hypenotic.com" target="_blank">Hypenotic</a>.</p>
	</div>
  </div>
</div><!-- #footer -->
	
</div><!-- .container -->
<?php wp_footer(); ?>
</body>
</html>
