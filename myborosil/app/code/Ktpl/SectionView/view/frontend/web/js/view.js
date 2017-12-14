define([
    "jquery",
    "jquery/ui"
], function($) {
    "use strict";

    function adjustPosition(config) {
        var originalWidth = $(config.container).attr('data-width');
        var originalHeight = $(config.container).attr('data-height');

        $(config.elements).each(function(index, element) {
            var actualLeft = Math.round(($(config.container).width() * element.left) / originalWidth);
            var actualTop = Math.round(($(config.container).height() * element.top) / originalHeight);
            $("#" + element.id).css({
                'top': actualTop + 'px',
                'left': actualLeft + 'px',
            });
        });
    }

    return function(config) {

        adjustPosition(config);

        if (config.isVideoEnable) {
            $(config.container + " > .section-wrapper > .section-video-trigger").click(function() {
                var videoUrl = config.videoUrl + "?autoplay=1";
                $(config.videoContainer)
                    .attr({
                        'src': videoUrl,
                        'width': $(this).width(),
                        'height': $(this).height()
                    }).css({'display': 'block'})
                    .next().show();
                $(this).css({'display': 'none'});

                $(config.container + " .section-element-container").hide();
            });

            $(config.videoContainer).next().click(function() {
            	$(config.container + " > .section-wrapper > .section-video-trigger")
            		.css('display', '');
            	$(this).prev()
            		.removeAttr('src')
            		.css({'display': 'none'});
        		$(this).hide();
        		$(config.container + " .section-element-container").show();
            });
        }

        // $(window).resize(function() {
        //     adjustPosition(config);
        // });
    };
});