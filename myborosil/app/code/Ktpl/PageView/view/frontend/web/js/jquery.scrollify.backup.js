(function ( $ ) {
 
    // Plugin definition.
	$.fn.scrollify = function( options ) {

	    var opts = $.extend( {}, $.fn.scrollify.defaults, options );

	    $.fn.scrollify.defaults = opts;

	    if($(opts.elements).length) {

	    	if(opts.specials.length) {
	    		var class_name = opts.elements.slice(1);
	    		$(opts.specials).each(function(index, element) {
	    			$(document).find(element).addClass(class_name);
	    		});
	    	}

	    	$(window).bind('mousewheel', $.fn.scrollify.scroll);

	    	$(document).keydown(function(e) {
			    switch(e.which) {
			        case 38: $.fn.scrollify.scroll.up(e); break;
			        case 40: $.fn.scrollify.scroll.down(e); break;
			        default: return; // exit this handler for other keys
			    }
			    e.preventDefault(); // prevent the default action (scroll / move caret)
			});
	    }
	};

	$.fn.scrollify.scroll = function(e) {

		var opts = $.fn.scrollify.defaults;

		if(opts.flag) {
			opts.flag = false;

			$.fn.scrollify.defaults = opts;

			if(e.originalEvent.wheelDelta > 0)
		       	$.fn.scrollify.scroll.up(e);
		    else
		        $.fn.scrollify.scroll.down(e);
		}
	};

	$.fn.scrollify.scroll.up = function(e) {

		var opts = $.fn.scrollify.defaults;

		opts.current--;

		if(opts.current < 0)
		{
			opts.current = 0;
			opts.animate = false;
			opts.flag = true;
		}
		else
			opts.animate = true;

		$.fn.scrollify.defaults = opts;
		$.fn.scrollify.scroll.animate(e);
	};

	$.fn.scrollify.scroll.down = function(e) {
		var opts = $.fn.scrollify.defaults;

		opts.current++;

		if(opts.current > ($(opts.elements).length - 1))
		{
			opts.current = $(opts.elements).length;
			opts.animate = false;
			opts.flag = true;
		}
		else
			opts.animate = true;

		$.fn.scrollify.defaults = opts;
		$.fn.scrollify.scroll.animate(e);
	};

	$.fn.scrollify.scroll.animate = function(e) {
		var opts = $.fn.scrollify.defaults;

		if(opts.animate) {
	    	var current_top = $(opts.elements + ':eq(' + opts.current + ')' ).offset().top;
			$("html, body").stop().animate({
				scrollTop: current_top
			}, 500,  function() { 
			   	opts.flag = true;
			   	$.fn.scrollify.defaults = opts;
			});
	    }

	    $.fn.scrollify.defaults = opts;
	};
	 
	$.fn.scrollify.defaults = {
	    elements: '.section',
	    specials: [
	    	'header'
	    ],
	    current: 0,
	    flag: true,
	    animate: false
	};
 
}( jQuery ));