var config = {
	paths: {            
            'custom_checkout': "Ktpl_CheckoutView/js/custom-checkout"
        },   
    shim: {
        'custom_checkout': {
            deps: ['jquery']
        }
    },	
     map: {
        '*': {
           "Magento_Checkout/template/summary/item/details.html": 'Ktpl_CheckoutView/template/summary/item/details.html',
           "Magento_Checkout/template/summary/cart-items.html": 'Ktpl_CheckoutView/template/summary/cart-items.html',
           "Magento_Checkout/js/view/summary/item/details": 'Ktpl_CheckoutView/js/view/summary/item/details',
           "Magento_Checkout/template/payment": "Ktpl_CheckoutView/template/payment",
           "Magento_Checkout/js/model/step-navigator": "Ktpl_CheckoutView/js/model/step-navigator",
           "Magento_Checkout/js/view/shipping": "Ktpl_CheckoutView/js/view/shipping",
           "Magento_Checkout/template/shipping-address/address-renderer/default":"Ktpl_CheckoutView/template/shipping-address/address-renderer/default",
           "Magento_Checkout/js/view/summary/abstract-total":"Ktpl_CheckoutView/js/view/summary/abstract-total"
        }
    }
};