

define([
    "jquery"
], function($){
    "use strict";

    return {

    	getImage: function(images)
    	{
    		if (!images.length) {
    			return false;
    		}
	      	var currIndex = $.cookieStorage.get('active_splash_index'),
	      		currIds = $.cookieStorage.get('active_splash_ids') ? $.cookieStorage.get('active_splash_ids') : [],
	      		first = null,
	      		active = null;

	      	$.each(images, function(key, value) {
	      		if ((!value.active_from || value.active_from < value.time) && (!value.active_to || value.end > value.time)) {

	      			if (value.sort_order >= currIndex && currIds.indexOf(value.image_id) < 0) {
	      				active = value;
	      				return false;
	      			} else {
	      				if (first && value.sort_order == currIndex ) {
	      					// continue;
	      				} else {
	      					first = value;
	      				}
	      			}
	      		}
	      	});

	      	if (!active) {
	      		active = first;
	      		currIds = [];
	      	}

	      	if (active) {
	      		currIds.push(active.image_id);

	      		$.cookieStorage.set('active_splash_index', active.sort_order);
	      		$.cookieStorage.set('active_splash_ids', currIds);
	      	}

	      	return active.name;
    	}
    }
});
