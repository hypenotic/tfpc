<?php

// Custom callback to list comments in the your-theme style
function custom_comments($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
 $GLOBALS['comment_depth'] = $depth;
  ?>

<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
  <?php if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n") ?>
  <div class="comment-content">
    <?php commenter_link() ?>
    <div class="comment-entry">
    <?php comment_text() ?>
     </div>
  </div>
  <div class="meta-comment">
  <?php // echo the comment reply link
  printf(__('<a href="%3$s">%1$s %2$s</a>&nbsp;'),
       get_comment_date(),
       get_comment_time(),
       '#comment-' . get_comment_ID() );
       edit_comment_link(__('Edit'), ' - <span class="edit-link">', '</span>');

   if($args['type'] == 'all' || get_comment_type() == 'comment') :
    comment_reply_link(array_merge($args, array(
     'reply_text' => __('Reply'),
     'login_text' => __('Log in to reply.'),
     'depth' => $depth,
     'before' => '',
     'after' => ''
    )));
   endif; ?>
</div>
<?php } // end custom_comments

// Produces an avatar image with the hCard-compliant photo class
function commenter_link() {
 $commenter = get_comment_author_link();
 if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
  $commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
 } else {
  $commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
 }
 $avatar_email = get_comment_author_email();
 $avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 32 ) );
 echo $avatar . ' <strong>' . $commenter . '</strong>';
} // end commenter_link

// Custom callback to list pings
function custom_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
        ?>
<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
  <div class="comment-author"><?php printf(__('By %1$s on %2$s at %3$s'),
         get_comment_author_link(),
         get_comment_date(),
         get_comment_time() );
         edit_comment_link(__('Edit'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
  <?php if ($comment->comment_approved == '0') _e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n') ?>
  <div class="comment-content">
    <?php comment_text() ?>
  </div>
  <?php } // end custom_pings
?>
