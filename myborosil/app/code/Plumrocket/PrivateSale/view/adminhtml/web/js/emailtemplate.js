/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket Private Sales and Flash Sales v4.x.x
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

define([
    "jquery",
    'jquery/ui'
], function ($) {
    "use strict";

    $.widget('plumrocket.emailtemplateEdit', {

    	processed: false,

        _create: function () {
        	$('#privatesale_emailtemplate_date').on('change',$.proxy(this.updateEmailCategories, this));
        },

    	updateEmailCategories: function ()
		{
			var self = this;
			if (this.processed) {
				return false;
			}

			self.processed = true;

			$.ajax({
				url		: this.options.updateCategoryUrl,
				data	: $('#edit_form').serialize(),
				type	: 'POST',
				dataType: 'json',
				success : function(data){

					self.processed = false;

					var categories = data.categories;

					var options = '';
					for(var i in categories)
					{
						var id 		= categories[i]['value'];
						var name 	= categories[i]['label'];
						if (!id) continue;

						options += '<option  value="'+id+'">'+name+'</option>';
					}
					$('#privatesale_emailtemplate_categories_ids').html(options);
				},
				error : function(){
					self.processed = false;
					alert('Unexpected error please try again later.');
				}
			});
		}

    });

    return $.plumrocket.emailtemplateEdit;
});

