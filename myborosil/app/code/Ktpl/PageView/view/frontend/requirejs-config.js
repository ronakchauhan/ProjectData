var config = {
    paths: {            
    	'mousewheel': 'Ktpl_PageView/js/jquery.mousewheel',
    	'scrollify': 'Ktpl_PageView/js/jquery.scrollify'
    },   
    shim: {    	
    	'mousewheel': {
    		deps: ['jquery']
    	},
    	'scrollify': {
    		deps: ['jquery', 'mousewheel']
    	}
	}
}