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
	 			<h4 class="entry-title">Introduction</h4>
	 			<p>Food security, food sovereignty and the history of food movements and networks in the GTA. </p>
			</div>
			<div class="column col2">
	 			<h4 class="entry-title">Availability</h4>
	 			<p>Food production, processing and distribution.</p>
			</div>
			<div class="column col2">
	 			<h4 class="entry-title">Accessibility</h4>
	 			<p>The changing food environment: purchasing, procurement, income and inclusion.</p>
			</div>
			<div class="column col2">
	 			<h4 class="entry-title">Acceptability</h4>
	 			<p>Diversity leading change.</p>
			</div>
			<div class="column col2">
	 			<h4 class="entry-title">Adequacy</h4>
	 			<p>Envisioning a sustainable food system.</p>
			</div>
			<div class="column col2">
	 			<h4 class="entry-title">Agency</h4>
	 			<p>Taking action for a better food system.</p>
			</div>
		</div>
 	</div>

 	<div class="entry-content column col12">
 		<h3>Tags <input type="text" id="txtFilter" name="txtFilter" placeholder="filter" style="position:relative;top:-3px;left:3px;" /></h3>
 		<ul id="resource-tags">
 			<li><a href="#">Food Sovereignty</a></li>
 			<li><a href="#">Golden Horseshoe</a></li>
 			<li><a href="#">Toronto Food Policy Council</a></li>
 			<li><a href="#">TFPC</a></li>
 			<li><a href="#">Mobile Vending</a></li>
 			<li><a href="#">Farmer's Markets</a></li>
 			<li><a href="#">Ontario Food Terminal</a></li>
 			<li><a href="#">Retail</a></li>
 			<li><a href="#">Food Deserts</a></li>
 			<li><a href="#">Culinary Tourism</a></li>
 			<li><a href="#">Good Food Box</a></li>
 			<li><a href="#">Community Food Centres</a></li>
 			<li><a href="#">Student Nutrition</a></li>
 			<li><a href="#">Social Assistance</a></li>
 			<li><a href="#">Food Citizenship</a></li>
			<li><a href="#">Diversity</a></li>
 			<li><a href="#">Youth</a></li>
 			<li><a href="#">Seniors</a></li>
 			<li><a href="#">Aboriginal</a></li>
 			<li><a href="#">Sustainable Design</a></li>
 			<li><a href="#">Organic</a></li>
 			<li><a href="#">Local Sustainable Food Procurement</a></li>
 			<li><a href="#">Urban Planning</a></li>
 			<li><a href="#">Waste</a></li>
 			<li><a href="#">Food Strategy</a></li>
 			<li><a href="#">Food Movement</a></li>
 			<li><a href="#">Food Skills</a></li>
 			<li><a href="#">Food Citizenship</a></li>
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