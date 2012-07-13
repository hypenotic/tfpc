<?php
/*
Template Name: Authors List
*/
 ?>
<?php get_header(); ?>

<div id="content" class="columns col10">
	<div class="post columns col10">
		<div class="entry-content column col10">
			<div class="entry-text">
				<h2 class="entry-title"><?php the_title(); ?></h2>

				<?php 
				wp_reset_query();
				$i=1;
				global $wpdb;
				$blogusers = $wpdb->get_results("SELECT DISTINCT a.* AS display_name FROM $wpdb->users a,$wpdb->posts b ORDER BY a.ID");
				foreach ( $blogusers as $user ) :
				/* $user_id = (int) $userid->ID;
				$user_login = stripslashes($userid->user_login);
				$display_name = stripslashes($userid->display_name);
				$return = '';
				$return .= "\t" . '<li>'. $display_name .'</li>' . "\n";
				print($return);
				endforeach;
				$blogusers = get_users('orderby=nicename');
				foreach ($blogusers as $user) : */
				?>
				
					<?php $user_meta = get_userdata($user->ID);?>
					<?php if($user->user_login == 'admin') { continue; } // skip the admin ?>					
					<?php $thirdclass = ($i % 2 == 0) ? 'last' : ''; ?>
				
					<div class="author-list <?php echo $thirdclass; ?>">
						<h3>
							<a href="<?php echo get_author_posts_url($user->ID);?>"><?php echo $user_meta->first_name. " " .$user_meta->last_name; ?></a>
						</h3>
						<div class="author-avatar">
							<?php 
							$email = $user->user_email;
							echo get_avatar($email,80);?>
						</div>
						
						<div class="author-description">
							<p><?php echo $user_meta->user_description; ?></p>
							
							<?php if(($user_meta->facebook!='')||($user_meta->twitter!='')) : ?>
							<div class="social">
								<?php if($user_meta->facebook != "") { ?>
								<a class="facebook" href="<?php echo $user_meta->facebook; ?>" target="_blank">Facebook</a> <?php } ?>
								<?php if($user_meta->twitter != "") { ?>
								<a class="twitter" href="<?php echo $user_meta->twitter; ?>" target="_blank">Twitter</a> <?php } ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				<?php $i++;endforeach; ?>
				

				<?php the_pagination(); ?>
			</div>
		</div><!-- .entry-content -->
	</div>


</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

