(function($, window, document, undefined) {
    "use strict";
    var pluginName = 'Builder';
    var bMode = "design";
    var $activeElement;
    var $activeSection;
    var $activeRow;

    var wraps = {
        sectionWrap: '<a data-redonly="true" class="grid-insert"><i class="icon plus"></i></a>',
    };

    var cssProp = [
        "paddingTop",
        "paddingBottom",
        "paddingLeft",
        "paddingRight",
        "marginTop",
        "marginBottom",
        "marginLeft",
        "marginRight",
        "borderTopLeftRadius",
        "borderTopRightRadius",
        "borderBottomLeftRadius",
        "borderBottomRightRadius",
        "boxShadow",
        "background",
        "backgroundColor",
        "backgroundAttachment",
        "backgroundImage",
        "backgroundPosition",
        "backgroundRepeat",
        "backgroundSize",
        "filter",
        "borderTopWidth",
        "borderBottomWidth",
        "borderLeftWidth",
        "borderRightWidth",
        "borderTopStyle",
        "borderBottomStyle",
        "borderLeftStyle",
        "borderRightStyle",
        "borderTopColor",
        "borderBottomColor",
        "borderLeftColor",
        "borderRightColor"
    ];

    /**
     * Description
     * @method Plugin
     * @param {} element
     * @param {} options
     * @return 
     */
    function Plugin(element, options) {

        this.element = element;
        this._name = pluginName;
        this._defaults = $.fn.Builder.defaults;
        this.options = $.extend({}, this._defaults, options);

        this.init();

    }

    $.extend(Plugin.prototype, {
        /**
         * Description
         * @method init
         * @return 
         */
        init: function() {
            this._initBuilder();
            this.bindEvents();
            this._editSection();
            this._toolbar();
            this._animations();

        },

        /**
         * Description
         * @method _initBuilder
         * @return 
         */
        _initBuilder: function() {
            var plugin = this;

            $(this.element).find('.row').each(function() {
                var id = plugin.makeid();
                $(this).prepend(wraps.sectionWrap).attr("data-id", id);
            });

            $("#canvas-helper, #element-helper, #section-helper").draggable({
                handle: ".handle"
            });

            $('[data-content]').popup({
                variation: "mini inverted",
                inline: true,
            });

            $("#element-helper").on('click', "[data-tab]", function(event) {
                var tab_id = $(this).attr('data-tab');

                $('#element-helper [data-tab]').removeClass('active');
                $('#element-helper .yoyo.tab').removeClass('active');

                $(this).addClass('active');
                $("#" + tab_id).addClass('active');
                event.preventDefault();
            });

            $('.rangers').each(function() {
                var set = $(this).data('ranger');
                $(this).asRange({
                    min: set.from,
                    max: set.to,
                    step: set.step,
                    skin: set.skin,
                    range: set.range,
                    tip: {
                        active: 'onMove'
                    },
                    format: function(value) {
                        return value + ' ' + set.format;
                    },
                });
            });

            $('.reswitch').on('click', 'a.action', function() {
                $("#builder").removeClass('tabletview phoneview');
                var mode = $(this).data('mode');
                $('.reswitch').find('.icon.primary').removeClass('primary');

                switch (mode) {
                    case "screen":
                        $("#builder").animate({
                            width: '100%'
                        }, 1000, function() {
                            $(this).removeClass('tabletview phoneview');
                            $(this).removeAttr("style");
                        });
                        break;

                    case "tablet":
                        $("#builder").addClass('tabletview');
                        $("#builder").animate({
                            width: '1024px'
                        }, 1000);
                        break;

                    case "phone":
                        $("#builder").addClass('phoneview');
                        $("#builder").animate({
                            width: '480px'
                        }, 1000);
                        break;
                }
                $(".icon", this).addClass('primary');
            });

            //Save page
            $('#saveAll').on('click', function() {
                var $button = $(this);
                $button.addClass("loading");
                $(plugin.element).children().find(".grid-insert").remove();

                $(plugin.element).find('.ws-layer').each(function() {
                    $(this).removeClass("active");
                    $(this).removeAttr("role aria-labelledby");
                });

                $(plugin.element).find('.ws-layer').find("*").each(function() {
                    $(this).removeAttr("data-redactor-span data-redactor-style-cache");
                });

                var id = $('.uitem', plugin.element).attr('id');
                var mode = $('.uitem', plugin.element).attr('data-type');
                var raw = $(plugin.element).html();

                $("#tempData").html($(".ucontent", plugin.element).html());
                $("#tempData").children().removeAttr('data-id');

                var content = $("#tempData").html();
                var image = $(".uimage", plugin.element).css("backgroundImage");
                image = image.replace(/(url\(|\)|'|")/gi, '');
                var color = $(".uimage", plugin.element).css("backgroundColor");
				var attr = $(".ucontent", plugin.element).attr("class");
				attr = attr.replace('ucontent', '');
				attr = $.trim(attr);

                $.post(plugin.options.purl + 'controller.php', {
                    action: "saveSlideData",
                    id: id.replace('item_', ''),
                    html: content,
                    html_raw: raw,
                    image: image,
                    color: color,
                    mode: mode,
					attr: attr,
                    slidename: plugin.options.slidename
                }, function(json) {
                    $.notice(json.message, {
                        autoclose: 12000,
                        type: json.type,
                        title: json.title
                    });
                    $button.removeClass('loading');
                    $(".row", plugin.element).prepend(wraps.sectionWrap);
                }, "json");
            });
        },

        /**
         * Description
         * @method bindEvents
         * @return 
         */
        bindEvents: function() {
            this._onEvents();
            this._editElements();
            this._editCanvas();
        },

        /**
         * Description
         * @method _toolbar
         * @return 
         */
        _toolbar: function() {
            var plugin = this;
            $("#builderHeader").on('click', '.is_size', function() {
                var size = $(this).data("mode");
                $("#builderHeader").find(".is_size .icon").removeClass("primary");
                $(this).children().addClass("primary");
                if ($activeSection.length) {
                    if (size === "shrink") {
                        $activeSection.parent(".columns").addClass("shrink");
                        $("#builderHeader").find(".is_align").removeClass("disabled");
                    } else {
                        $activeSection.parent(".columns").removeClass("shrink");
                        $("#builderHeader").find(".is_align").addClass("disabled");
                    }
                }
            });

            $("#builderHeader").on('click', '.is_align', function() {
                var align = $(this).data("mode");
                $("#builderHeader").find(".is_align .icon").removeClass("primary");
                $(this).children().addClass("primary");
                if ($activeSection.length) {
                    $activeSection.closest(".row").removeClass(function(index, className) {
                        return (className.match(/(^|\s)align-\S+/g) || []).join(' ');
                    });
                    $activeSection.closest(".row").addClass(align);
                }
            });

            $("#builderHeader").on('click', '.is_position', function() {
                var align = $(this).data("mode");
                if ($(this).children().is(".primary")) {
                    $(this).children().removeClass("primary");
                    $(plugin.element).find(".ucontent").removeClass(function(index, className) {
                        return (className.match(/(^|\s)align-\S+/g) || []).join(' ');
                    });
                } else {
                    $("#builderHeader").find(".is_position .icon").removeClass("primary");
                    $(this).children().addClass("primary");
                    $(plugin.element).find(".ucontent").removeClass(function(index, className) {
                        return (className.match(/(^|\s)align-\S+/g) || []).join(' ');
                    });

                    $(plugin.element).find(".ucontent").addClass(align);

                }
            });

            //enter edit mode
            $("#builderAside").on('click', '.editor', function() {
                bMode = "edit";
                $(plugin.element).find("a.grid-insert").addClass("hide");
                $("#builderHeader").find(".button").addClass("disabled");
                $("#builderAside").find(".button").addClass("disabled");
                $("#builderAside").find(".button.save").removeClass("disabled");
                if ($activeSection.length) {
                    $(plugin.element).find(".ws-layer:not(.active)").addClass("editing");
                    $(plugin.element).find(".ws-layer").closest(".row").attr("data-mode", "readonly");
                    $activeSection.closest(".row").removeAttr("data-mode");
                }
            });

            //exit edit mode
            $("#builderAside").on('click', '.save', function() {
                bMode = "design";
                $(plugin.element).find("a.grid-insert").removeClass("hide");
                $(plugin.element).find(".row").removeAttr("data-mode");
                $(plugin.element).find(".ws-layer").removeClass("editing");
                $("#builderAside").find(".button").removeClass("disabled");
				$("#builderHeader").find(".button").removeClass("disabled");
				/*
                $("#builderHeader").find(".reswitch .button").removeClass("disabled");
                $("#builderHeader").find("#play.button").removeClass("disabled");
                $("#builderHeader").find(".source .button").removeClass("disabled");*/
                $(this).addClass("disabled");
            });

            //edit html
            $("#builderAside").on('click', '.html', function() {
                bMode = "design";
                var html = $activeSection.closest(".row").html();
				$("#tempData").html(html);
				$("#tempData").find(".grid-insert").remove();
                $("#tempHtml").val($("#tempData").html());
				
                plugin._formatSource($("#tempHtml"));

                $("#editSource").modal('setting', 'onApprove', function() {
                    $activeSection.closest(".row").html($("#tempHtml").val()).prepend(wraps.sectionWrap);
                }).modal('setting', 'onHidden', function() {
                    $("#tempHtml").val("");
                    $("#tempData").html("");
                }).modal('show');
            });

            //edit canvas html
            $("#builderAside").on('click', '.editHtml', function() {
                bMode = "design";
                var element = $(plugin.element).find(".ucontent");
				$("#tempData").html(element.html());
				$("#tempData").find(".grid-insert").remove();
				
                $("#tempHtml").val($("#tempData").html());
                plugin._formatSource($("#tempHtml"));

                $("#editSource").modal('setting', 'onApprove', function() {
                    element.html($("#tempHtml").val());
					$(element).find('.row').each(function() {
						$(this).prepend(wraps.sectionWrap);
					});
                }).modal('setting', 'onHidden', function() {
                    $("#tempHtml").val("");
                    $("#tempData").html("");
                }).modal('show');
            });

            //insert grid
            $(plugin.element).on('click', '.grid-insert', function() {
                $activeRow = $(this).parent(".row");
                $("#row-helper").css("top", $(this).offset().top + 100);

                if ($("#row-helper").is(".hidden")) {
                    $('#row-helper').transition('fade in');
                }
            });

            $("#row-helper").on('click', '.column a', function() {
                plugin.makeRows($(this).data("row"));
                $("#row-helper").transition("fade out");
            });

            $("#row-helper").on('click', 'a.close', function() {
                $("#row-helper").transition("fade out");
            });

            //insert element
            $(this.element).on('click', '.is_empty', function() {
                bMode = "design";
                $activeElement = $(this);
                $(plugin.element).find(".row").attr("data-mode", "readonly");

                if ($("#element-helper").is(".hidden")) {
                    $('#element-helper').transition('scale');
                }
                plugin._prepareButton();
                plugin._prepareText();
                plugin._prepareImage();
                plugin._prepareIcon();
            });

            $("#element-helper").on('click', '.insert', function(event) {
                var el = $("#element-helper").find(".tbutton.active");
                var type = el.data("type");

                switch (type) {
                    case "button":
                        plugin._insertButton();
                        break;

                    case "icon":
                        plugin._insertIcon();
                        break;

                    case "image":
                        plugin._insertImage();
                        break;

                    default:
                        plugin._insertText();
                        break;
                }

                $activeElement.removeClass("is_empty");

                event.preventDefault();
            });

            $("#element-helper").on('click', 'a.close-styler', function(event) {
                $('#element-helper').transition('fade out');
                $(plugin.element).find(".row").removeAttr("data-mode");
                event.preventDefault();
            });

            //delete section
            $("#builderAside").on('click', '.is_trash', function() {
                if ($activeSection.length) {
                    var parent = $activeSection.parent();
                    $activeSection.transition({
                        animation: 'slide up out',
                        duration: '.6s',
                        onComplete: function() {
                            $activeSection.remove();
                            $activeSection = '';
                            $("#builderAside").find(".is_edit").addClass("disabled");
                            $("#builderHeader").find(".is_edit").addClass("disabled");
                            $("#builderHeader").find(".is_size").addClass("disabled");
                            $("#builderHeader").find(".is_align").addClass("disabled");
                            if (parent.children().length === 0) {
                                parent.addClass("is_empty");
                            }
                        }
                    });
                }
            });
        },

        /**
         * Description
         * @method _animations
         * @return 
         */
        _animations: function() {
            var plugin = this;
            //Animation
            $("#anipack").on('click', '.item', function() {
                var selected = $(this).data("value");
                $('#anipack').find(".selected.item").removeClass("selected");
                $(this).addClass("selected");

                if ($activeSection.length) {
                    $activeSection.attr("data-animation", selected);
                    var type = $activeSection.attr('data-animation');
                    var time = $activeSection.attr('data-duration');
                    var delay = $activeSection.attr('data-delay');

                    if (!type) {
                        type = selected;
                    }
                    if (!time) {
                        time = 1500;
                    }

                    if (!delay) {
                        delay = 500;
                    }

                    if (selected === "none") {
                        $activeSection.attr("data-animation", '');
                    } else {
                        var values = "animate " + type;
                        $activeSection.addClass(values).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                            setTimeout(function() {
                                $activeSection.removeClass(values);
                            }, 500);
                        });
                    }
                }
            });

            // Play Animation
            $("#play").on('click', function() {
                $.each($(plugin.element).find('.ws-layer'), function() {
                    var $this = $(this);
                    $(this).removeClass("active");
                    var type = $(this).attr('data-animation');
                    if (type) {
                        var values = "animate " + type;
                        $this.addClass(values).on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                            setTimeout(function() {
                                $this.removeClass(values);
                            }, 500);
                        });
                    }
                });
            });

            //Animation time
            $("#duration").on('change', function() {
                var time = $(this).val().replace(/[^0-9\.]/g, '');
                var type = $activeSection.attr('data-animation');
                if ($activeSection.length) {
                    $activeSection.attr("data-duration", time);
                    if (type) {
                        var values = "animate " + type;
                        $activeSection.addClass(values).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                            setTimeout(function() {
                                $activeSection.removeClass(values);
                            }, 500);
                        });
                    }
                }
            });

            //Animation delay
            $("#delay").on('change', function() {
                var time = $(this).val().replace(/[^0-9\.]/g, '');
                var type = $activeSection.attr('data-animation');
                if ($activeSection.length) {
                    $activeSection.attr("data-delay", time);
                    if (type) {
                        var values = "animate " + type;
                        $activeSection.addClass(values).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                            setTimeout(function() {
                                $activeSection.removeClass(values);
                            }, 500);
                        });
                    }
                }
            });
        },

        /**
         * Description
         * @method _editElements
         * @return 
         */
        _editElements: function() {
            var plugin = this;
            $("#builderAside").on('click', '.element', function() {
                var css = $activeSection.css(cssProp);

                $("#section-helper input[name=marginTop]").asRange('set', parseInt(css.marginTop));
                $("#section-helper input[name=marginBottom]").asRange('set', parseInt(css.marginBottom));
                $("#section-helper input[name=marginLeft]").asRange('set', parseInt(css.marginLeft));
                $("#section-helper input[name=marginRight]").asRange('set', parseInt(css.marginRight));

                $('.rangers').on('asRange::change', function(event, el) {
                    switch (el.$element.prop('name')) {
                        case "marginTop":
                        case "marginBottom":
                        case "marginLeft":
                        case "marginRight":
                            $activeSection.css(el.$element.prop('name'), el.value);
                            break;
                    }
                });
                $(plugin.element).find(".row").attr("data-mode", "readonly");

                if ($("#section-helper").is(".hidden")) {
                    $('#section-helper').transition('scale');
                }
            });

            $("#section-helper").on('click', 'a.close-styler', function(event) {
                $('#section-helper').transition('fade out');
                $(plugin.element).find(".row").removeAttr("data-mode");
                event.preventDefault();
            });
        },

        /**
         * Description
         * @method _editCanvas
         * @return 
         */
        _editCanvas: function() {
            var plugin = this;
            $(".editCanvas").on('click', function() {
                $(plugin.element).find(".ws-layer.active").removeClass("active");
                var css = $(plugin.element).find(".ucontent").css(cssProp);
                $("#canvas-helper input[name=paddingTop]").asRange('set', parseInt(css.paddingTop));
                $("#canvas-helper input[name=paddingBottom]").asRange('set', parseInt(css.paddingBottom));

                $('.rangers').on('asRange::change', function(event, el) {
                    switch (el.$element.prop('name')) {
                        case "paddingTop":
                        case "paddingBottom":
                            $(plugin.element).find(".ucontent").css(el.$element.prop('name'), el.value);
                            break;
                    }
                });

                if ($("#canvas-helper").is(".hidden")) {
                    $('#canvas-helper').transition('scale');
                }
            });

            $("#canvas-helper").on('click', 'a.close-styler', function(event) {
                $('#canvas-helper').transition('fade out');
                event.preventDefault();
            });
        },

        /**
         * Description
         * @method _editSection
         * @return 
         */
        _editSection: function() {
            var plugin = this;
            $(this.element).on("click", ".ws-layer", function() {
                $activeSection = $(this);
                $(plugin.element).find(".ws-layer.active").removeClass("active");
                $activeSection.addClass("active");

                var time = $(this).attr('data-duration');
                var delay = $(this).attr('data-delay');
                var animation = $(this).attr('data-animation');

                $("#anipack").find(".selected").removeClass("selected");
                $("[data-dropdown='#anipack']").find("span").text(animation);
                $("#anipack").find(".item[data-value='" + animation + "']").addClass("selected");

                $("#duration").val(time);
                $("#delay").val(delay);

                if (bMode === "design") {
                    $("#builderAside").find(".is_edit").removeClass("disabled");
                    $("#builderHeader").find(".is_edit").removeClass("disabled");
                    $("#builderHeader").find(".is_size").removeClass("disabled");
                    $("#builderHeader").find(".is_size .icon").removeClass("primary");

                    if ($activeSection.closest(".row").is(".align-center")) {
                        $("#builderHeader").find("[data-mode='align-center']").children().addClass("primary");
                    } else if ($activeSection.closest(".row").is(".align-right")) {
                        $("#builderHeader").find("[data-mode='align-right']").children().addClass("primary");
                    } else {
                        $("#builderHeader").find("[data-mode='align-left']").children().addClass("primary");
                    }

                    if ($activeSection.parent(".columns").is(".shrink")) {
                        $("#builderHeader").find("[data-mode='shrink']").children().addClass("primary");
                        $("#builderHeader").find(".is_align").removeClass("disabled");
                    } else {
                        $("#builderHeader").find("[data-mode='full']").children().addClass("primary");
                        $("#builderHeader").find(".is_align").addClass("disabled");
                    }
                }
            });
        },

        /**
         * Description
         * @method _prepareButton
         * @return 
         */
        _prepareButton: function() {
            var plugin = this;
            $("#el_button .docolors").spectrum({
                showInput: true,
                showAlpha: true,
                move: function(color) {
                    var rgba = "transparent";
                    if (color) {
                        rgba = color.toRgbString();
                    }
                    switch ($(this).data('color')) {
                        case "bg":
                            $("#buttons .button").css("background-color", rgba);
                            break;
                        case "text":
                            $("#buttons .button").css("color", rgba);
                            break;
                        case "icon":
                            $("#buttons .button .icon").css("color", rgba);
                            break;
                    }
                },
                change: function(color) {
                    var rgba = "transparent";
                    if (color) {
                        rgba = color.toRgbString();
                    }
                    $(this).prev('input').val(rgba);
                    $(".icon", this).css("color", rgba);
                }
            });

            $("#el_button").on('click', '#buttons .button', function(e) {
                if ($(e.target).is('i')) {
                    var list = '<div class="row screen-block-6 half-gutters content-center">';
                    $.getJSON(plugin.options.url + '/builder/snippets/iconset.json')
                        .done(function(json) {
                            $.each(json.iconset, function(i, item) {
                                list += '<div class="column"><a class="yoyo basic icon fluid button" title="' + item.name + '"><i class="icon ' + item.code + '"></i></a></div>';
                            });
                            list += '</div>';
                            var template = '' +
                                '<div class="yoyo tiny modal" id="modalIcons">' +
                                '<div class="content" style="height:600px;overflow:auto;">' + list + '</div>' +
                                '</div>';
                            $(template).modal('setting', 'onShow', function() {
                                var $modal = $(this);
                                $("#modalIcons").on('click', '.column a', function() {
                                    var icon = $(this).html();
                                    $("#buttons i.icon").replaceWith(icon);
                                    $modal.modal('hide');
                                });
                            }).modal('setting', 'onHidden', function() {
                                $(this).remove();
                            }).modal('show');
                        });
                }
                $("#buttons .button").parent().removeClass('elactive').removeAttr("data-active");
                $(this).parent().addClass('elactive').attr("data-active", "true");
            });

            $("#el_button").on('change', 'input[name=btext]', function() {
                $("#buttons .button span").text($(this).val());
            });

            $("#el_button").on('change', 'input[name=burl]', function() {
                $("#buttons .button").attr("href", $(this).val());
            });
        },

        /**
         * Description
         * @method _insertButton
         * @return 
         */
        _insertButton: function() {
            var $active = $("#el_button").find('[data-active=true]');
            if ($active.length) {
                $("#tempData").html($active.html());
                $("#tempData span").replaceWith(function() {
                    return $(this).contents();
                });
                var button = '<div class="ws-layer" data-delay="0" data-duration="0" data-animation="" data-type="button">' + $("#tempData").html() + '</div>';
                $activeElement.append(button);
            } else {
                return false;
            }
        },

        /**
         * Description
         * @method _prepareImage
         * @return 
         */
        _prepareImage: function() {
            $("#el_image").on('click', '.item', function() {
                $("#el_image .item").removeClass('elactive').removeAttr("data-active");
                $(this).addClass('elactive').attr("data-active", "true");
            });
        },

        /**
         * Description
         * @method _insertImage
         * @return 
         */
        _insertImage: function() {
            var plugin = this;
            var $active = $("#el_image").find('[data-active=true]');
            if ($active.length) {
                var str = $active.data("src");
                var image = '<div class="ws-layer" data-delay="0" data-duration="0" data-animation="" data-type="image"><img src="' + plugin.options.surl + '/' + str + '"></div>';
                $activeElement.append(image);
            } else {
                return false;
            }
        },

        /**
         * Description
         * @method _prepareIcon
         * @return 
         */
        _prepareIcon: function() {
            $("#el_icon").on('click', '.button', function() {
                $("#el_icon .button").removeClass('primary').removeAttr("data-active");
                $(this).addClass('primary').attr("data-active", "true");
            });
        },

        /**
         * Description
         * @method _insertIcon
         * @return 
         */
        _insertIcon: function() {
            var $active = $("#el_icon").find('[data-active=true]');
            if ($active.length) {
                var html = $active.html();
                var image = '<div class="ws-layer" data-delay="0" data-duration="0" data-animation="" data-type="icon">' + html + '</div>';
                $activeElement.append(image);
            } else {
                return false;
            }
        },

        /**
         * Description
         * @method _prepareText
         * @return 
         */
        _prepareText: function() {
            $("#el_text").on('click', '.item', function() {
                $("#el_text .item").removeClass('elactive').removeAttr("data-active");
                $(this).addClass('elactive').attr("data-active", "true");
            });
        },

        /**
         * Description
         * @method _insertText
         * @return 
         */
        _insertText: function() {
            var $active = $("#el_text").find('[data-active=true]');
            if ($active.length) {
                var html = $active.html();
                var text = '<div class="ws-layer" data-delay="0" data-duration="0" data-animation="" data-type="text">' + html + '</div>';
                $activeElement.append(text);
            } else {
                return false;
            }
        },

        /**
         * Description
         * @method makeRows
         * @param {} row
         * @return 
         */
        makeRows: function(row) {
            var html = '';
            switch (row) {

                case 2:
                    html += '' +
                        '<div class="columns mobile-50 phone-100"></div>' +
                        '<div class="columns mobile-50 phone-100"></div>';
                    break;

                case 3:
                    html += '' +
                        '<div class="columns phone-100"></div>' +
                        '<div class="columns phone-100"></div>' +
                        '<div class="columns phone-100"></div>';
                    break;

                case 4:
                    html += '' +
                        '<div class="columns tablet-50 mobile-100 phone-100"></div>' +
                        '<div class="columns tablet-50 mobile-100 phone-100"></div>' +
                        '<div class="columns tablet-50 mobile-100 phone-100"></div>' +
                        '<div class="columns tablet-50 mobile-100 phone-100"></div>';
                    break;

                case 5:
                    html += '' +
                        '<div class="columns screen-60 tablet-60 mobile-50 phone-100"></div>' +
                        '<div class="columns screen-40 tablet-40 mobile-50 phone-100"></div>';
                    break;

                case 6:
                    html += '' +
                        '<div class="columns screen-40 tablet-40 mobile-50 phone-100"></div>' +
                        '<div class="columns screen-60 tablet-60 mobile-50 phone-100"></div>';
                    break;

                case 7:
                    html += '' +
                        '<div class="columns screen-70 tablet-70 mobile-50 phone-100"></div>' +
                        '<div class="columns screen-30 tablet-30 mobile-50 phone-100"></div>';
                    break;

                case 8:
                    html += '' +
                        '<div class="columns screen-30 tablet-30 mobile-50 phone-100"></div>' +
                        '<div class="columns screen-70 tablet-70 mobile-50 phone-100"></div>';
                    break;

                case 9:
                    html += '' +
                        '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>' +
                        '<div class="columns screen-60 tablet-60 mobile-100 phone-100"></div>' +
                        '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>';
                    break;

                case 10:
                    html += '' +
                        '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>' +
                        '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>' +
                        '<div class="columns screen-60 tablet-60 mobile-100 phone-100"></div>';
                    break;

                case 11:
                    html += '' +
                        '<div class="columns screen-60 tablet-60 mobile-100 phone-100"></div>' +
                        '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>' +
                        '<div class="columns screen-20 tablet-20 mobile-100 phone-100"></div>';
                    break;

                default:
                    html += '<div class="columns"></div>';
                    break;
            }

            var el = $("<div class=\"row\">" + html + "</div>").prepend(wraps.sectionWrap);
            el.insertAfter($activeRow);
            el.transition({
                animation: 'scale in',
                duration: '1s',
                onComplete: function() {
                    el.find(".columns").addClass("is_empty");
                    el.removeClass("transition visible");
                    el.removeAttr("style");
                }
            });
        },

        /**
         * Description
         * @method makeid
         * @return BinaryExpression
         */
        makeid: function() {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            for (var i = 0; i < 2; i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            var text2 = "";
            var possible2 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for (var k = 0; k < 5; k++) {
                text2 += possible2.charAt(Math.floor(Math.random() * possible2.length));
            }
            return text + text2;
        },

        /**
         * Description
         * @method _offEvents
         * @return 
         */
        _offEvents: function() {
            $(this.element).off('mouseenter mouseleave mouseover mouseout click');
        },

        /**
         * Description
         * @method _onEvents
         * @return 
         */
        _onEvents: function() {
            var plugin = this;
            $(this.element).on("click", function(event) {
                if ($(event.target).is(".uimage")) {
                    $(plugin.element).find(".ws-layer.active").removeClass("active");
                    $("#builderAside").find(".is_edit").addClass("disabled");
                    $("#builderHeader").find(".is_edit").addClass("disabled");
                    $("#builderHeader").find(".is_size").addClass("disabled");
                    $("#builderHeader").find(".is_size").children().removeClass("primary");
                }
            });
        },

        /**
         * Description
         * @method _formatSource
         * @param {} code
         * @return CallExpression
         */
        _formatSource: function(code) {
            var val = this._replaceTags(code);
            var el = $("<div></div>").html(val);
            this._prettifyHtml(el);
            //code.css('white-space', 'pre').val(this._undoTags(el.html()));
            code.val(this._undoTags(el.html()));

            return el.html();
        },

        /**
         * Description
         * @method _replaceTags
         * @param {} val
         * @return val
         */
        _replaceTags: function(val) {
            val = val.val();
            val = val.replace(/<html/i, '<div id="replace_html"');
            val = val.replace(/<\/html>/i, '</div>*-html-*');

            val = val.replace(/<head/i, '<div id="replace_head"');
            val = val.replace(/<\/head>/i, '</div>*-head-*');

            val = val.replace(/<body/i, '<div id="replace_body"');
            val = val.replace(/<\/body>/i, '</div>*-body-*');

            val = val.replace(/\t/ig, "").replace(/\n/ig, "").replace(/\s\s+/ig, "");
            val = val.replace(/(?:(?:\r\n|\r|\n)\s*){2}/gm, "");

            return val;
        },

        /**
         * Description
         * @method _undoTags
         * @param {} val
         * @return val
         */
        _undoTags: function(val) {
            val = val.replace('<div id="replace_html"', '<html');
            val = val.replace('</div>*-html-*', '</html>');

            val = val.replace('<div id="replace_head"', '<head');
            val = val.replace('</div>*-head-*', '</head>');

            val = val.replace('<div id="replace_body"', '<body');
            val = val.replace('</div>*-body-*', '</body>');
            return val;
        },

        /**
         * Description
         * @method _prettifyHtml
         * @param {} element
         * @return 
         */
        _prettifyHtml: function(element) {
            var plugin = this;
            var tbc = '';
            if (element.parent().length > 0 && element.parent().data('assign')) {
                element.data('assign', element.parent().data('assign') + 1);
            } else {
                element.data('assign', 1);
            }

            if (element.children().length > 0) {
                element.children().each(function() {
                    tbc = '';
                    for (var i = 0; i < $(this).parent().data('assign'); i++) {
                        tbc += '  ';
                    }
                    $(this).before('\n' + tbc);
                    $(this).prepend('  ');
                    $(this).append('\n' + tbc);
                    plugin._prettifyHtml($(this));

                });
            } else {
                tbc = '';
                for (var i = 0; i < element.parent().data('assign'); i++) {
                    tbc += '  ';
                }
                element.prepend('\n' + tbc);
            }
        },

        /**
         * Description
         * @method _htmlSpecialChars
         * @param {} string
         * @param {} quote_style
         * @param {} charset
         * @param {} double_encode
         * @return string
         */
        _htmlSpecialChars: function(string, quote_style, charset, double_encode) {
            var optTemp = 0,
                i = 0,
                noquotes = false;
            if (typeof quote_style === 'undefined' || quote_style === null) {
                quote_style = 2;
            }
            string = string.toString();
            if (double_encode !== false) {
                string = string.replace(/&/g, '&amp;');
            }
            string = string.replace(/</g, '&lt;').replace(/>/g, '&gt;');
            var OPTS = {
                'ENT_NOQUOTES': 0,
                'ENT_HTML_QUOTE_SINGLE': 1,
                'ENT_HTML_QUOTE_DOUBLE': 2,
                'ENT_COMPAT': 2,
                'ENT_QUOTES': 3,
                'ENT_IGNORE': 4
            };
            if (quote_style === 0) {
                noquotes = true;
            }
            if (typeof quote_style !== 'number') {
                quote_style = [].concat(quote_style);
                for (i = 0; i < quote_style.length; i++) {
                    if (OPTS[quote_style[i]] === 0) {
                        noquotes = true;
                    } else if (OPTS[quote_style[i]]) {
                        optTemp = optTemp || OPTS[quote_style[i]];
                    }
                }
                quote_style = optTemp;
            }
            if (quote_style && OPTS.ENT_HTML_QUOTE_SINGLE) {
                string = string.replace(/'/g, '&#039;');
            }
            if (!noquotes) {
                string = string.replace(/"/g, '&quot;');
            }
            return string;
        },
    });

    /**
     * Description
     * @method Builder
     * @param {} options
     * @return ThisExpression
     */
    $.fn.Builder = function(options) {
        this.each(function() {
            if (!$.data(this, pluginName)) {
                $.data(this, pluginName, new Plugin(this, options));
            }
        });
        return this;
    };

    $.fn.Builder.defaults = {
        editables: ["div", "p", "h1", "h2", "h3", "h4", "h5", "h6", "i", "span"],
        url: "",
        surl: "",
        purl: "",
        slidename: "",
        lang: {
            btnOk: "ok",
            btnCancel: "cancel",
            msgUndone: "Are you sure you want to restore it, this action can not be undone!",
            msgUrlError: "Invalid url detected!!!",
        }
    };

})(jQuery, window, document);