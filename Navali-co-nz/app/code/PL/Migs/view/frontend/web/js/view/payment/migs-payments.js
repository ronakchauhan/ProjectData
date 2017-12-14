define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'migs',
                component: 'PL_Migs/js/view/payment/method-renderer/migs-method'
            },
            {
                type: 'migs_hosted',
                component: 'PL_Migs/js/view/payment/method-renderer/migs-hosted'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);