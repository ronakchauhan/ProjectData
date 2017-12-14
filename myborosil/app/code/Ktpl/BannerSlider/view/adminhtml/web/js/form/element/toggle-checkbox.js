define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/single-checkbox-toggle-notice',
    'Magento_Ui/js/modal/modal'
], function (_, uiRegistry, checkbox, modal) {
    'use strict';

    return checkbox.extend({

        /**
         * Choose notice on initialization
         *
         * @returns {*|void|Element}
         */
        initialize: function (value) {
            this._super();
            
            var field1 = uiRegistry.get('index = video_url');
            
            if(this.value() == 1){
                field1.show();
            } else {
                field1.hide();
            }

            return this;
        },
        
        /**
         * On value change handler.
         *
         * @param {String} value
         */
        onUpdate: function (value) {

            var field1 = uiRegistry.get('index = video_url');

            if (field1.visibleValue == value) {
                field1.show();
            } else {
                field1.hide();
            }

            return this._super();
        },
    });
});