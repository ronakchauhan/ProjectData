/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/* global $, $H */

define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedPincodes = config.selectedPincodes,
            zonePincodes = $H(selectedPincodes),
            gridJsObject = window[config.gridJsObjectName],
            tabIndex = 1000;

        $('in_pincode_zones').value = Object.toJSON(zonePincodes);

        /**
         * Register Category Product
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerCategoryProduct(grid, element, checked) {
            if (checked) {
                zonePincodes.set(element.value, '');
            } else {                
                zonePincodes.unset(element.value);
            }
            $('in_pincode_zones').value = Object.toJSON(zonePincodes);
            grid.reloadParams = {
                'selected_pincodes[]': zonePincodes.keys()
            };
        }

        /**
         * Click on product row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function categoryProductRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                isInput = Event.element(event).tagName === 'INPUT',
                checked = false,
                checkbox = null;

            if (trElement) {
                checkbox = Element.getElementsBySelector(trElement, 'input');

                if (checkbox[0]) {
                    checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                }
            }
        }

        /**
         * Change product position
         *
         * @param {String} event
         */
        function positionChange(event) {
            var element = Event.element(event);

            if (element && element.checkboxElement && element.checkboxElement.checked) {
                zonePincodes.set(element.checkboxElement.value, element.value);
                $('in_pincode_zones').value = Object.toJSON(zonePincodes);
            }
        }

        /**
         * Initialize category product row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function categoryProductRowInit(grid, row) {
            var checkbox = $(row).getElementsByClassName('checkbox')[0],
                position = $(row).getElementsByClassName('input-text')[0];

            if (checkbox && position) {
                checkbox.positionElement = position;
                position.checkboxElement = checkbox;
                position.disabled = !checkbox.checked;
                position.tabIndex = tabIndex++;
                Event.observe(position, 'keyup', positionChange);
            }
        }

        gridJsObject.rowClickCallback = categoryProductRowClick;
        gridJsObject.initRowCallback = categoryProductRowInit;
        gridJsObject.checkboxCheckCallback = registerCategoryProduct;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                categoryProductRowInit(gridJsObject, row);
            });
        }
    };
});
