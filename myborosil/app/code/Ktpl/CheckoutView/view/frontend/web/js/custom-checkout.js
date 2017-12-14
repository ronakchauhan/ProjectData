define([
	'jquery',
    'ko',
    'mage/url',
    'Magento_Checkout/js/model/step-navigator'
], function ($,ko,url,stepNavigator) {
    return {
    	init: function(){
    		$(document).on("click",".custom-step",function(){
    			var getProgress = $("#checkout .opc-progress-bar"); 
    			var getStep = parseInt($(this).attr('data-step'));
    			var checkoutUrl = url.build('checkout', {});
    			if(getStep == 1){
    				stepNavigator.navigateTo('shipping');
    			}
    			else if(getStep == 2){    				
    				$('.payment-method._active .primary button.checkout').removeClass("disabled");
    				stepNavigator.navigateTo('payment');    				
    			}
    			return false;
    		});
    	},
        test: function () {
            return url.build('checkout/cart', {});
        },
    }
});
