(function ( $ ) {
 
    // Plugin definition.
	$.fn.scrollify = function( options ) {

	    var opts = $.extend( {}, $.fn.scrollify.defaults, options );

	    $(document).ready(function () {
	    	var scrollLock = false;

	    	if($(opts.elements).length) {

	    		if(opts.specials.length) {
		    		var class_name = opts.elements.slice(1);
		    		$(opts.specials).each(function(index, element) {
		    			$(document).find(element).addClass(class_name);
		    		});
		    	}

		    	$(document).keydown(function(e) {
		    		var offset = $(window).scrollTop();
				    switch(e.which) {
				        case 38: 
				        	if(scrollLock === false) {
				                scrollLock = true;
				            	scrollUp(opts, offset);
				        	}
				        	break;
				        case 40: 
				        	if(scrollLock === false) {
				                scrollLock = true;
				            	scrollDown(opts, offset);
				        	}
				        	break;
				        default: return; // exit this handler for other keys
				    }
				    e.preventDefault(); // prevent the default action (scroll / move caret)
				});

                var mousewheelevt = (/Firefox/i.test(navigator.userAgent))? "DOMMouseScroll" : "mousewheel";
                //FF doesn't recognize mousewheel as of FF3.x

	    		$(document).bind(mousewheelevt, function (e) {
	    		    var delta = e.originalEvent.detail < 0 || e.originalEvent.wheelDelta > 0 ? 1 : -1;
			        var offset = $(window).scrollTop();
		            if (delta < 0) {
		            	if(scrollLock === false) {
			                scrollLock = true;
			            	scrollDown(opts, offset);
			        	}
		            } else if (delta > 0) {
		            	if(scrollLock === false) {
			                scrollLock = true;
			            	scrollUp(opts, offset);
			        	}
		            }
			    });
	    	}

		    function toggleLock() {
		        scrollLock = !scrollLock;
		    };

		    function scrollUp(opts, offset) {
		    	opts.current--;

				if(opts.current < 0)
					opts.current = 0;

				if($(opts.elements + ':eq(' + opts.current + ')').offset() != undefined)
			    {
					var anchor = $(opts.elements + ':eq(' + opts.current + ')' ).offset().top;

					if(offset > anchor) {

						setTimeout(function() {
							// animate to anchor( nav menu)
			                $("body, html").animate({
			                    scrollTop: anchor + 1
			                }, 500);
			                // unlock in 250ms
            				setTimeout(toggleLock, 250);
						}, 250);
		                
		            }
		            else
	                	setTimeout(toggleLock, 250);
			    }				
			    else
	                setTimeout(toggleLock, 250);
		    };

		    function scrollDown(opts, offset) {
		    	opts.current++;

				if(opts.current > ($(opts.elements).length - 1))
					opts.current = $(opts.elements).length;	

	            if($(opts.elements + ':eq(' + opts.current + ')').offset() != undefined)
			    {
					var anchor = $(opts.elements + ':eq(' + opts.current + ')' ).offset().top;

					if(offset < anchor) {

						setTimeout(function() {
							// animate to anchor( nav menu)
			                $("body, html").animate({
			                    scrollTop: anchor + 1
			                }, 500);
			                // unlock in 250ms
            				setTimeout(toggleLock, 250);
						}, 250);
		                
		            }
		            else
	                	setTimeout(toggleLock, 250);
			    }				
			    else
	                setTimeout(toggleLock, 250);
		    };

	    });

	    
	};

	$.fn.scrollify.defaults = {
	    elements: '.section',
	    current: 0,
	    specials: [
	    	'.register-product'
	    ]
	};
 
}( jQuery ));