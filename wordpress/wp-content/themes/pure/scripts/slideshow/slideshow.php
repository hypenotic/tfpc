<?php $slideshow_speed = ttrust_get_option('ttrust_slideshow_speed').'000'; ?>
<script type="text/javascript">
//<![CDATA[
jQuery.noConflict();
jQuery(document).ready(function() {	
    jQuery('#slides').cycle({ 
	    fx:     '<?php echo ttrust_get_option('ttrust_slideshow_transition'); ?>', 
    	speed:   500, 
    	timeout: <?php echo $slideshow_speed; ?>,  
    	pause:   1,   
	    next:   '#slideshowNext', 
	    prev:   '#slideshowPrev',
		pager: '#slideshowNavPager',
		pagerAnchorBuilder: function(idx, slide) {
		        return '<a><span>'+(idx+1)+'</span></a>';
		    }		
	});
});
//]]>
</script>