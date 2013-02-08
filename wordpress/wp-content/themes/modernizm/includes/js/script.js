jQuery.extend(jQuery.expr[':'], {missing: function (elem, index, match) {
		return (elem.textContent || elem.innerText || "").toLowerCase().indexOf(match[3]) == -1;
	}
});
jQuery.extend($.expr[':'], {'containsi': function(elem, i, match, array){
	return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
}
});

(function($){



/* Equal height plugin
------------------------------*/
$.fn.equalHeight = function() {
    var tallest     = 0,
        winWidth    = $(window).width();
		this
		.addClass('equal-height')
		.each(function(){
				var colHeight = $(this).height();
				if( colHeight > tallest ) {
						tallest = colHeight;
				}
		});

    $(this).height( tallest );
    return this;
}

/* On Window Load Event
------------------------------*/
$(window).load(function(){

    /* initiate equal height */
		$('.headline .featured').equalHeight();
		$('.footer-widget .widget').equalHeight();
		$('#content, #sidebar').equalHeight();
    
});

/* On Document ready Event
------------------------------*/
$(document).ready(function(){

    /* Menu Splitter for custom top menu */
    $('#topmenu ul')
    .addClass('column col2')
    .easyListSplitter({
        colNumber: 3
    });

    /* Set padding right on class row */
    $('.recent-list .row').each(function(){
			var	$this		= $(this),
					colLen 	= $this.find('.column').length;
			if( colLen % 5 !== 0 ) {
				$this.addClass('partial');
			}
		});

		// Set logo center when on mobile
		var windowWidth = $(window).width();
		function setCenter() {
			var logo			= $('#logo'),
					tagline		= logo.find('p'),
					logoWidth	= logo.find('a').width();
			logo.find('h1').css({
				'left'				: '50%',
				'position'		: 'relative',
				'width'				: logoWidth,
				'margin-left'	: -(logoWidth / 2)
			});
			tagline.css('text-align','center');
			logo.css('margin-left','0');
		}
		if( windowWidth <= 767 ) {
			setCenter();
		}

		/* When window resized, remove equal height */
    $(window).resize(function(e){
        var windowWidth = $(this).width();
        if( windowWidth <= 959 ) {
            //$('.recent-list .col2').equalHeight();
            $('.equal-height').height('auto');
        }
    });

    // Add class video thumb
    $('.playicon').parent('a').addClass('video-thumb');
    
});
})(jQuery);


$(document).ready(function(){

	// Load slider carousel on index page
  if($('.flexslider').length){
    $('.flexslider').flexslider({
    	directionNav: false
    });
  }

  // load jtweetsanywhere in footer

      $('#jTweetsAnywhere').jTweetsAnywhere({
        username: 'TOfoodpolicy',
        count: 3,
        showTweetFeed: {
        		autorefresh: {
	            mode: 'trigger-insert',
	            interval: 60
	        	},
            showUserFullNames: true,
            showSource: true,
        }
    });

});


