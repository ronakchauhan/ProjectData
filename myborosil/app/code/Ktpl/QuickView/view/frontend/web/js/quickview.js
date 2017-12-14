define([
    'jquery',
    'magnificPopup',
    'domReady!'
], function ($, magnificPopup) {
    "use strict";

    return {
        displayContent: function(prodUrl, updateCartUrl) {
            if (!prodUrl.length) {
                return false;
            }

            $.magnificPopup.open({
                items: {
                    src: prodUrl
                },
                type: 'iframe',
                closeOnBgClick: false, // dont close on bg click
                //showCloseBtn: false
                //alignTop: true
                //fixedContentPos: true,
                preloader: true,
                tLoading: '',
                callbacks: {
                    open: function() {
                        $('.mfp-preloader').css('display', 'block');
                    },
                    beforeClose: function() {
                        $.ajax({
                            url: updateCartUrl,
                            method: "POST",
                            success: function(res) {
                                $('[data-block="minicart"]').trigger('contentLoading');
                            }
                        });
                    },
                    close: function() {
                        $('.mfp-preloader').css('display', 'none');
                    }
                }
            });
        }
    };

});