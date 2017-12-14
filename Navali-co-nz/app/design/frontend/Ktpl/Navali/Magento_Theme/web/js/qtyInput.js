/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
define([
    'jquery',
    'jquery/ui',
], function ($)
{
	"use strict";
	
	$.widget('mage.qtyInput',
    {
    	options:
    	{
            qtyInputSelector: '.inputQty',
            qtyIncrementSelector: '#incrememtQty',
            qtyDecrementSelector: '#decrementQty'
        },

        /**
         * Bind handlers to events
         */
        _create: function () {
        	
        	var self = this;
        	self._clickFunction();
        },
        
		_clickFunction: function ()
        {
        	var self = this;
        	$(self.options.qtyIncrementSelector).click(function()
    		{
    			 var oldValue = $(self.options.qtyInputSelector).val();
    			 var newVal = parseFloat(oldValue) + 1;
    			 $(self.options.qtyInputSelector).val(newVal);
    			 $(self.options.qtyInputSelector).trigger('change');
                 $('.form-cart').submit();
    		});
    		
    		$(self.options.qtyDecrementSelector).click(function()
    		{
    			var oldValue = $(self.options.qtyInputSelector).val();
    			if (oldValue > 0) {
    				var newVal = parseFloat(oldValue) - 1;	
    			}
    			else
    			{
     				newVal = 0;
    			}
    			$(self.options.qtyInputSelector).val(newVal);
    			$(self.options.qtyInputSelector).trigger('change');
                $('.form-cart').submit();
    		});
        }
    })

    return $.mage.qtyInput;
});
