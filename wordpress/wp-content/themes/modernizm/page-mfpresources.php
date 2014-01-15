<?php
/*
Template Name: MFPResources
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
			   	$category_id = get_cat_ID( 'Municipal/Regional FPCs with strong government connection' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
			<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'FPCs/Roundtables facilitated by government' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
			<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'Regional initiatives not connected to an official FPC, but where governments are involved' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
			<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'Local CSO based Food Networks Category A' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
			<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'Local CSO based Food Networks Category B' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
			<div class="column col2">
	 			<?php
			   	$category_id = get_cat_ID( 'County, District, Regional FPCs' );
				$category_link = get_category_link( $category_id );
				?>
				<h4 class="entry-title"><a href="<?php echo esc_url( $category_link ); ?>" title="<?php echo get_cat_name($category_id);?>"><?php echo get_cat_name($category_id);?></a></h4>
	 			<p><?php echo category_description($category_id);?></p>
			</div>
		</div>
		 	<div class="entry-content column col11">
	<h3>Food Policy Initiatives by Region</h3>
 		<ul id="region-tags">
 			<li><a href="<?php echo get_settings('home').'/tag/west-canada';?>">Western Canada</a></li>
 			<li><a href="<?php echo get_settings('home').'/tag/central-canada';?>">Central Canada</a></li>
		</ul>
		</div>
 	</div>
		<div class="entry-content column col12">
 		<h3>Tags <input type="text" id="txtFilter" name="txtFilter" placeholder="filter" style="position:relative;top:-3px;left:3px;" /></h3>
 		<ul id="resource-tags">
			<li><a href="<?php echo get_settings('home').'/tag/food-charter';?>">Food Charter</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-charter-adopted';?>">Food Charter (Adopted)</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-charter-endorsed';?>">Food Charter (Endorsed)</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-charter-not-yet-adopted';?>">Food Charter (Not Yet Adopted)</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-charter-working-draft';?>">Food Charter (Working Draft)</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/official-plan';?>">Official Plan</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/community-food-action-plan';?>">Community Food Action Plan</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-and-health-action-plan';?>"> Food and Health Action Plan</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/green-action-plan';?>"> Green Action Plan</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-action-plan';?>"> Food Action Plan</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/good-food-box';?>"> Good Food Box</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-box';?>"> Food Box</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/local-food-guide';?>"> Local Food Guide</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/local-food-maps';?>">Local Food Map</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/affordable-food-map';?>"> Affordable Food Map</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-security';?>"> Food Security</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/best-practices';?>"> Best Practices</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/community-kitchens';?>"> Community Kitchens</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/gleaning-program';?>"> Gleaning Program</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/gleaning-project';?>"> Gleaning Project</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-hub';?>"> Food Hub</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/urban-agriculture';?>"> Urban Agriculture</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/community-gardening';?>">Community Gardening</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/community-food-assesment';?>"> Community Food Assessment</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-system-assessment';?>"> Food System Assessment</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-summit';?>"> Food Summit</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-festival';?>"> Food Festival</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-security-conference';?>"> Food Security Conference</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/farmers-market';?>"> Farmers Market</a></li>
			<li><a href="<?php echo get_settings('home').'/tag/food-forum';?>"> Food Forum</a></li>
						
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
