/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'jquery',
        'underscore',
        'Magento_Ui/js/form/form',
        'ko',
        'Magento_Customer/js/model/customer',
        'Magento_Customer/js/model/address-list',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/create-shipping-address',
        'Magento_Checkout/js/action/select-shipping-address',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-address/form-popup-state',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/model/shipping-rate-registry',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Ui/js/modal/modal',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Ktpl_CheckoutView/js/checkout-data',
        'uiRegistry',
        'mage/translate',
        'Magento_Checkout/js/model/shipping-rate-service'
    ],
    function (
        $,
        _,
        Component,
        ko,
        customer,
        addressList,
        addressConverter,
        quote,
        createShippingAddress,
        selectShippingAddress,
        shippingRatesValidator,
        formPopUpState,
        shippingService,
        selectShippingMethodAction,
        rateRegistry,
        setShippingInformationAction,
        stepNavigator,
        modal,
        checkoutDataResolver,
        checkoutData,
        registry,
        $t
    ) {
        'use strict';

        var popUp = null;

        return Component.extend({
            defaults: {
                template: 'Ktpl_CheckoutView/shipping'
            },
            visible: ko.observable(!quote.isVirtual()),
            errorValidationMessage: ko.observable(false),
            isCustomerLoggedIn: customer.isLoggedIn,
            isFormPopUpVisible: formPopUpState.isVisible,
            isFormInline: addressList().length == 0,
            isNewAddressAdded: ko.observable(false),
            saveInAddressBook: 1,
            quoteIsVirtual: quote.isVirtual(),            
            canVisibleBlock: 1,

            /**
             * @return {exports}
             */
            initialize: function () {
                var self = this,
                    hasNewAddress,
                    fieldsetName = 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset';

                this._super();
                shippingRatesValidator.initFields(fieldsetName);

                if (!quote.isVirtual()) {
                    stepNavigator.registerStep(
                        'shipping',
                        '',
                        $t('Contact & Shipping Information'),
                        this.visible, _.bind(this.navigate, this),
                        10
                    );
                }
                checkoutDataResolver.resolveShippingAddress();

                hasNewAddress = addressList.some(function (address) {
                    return address.getType() == 'new-customer-address';
                });

                this.isNewAddressAdded(hasNewAddress);

                this.isFormPopUpVisible.subscribe(function (value) {
                    if (value) {
                        self.getPopUp().openModal();
                    }
                });

                quote.shippingMethod.subscribe(function () {
                    self.errorValidationMessage(false);
                });
                registry.async('checkoutProvider')(function (checkoutProvider) {
                    var shippingAddressData = checkoutData.getShippingAddressFromData();
                    if (shippingAddressData) {
                        checkoutProvider.set(
                            'shippingAddress',
                            $.extend({}, checkoutProvider.get('shippingAddress'), shippingAddressData)
                        );
                    }

                    checkoutProvider.on('shippingAddress', function (shippingAddressData) {
                        checkoutData.setShippingAddressFromData(shippingAddressData);
                    });
                });

                return this;
            },

            /**
             * Load data from server for shipping step
             */
            navigate: function () {
                //load data from server for shipping step
            },

            /**
             * @return {*}
             */
            getPopUp: function () {
                var self = this,
                    buttons;

                if (!popUp) {
                    buttons = this.popUpForm.options.buttons;
                    this.popUpForm.options.buttons = [
                        {
                            text: buttons.save.text ? buttons.save.text : $t('Save Address'),
                            class: buttons.save.class ? buttons.save.class : 'action primary action-save-address',
                            click: self.saveNewAddress.bind(self)
                        },
                        {
                            text: buttons.cancel.text ? buttons.cancel.text : $t('Cancel'),
                            class: buttons.cancel.class ? buttons.cancel.class : 'action secondary action-hide-popup',
                            click: function () {
                                this.closeModal();
                            }
                        }
                    ];
                    this.popUpForm.options.closed = function () {
                        self.isFormPopUpVisible(false);
                    };
                    popUp = modal(this.popUpForm.options, $(this.popUpForm.element));
                }

                return popUp;
            },

            /**
             * Show address form popup
             */
            showFormPopUp: function () {
                this.isFormPopUpVisible(true);
            },

            /**
             * Save new shipping address
             */
            saveNewAddress: function () {
                var addressData,
                    newShippingAddress;

                this.source.set('params.invalid', false);
                this.source.trigger('shippingAddress.data.validate');

                if (!this.source.get('params.invalid')) {
                    addressData = this.source.get('shippingAddress');

                    // if user clicked the checkbox, its value is true or false. Need to convert.
                    addressData.save_in_address_book = this.saveInAddressBook ? 1 : 0;

                    // New address must be selected as a shipping address
                    newShippingAddress = createShippingAddress(addressData);
                    selectShippingAddress(newShippingAddress);

                    //set shipping address
                    $('.po-name-selected').text(newShippingAddress.firstname+' '+newShippingAddress.lastname);
                    $('.po-phone-selected').text(newShippingAddress.telephone);
                    var street1 = '';
                    if(typeof newShippingAddress.street[1] != 'undefined' && newShippingAddress.street[1] != '')
                        street1 = newShippingAddress.street[1];
                    $('.po-address-selected').text(newShippingAddress.street[0]+' '+street1);
                    $('.po-city-selected').text(newShippingAddress.city);
                    var setRegion = $('div[name="shippingAddress.region_id"]').find("select option:selected").text();
                    $('.po-state-selected').text(setRegion);
                    $('.po-zip-selected').text(newShippingAddress.postcode);

                    if(newShippingAddress.countryId == 'US')
                        $('.po-country-selected').text('United States');
                    else
                        $('.po-country-selected').text($('.shipping-address-item.selected-item .po-country-r').text());
                    
                    //set shipping address end here
                    checkoutData.setSelectedShippingAddress(newShippingAddress.getKey());
                    checkoutData.setNewCustomerShippingAddress(addressData);
                    this.getPopUp().closeModal();
                    this.isNewAddressAdded(true);
                }
            },

            /**
             * Shipping Method View
             */
            rates: shippingService.getShippingRates(),
            isLoading: shippingService.isLoading,
            isSelected: ko.computed(function () {
                    return quote.shippingMethod() ?
                        quote.shippingMethod().carrier_code + '_' + quote.shippingMethod().method_code
                        : null;
                }
            ),

            /**
             * @param {Object} shippingMethod
             * @return {Boolean}
             */
            selectShippingMethod: function (shippingMethod) {
                selectShippingMethodAction(shippingMethod);
                checkoutData.setSelectedShippingRate(shippingMethod.carrier_code + '_' + shippingMethod.method_code);

                return true;
            },

            /**
             * Set shipping information handler
             */
            setShippingInformation: function () {                
                if (this.validateShippingInformation()) {
                    setShippingInformationAction().done(
                        function () {
                            stepNavigator.next();
                        }
                    );
                }
            },

            /**
             * @return {Boolean}
             */
            validateShippingInformation: function () {
                var shippingAddress,
                    addressData,
                    loginFormSelector = 'form[data-role=email-with-possible-login]',
                    emailValidationResult = customer.isLoggedIn();
                
                if (!quote.shippingMethod()) {
                    this.errorValidationMessage('Please specify a shipping method.');
                    return false;
                }

                if (!customer.isLoggedIn()) {
                    $(loginFormSelector).validation();
                    emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
                }

                if (this.isFormInline) {
                    this.source.set('params.invalid', false);
                    this.source.trigger('shippingAddress.data.validate');

                    if (this.source.get('shippingAddress.custom_attributes')) {
                        this.source.trigger('shippingAddress.custom_attributes.data.validate');
                    }

                    if (this.source.get('params.invalid') ||
                        !quote.shippingMethod().method_code ||
                        !quote.shippingMethod().carrier_code ||
                        !emailValidationResult
                    ) {
                        return false;
                    }

                    shippingAddress = quote.shippingAddress();
                    addressData = addressConverter.formAddressDataToQuoteAddress(
                        this.source.get('shippingAddress')
                    );

                    //Copy form data to quote shipping address object
                    for (var field in addressData) {

                        if (addressData.hasOwnProperty(field) &&
                            shippingAddress.hasOwnProperty(field) &&
                            typeof addressData[field] != 'function' &&
                            _.isEqual(shippingAddress[field], addressData[field])
                        ) {
                            shippingAddress[field] = addressData[field];
                        } else if (typeof addressData[field] != 'function' &&
                            !_.isEqual(shippingAddress[field], addressData[field])) {
                            shippingAddress = addressData;
                            break;
                        }
                    }

                    if (customer.isLoggedIn()) {
                        shippingAddress.save_in_address_book = 1;
                    }
                    selectShippingAddress(shippingAddress);
                }

                if (!emailValidationResult) {
                    $(loginFormSelector + ' input[name=username]').focus();
                    return false;
                }

                return true;
            },

            contactValidation: function(){
                var errorCount = 0;
                $('.mage-error').hide();
                if($('#contact_company').val() == ''){
                    $('.contact_company').show();
                    errorCount = 1;
                }
                if($('#contact_fax').val() == ''){
                    $('.contact_fax').show();
                    errorCount = 1;
                }
                if($('#contact_name').val() == ''){
                    $('.contact_name').show();
                    errorCount = 1;
                }
                if($('#contact_email').val() == ''){
                    $('.contact_email').show();
                    errorCount = 1;
                }else{
                    if(! /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#contact_email').val()) ){
                        $('.contact_email').show();
                        errorCount = 1;   
                    }
                }

                if(errorCount == 1){
                    return false;
                }else{
                    /* Validation success, save contact value in quote */
                    if($('#contact_resale').val() == 1){
                        var resaleValue = 'Yes';
                    }else{
                        var resaleValue = 'No';
                    }
                    var contactCompany = $('#contact_company').val();
                    var contactName = $('#contact_name').val();
                    var contactPhone = $('#contact_phone').val();
                    var contactEmail = $('#contact_email').val();
                    var contactFax = $('#contact_fax').val();
                    var contactResale = $('#contact_resale').val();

                    var contactInfo = {
                                        "contact_company":contactCompany,
                                        "contact_name":contactName,
                                        "contact_phone":contactPhone,
                                        "contact_email":contactEmail,
                                        "contact_fax":contactFax,
                                        "contact_resale":resaleValue
                                      };
                    var cookieContact = contactCompany+'||'+contactName+'||'+contactPhone+'||'+contactEmail+'||'+contactFax+"||"+contactResale;
                    $.cookie("contact-data", cookieContact);
                    checkoutData.setGuestContact(contactInfo);
                }
                return true;
            }
        });
    }
);

