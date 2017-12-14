/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'uiComponent',
        'mage/storage',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/action/get-totals',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/get-payment-information',
        'Magento_Ui/js/modal/confirm',
        'Magento_Ui/js/modal/alert',
        'mage/translate',
        'Magento_Catalog/js/price-utils'
    ],
    function (
        $,
        Component,
        storage,
        customerData,
        getTotalsAction,
        totals,
        quote,
        getPaymentInformation,
        confirm,
        alertPopup,
        Translate,
        priceUtils
    ) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Magento_Checkout/summary/item/details'
            },
            getValue: function(quoteItem) {
                return quoteItem.name;
            },
            addQty: function (data) {
                this.updateQty(data.item_id, 'update', data.qty + 1);
            },

            minusQty: function (data) {
                this.updateQty(data.item_id, 'update', data.qty - 1);
            },

            updateNewQty: function (data) {
                this.updateQty(data.item_id, 'update', data.qty);
            },
            showOverlay: function () {
                $('#ajax-loader3').show();
                $('#control_overlay_review').show();
            },

            hideOverlay: function () {
                $('#ajax-loader3').hide();
                $('#control_overlay_review').hide();
            },

            updateQty: function (itemId, type, qty) {
                var params = {
                    itemId: itemId,
                    qty: qty,
                    updateType: type
                };
                var self = this;
                this.showOverlay();
                storage.post(
                    'checkoutview/quote/update',
                    JSON.stringify(params),
                    false
                ).done(
                    function (result) {
                        var minicart = $('[data-block="minicart"]');
                        minicart.trigger('contentLoading');
                        customerData.reload('cart', true);
                        minicart.trigger('contentUpdated');
                    }
                ).fail(
                    function (result) {

                    }
                ).always(
                    function (result) {
                        if (result.error) {
                            alertPopup({
                                content: Translate(result.error),
                                autoOpen: true,
                                clickableOverlay: true,
                                focus: "",
                                actions: {
                                    always: function(){

                                    }
                                }
                            });
                        }
                        if(result.cartEmpty || result.is_virtual){
                            window.location.reload();
                        }else{
                            if (result.affiliateDiscount) {
                                $('tr td.amount span').each(function () {
                                    if ($(this).data('th') == Translate('Affiliateplus Discount')) {
                                        if (result.affiliateDiscount) {
                                            $(this).text(priceUtils.formatPrice(result.affiliateDiscount, quote.getPriceFormat()));
                                            $(this).show();
                                        } else {
                                            $(this).hide();
                                        }

                                    }
                                })
                            }
                            
                            getPaymentInformation().done(function () {
                                self.hideOverlay();
                            });
                        }
                    }
                );
            }
        });
    }
);
