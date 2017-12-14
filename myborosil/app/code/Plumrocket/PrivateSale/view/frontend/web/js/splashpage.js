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
    'mage/storage',
    'Magento_Customer/js/customer-data',
    'Plumrocket_PrivateSale/js/model/image',
    'jquery/ui',
    'mage/mage'
], function ($, storage,customerData, image) {
    "use strict";

    $.widget('plumrocket.privatesaleSplashPage', {

        _create: function () {
            var self = this;
            $('form').mage('validation', {});
            $(function() {
                self.prepareRegistrationForm();
            });
            $('[data-role="change-form"]').on('click',$.proxy(this.changeForm, this));
            $('#prpop-pop-up-bg form').on('submit', $.proxy(this.sendAjax, this));
            $('#prpop-for_messages_popup .close a').on('click',$.proxy(this.closeMessages, this));

            this.updateRegistrationForm();
            this.prepareImages(image);
        },

        prepareImages: function(image) {
            if (this.options.images.length) {
                var url = image.getImage(this.options.images);

                url = this.options.mediaUrl + url;
                $('#bg').css('background-image', 'url(' + url + ')');
            }
        },

        updateRegistrationForm: function() {
            this.moveRegistrationButton();
            var $registrationForm = $('[data-container="register"]');
            $registrationForm.find('.field').each(function() {
                if (typeof $(this).find('input').attr('placeholder') == 'undefined') {
                    var placeholder = $(this).find('label').text();
                    $(this).find('input').attr('placeholder', placeholder);
                }
            });
        },

        moveRegistrationButton: function() {
            var registrationButton = jQuery('[data-container="register"]').find('.actions-toolbar');
            jQuery('[data-container="register"] .prpop-back-hld').before(registrationButton);
        },

        changeForm: function(event) {
            this.hideAll();

            if (typeof event == 'object') {
                var $element = $(event.currentTarget);
                var name = $element.data('show');
            } else {
                var name = event;
            }
            this.showContainer(name);
        },

        prepareRegistrationForm: function() {
            if ($('form.form-create-account').length) {
                var fields = [];
                $('form.form-create-account').attr('action', this.options.registerUrl);
                $('form.form-create-account').find('.field').each(function() {
                    if ($(this).find('input').hasClass('input-text')) {
                        var text = $(this).find('label').text();
                        $(this).find('input').attr('placeholder', text.trim());
                    }
                });
            }
        },

        showContainer: function(name) {
            this.closeMessages();
            $('[data-container="' + name + '"]').show();
        },

        hideAll: function() {
            $('.block-container').hide();
        },

        sendAjax: function(event)
        {
            var $form = $(event.currentTarget),
                action = $form.attr('action'),
                loginData = {},
                self = this,
                data = $form.serializeArray();

            if ($form.validation('isValid')) {

                $form.find('button[type="submit"]').attr('disabled', true);

                self.showLoader();
                data.forEach(function (entry) {
                    loginData[entry.name] = entry.value;
                });

                storage.post(
                    action,
                    JSON.stringify(loginData),
                    true
                ).done(function (response) {
                    $form.find('button[type="submit"]').attr('disabled', false);
                    self.hideLoader();
                    if (response.errors) {
                        self.showError(response.message);
                    } else {
                        if ($form.attr('id') == 'login-form') {
                            customerData.invalidate(["*", "messages"]);
                            self.redirect();
                        } else if ($form.hasClass('form-create-account')) {
                            self.changeForm('reg-success');
                            customerData.invalidate(["*", "messages"]);
                            setTimeout(function() {
                                self.redirect();
                            }, 2000);
                        } else if ($form.attr('id') == 'forgot-form') {
                            self.changeForm('forgot-success');
                            $('#forgot_text span').html(loginData.email);
                        }
                    }
                }).fail(function () {
                    $form.find('button[type="submit"]').attr('disabled', false);
                    self.hideLoader();
                });
            }

            return false;
        },

        showError: function(message) {
            $('#prpop-for_messages_popup').find('.error-msg span').html(message);
            $('#prpop-for_messages_popup').show();

            var errorLink = $('#prpop-for_messages_popup .messages').find('a');
            var self = this;
            if (errorLink.attr('href').search('forgotpassword') > 0) {
                errorLink.click(function() {
                    self.hideAll();
                    self.showContainer('forgot');
                    return false;
                });
            }
        },

        showLoader: function() {
            $('.prpop-ajax-loader-wrapper').show();
        },

        hideLoader: function() {
            $('.prpop-ajax-loader-wrapper').hide();
        },

        redirect: function() {
            if (this.options.canRedirect) {
                document.location = this.options.baseUrlForRedirect;
            }
        },

        closeMessages: function() {
            $('#prpop-for_messages_popup').find('.error-msg span').html('');
            $('#prpop-for_messages_popup').hide();
        }

    });

    return $.plumrocket.privatesaleSplashPage;
});
