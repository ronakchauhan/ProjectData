/**
 * Created by Linh on 6/8/2016.
 */
define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'PL_Migs/js/action/set-payment-method',
        'Magento_Checkout/js/model/payment/additional-validators'
    ],
     function ($, Component, setPaymentMethodAction, additionalValidators){
        'use strict';
       // var paymentMethod = ko.observable(null);

        return Component.extend({
            defaults: {
                template: 'PL_Migs/payment/migs-hosted'
            },
            getCode: function() {
                return 'migs_hosted';
            },

            isActive: function() {
                return true;
            },
			
            continueToMigs: function () {
                if (this.validate() && additionalValidators.validate()) {
                    //update payment method information if additional data was changed
                    this.selectPaymentMethod();
                    setPaymentMethodAction();
                    return false;
                }
            }
            
        });
    }
);