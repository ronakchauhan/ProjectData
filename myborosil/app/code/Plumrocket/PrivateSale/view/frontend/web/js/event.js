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
    'jquery/ui',
    "Plumrocket_PrivateSale/js/countdown/jquery.countdown",
    "domReady!"
], function ($) {
    "use strict";

    $.widget('plumrocket.privatesaleEvent', {

    	data: {},

        _create: function () {
        	var self = this;
        	$(function() {
        		self.getTimes.call(self, self.callback);
        	});
        },

        getTimes: function (callback) {

			var data = this.data
				self = this;

			if (jQuery.isEmptyObject(data)) {
				return false;
			}

			$.ajax({
				url: this.options.timeUrl,
				dataType: 'json',
				method: 'post',
				data: {
					'data': data,
					'previewDate': this.options.previewDate,
					'psPreviewMode': this.options.previewMode
				}
			})
				.success(function(response) {
					if (response.success) {
						callback.call(self, response.data);
						/* Fix for ultimo grid */
						if (window.setGridItemsEqualHeight) {
							setTimeout(function(){
								window.setGridItemsEqualHeight(jQuery); /*use native jquery*/
							}, 500);
						}
						/* end */
						return response.data;
					} else {
						console.error('Something went wrong. Times not loaded');
					}
				})
				.error(function() {
					console.error('Something went wrong. Times not loaded');
				});
		},

        callback: function(result) {
			if (result) {
				var self = this;
				$.each(result, function (key, value) {

					if (value > 0) {
						var $item = $('.privatesale-countdown-timer[data-item="' + key + '"]');

						$item.show();
						$item.find('.timer').countdown({
							until: 		+value,
							format: 	'dhMS',
							layout: '{d<}{dn} {dl}, {d>}{h<}{hnn}:{h>}{mnn}:{snn}',
							padZeroes: 	true,
							labels: self.options.countdownLabelsFew.split(','),
							// The display texts for the counters if only one
							labels1: self.options.countdownLabelsOne.split(','),
							onExpiry: function() {
								window.location.reload();
							}
						});
					}
				});
			}
		},

		addItem: function (itemId, time)
		{
			this.data[itemId] = time;
		},

		setData: function(data)
		{
			this.data = data;
		}

    });

    return $.plumrocket.privatesaleEvent;
});
