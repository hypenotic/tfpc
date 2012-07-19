<?php
/*
Template Name: Resources
*/
 ?>
 <?php get_header();?>
 <div id="content" class="recent-list columns col12">
 	<?php while ( have_posts() ) : the_post(); ?>
 	<div class="entry-content column col12">
 		<?php the_content();?>
 	</div>
	<?php endwhile; ?>
 	<div class="recent-entry columns col12">
 		<div class="recent-list columns" id="main-cats">
	 		<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'Introduction' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
			<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'Availability' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
			<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'Accessibility' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
			<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'Acceptability' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
			<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'Adequacy' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
			<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'Agency' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
		</div>
 	</div>

 	<div class="entry-content column col12">
 		<h3>Tags <input type="text" id="txtFilter" name="txtFilter" placeholder="filter" style="position:relative;top:-3px;left:3px;" /></h3>
 		<ul id="resource-tags">
 			<li><a href="<?php echo get_settings('home').'/tag/food-sovereignty';?>">Food Sovereignty</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/food-security';?>">Food Security</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/golden-horseshoe';?>">Golden Horseshoe</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/toronto-food-policy-council';?>">Toronto Food Policy Council</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/tfpc';?>">TFPC</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/urban-agriculture';?>">Urban Agriculture</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/rooftop-garden';?>">Rooftop Garden</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/backyard';?>">Backyard</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/csa';?>">CSA</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/bees';?>">Bees</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/chickens';?>">Chickens</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/composting';?>">Composting</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/community-gardening';?>">Community Gardening</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/transportation';?>">Transporation</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/processing';?>">Processing</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/mobile-vending';?>">Mobile Vending</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/farmers-markets';?>">Farmer's Markets</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/ontario-food-terminal';?>">Ontario Food Terminal</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/retail';?>">Retail</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/food-deserts';?>">Food Deserts</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/culinary-tourism';?>">Culinary Tourism</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/good-food-box';?>">Good Food Box</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/community-food-centres';?>">Community Food Centres</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/student-nutrition';?>">Student Nutrition</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/social-assistance';?>">Social Assistance</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/food-citizenship';?>">Food Citizenship</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/diversity';?>">Diversity</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/youth';?>">Youth</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/seniors';?>">Seniors</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/aboriginal';?>">Aboriginal</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/sustainable-design';?>">Sustainable Design</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/organic';?>">Organic</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/local-sustainable-food-procurement';?>">Local Sustainable Food Procurement</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/urban-planning';?>">Urban Planning</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/waste';?>">Waste</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/food-strategy';?>">Food Strategy</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/food-movement';?>">Food Movement</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/food-skills';?>">Food Skills</a></li>
 			
		</ul>
		<div class="cf"></div>
 		 	<script type="text/javascript">
			$(document).ready(function () {
				var query;
		        query = $("#txtFilter").val().toLowerCase();
		        $("#txtFilter").keyup(function () {
		            query = $(this).val().toLowerCase();
					$("ul#resource-tags li:containsi('" + query.toString() + "')").show();
		            $("ul#resource-tags li:missing('" + query.toString() + "')").hide();
		            

		        });
		    });
 			</script>
 		</ul>
 	</div>	
 </div>
 <?php get_footer(); ?>