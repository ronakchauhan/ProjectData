define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
], function ($, _, uiRegistry, select, modal) {
    'use strict';

    return select.extend({

        /**
         * Init
         */
        initialize: function () {
            this._super();

            this.fieldDepend(this.value());

            return this;
        },

        /**
         * On value change handler.
         *
         * @param {String} value
         */
        onUpdate: function (value) {
            this.fieldDepend(value);

            return this._super();
        },
        
        /**
         * On value change handler.
         *
         * @param {String} value
         */
        fieldDepend: function (value) {
            setTimeout(function(){ 

                var field = uiRegistry.get('index = short_desc_title');
                if (field.visibleValue == value || value == 2 || value == 3) {
                    field.show();
                } else {
                    field.hide();
                }

                var field1 = uiRegistry.get('index = short_desc');
                
                if (field1.visibleValue == value || value == 2 || value == 3) {
                    field1.show();
                } else {
                    field1.hide();
                }
                
                var field4 = uiRegistry.get('index = left_desc_title');
                
                if (field4.visibleValue == value || value == 2) {
                    field4.show();
                } else {
                    field4.hide();
                }

                var field5 = uiRegistry.get('index = left_desc');
                
                if (field5.visibleValue == value || value == 2) {
                    field5.show();
                } else {
                    field5.hide();
                }

                var field6 = uiRegistry.get('index = right_desc_title');
                
                if (field6.visibleValue == value || value == 2) {
                    field6.show();
                } else {
                    field6.hide();
                }

                var field7 = uiRegistry.get('index = right_desc');
                
                if (field7.visibleValue == value || value == 2) {
                    field7.show();
                } else {
                    field7.hide();
                }

                var field8 = uiRegistry.get('index = verticle_desc_one_title');
                
                if (field8.visibleValue == value) {
                    field8.show();
                } else {
                    field8.hide();
                }

                var field9 = uiRegistry.get('index = verticle_desc_one');
                
                if (field9.visibleValue == value) {
                    field9.show();
                } else {
                    field9.hide();
                }

                var field9 = uiRegistry.get('index = verticle_desc_two_title');
                
                if (field9.visibleValue == value) {
                    field9.show();
                } else {
                    field9.hide();
                }

                var field10 = uiRegistry.get('index = verticle_desc_two');
                
                if (field10.visibleValue == value) {
                    field10.show();
                } else {
                    field10.hide();
                }

                var field11 = uiRegistry.get('index = verticle_desc_three_title');
                
                if (field11.visibleValue == value) {
                    field11.show();
                } else {
                    field11.hide();
                }

                var field12 = uiRegistry.get('index = verticle_desc_three');
                
                if (field12.visibleValue == value) {
                    field12.show();
                } else {
                    field12.hide();
                }

                var field13 = uiRegistry.get('index = article_link');
                
                if (field13.visibleValue == value) {
                    field13.show();
                } else {
                    field13.hide();
                }

                if (value == 0) {
                    var fields = $("div[data-index='descriptions']");
                    fields.hide();
                }
                else{
                    var fields = $("div[data-index='descriptions']");
                    fields.show();
                }
                
             }, 1);
            

            return this._super();
        },
    });
});