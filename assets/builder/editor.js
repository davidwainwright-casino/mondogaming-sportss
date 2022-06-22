/*
  jquery.popline.js 1.0.0
  Version: 1.0.0
  jquery.popline.js is an open source project, contribute at GitHub:
  https://github.com/kenshin54/popline.js
  (c) 2014 by kenshin54
*/
(function($) {
    "use strict";
    var LEFT = -2,
        UP = -1,
        RIGHT = 2,
        DOWN = 1,
        NONE = 0;

    var isIMEMode = false;
    $(document).on('compositionstart', function() {
        isIMEMode = true;
    });
    $(document).on('compositionend', function() {
        isIMEMode = false;
    });

    var toggleBox = function(event) {
        if ($.popline.utils.isNull($.popline.current)) {
            return;
        }
		
		var readonly = false;
		var parent = $.popline.utils.selection().focusNode().parentNode;
		if (typeof $(parent).attr('data-redonly') !== 'undefined') {
			readonly = true;
		}

        var isTargetOrChild = $.contains($.popline.current.target.get(0), event.target) || $.popline.current.target.get(0) === event.target;
        var isBarOrChild = $.contains($.popline.current.bar.get(0), event.target) || $.popline.current.bar.get(0) === event.target;
        if ((isTargetOrChild || isBarOrChild) && $.popline.utils.selection().text().length > 0 && !$.popline.current.keepSilentWhenBlankSelected() && readonly === false) {
			
            var bar = $.popline.current.bar;
            if (bar.is(":hidden") || bar.is(":animated")) {
                bar.stop(true, true);
                var pos = Position().mouseup(event);
                $.popline.current.show(pos);
            }
        } else {
            $.popline.hideAllBar();
        }
    };

    var targetEvent = {
        mousedown: function() {
            $.popline.current = $(this).data("popline");
            $.popline.hideAllBar();
        },
        keyup: function() {
            var popline = $(this).data("popline");
            //bar = popline.bar;
            if (!isIMEMode && $.popline.utils.selection().text().length > 0 && !popline.keepSilentWhenBlankSelected()) {
                var pos = Position().keyup();
                $.popline.current.show(pos);
            } else {
                $.popline.current.hide();
            }
        },
        keydown: function() {
            $.popline.current = $(this).data("popline");
            var text = $.popline.utils.selection().text();
            if (!$.popline.utils.isNull(text) && $.trim(text) !== "") {
                var rects = $.popline.utils.selection().range().getClientRects();
                if (rects.length > 0) {
                    $(this).data('lastKeyPos', $.popline.boundingRect());
                }
            }
        }
    };

    var Position = function() {
        var target = $.popline.current.target,
            bar = $.popline.current.bar,
            positionType = $.popline.current.settings.position;

        var positions = {
            "iframe": {
                mouseup: function(event) {
                    var rect = $.popline.utils.selection().range().getBoundingClientRect();
                    var left = event.pageX - bar.width() / 2;
                    var scrollTop = parent.document.body.scrollTop || parent.document.documentElement.scrollTop;
					var header = $("#builderHeader", parent.document).outerHeight();
                    if (left < 0) {
                        left = 10;
                    }
                    var top = scrollTop + (rect.top + header) - bar.outerHeight() - 10;
                    if (top < scrollTop) {
                        top = scrollTop + rect.bottom + 10;
                    }
                    return {
                        left: left,
                        top: top
                    };
                },
                keyup: function() {
                    var left = null,
                        top = null;
                    var rect = $.popline.getRect(),
                        keyMoved = $.popline.current.isKeyMove();
                    if (typeof(rect) !== undefined) {
                        if (keyMoved === DOWN || keyMoved === RIGHT) {
                            left = rect.right - bar.width() / 2;
                        } else if (keyMoved === UP || keyMoved === LEFT) {
                            left = rect.left - bar.width() / 2;
                        }
                    }
					var header = $("#builderHeader", parent.document).outerHeight();
                    var scrollTop = parent.document.body.scrollTop || parent.document.documentElement.scrollTop;
                    top = scrollTop + (rect.top + header) - bar.outerHeight() - 10;
                    if (top < scrollTop) {
                        top = scrollTop + rect.bottom + 10;
                    }
                    return {
                        left: left,
                        top: top
                    };
                }
            },
            "fixed": {
                mouseup: function(event) {
                    var rect = $.popline.utils.selection().range().getBoundingClientRect();
                    var left = event.pageX - bar.width() / 2;
                    var scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
                    if (left < 0) {
                        left = 10;
                    }
                    var top = scrollTop + rect.top - bar.outerHeight() - 10;
                    if (top < scrollTop) {
                        top = scrollTop + rect.bottom + 10;
                    }
                    return {
                        left: left,
                        top: top
                    };
                },
                keyup: function() {
                    var left = null,
                        top = null;
                    var rect = $.popline.getRect(),
                        keyMoved = $.popline.current.isKeyMove();
                    if (typeof(rect) !== undefined) {
                        if (keyMoved === DOWN || keyMoved === RIGHT) {
                            left = rect.right - bar.width() / 2;
                        } else if (keyMoved === UP || keyMoved === LEFT) {
                            left = rect.left - bar.width() / 2;
                        }
                    }
                    var scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
                    top = scrollTop + rect.top - bar.outerHeight() - 10;
                    if (top < scrollTop) {
                        top = scrollTop + rect.bottom + 10;
                    }
                    return {
                        left: left,
                        top: top
                    };
                }
            },
            "relative": {
                mouseup: function(event) {
                    var left = event.pageX - bar.width() / 2;
                    if (left < 0) {
                        left = 10;
                    }
                    var scrollTop = $(document).scrollTop();
                    var top = event.pageY - bar.outerHeight() - parseInt(target.css('font-size'));
                    if (top < scrollTop) {
                        top = event.pageY + parseInt(target.css('font-size'));
                    }
                    return {
                        left: left,
                        top: top
                    };
                },
                keyup: function() {
                    var left = null,
                        top = null;
                    var rect = $.popline.getRect(),
                        keyMoved = $.popline.current.isKeyMove();
                    if (typeof(rect) !== undefined) {
                        var scrollTop = $(document).scrollTop();
                        if (keyMoved === DOWN || keyMoved === RIGHT) {
                            left = rect.right - bar.width() / 2;
                            top = scrollTop + rect.bottom - bar.outerHeight() - parseInt(target.css("font-size"));
                            if (top < scrollTop) {
                                top = scrollTop + rect.bottom + parseInt(target.css('font-size'));
                            }
                        } else if (keyMoved === UP || keyMoved === LEFT) {
                            left = rect.left - bar.width() / 2;
                            top = scrollTop + rect.top - bar.outerHeight();
                            if (top < scrollTop) {
                                top = scrollTop + rect.top + parseInt(target.css('font-size'));
                            }
                        }
                    }
                    return {
                        left: left,
                        top: top
                    };
                }
            }
        };

        return positions[positionType];
    };

    $.fn.popline = function(options) {
        var _arguments = arguments;
        var popline;
        this.each(function() {
            if (_arguments.length >= 1 && typeof(_arguments[0]) === "string" && $(this).data("popline")) {
                var func = $(this).data("popline")[_arguments[0]];
                if (typeof(func) === "function") {
                    func.apply($(this).data("popline"), Array.prototype.slice.call(_arguments, 1));
                }
            } else if (!$(this).data("popline")) {
                popline = new $.popline(options, this);
            }
        });

        if (!$(document).data("popline-global-binded")) {
            $(document).mouseup(function(event) {
                var _this = this;
                setTimeout((function() {
                    toggleBox.call(_this, event);
                }), 1);
            });
            $(document).data("popline-global-binded", true);
        }
    };

    $.popline = function(options, target) {
        this.settings = $.extend(true, {}, $.popline.defaults, options);
        this.setPosition(this.settings.position);
        this.target = $(target);
        this.init();
        $.popline.addInstance(this);
    };

    $.extend($.popline, {

        defaults: {
            zIndex: 300,
			linkList: {},
            mode: "edit",
            enable: null,
            disable: null,
            position: "iframe",
            keepSilentWhenBlankSelected: true
        },

        instances: [],
        current: null,

        prototype: {
            init: function() {
                this.bar = $("<ul class='popline' style='z-index:" + this.settings.zIndex + "'></ul>").appendTo(parent.$("body"));
                this.bar.data("popline", this);
                this.target.data("popline", this);
                var me = this;

                var isEnable = function(array, name) {
                    if (array === null) {
                        return true;
                    }
                    for (var i = 0, l = array.length; i < l; i++) {
                        var v = array[i];
                        if (typeof(v) === "string" && name === v) {
                            return true;
                        } else if ($.isArray(v)) {
                            if (isEnable(v, name)) {
                                return true;
                            }
                        }
                    }
                    return false;
                };


                var isDisable = function(array, name) {
                    if (array === null) {
                        return false;
                    }
                    for (var i = 0, l = array.length; i < l; i++) {
                        var v = array[i];
                        if (typeof(v) === "string" && name === v) {
                            return true;
                        } else if ($.isArray(v)) {
                            if ((v.length === 1 || !$.isArray(v[1])) && isDisable(v, name)) {
                                return true;
                            } else if (isDisable(v.slice(1), name)) {
                                return true;
                            }
                        }
                    }
                    return false;
                };

                var makeButtons = function(parent, buttons) {
                    for (var name in buttons) {
                        if (buttons.hasOwnProperty(name)) {
                            var button = buttons[name];
                            var mode = $.popline.utils.isNull(button.mode) ? $.popline.defaults.mode : button.mode;

                            if (mode !== me.settings.mode ||
                                !isEnable(this.settings.enable, name) ||
                                isDisable(this.settings.disable, name)) {
                                continue;
                            }
                            var $button = $("<li><span class='popline-btn'></span></li>");

                            $button.addClass("popline-button popline-" + name + "-button");

                            if (button.icon) {
                                $button.children(".popline-btn").append("<a class='" + button.iconClass + "' href='javascript:void(0);'><i class='" + button.icon + "'></i></a>");
                            }

                            if (button.text) {
                                $button.children(".popline-btn").append("<a href='javascript:void(0);'>" + button.text + "</a>");
                            }

                            if (button.bgColor) {
                                $button.css({
                                    'background-color': button.bgColor
                                });
                                $button.find("a").addClass('popline-color');
                            }

                            if ($.isFunction(button.beforeShow)) {
                                this.beforeShowCallbacks.push({
                                    name: name,
                                    callback: button.beforeShow
                                });
                            }

                            if ($.isFunction(button.afterHide)) {
                                this.afterHideCallbacks.push({
                                    name: name,
                                    callback: button.afterHide
                                });
                            }

                            $button.appendTo(parent);

                            if (button.buttons) {
                                var $subbar = $("<ul class='popline-subbar'></ul>");
                                $button.append($subbar);
                                makeButtons.call(this, $subbar, button.buttons);
                                $button.click(function(event) {
                                    var _this = this;
                                    if (!$(this).hasClass("popline-boxed")) {
                                        me.switchBar($(this), function() {
                                            $(_this).siblings("li").hide().end()
                                                .children(".popline-btn").hide().end()
                                                .children("ul").show().end();
                                        });
                                        event.stopPropagation();
                                    }
                                });
                            } else if ($.isFunction(button.action)) {
                                $button.click((function(button) {
                                    return function(event) {
                                        button.action.call(this, event, me);
                                    };
                                })(button));
                            }
                            $button.mousedown(function(event) {
                                if (!$(event.target).is("input")) {
                                    event.preventDefault();
                                }
                            });
                            $button.mouseup(function(event) {
                                event.stopPropagation();
                            });
                        }
                    }
                };

                makeButtons.call(this, this.bar, $.popline.buttons);
                this.target.on(targetEvent);

                this.bar.on("mouseenter", "li", function(event) {
                    if (!($(this).hasClass("popline-boxed"))) {
                        $(this).addClass("popline-hover");
                    }
                    event.stopPropagation();
                });
                this.bar.on("mouseleave", "li", function(event) {
                    if (!($(this).hasClass("popline-boxed"))) {
                        $(this).removeClass("popline-hover");
                    }
                    event.stopPropagation();
                });
            },

            show: function(options) {
                for (var i = 0, l = this.beforeShowCallbacks.length; i < l; i++) {
                    var obj = this.beforeShowCallbacks[i];
                    var $button = this.bar.find("li.popline-" + obj.name + "-button");
                    obj.callback.call($button, this);
                }
                this.bar.css('top', options.top + "px").css('left', options.left + "px").stop(true, true).fadeIn();
            },

            hide: function() {
                var _this = this;
                if (this.bar.is(":visible") && !this.bar.is(":animated")) {
                    this.bar.fadeOut(function() {
                        _this.bar.find("li").removeClass("popline-boxed").show();
                        _this.bar.find(".popline-subbar").hide();
                        _this.bar.find(".popline-textfield").hide();
                        _this.bar.find(".popline-btn").show();
                        for (var i = 0, l = _this.afterHideCallbacks.length; i < l; i++) {
                            var obj = _this.afterHideCallbacks[i];
                            var $button = _this.bar.find("li.popline-" + obj.name + "-button");
                            obj.callback.call($button, _this);
                        }
                    });
                }
            },

            destroy: function() {
                this.target.unbind(targetEvent);
                this.target.removeData("popline");
                this.target.removeData("lastKeyPos");
                this.bar.remove();
            },

            switchBar: function(button, hideFunc, showFunc) {
                if (typeof(hideFunc) === "function") {
                    var _this = this;
                    var position = parseInt(_this.bar.css('left')) + _this.bar.width() / 2;
                    _this.bar.animate({
                        opacity: 0,
                        marginTop: -_this.bar.height() + 'px'
                    }, function() {
                        hideFunc.call(this);
                        button.removeClass('popline-hover').addClass('popline-boxed').show();
                        _this.bar.css("margin-top", _this.bar.height() + "px");
                        _this.bar.css("left", position - _this.bar.width() / 2 + "px");
                        if (typeof(showFunc) === "function") {
                            _this.bar.animate({
                                opacity: 1,
                                marginTop: 0
                            }, showFunc);
                        } else {
                            _this.bar.animate({
                                opacity: 1,
                                marginTop: 0
                            });
                        }
                    });
                }
            },

            keepSilentWhenBlankSelected: function() {
                if (this.settings.keepSilentWhenBlankSelected && $.trim($.popline.utils.selection().text()) === "") {
                    return true;
                } else {
                    return false;
                }
            },

            isKeyMove: function() {
                var lastKeyPos = this.target.data('lastKeyPos');
                var currentRect = $.popline.boundingRect();
                if ($.popline.utils.isNull(lastKeyPos)) {
                    return null;
                }
                if (currentRect.top === lastKeyPos.top && currentRect.bottom !== lastKeyPos.bottom) {
                    return DOWN;
                }
                if (currentRect.bottom === lastKeyPos.bottom && currentRect.top !== lastKeyPos.top) {
                    return UP;
                }
                if (currentRect.right !== lastKeyPos.right) {
                    return RIGHT;
                }
                if (currentRect.left !== lastKeyPos.left) {
                    return LEFT;
                }
                return NONE;
            },

            setPosition: function(position) {
                //this.settings.position = position === "relative" ? "relative" : "fixed";
				this.settings.position = position;
            },

            beforeShowCallbacks: [],
            afterHideCallbacks: []

        },

        hideAllBar: function() {
            for (var i = 0, l = $.popline.instances.length; i < l; i++) {
                $.popline.instances[i].hide();
            }
        },

        addInstance: function(popline) {
            $.popline.instances.push(popline);
        },

        boundingRect: function(rects) {
            if ($.popline.utils.isNull(rects)) {
                rects = $.popline.utils.selection().range().getClientRects();
            }
            return {
                top: parseInt(rects[0].top),
                left: parseInt(rects[0].left),
                right: parseInt(rects[rects.length - 1].right),
                bottom: parseInt(rects[rects.length - 1].bottom)
            };
        },

        webkitBoundingRect: function() {
            var rects = $.popline.utils.selection().range().getClientRects();
            var wbRects = [];
            for (var i = 0, l = rects.length; i < l; i++) {
                var rect = rects[i];
                if (rect.width === 0) {
                    continue;
                } else if ((i === 0 || i === rects.length - 1) && rect.width === 1) {
                    continue;
                } else {
                    wbRects.push(rect);
                }
            }
            return $.popline.boundingRect(wbRects);
        },

        getRect: function() {
            if ($.popline.utils.browser.firefox || $.popline.utils.browser.opera || $.popline.utils.browser.ie) {
                return $.popline.boundingRect();
            } else if ($.popline.utils.browser.chrome || $.popline.utils.browser.safari) {
                return $.popline.webkitBoundingRect();
            }
        },

        utils: {
            isNull: function(data) {
                if (typeof(data) === "undefined" || data === null) {
                    return true;
                }
                return false;
            },
            randomNumber: function() {
                return Math.floor((Math.random() * 10000000) + 1);
            },
            trim: function(string) {
                return string.replace(/^\s+|\s+$/g, '');
            },
            browser: {
                chrome: navigator.userAgent.match(/chrome/i) ? true : false,
                safari: navigator.userAgent.match(/safari/i) && !navigator.userAgent.match(/chrome/i) ? true : false,
                firefox: navigator.userAgent.match(/firefox/i) ? true : false,
                opera: navigator.userAgent.match(/opera/i) ? true : false,
                ie: navigator.userAgent.match(/msie|trident\/.*rv:/i) ? true : false,
                webkit: navigator.userAgent.match(/webkit/i) ? true : false,
                ieVersion: function() {
                    var rv = -1; // Return value assumes failure.
                    var ua = navigator.userAgent;
                    var re = new RegExp("(MSIE |rv:)([0-9]{1,}[.0-9]{0,})");
                    if (re.exec(ua) !== null) {
                        rv = parseFloat(RegExp.$2);
                    }
                    return rv;
                }
            },
            findNodeWithTags: function(node, tags) {
                if (!$.isArray(tags)) {
                    tags = [tags];
                }
                while (node) {
                    if (node.nodeType !== 3) {
                        var index = $.inArray(node.tagName, tags);
                        if (index !== -1) {
                            return node;
                        }
                    }
                    node = node.parentNode;
                }
                return null;
            },
            selection: function() {
                return {
                        obj: function() {
                            return window.getSelection();
                        },
                        range: function() {
                            return window.getSelection().getRangeAt(0);
                        },
                        text: function() {
                            return window.getSelection().toString();
                        },
                        focusNode: function() {
                            return window.getSelection().focusNode;
                        },
                        select: function(range) {
                            window.getSelection().addRange(range);
                        },
                        empty: function() {
                            window.getSelection().removeAllRanges();
                        }
                    };
     
            }
        },

        addButton: function(button) {
            $.extend($.popline.buttons, button);
        },
        buttons: {}
    });
})(jQuery);
(function($) {
    "use strict";
    var colors = ['#F44336', '#9CBE5A', '#2196F3', '#00BCD4', '#009688', '#eb1515', '#FF9800', '#795548', '#9E9E9E', '#607D8B', '#000000','#FFFFFF'];

    function componentToHex(c) {
        var hex = c.toString(16);
        return hex.length === 1 ? "0" + hex : hex;
    }

    function colorToHex(color) {
        if (color.substr(0, 1) === '#') {
            return color;
        }
        var digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);
        var red = parseInt(digits[2]);
        var green = parseInt(digits[3]);
        var blue = parseInt(digits[4]);
        return '#' + componentToHex(red) + componentToHex(green) + componentToHex(blue);
    }
    var getColorButtons = function() {
        var buttons = {};
        $(colors).each(function(index, color) {
            buttons['color' + index] = {
                bgColor: color,
                text: '&nbsp;',
                action: function() {
                    document.execCommand('ForeColor', false, colorToHex($(this).css('background-color')));
                    $("font").replaceWith(function() {
                        var tag = $(this);
                        return $("<span/>").html(tag.html()).css("color", tag.attr("color"));
                    });
                }
            };
        });
        buttons.color100 = {
            iconClass: "",
            icon: "icon eraser",
            mode: "edit",
            action: function() {
                var focusNode = $.popline.utils.selection().focusNode().parentNode;
                if ($(focusNode).css('color')) {
                    $(focusNode).css('color', "");
                }
            }
        };
        return buttons;
    };
    $.popline.addButton({
        color: {
            iconClass: "",
            icon: "icon wysiwyg contrast",
            mode: "edit",
            buttons: getColorButtons()
        }
    });
})(jQuery);
(function($) {
    "use strict";
    var tags = ["P", "H1", "H2", "H3", "H4", "H5", "H6", "VOID"];
    var voidClass = "popline-el-void";
    var commonWrap = function(tag) {
        var range = window.getSelection().getRangeAt(0);
        var focusNode = window.getSelection().focusNode;
        var matchedNode = $.popline.utils.findNodeWithTags(focusNode, tags);
        tag = matchedNode && matchedNode.tagName === tag ? "VOID" : tag;
        var node = document.createElement(tag);
        var fragment = range.extractContents();
        removeEmptyTag(matchedNode);
        var textNode = document.createTextNode($(fragment).text());
        node.appendChild(textNode);
        range.insertNode(node);
        window.getSelection().selectAllChildren(node);
    };
    var wrap = function(tag) {
        commonWrap(tag);
    };
    var removeEmptyTag = function(node) {
        if ($.popline.utils.trim($(node).text()) === "") {
            $(node).remove();
        }
    };
    $.popline.addButton({
        blockFormat: {
            iconClass: "H",
            mode: "edit",
            icon: "icon wysiwyg type",
            buttons: {
                normal: {
                    text: "P",
                    textClass: "lighter",
                    action: function() {
                        wrap("P");
                    }
                },
                h1: {
                    text: "H1",
                    action: function() {
                        wrap("H1");
                    }
                },
                h2: {
                    text: "H2",
                    action: function() {
                        wrap("H2");
                    }
                },
                h3: {
                    text: "H3",
                    action: function() {
                        wrap("H3");
                    }
                },
                h4: {
                    text: "H4",
                    action: function() {
                        wrap("H4");
                    }
                },
                h5: {
                    text: "H5",
                    action: function() {
                        wrap("H5");
                    }
                },
                h6: {
                    text: "H6",
                    action: function() {
                        wrap("H6");
                    }
                }
            },
            afterHide: function(popline) {
                popline.target.find("void, ." + voidClass).contents().unwrap();
            }
        }
    });
})(jQuery);
(function($) {
    "use strict";
    var firefoxUnquote = function() {
        var selection = $.popline.utils.selection().obj();
        var focusNode = selection.focusNode;
        var node = $.popline.utils.findNodeWithTags(focusNode, 'BLOCKQUOTE');
        var startContainer = selection.anchorNode,
            startOffset = selection.anchorOffset,
            endContainer = selection.focusNode,
            endOffset = selection.focusOffset;
        $(node).children().unwrap();
        var newRange = document.createRange();
        newRange.setStart(startContainer, startOffset);
        newRange.setEnd(endContainer, endOffset);
        $.popline.utils.selection().empty();
        $.popline.utils.selection().select(newRange);
    };
    var quoteUtils = function() {
        if ($.popline.utils.browser.ie) {
            return {
                quote: function() {
                    document.execCommand('indent');
                },
                unquote: function() {
                    document.execCommand('outdent');
                }
            };
        } else {
            return {
                quote: function() {
                    document.execCommand('formatblock', false, 'BLOCKQUOTE');
                },
                unquote: function() {
                    if ($.popline.utils.browser.firefox) {
                        firefoxUnquote();
                    } else {
                        document.execCommand('formatblock', false, 'P');
                    }
                }
            };
        }
    };
    $.popline.addButton({
        blockquote: {
            iconClass: "fa fa-quote-left",
            mode: "edit",
            icon: "icon quote",
            action: function(event, popline) {
                var focusNode = $.popline.utils.selection().focusNode();
                var node = $.popline.utils.findNodeWithTags(focusNode, 'BLOCKQUOTE');
                if (node) {
                    quoteUtils().unquote();
                } else {
                    quoteUtils().quote();
                }
            }
        }
    });
})(jQuery);
(function($) {
    "use strict";
    $.popline.addButton({
        bold: {
            iconClass: "fa fa-bold",
            mode: "edit",
            icon: "icon wysiwyg bold",
            action: function() {
                document.execCommand("bold");
            }
        },
        italic: {
            iconClass: "fa fa-italic",
            mode: "edit",
            icon: "icon wysiwyg italic",
            action: function() {
                document.execCommand("italic");
            }
        },
        strikethrough: {
            iconClass: "fa fa-strikethrough",
            mode: "edit",
            icon: "icon wysiwyg strikethrough",
            action: function() {
                document.execCommand("strikethrough");
                $("strike").replaceWith(function() {
                    var tag = $(this);
                    return $("<span/>").html(tag.html()).css("textDecoration", "line-through");
                });
            }
        },
        underline: {
            iconClass: "fa fa-underline",
            mode: "edit",
            icon: "icon wysiwyg underline",
            action: function() {
                document.execCommand("underline");
            }
        }
    });
})(jQuery);
(function($) {
    "use strict";
    var pattern = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i;
    var selectionIsEmail = function() {
        var result = false;
        var text = $.popline.utils.selection().text();
        if (pattern.test(text)) {
            result = true;
        }
        return result;
    };
    $.popline.addButton({
        email: {
            text: "@",
            mode: "view",
            beforeShow: function(popline) {
                if (!selectionIsEmail()) {
                    this.hide();
                }
            },
            action: function() {
                var $emailLink = $("<a></a>").attr("id", "popline-email-link").attr("href", "mailto:" + $.popline.utils.selection().text());
                $emailLink.click(function(event) {
                    event.stopPropagation();
                });
                $(this).find(".btn").after($emailLink);
                $emailLink[0].click();
                $emailLink.remove();
            }
        }
    });
})(jQuery);
(function($) {
    "use strict";
    var removeRedundantParagraphTag = function(popline, align) {
        if ($.popline.utils.browser.ie) {
            var $paragraphs = popline.target.find(".content-" + align + "");
            $paragraphs.each(function(i, obj) {
                if (obj.childNodes.length === 1 && obj.childNodes[0].nodeType === 3 && !/\S/.test(obj.childNodes[0].nodeValue)) {
                    $(obj).remove();
                }
            });
        }
    };
    $.popline.addButton({
        justify: {
            iconClass: "fa fa-align-justify",
            mode: "edit",
            icon: "icon reorder",
            buttons: {
                justifyLeft: {
                    iconClass: "fa fa-align-left",
                    icon: "icon wysiwyg align left",
                    action: function(event, popline) {
                        var el = $.popline.utils.selection().focusNode().parentElement;
                        $(el).removeClass("content-center content-right content-full");
                        removeRedundantParagraphTag(popline, "left");
                    }
                },
                justifyCenter: {
                    iconClass: "fa fa-align-center",
                    icon: "icon wysiwyg align center",
                    action: function(event, popline) {
                        var el = $.popline.utils.selection().focusNode().parentElement;
                        $(el).removeClass("content-right content-full");
                        $(el).addClass("content-center");
                        removeRedundantParagraphTag(popline, "center");
                    }
                },
                justifyRight: {
                    iconClass: "fa fa-align-right",
                    icon: "icon wysiwyg align right",
                    action: function(event, popline) {
                        var el = $.popline.utils.selection().focusNode().parentElement;
                        $(el).removeClass("content-center content-full");
                        $(el).addClass("content-right");
                        removeRedundantParagraphTag(popline, "right");
                    }
                },
                justifyRFull: {
                    iconClass: "fa fa-align-justify",
                    icon: "icon wysiwyg align justify",
                    action: function(event, popline) {
                        var el = $.popline.utils.selection().focusNode().parentElement;
                        $(el).removeClass("content-center content-right");
                        $(el).addClass("content-full");
                        removeRedundantParagraphTag(popline, "full");
                    }
                },
            }
        }
    });
})(jQuery);
(function($) {
    "use strict";
    var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    var protocolPattern = /.+:\/\//;
    var selectionIsLink = function() {
        var result = false;
        var focusNode = $.popline.utils.selection().focusNode();
        if ($.popline.utils.browser.webkit || $.popline.utils.browser.ie) {
            result = $.popline.utils.findNodeWithTags(focusNode, 'A');
        } else if ($.popline.utils.browser.firefox) {
            result = firefoxSelectionIsLink();
        }
        return result;
    };
    var firefoxSelectionIsLink = function() {
        var selection = window.getSelection();
        var range = window.getSelection().getRangeAt(0);
        var fragment = range.cloneContents();
        if (fragment.childNodes.length === 1 && fragment.firstChild.tagName === "A") {
            return true;
        }
        return $.popline.utils.findNodeWithTags(selection.focusNode, 'A');
    };
    var buildTextField = function(popline, button) {
        if (button.find(":text").length === 0) {
            var $textField = $("<input type='text' />");
            $textField.addClass("popline-textfield");
            $textField.attr("placeholder", "http://");
            $textField.keyup(function(event) {
                if (event.which === 13) {
                    $(this).blur();
                    var text = $(this).val();
                    if (!protocolPattern.test(text)) {
                        text = "http://" + text;
                    }
                    if (pattern.test(text)) {
                        $.popline.utils.selection().empty();
                        $.popline.utils.selection().select(button.data('selection'));
                        document.execCommand("createlink", false, text);
                    } else {
                        $.popline.utils.selection().select(button.data('selection'));
                    }
                    popline.hide();
                }
            });
            $textField.mouseup(function(event) {
                event.stopPropagation();
            });
            button.append($textField);
        }
    };
    $.popline.addButton({
        link: {
            iconClass: "fa fa-link",
            mode: "edit",
            icon: "icon url",
            beforeShow: function(popline) {
                var $a = this.find("a .icon");
                if (selectionIsLink()) {
                    $a.removeClass("url").addClass("unlink");
                } else {
                    $a.removeClass("unlink").addClass("url");
                }
                if (!this.data("click-event-binded")) {
                    this.click(function(event) {
                        var $_this = $(this);
                        if (selectionIsLink()) {
                            document.execCommand("unlink");
                            $_this.find("a .icon").removeClass("unlink").addClass("url");
                        } else {
                            if (!$_this.hasClass("popline-boxed")) {
                                buildTextField(popline, $_this);
                                $_this.data('selection', $.popline.utils.selection().range());
                                $.popline.utils.selection().empty();
                                popline.switchBar($_this, function() {
                                    $_this.siblings("li").hide().end().children(":text").show().end();
                                }, function() {
                                    $_this.children(":text").focus();
                                });
                                event.stopPropagation();
                            }
                        }
                    });
                    this.data("click-event-binded", true);
                }
            },
            afterHide: function() {
                this.find(":text").val('');
            }
        }
    });
})(jQuery);
(function($) {
    "use strict";
    var firefoxCleanupCheck = function(tag) {
        var focusNode = $.popline.utils.selection().focusNode();
        var node = $.popline.utils.findNodeWithTags(focusNode, tag);
        return node ? true : false;
    };
    var addPTag;
    var firefoxCleanup = function(addPTag) {
        var node;
        $.popline.current.target.find("br[type=_moz]").parent().remove();
        if (addPTag) {
            document.execCommand("formatblock", false, "P");
            var selection = $.popline.utils.selection().obj();
            if (selection.anchorNode && (node = selection.anchorNode.parentNode.previousSibling) && node.tagName === "BR") {
                node.remove();
            }
            if (selection.focusNode && (node = selection.focusNode.parentNode.nextSibling) && node.tagName === "BR") {
                node.remove();
            }
            addPTag = null;
        }
    };
    $.popline.addButton({
        orderedList: {
            iconClass: "fa fa-list-ol",
            mode: "edit",
            icon: "icon ordered list",
            action: function() {
                if ($.popline.utils.browser.firefox) {
                    addPTag = firefoxCleanupCheck('OL');
                }
                document.execCommand("InsertOrderedList");
                if ($.popline.utils.browser.firefox) {
                    firefoxCleanup(addPTag);
                }
                var focusNode = $.popline.utils.selection().focusNode().parentNode;
                $(focusNode).parent().addClass("yoyo ordered list");
            }
        },
        unOrderedList: {
            iconClass: "fa fa-list-ul",
            mode: "edit",
            icon: "icon unordered list",
            action: function() {
                if ($.popline.utils.browser.firefox) {
                    addPTag = firefoxCleanupCheck('UL');
                }
                document.execCommand("InsertUnorderedList");
                if ($.popline.utils.browser.firefox) {
                    firefoxCleanup(addPTag);
                }
                var focusNode = $.popline.utils.selection().focusNode().parentNode;
                $(focusNode).parent().addClass("yoyo list");
            }
        }
    });
})(jQuery);