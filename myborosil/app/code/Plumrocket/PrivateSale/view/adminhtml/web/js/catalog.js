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

require([
    'jquery',
    'jquery/ui',
    'mage/adminhtml/events',
    'mage/backend/tabs',
    'domReady!'
], function($) {
    "use strict";

    $.widget('plumrocket.privateSaleCategoryEdit', {

        options: {
            categoryA: '[name="privatesale_private_event"]',
            categoryB: '[data-index="privatesale_event_landing"]',
            categoryC: '[data-index="privatesale_restrict_cgroup"]',
            productA: '[name="product[privatesale_private_event]"',
            productB: '[data-index="privatesale_event_landing"]',
            productC: '[data-index="privatesale_restrict_cgroup"',
            category20A: '[name="general[privatesale_private_event]"]',
            category20B: '.field-privatesale_event_landing',
            product20B: '#attribute-privatesale_event_landing-container'
        },

        _create: function() {
            var self = this;
            $('#category_info_tabs').on('tabscreate tabsactivate', function() {
                self.addDependency(self.options.category20A, self.options.category20B);
            });
            $('#product_info_tabs').on('tabscreate tabsactivate', function() {
                self.addDependency(self.options.productA, self.options.product20B);
            });
            $('body.catalog-category-edit .entry-edit').on('click', function() {
                var prTimeout = setInterval(function(){
                    if ($(self.options.categoryA).val()) {
                        clearInterval(prTimeout);
                        self.addDependency(self.options.categoryA, self.options.categoryB, self.options.categoryC);
                    }
                }, 100);
            });

            $('body.catalog-product-edit .entry-edit').on('click', function() {
                var prTimeout = setInterval(function(){
                    if ($(self.options.productA).val()) {
                        clearInterval(prTimeout);
                        self.addDependency(self.options.productA, self.options.productB, self.options.productC);
                    }
                }, 100);
            });
        },

        /**
         * add depedemct b fom a
         * @param {string} a selector
         * @param {string} b selector
         */
        addDependency: function(a, b, c = false) {
            if ($(a).val() != '2') {
                $(b).hide();
                if (c) {
                    $(c).hide();
                }
            }
            $(a).on('change', function() {
                if ($(a).val() != '2') {
                    $(b).hide();
                    if (c) {
                        $(c).hide();
                    }
                } else {
                    $(b).show();
                    if (c) {
                        $(c).show();
                    }
                }
            });
        }

    });

    $(function() {
        $('body').privateSaleCategoryEdit();
    });

    return $.plumrocket.privateSaleCategoryEdit;

});
