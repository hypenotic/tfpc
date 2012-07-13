<?php
/*
Template Name: Contact
*/
?>
<?php 

/* //If the form is submitted */

if(isset($_POST['submitted'])) {
	/* //Check to see if the honeypot captcha field was filled in */
	if(trim($_POST['checking']) !== '') {
		$captchaError = true;
	} else {	
		/* //Check to make sure that the name field is not empty */
		if(trim($_POST['contactName']) === '') {
			$nameError = 'You forgot to enter your name.';
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}		
		/* //Check to make sure sure that a valid email address is submitted */
		if(trim($_POST['email']) === '')  {
			$emailError = 'You forgot to enter your email address.';
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = 'You entered an invalid email address.';
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}

		/* //Check to make sure that the subject field is not empty */
		if(trim($_POST['subjectmail']) === '') {
			$subjectError = 'You forgot to enter your subject.';
			$hasError = true;
		} else {
			$subjectmail = trim($_POST['subjectmail']);
		}

		/* //Check to make sure comments were entered	 */
		if(trim($_POST['comments']) === '') {
			$commentError = 'You forgot to enter your comments.';
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}			

		/* //If there is no error, send the email */
		if(!isset($hasError)) {
			$emailTo = get_option('colabs_contactform_email');
			$subject = $subjectmail;
			$sendCopy = trim($_POST['sendCopy']);
			$body = "Name: $name \n\nEmail: $email \n\nComments: $comments";
			$headers = 'From: My Site <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;			
			mail($emailTo, $subject, $body, $headers);

			if($sendCopy == true) {
				$subject = $subjectmail;
				$headers = 'From: <'.get_option('blogname').'>'. "\r\n" . 'Reply-To: ' . $emailTo;	
				//$headers = 'From: '.get_option('blogname');
				mail($email, $subject, $body, $headers);
			}

			$emailSent = true;

		}
	}
} ?>
<?php get_header();?>
<div id="content" class="columns col10">


	<div class="post columns col10">
		<div class="entry-content column col6">
				<h3 class="entry-title"><?php the_title(); ?></h3>
		
				<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); ?>
						<?php the_content();	 ?>
					<?php endwhile; ?>
				<?php endif; ?>
				
		</div><!-- .entry-content -->
		<div class="column col4 contactform">
		 <?php if(isset($emailSent) && $emailSent == true) { ?>
					
						<p>Thanks,<?php echo $name;?></p>
						
						<p>Your email was successfully sent. I will be in touch soon. Thanks for visiting!</p>
		<?php } else { ?>				
					<?php if(isset($hasError) || isset($captchaError)) { ?>
					 <p class="error">There was an error submitting the form.<p>
					 <?php } ?>
					 <form action="<?php the_permalink(); ?>" id="contactForm" method="post">
					  <ul class="forms">
						<li>
						  <label for="contactName">Name</label>
						  <input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="requiredField" />
						  <?php if($nameError != '') { ?>
						  <span class="error"><?php echo $nameError;?></span>
						  <?php } ?>
						</li>
						<li>
						  <label for="email">Email</label>
						  <input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="requiredField email" />
						  <?php if($emailError != '') { ?>
						  <span class="error"><?php echo $emailError;?></span>
						  <?php } ?>
						</li>
						<li>
						  <label for="subjectmail">Subject</label>
						  <input type="text" name="subjectmail" id="subjectmail" value="<?php if(isset($_POST['subjectmail']))  echo $_POST['subjectmail'];?>" class="requiredField" />
						  <?php if($subjectError != '') { ?>
						  <span class="error"><?php echo $subjectmail;?></span>
						  <?php } ?>
						</li>
						<li class="textarea">
						  <label for="commentsText">Message</label>
						  <textarea name="comments" id="commentsText" class="requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
						  <?php if($commentError != '') { ?>
						  <span class="error"><?php echo $commentError;?></span>
						  <?php } ?>
						</li>
						<li class="inline">
						  <input type="checkbox" name="sendCopy" id="sendCopy" value="true"<?php if(isset($_POST['sendCopy']) && $_POST['sendCopy'] == true) echo ' checked="checked"'; ?> />
						  <label for="sendCopy">Send a copy of this email to yourself</label>
						</li>
						<li class="screenReader">
						  <label for="checking" class="screenReader">If you want to submit this form, do not enter anything in this field</label>
						  <input type="text" name="checking" id="checking" class="screenReader" value="<?php if(isset($_POST['checking']))  echo $_POST['checking'];?>" />
						</li>
						<li class="buttons">
						  <input type="hidden" name="submitted" id="submitted" value="true" />
						  <button type="submit">Email Us</button>
						</li>
					  </ul>
					</form>
		<?php } ?>	
		</div>	
	</div>


</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
