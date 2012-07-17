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
	 			<h4 class="entry-title">Main Category</h4>
	 			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Qui ita affectus, beatum esse numquam probabis; Nummus in Croesi divitiis obscuratur, pars est tamen divitiarum. Duo Reges: constructio interrete. Non laboro, inquit, de nomine. </p>
			</div>
			<div class="column col2">
	 			<h4 class="entry-title">Main Category</h4>
	 			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Qui ita affectus, beatum esse numquam probabis; Nummus in Croesi divitiis obscuratur, pars est tamen divitiarum. Duo Reges: constructio interrete. Non laboro, inquit, de nomine. </p>
			</div>
			<div class="column col2">
	 			<h4 class="entry-title">Main Category</h4>
	 			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Qui ita affectus, beatum esse numquam probabis; Nummus in Croesi divitiis obscuratur, pars est tamen divitiarum. Duo Reges: constructio interrete. Non laboro, inquit, de nomine. </p>
			</div>
			<div class="column col2">
	 			<h4 class="entry-title">Main Category</h4>
	 			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Qui ita affectus, beatum esse numquam probabis; Nummus in Croesi divitiis obscuratur, pars est tamen divitiarum. Duo Reges: constructio interrete. Non laboro, inquit, de nomine. </p>
			</div>
			<div class="column col2">
	 			<h4 class="entry-title">Main Category</h4>
	 			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Qui ita affectus, beatum esse numquam probabis; Nummus in Croesi divitiis obscuratur, pars est tamen divitiarum. Duo Reges: constructio interrete. Non laboro, inquit, de nomine. </p>
			</div>
			<div class="column col2">
	 			<h4 class="entry-title">Main Category</h4>
	 			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Qui ita affectus, beatum esse numquam probabis; Nummus in Croesi divitiis obscuratur, pars est tamen divitiarum. Duo Reges: constructio interrete. Non laboro, inquit, de nomine. </p>
			</div>
		</div>
 	</div>

 	<div class="entry-content column col12">
 		<h3>Tags <input type="text" id="txtFilter" name="txtFilter" placeholder="filter" style="position:relative;top:-3px;left:3px;" /></h3>
 		<ul id="resource-tags">
 			<li><a href="#">Foo</a></li>
 			<li><a href="#">Bar</a></li>
 			<li><a href="#">Baz</a></li>
 			<li><a href="#">Foo</a></li>
 			<li><a href="#">Bar</a></li>
 			<li><a href="#">Baz</a></li>
 			<li><a href="#">Foo</a></li>
 			<li><a href="#">Bar</a></li>
 			<li><a href="#">Baz</a></li>
 			<li><a href="#">Foo</a></li>
 			<li><a href="#">Bar</a></li>
 			<li><a href="#">Baz</a></li>
 			<li><a href="#">Foo</a></li>
 			<li><a href="#">Bar</a></li>
 			<li><a href="#">Baz</a></li>
			<li><a href="#">Foo</a></li>
 			<li><a href="#">Bar</a></li>
 			<li><a href="#">Baz</a></li>

 		</ul>
 		 	<script type="text/javascript">
			$(document).ready(function () {
				var query;
		        query = $("#txtFilter").val();
		        $("#txtFilter").keyup(function () {
		            query = $(this).val();

		            $("ul#resource-tags li:missing('" + query.toString() + "')").fadeOut(400);
		            $("ul#resource-tags li:contains('" + query.toString() + "')").show();

		        });
		    });
 			</script>
 		</ul>
 	</div>	
 </div>
 <?php get_footer(); ?>