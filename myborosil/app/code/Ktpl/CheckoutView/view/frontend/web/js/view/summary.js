/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'jquery',
        'uiComponent',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/step-navigator',
        'ko',
        'mage/url',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'mage/storage'
    ],
    function($,Component, totals,stepNavigator,ko,url,quote,priceUtils,storage) {
        'use strict';
        return Component.extend({
            isLoading: totals.isLoading,
            stepIndex: ko.observable(stepNavigator.getActiveItemIndex()),
            totals: quote.getTotals(),
            cartUrl: url.build('checkout/cart'),
            isCalculated: function() {
                return this.totals() && null != quote.shippingMethod();
            },
            getQuoteShip: function() {
                if (!this.isCalculated()) {
                    return 'T.B.D.';
                }
                var price =  this.totals().shipping_amount;
                return this.getFormattedPrice(price);//this.getFormattedPrice(price);
            },

            /*
            * After checkout page completely loaded with ko dom,
            */
            afterloading: function(){
                $('.payment-block').html(window.checkoutConfig.sidebar_payment_block);                
            },
            getTotalItems: function(){
                var itemQty = window.checkoutConfig.totalsData.items_qty;
                if(itemQty > 1){
                    return "Items:("+Math.trunc(itemQty)+")";
                }else{
                    return "Item:("+Math.trunc(itemQty)+")";
                }
            },
            getSubtotalwotDiscount: function(){
                return this.getFormattedPrice(window.checkoutConfig.totalsData.subtotal);
            },
            getQuoteTax: function(){
                return this.getFormattedPrice(window.checkoutConfig.totalsData.tax_amount);  
            },
            getFormattedPrice: function (price) {
                return priceUtils.formatPrice(price, quote.getPriceFormat());
            },
            getGrandFinal: function(){
                var price = 0;
                if (this.totals()) {
                    price = totals.getSegment('grand_total').value;
                }
                return this.getFormattedPrice(price);
                //return this.getFormattedPrice(window.checkoutConfig.totalsData.grand_total);
            },
            getDiscountExist: function(){                
                if(window.checkoutConfig.totalsData.discount_amount < 0){
                    return true
                }
                return false;
            },
            getDiscountAmount: function(){
                return this.getFormattedPrice(window.checkoutConfig.totalsData.discount_amount);
            },
            submitAdditional: function(){
                console.log(111);
                var oscComment = '';

                if ($('#osc-comment').val().length > 0) {
                    oscComment = $('#osc-comment').val();
                }

                var params = {
                    'osc_comment': oscComment
                };

                if (oscComment) {
                    storage.post(
                        'contactform/index/saveAdditionalData',
                        JSON.stringify(params),
                        false
                    ).done(
                        function (result) {
                            console.log(result);
                        }
                    ).fail(
                        function (result) {

                        }
                    );
                } 

                /*var getCommentData = $('#osc-comment').val();
                $('.extra_order_comment').val(getCommentData);*/
            },
            placeorderSidebar: function(){
                $('.payment-method._active .primary button.checkout').trigger('click');
            }
        });
    }
);

