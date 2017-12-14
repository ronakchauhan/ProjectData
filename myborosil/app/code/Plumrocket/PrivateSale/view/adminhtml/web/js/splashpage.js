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

define([
    "jquery",
    'jquery/ui'
], function ($) {
    "use strict";

    $.widget('plumrocket.splashpage', {

    	index: 0,

        _create: function () {
        	var self = this;

        	$('.input-date').datepicker({
	            showButtonPanel: true
        	});

            $( "#privatesale_splashpage_tabs" ).on( "tabsbeforeactivate", function( event, ui ) {
                if ($('#privatesale_splashpage_enabled_page').val() == 2 && event != false) {
				    alert('These settings are not available with Magento Login Page Template');
				    return false;
				}
            });

        	$('.ui-datepicker-trigger').on('click',$.proxy(this.showDatePicker, this));
        	$(document).ready(function(){


				$('#files_list').empty();
				$('#inputs_list').empty();

				$('#add_file').click(function(){
					self.index++;

					$('#file_disabled').clone()
						.attr({'id': 'file_' + self.index, 'name': 'files_' + self.index + ''})
						.change(function(){
							changeStr();
							$(this).unbind('change');
						})
						.appendTo('#inputs_list');
					$('#file_' + self.index).click();
				});

				function changeStr()
				{
					$('#files_list').empty();
					$('.files').each(function(){
						var _id = $(this).attr('id');
						if (_id != 'file_disabled' && $(this).val() != '') {
							var _clone = $('#list_item_disabled').clone()
								.attr({'id': ''})
								.appendTo('#files_list');

							$(_clone).find('.file-info')
								.text( $(this).val() );
							$(_clone).find('.delete')
								.bind('click', {id: _id}, deleteItem);
						}
					});

					if ($('.files').length <= 1) {
						$('#isNewFiles').val(0);
					} else {
						$('#isNewFiles').val(1);
					}
				};

				function deleteItem(data)
				{
					$('#' + data.data.id).remove();
					changeStr();
				}
			});
        },

        showDatePicker: function(event) {
        	$(event.target).prev().datepicker('show');
        },

    });

    return $.plumrocket.splashpage;
});

