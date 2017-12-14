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

    $.widget('plumrocket.privatesalePreviewPanel', {

        _create: function () {
        	$(this.options.dateFieldSelector).on('click',$.proxy(this.showDatePicker, this));

            $(this.options.dateFieldSelector).datepicker({
	            showButtonPanel: true,
                "dateFormat": "mm/dd/yy"
            });

        	$(this.options.closeLinkSelector).on('click',$.proxy(this.reset, this));
        	$(this.options.updateButtonSelector).on('click',$.proxy(this.updateDate, this));

        	var self = this;

        	$(function() {
        		var currentParams = window.location.search;
				if (currentParams.search('psPreviewMode') > 0) {

					var param = window.location.href.match(/(?:\?|&)(psPreviewMode=[a-zA-Z0-9]+)(?:&|$)/);
					var param2 = window.location.href.match(/(?:\?|&)(previewDate=.+?)(?:&|$)/);
					self.addParamToAllLink(param[1]);

					if (param2) {
						self.addParamToAllLink(param2[1]);
					}
				}
        	});
        },

        addParamToAllLink: function(param) {

			$('a').each(function() {
				var linkUrl = $(this).attr('href');

				if (linkUrl) {
					var sep = '?';
					if (linkUrl.indexOf('?') > 0) {
						sep = '&';
					}

					linkUrl += sep + param;
					$(this).attr('href', linkUrl);
				}

			});
		},

        updateDate: function() {
        	var newDate = $(this.options.dateFieldSelector).val();

			var currentUrl = window.location.href;
			currentUrl = currentUrl.replace(/(?:\?|&)(previewDate=.+?)(?:&|$)/, '');
			var newUrl = currentUrl  + '&previewDate=' + newDate;

			window.location = newUrl;
        },

        reset: function() {
        	var newUrl = window.location.href.replace(/(?:\?|&)(previewDate=.+?)(?:&|$)/, '');
			newUrl = newUrl.replace(/(?:\?|&)(psPreviewMode=[a-zA-Z0-9]+)(?:&|$)/, '');
			window.location = newUrl;
        }

    });

    return $.plumrocket.privatesalePreviewPanel;
});
