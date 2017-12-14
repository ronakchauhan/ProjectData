require([
    "jquery",
    "Magento_Ui/js/modal/modal",
    'Magento_Ui/js/modal/confirm',
    "jquery/ui",
    "mage/validation"
], function ($, modal, confirmation) {
    'use strict';

    $.fn.editor = function(options) {

        $.fn.editor.defaults = $.extend({}, $.fn.editor.defaults, options);

        var savedData = JSON.parse($.fn.editor.defaults.savedData);
        if(savedData.length)
            $.fn.editor.loadData(savedData);

        $('#text-form').mage('validation').find('input:text').attr('autocomplete', 'off');
        $('#button-form').mage('validation').find('input:text').attr('autocomplete', 'off');

        $("#add_text").click(function() { $.fn.editor.popupText(false, { text_content: '', text_css: ''}, undefined); });
        $("#add_button").click(function() { $.fn.editor.popupButton(false, { button_title: '', button_url: '', button_css: '' }, undefined); });

        $.fn.editor.bindBold();
        $.fn.editor.bindItalic();
        $.fn.editor.bindUnderline();

        $.fn.editor.bindHorizontalAlign();
        $.fn.editor.bindVerticalAlign();        

        $.fn.editor.bindEdit();
        $.fn.editor.bindDelete();

        $.fn.editor.bindPosition();

        $.fn.editor.bindKeyPress();

        $(".editor_wrapper").css({'position': 'relative'});

        $.fn.editor.formatData();
    };

    $.fn.editor.currentElement = undefined;
    $.fn.editor.defaults = { savedData: '[]', setData: function (data) { } };

    $.fn.editor.popupText = function(isEdit, data, element) {
        modal({
            autoOpen: true,
            responsive: true,
            clickableOverlay: false,
            modalClass: 'modal-custom',
            title: (isEdit? $.mage.__('Edit Text'): $.mage.__('Add Text')),
            buttons: [{
                text: $.mage.__('Cancel'),
                class: 'action-secondary action-dismiss',
                click: function () {
                    this.closeModal();
                }
            }, {
                text: $.mage.__('OK'),
                class: 'action-primary action-accept',
                click: function () {
                    if($('#text-form').validation('isValid')) {
                        if(isEdit) {
                            $(element).attr({'data-content': $("#text_content").val(), 'data-css': $("#text_css").val()})
                                .find('span').html($("#text_content").val());

                            $(element).unbind("click");

                            $(element).click(function() {
                                $.fn.editor.selectElement({
                                    'type': 'text',
                                    'element': element,
                                    'data': { text_content: $(element).attr('data-content'), text_css: $(element).attr('data-css') }
                                })
                            });

                            $.fn.editor.selectElement({
                                'type': 'text',
                                'element': element,
                                'data': { text_content: $(element).attr('data-content'), text_css: $(element).attr('data-css') }
                            });
                        }
                        else
                            $.fn.editor.addText({
                                text_content: $("#text_content").val(),
                                text_css: $("#text_css").val()
                            }, '0', '0', undefined, undefined, false, false, false);

                        $.fn.editor.formatData();
                        this.closeModal();
                    }                    
                }
            }],
            opened: function($Event) {
                $("#text_content").val(data.text_content);
                $("#text_css").val(data.text_css);
                $('.modal-header button.action-close', $Event.srcElement).hide();
            },
            keyEventHandlers: {
                escapeKey: function () { return; }
            }
        }, $("#add_text_popup"));
    };

    $.fn.editor.addText = function(data, top, left, width, height, isBold, isItalic, isUnderline) {
        var draggableElement = $('<div></div>').addClass('draggable-container')
            .attr({'data-content': data.text_content, 'data-css': data.text_css, 'data-left': left, 'data-top': top, 'data-type': 'text'})
            .append(
                $('<span></span>').addClass('front-text').css({'cursor': 'move'}).html(data.text_content)
            ).css({
                'top': top + 'px', 'left': left + 'px', 'position': 'absolute'
            }).click(function() {
                $.fn.editor.selectElement({
                    'type': 'text',
                    'element': this,
                    'data': { text_content: $(this).attr('data-content'), text_css: $(this).attr('data-css') }
                })
            }).draggable({ 
                containment: "parent", 
                start: function() { $.fn.editor.formatData(); },
                drag: function(event, ui) { $("#editor_position_left").val(ui.position.left); $("#editor_position_top").val(ui.position.top); $(this).attr({'data-left': ui.position.left, 'data-top': ui.position.top}); },
                stop: function() { $.fn.editor.formatData(); }
            }).resizable({ 
                containment: "parent", 
                start: function() { $.fn.editor.formatData(); },
                resize: function(event, ui) { $(this).attr({'data-width': ui.size.width, 'data-height': ui.size.height}); },
                stop: function() { $.fn.editor.formatData(); }
            });

        if(width != undefined)
            $(draggableElement).attr('data-width', width).css({'width': width + 'px'});

        if(height != undefined)
            $(draggableElement).attr('data-height', height).css({'height': height + 'px'});

        if(isBold) $(draggableElement).attr('data-bold', 'true').find('span').css('font-weight', 'bold');
        if(isItalic) $(draggableElement).attr('data-italic', 'true').find('span').css('font-style', 'italic');
        if(isUnderline) $(draggableElement).attr('data-underline', 'true').find('span').css('text-decoration', 'underline');

        $(".editor_wrapper").append(draggableElement);
        $.fn.editor.deSelectElement();
    }

    $.fn.editor.popupButton = function(isEdit, data, element) {
        modal({
            autoOpen: true,
            responsive: true,
            clickableOverlay: false,
            modalClass: 'modal-custom',
            title: (isEdit? $.mage.__('Edit Button'): $.mage.__('Add Button')),
            buttons: [{
                text: $.mage.__('Cancel'),
                class: 'action-secondary action-dismiss',
                click: function () {
                    this.closeModal();
                }
            }, {
                text: $.mage.__('OK'),
                class: 'action-primary action-accept',
                click: function () {
                    if($('#button-form').validation('isValid')) {
                        if(isEdit) {
                            $(element).attr({'data-title': $("#button_title").val(), 'data-href': $("#button_url").val(), 'data-css': $("#button_css").val()})
                                .find('a').html($("#button_title").val());

                            $(element).unbind("click");

                            $(element).click(function() {
                                $.fn.editor.selectElement({
                                    'type': 'button',
                                    'element': element,
                                    'data': { button_title: $(element).attr('data-title'), button_url: $(element).attr('data-href'), button_css: $(element).attr('data-css') }
                                })
                            });

                            $.fn.editor.selectElement({
                                'type': 'button',
                                'element': element,
                                'data': { button_title: $(element).attr('data-title'), button_url: $(element).attr('data-href'), button_css: $(element).attr('data-css') }
                            });
                        }
                        else
                            $.fn.editor.addButton({
                                button_title: $("#button_title").val(), 
                                button_url: $("#button_url").val(),
                                button_css: $("#button_css").val()
                            }, '0', '0', false, false, false);

                        $.fn.editor.formatData();
                        this.closeModal();
                    }                    
                }
            }],
            opened: function($Event) {
                $("#button_title").val(data.button_title);
                $("#button_url").val(data.button_url);
                $("#button_css").val(data.button_css);
                $('.modal-header button.action-close', $Event.srcElement).hide();
            },
            keyEventHandlers: {
                escapeKey: function () { return; }
            }
        }, $("#add_button_popup"));
    };

    $.fn.editor.addButton = function(data, top, left, isBold, isItalic, isUnderline) {
        var draggableElement = $('<div></div>').addClass('draggable-container')
            .attr({'data-title': data.button_title, 'data-href': data.button_url, 'data-css': data.button_css, 'data-left': left, 'data-top': top, 'data-type': 'button'})
            .append(
                $('<a></a>').addClass('front-button').css({'cursor': 'move', 'white-space': 'nowrap'}).html(data.button_title)
            ).css({
                'top': top + 'px', 'left': left + 'px', 'position': 'absolute'
            }).click(function() {
                $.fn.editor.selectElement({
                    'type': 'button',
                    'element': this,
                    'data': { button_title: $(this).attr('data-title'), button_url: $(this).attr('data-href'), button_css: $(this).attr('data-css') }
                })
            }).draggable({ 
                containment: "parent", 
                start: function() { $.fn.editor.formatData(); },
                drag: function(event, ui) { $("#editor_position_left").val(ui.position.left); $("#editor_position_top").val(ui.position.top); $(this).attr({'data-left': ui.position.left, 'data-top': ui.position.top}); },
                stop: function() { $.fn.editor.formatData(); }
            });

        if(isBold) $(draggableElement).attr('data-bold', 'true').find('a').css('font-weight', 'bold');
        if(isItalic) $(draggableElement).attr('data-italic', 'true').find('a').css('font-style', 'italic');
        if(isUnderline) $(draggableElement).attr('data-underline', 'true').find('a').css('text-decoration', 'underline');

        $(".editor_wrapper").append(draggableElement);
        $.fn.editor.deSelectElement();
    }

    $.fn.editor.selectElement = function(element) {
        $.fn.editor.currentElement = element;
        
        $('.draggable-container').removeClass('selected');
        $(element.element).addClass('selected');

        if($(element.element).attr('data-bold') != undefined) $("#bold_element").addClass('on');
        else $("#bold_element").removeClass('on');

        if($(element.element).attr('data-italic') != undefined) $("#italic_element").addClass('on');
        else $("#italic_element").removeClass('on');

        if($(element.element).attr('data-underline') != undefined) $("#underline_element").addClass('on');
        else $("#underline_element").removeClass('on');

        $(".editor_toolbar .toolbar").show();

        $(".editor_position").show();
        $("#editor_position_left").val($(element.element).attr('data-left'));
        $("#editor_position_top").val($(element.element).attr('data-top'));
    };

    $.fn.editor.deSelectElement = function() {
        $.fn.editor.currentElement = undefined;
        $('.draggable-container').removeClass('selected');

        $("#bold_element").removeClass('on');
        $("#italic_element").removeClass('on');
        $("#underline_element").removeClass('on');

        $(".editor_toolbar .toolbar").hide();
        $(".editor_position").hide();
    };

    $.fn.editor.bindBold = function() {
        $("#bold_element").click(function() {
            if($.fn.editor.currentElement != undefined) {
                var element = $.fn.editor.currentElement.element, dataBold = $(element).attr('data-bold');
                if($.fn.editor.currentElement.type == "button") {
                    if(dataBold == undefined)
                        $(element).attr('data-bold', 'true').find('a').css('font-weight', 'bold');
                    else
                        $(element).removeAttr('data-bold').find('a').css('font-weight', '');
                }
                else if($.fn.editor.currentElement.type == "text") {
                    if(dataBold == undefined)
                        $(element).attr('data-bold', 'true').find('span').css('font-weight', 'bold');
                    else
                        $(element).removeAttr('data-bold').find('span').css('font-weight', '');
                }
                $.fn.editor.selectElement($.fn.editor.currentElement);
                $.fn.editor.formatData();
            }
        });
    };

    $.fn.editor.bindItalic = function() {
        $("#italic_element").click(function() {
            if($.fn.editor.currentElement != undefined) {
                var element = $.fn.editor.currentElement.element, dataBold = $(element).attr('data-italic');
                if($.fn.editor.currentElement.type == "button") {
                    if(dataBold == undefined)
                        $(element).attr('data-italic', 'true').find('a').css('font-style', 'italic');
                    else
                        $(element).removeAttr('data-italic').find('a').css('font-style', '');
                }
                else if($.fn.editor.currentElement.type == "text") {
                    if(dataBold == undefined)
                        $(element).attr('data-italic', 'true').find('span').css('font-style', 'italic');
                    else
                        $(element).removeAttr('data-italic').find('span').css('font-style', '');
                }
                $.fn.editor.selectElement($.fn.editor.currentElement);
                $.fn.editor.formatData();
            }
        });
    };

    $.fn.editor.bindUnderline = function() {
        $("#underline_element").click(function() {
            if($.fn.editor.currentElement != undefined) {
                var element = $.fn.editor.currentElement.element, dataBold = $(element).attr('data-underline');
                if($.fn.editor.currentElement.type == "button") {
                    if(dataBold == undefined)
                        $(element).attr('data-underline', 'true').find('a').css('text-decoration', 'underline');
                    else
                        $(element).removeAttr('data-underline').find('a').css('text-decoration', '');
                }
                else if($.fn.editor.currentElement.type == "text") {
                    if(dataBold == undefined)
                        $(element).attr('data-underline', 'true').find('span').css('text-decoration', 'underline');
                    else
                        $(element).removeAttr('data-underline').find('span').css('text-decoration', '');
                }
                $.fn.editor.selectElement($.fn.editor.currentElement);
                $.fn.editor.formatData();
            }
        });
    };

    $.fn.editor.bindHorizontalAlign = function() {
        $("#horizontal_align").click(function() {
            var element = $.fn.editor.currentElement.element;
            var left = Math.round(($(".editor_wrapper").width() / 2) - ($(element).width() / 2));
            $(element).css('left', left + 'px').attr('data-left', left);
            $.fn.editor.formatData();
        });
    };

    $.fn.editor.bindVerticalAlign = function() {
        $("#vertical_align").click(function() {
            var element = $.fn.editor.currentElement.element;
            var top = Math.round(($(".editor_wrapper").height() / 2) - ($(element).height() / 2));
            $(element).css('top', top + 'px').attr('data-top', top);
            $.fn.editor.formatData();
        });
    };

    $.fn.editor.bindEdit = function() {
        $("#edit_element").click(function() {
            if($.fn.editor.currentElement != undefined) {
                if($.fn.editor.currentElement.type == "button")
                    $.fn.editor.popupButton(true, $.fn.editor.currentElement.data, $.fn.editor.currentElement.element);
                else if($.fn.editor.currentElement.type == "text")
                    $.fn.editor.popupText(true, $.fn.editor.currentElement.data, $.fn.editor.currentElement.element);
            }
        });
    };

    $.fn.editor.bindDelete = function() {
        $("#delete_element").click(function() {
            if($.fn.editor.currentElement != undefined) {
                confirmation({
                    title: $.mage.__('Delete'),
                    content: $.mage.__('Are you sure you want to delete?'),
                    actions: {
                        confirm: function() {
                            $($.fn.editor.currentElement.element).remove();
                            $.fn.editor.deSelectElement();
                            $.fn.editor.formatData();
                        }
                    }
                });
            }
        });
    };

    $.fn.editor.formatData = function() {
        var draggableObjectArray = new Array();

        $('.draggable-container').each(function(index, element) {
            var draggableObject = {
                top: $(element).attr('data-top'),
                left: $(element).attr('data-left'),
                type: $(element).attr('data-type')
            };

            if(draggableObject.type == "button") {
                draggableObject.title = $(element).attr('data-title');
                draggableObject.href = $(element).attr('data-href');
                draggableObject.css = $(element).attr('data-css');
            }
            else if(draggableObject.type == "text") {
                draggableObject.content = $(element).attr('data-content');
                draggableObject.css = $(element).attr('data-css');
                draggableObject.width = $(element).attr('data-width');
                draggableObject.height = $(element).attr('data-height');
            }

            if($(element).attr('data-bold') != undefined) draggableObject.bold = true;
            if($(element).attr('data-italic') != undefined) draggableObject.italic = true;
            if($(element).attr('data-underline') != undefined) draggableObject.underline = true;

            draggableObjectArray.push(draggableObject);
        })
        $.fn.editor.defaults.setData(JSON.stringify(draggableObjectArray));
    };

    $.fn.editor.loadData = function(savedData) {
        $(savedData).each(function(index, element) {
            var isBold = (element.bold != undefined);
            var isItalic = (element.italic != undefined);
            var isUnderline = (element.underline != undefined);
            if(element.type == 'button') {
                $.fn.editor.addButton({
                    button_title: element.title, 
                    button_url: element.href,
                    button_css: element.css
                }, element.top, element.left, isBold, isItalic, isUnderline);
            }
            else if(element.type == 'text') {
                $.fn.editor.addText({
                    text_content: element.content,
                    text_css: element.css
                }, element.top, element.left, element.width, element.height, isBold, isItalic, isUnderline);
            }
        });
    };

    $.fn.editor.bindPosition = function() {
        $("#close_position").click(function() {
            $('.editor_position').removeClass('open').animate( { left: '-=200' }, 600);
            $.fn.editor.formatData();
        });

        $("#editor_position_left").keyup(function() {
            if($.fn.editor.currentElement != undefined) {
                var left = $(this).val();
                if(isNaN(left))
                    left = 0;

                var element = $.fn.editor.currentElement.element;
                $(element).css('left', left + 'px').attr('data-left', left);
                $.fn.editor.formatData();
            }            
        });

        $("#open_position").click(function() {
            $('.editor_position').addClass('open').animate( { left: '+=200' }, 600);
            $.fn.editor.formatData();
        });

        $("#editor_position_top").keyup(function() {
            if($.fn.editor.currentElement != undefined) {
                var top = $(this).val();
                if(isNaN(top))
                    top = 0;

                var element = $.fn.editor.currentElement.element;
                $(element).css('top', top + 'px').attr('data-top', top);
                $.fn.editor.formatData();
            }            
        });
    };

    $.fn.editor.bindKeyPress = function() {
        $(document).keydown(function(e) {
            if($.fn.editor.currentElement != undefined) {
                var element = $.fn.editor.currentElement.element;
                switch(e.which) {
                    case 37: // left
                        var left = parseInt($(element).attr('data-left'));
                        left--;
                        $(element).css('left', left + 'px').attr('data-left', left);
                        $("#editor_position_left").val(left);
                        $.fn.editor.formatData();
                		e.preventDefault(); // prevent the default action (scroll / move caret)
                        break;
                    case 38: // up
                        var top = parseInt($(element).attr('data-top'));
                        top--;
                        $(element).css('top', top + 'px').attr('data-top', top);
                        $("#editor_position_top").val(top);
                        $.fn.editor.formatData();
                		e.preventDefault(); // prevent the default action (scroll / move caret)
                        break;
                    case 39: // right
                        var left = parseInt($(element).attr('data-left'));
                        left++;
                        $(element).css('left', left + 'px').attr('data-left', left);
                        $("#editor_position_left").val(left);
                        $.fn.editor.formatData();
                		e.preventDefault(); // prevent the default action (scroll / move caret)
                        break;
                    case 40: // down
                        var top = parseInt($(element).attr('data-top'));
                        top++;
                        $(element).css('top', top + 'px').attr('data-top', top);
                        $("#editor_position_top").val(top);
                        $.fn.editor.formatData();
                		e.preventDefault(); // prevent the default action (scroll / move caret)
                        break;
                }                
            }
        });
    };
});