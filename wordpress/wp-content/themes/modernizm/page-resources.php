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
 		<div class="recent-list columns">
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
 		<h3>Tags</h3>
 		<ul id="resource-tags">
 			<li>Foo</li>
 			<li>Bar</li>
 			<li>Baz</li>
 			<li>Foo</li>
 			<li>Bar</li>
 			<li>Baz</li>
 			<li>Foo</li>
 			<li>Bar</li>
 			<li>Baz</li>
 			<li>Foo</li>
 			<li>Bar</li>
 			<li>Baz</li>
 		</ul>
 	</div>	
 </div>
 <?php get_footer(); ?>