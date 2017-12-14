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

       $.widget('plumrocket.privatesaleHomepage', {

        _create: function () {
        	var self = this;
			self.onloadW();
        },

        onloadW: function() {
        	var elements = document.getElementsByClassName("pps-item");
			var i = 0,
				self =this;

			if ( elements.length > 0 ) {
				var timerId = setInterval(function() {
					self.setParam(elements, i);
					i++;
					if (i == elements.length) {
						clearInterval(timerId);
					}
				}, 200);
			}
        },

         setParam: function(elements, i) {
        	elements[i].className += " pps-show-item";
        }
    });

    return $.plumrocket.privatesaleHomepage;
});

