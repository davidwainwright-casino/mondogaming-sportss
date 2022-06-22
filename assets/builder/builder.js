(function($, window, document, undefined) {
    "use strict";
    var pluginName = 'Builder';
    var bMode = "design";
    var insertType = "section";
    var $activeSection;
    var $activeElement;
	var $activeBlock;
    var $activeColumn;
    var $activeRow;
	var $elementStyle;
	var $elementClass;
	var $sectionStyle;
    var radiusAll = null;
    var bProps = {
        "style": "solid",
        "width": "0px",
        "color": "#000000"
    };

    var sProps = {
        "horizontal": 0,
        "vertical": 0,
        "blur": 0,
        "spread": 0,
        "color": "rgba(0, 0, 0, .2)",
        "position": "none"
    };

    var gProps = {
        "deg": 0,
        "color1": "",
        "color2": "",
        "start": 0,
        "stop": 100
    };

    var fProps = {
        "grayscale": 0,
        "blur": 0,
        "brightness": 100,
        "contrast": 100,
        "hueRotate": 0,
        "opacity": 100,
        "invert": 0,
        "saturate": 100,
        "sepia": 0,
    };

    var iconSize = {
        "-10": "tiny",
        "-5": "small",
        0: "",
        5: "medium",
        10: "large",
        15: "big",
        20: "huge",
    };

    var rowSize = {
        space: "both",
        size: 0,
    };

    var wraps = {
        sectionWrap: '<div data-redonly="true" class="section-tool">' +
            '<a class="s-move" title="Move"><i class="icon move"></i></a>' +
            '<a class="s-edit" title="Edit"><i class="icon sliders horizontal"></i></a>' +
            '<a class="s-up" title="Move Up"><i class="icon chevron up"></i></a>' +
            '<a class="s-down" title="Move Down"><i class="icon chevron down"></i></a>' +
			'<a class="s-html" title="Html"><i class="icon code alt"></i></a>' +
            //'<a class="s-restore" title="Restore"><i class="icon refresh"></i></a>' +
            '<a class="s-delete" title="Delete"><i class="icon trash"></i></a>' +
            '</div>' +
            '<a data-redonly="true" class="grid-insert"><i class="icon plus"></i></a>',
        elementWrap: '<div data-redonly="true" class="column-tool">' +
            '<a class="c-move" title="Move"><i class="icon move"></i></a>' +
            '<a class="c-insert" title="Insert"><i class="icon plus"></i></a>' +
            '<a class="c-copy" title="Copy"><i class="icon copy"></i></a>' +
            '<a class="c-edit" title="Code"><i class="icon code"></i></a>' +
			'<a class="c-animate" title="Animate"><i class="icon spin circles"></i></a>' +
            '<a class="c-delete" title="Delete"><i class="icon trash"></i></a>' +
			'<a class="c-remove" title="Remove"><i class="icon delete"></i></a>' +
            '</div>',
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
            this._initSortableRows();
            this._initSortableColumns();
            this._builderMode();
            this._closeStyler("default");
            this._closeEditor("default");
			this._closeAnimator("default");
            this.bindEvents();
        },
		
        /**
         * Description
         * @method _initBuilder
         * @return 
         */
        _initBuilder: function() {
            var plugin = this;
            $(this.element).children().each(function() {
                if ($(this).not(".section").length) {
                    $(this).wrap("<div class=\"section\"></div>");
                }
            });

            $(this.element).children('.section').each(function() {
                var id = plugin.makeid();
                if ($(this).children().not(".section-tool")) {
                    $(this).prepend(wraps.sectionWrap).attr("data-id", id);
                    $(this).children(".section-tool").attr("data-id", id);
                }
				if ($(this).find('.columns').children("[data-weditable]")) {
                //if ($(this).find('.columns').children().not(".column-dummy")) {
                    $(this).find('.columns').children("[data-weditable]").wrap("<div class=\"column-dummy\"></div>");
                    $(this).find('.columns').children(".column-dummy").prepend(wraps.elementWrap);
                }
            });

            $(this.element).find("[data-copy=false]").prev(".column-tool").find(".c-copy").remove();
            $(this.element).find("[data-insert=false]").prev(".column-tool").find(".c-insert").remove();
			$(this.element).find("[data-animate=false]").prev(".column-tool").find(".c-animate").remove();
            $(this.element).find("[data-weditable] .yoyo.button").attr("data-type", "button");
            $(this.element).find("[data-weditable] i.icon").filter(function() {
                return $(this).parent().is(":not(.yoyo.button)");
            }).attr("data-type", "icon");
            $('[data-wplugin-id]', this.element).each(function() {
                var id = $(this).attr('data-wplugin-id');
                $("#plugins").find("[data-plugin-plugin_id=" + id + "]").closest('.column').remove();
            });

            $('[data-wmodule-alias]', this.element).each(function() {
                var id = $(this).attr('data-wmodule-alias');
                $("#modules").find("[data-module-alias=" + id + "]").closest('.column').remove();
            });
            $("#style-helper, #element-helper, #section-helper, #animation-helper").draggable({
                handle: ".handle"
            });

            $('.optiscroll').optiscroll();
            $('select').yoyoSelect();

            $('#blockFilter').on('change', function() {
                $('#builder-elements .item').hide();
                $('#builder-elements .item a[data-element=' + $(this).val() + ']').parent().show();

                if ($(this).val() === 'all') {
                    $('#builder-elements .item').show();
                }
            });
            $('.yoyo.accordion').accordion({
                openSingle: true
            });
            $("#style-helper").on('click', "[data-tab]", function(event) {
                var tab_id = $(this).attr('data-tab');

                $('#style-helper [data-tab]').removeClass('active');
                $('#style-helper .yoyo.tab').removeClass('active');

                $(this).addClass('active');
                $("#" + tab_id).addClass('active');
                event.preventDefault();
            });

            $("#section-helper").on('click', ".yoyo.tabs a", function(event) {
                var tab_id = $(this).attr('yoyo-tab');
                $('#section-helper .yoyo.tab').removeClass('active');
                $('#section-helper .yoyo.tabs li').removeClass('active');

                $(this).parent().addClass('active');
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
            $(".yoyo.colorpicker").spectrum({
                showInput: false,
                allowEmpty: false,
                showAlpha: true,
                showPalette: true,
                flat: true,
                showInitial: true,
                preferredFormat: "rgb",
                showSelectionPalette: false,
                maxSelectionSize: 6,
                palette: [
                    ['#61BD6D', '#1ABC9C', '#54ACD2', '#2C82C9', '#9365B8', '#475577']
                ]
            }).on("move.spectrum", function(event, color) {
                switch ($(event.target).attr("id")) {
                    case "borderColor":
                        bProps.color = color.toRgbString();
                        plugin._buildBorders();
                        break;

                    case "shadowColor":
                        sProps.color = color.toRgbString();
                        plugin._buildShadow();
                        break;

                    case "gradColorStart":
                        gProps.color1 = color.toRgbString();
                        plugin._buildGradient();
                        break;

                    case "gradColorStop":
                        gProps.color2 = color.toRgbString();
                        plugin._buildGradient();
                        break;

                    case "backgroundColor":
                        plugin._buildBgColor(color.toRgbString());
                        break;
                }
            });
			
            $('.reswitch').on('click', 'a.action', function() {
                $("#builder").removeClass('tabletview phoneview');
                var mode = $(this).data('mode');
                $('.reswitch').find('.icon.primary').removeClass('primary');

                switch (mode) {
                    case "screen":
                        $("#builder").animate({
                            width: '100%'
                        },1000, function() {
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
                            width: '768px'
                        }, 1000);
                        break;
                }
                $(".icon", this).addClass('primary');
            });
			
			//Save page
			$('#saveAll').on('click', function() {
				$("#builder").addClass('loading');
				$("#tempData").html($(plugin.element).html());
				$("#tempData").find('[contenteditable]').removeAttr('contenteditable');
				$("#tempData").find('style').remove();
				$("#tempData").children().each(function() {
					$(this).removeClass("ui-draggable");
					$(this).removeAttr("data-id");
					$(this).children('.section-tool').remove();
					$(this).children('.grid-insert').remove();
				});
				
				$("#tempData").find('.live').removeClass("live");	
				$("#tempData").find('.column-tool').remove();
				$("#tempData").find('.columns').each(function() {
					$(this).removeClass("ui-sortable");
					$(this).find('.column-dummy').children().unwrap();
				});
				
				$("#tempData").find('[data-wplugin-id]').each(function() {
					var palias = $(this).attr('data-wplugin-alias');
					var pid = $(this).attr('data-wplugin-id');
					var plugin_id = $(this).attr('data-wplugin-plugin_id');
					$(this).replaceWith("%%" + palias + "|plugin|" + plugin_id + "|" + pid + "%%");
				});
				
				$("#tempData").find('[data-wmodule-id]').each(function() {
					var malias = $(this).attr('data-wmodule-alias');
					var mid = $(this).attr('data-wmodule-id');
					var module_id =$(this).attr('data-wmodule-module_id');
					$(this).replaceWith("%%" + malias + "|module|" + module_id + "|" + mid + "%%");
				});

				$("#tempData").find('[data-wuplugin-id]').each(function() {
					var pid = $(this).attr('data-wuplugin-id');
					$(this).replaceWith("%%user|uplugin|0|" + pid + "%%");
				});
				
				var html = $("#tempData").html();
				var langall = ($("input[name=langall]").is(':checked')) ? "all" : $.url().segment(-2);
		
				$.ajax({
					url: plugin.options.url + '/controller.php',
					dataType: "json",
					method: "POST",
					data: {
						action: "processBuilder",
						content: html,
						id: $.url().segment(-1),
						lang: langall,
						pagename: plugin.options.pagename
					}
				}).done(function(json) {
					$.notice(json.message, {
						autoclose: 12000,
						type: json.type,
						title: json.title
					});
					$("#builder").removeClass('loading');
				});
			});
			
            $(".elementRestore").on('click', function() {
				if($elementStyle !== "undefined") {
					$activeElement.attr("style", $elementStyle);
				}
				if($elementClass !== "undefined") {
					$activeElement.attr("class", $elementClass);
				}
            });
			
            $(".sectionRestore").on('click', function() {
				if($sectionStyle !== "undefined") {
					$activeSection.attr("style", $sectionStyle);
				}
            });
        },
		
        /**
         * Description
         * @method bindEvents
         * @return 
         */
        bindEvents: function() {
            this._onEvents();
            this._insertPrepare();
            this._insert();
            this._editElements();
            this._editSection();
            this._editHtml();
			this._editSectionHtml();
            this._deleteBlock();
			this._removeBlock();
            this._moveBlock();
            this._copyBlock();
			this._undoAction();
			this._animateBlock();
        },
		
        /**
         * Description
         * @method _initSortableRows
         * @return 
         */
        _initSortableRows: function() {
            $(this.element).sortable({
                cursor: "pointer",
                cursorAt: {
                    top: 10,
                    left: 10
                },
                tolerance: "pointer",
                handle: ".s-move",
                placeholder: "row-ghost",
                helper: function() {
                    return '<span style="box-shadow: 0 2px 2px 0 rgba(153, 153, 153, 0.14), 0 3px 1px -2px rgba(153, 153, 153, 0.2), 0 1px 5px 0 rgba(153, 153, 153, 0.12);background:#26C6DA;text-align:center;border-radius:3px;width:48px;height:48px;background:#1E88E5;padding:0;line-height:48px;"><i class="icon white arrows"></i></span>';
                },
            });
        },
		
        /**
         * Description
         * @method _initSortableColumns
         * @return 
         */
        _initSortableColumns: function() {
            var plugin = this;
            $('.columns', this.element).sortable({
                connectWith: ".columns",
                placeholder: "column-ghost",
                handle: ".c-move",
                cursor: "default",
                tolerance: "pointer",
                cursorAt: {
                    top: 20,
                    left: 20
                },
                start: function() {
                    $(plugin.element).css("pointer-events", "none");
                    $('.columns', plugin.element).find(".column-dummy").addClass("indicate");
                },
                stop: function() {
                    $(plugin.element).css("pointer-events", "");
                    $('.columns', plugin.element).find(".column-dummy").removeClass("indicate");

                },
                helper: function() {
                    return '<span style="box-shadow: 0 2px 2px 0 rgba(153, 153, 153, 0.14), 0 3px 1px -2px rgba(153, 153, 153, 0.2), 0 1px 5px 0 rgba(153, 153, 153, 0.12);background:#26C6DA;text-align:center;border-radius:3px;width:48px;height:48px;background:#26C6DA;padding:0;line-height:48px;"><i class="icon white arrows"></i></span>';
                },
                receive: function() {
                    plugin._doEmptyBlock();
                }
            });
        },
		
        /**
         * Description
         * @method _builderMode
         * @return 
         */
        _builderMode: function() {
            var plugin = this;
            $("input[name='bmode']").on('change', function() {
                bMode = $(this).val();
                switch (bMode) {
                    case "edit":
                        plugin._closeStyler("auto");
						plugin._closeAnimator("auto");
                        $("body").removeClass("content design").addClass(bMode);
                        $(plugin.element).children(".section").find(".section-tool, .section-insert").css("display", "none");
                        $(plugin.element).children(".section").find(".column-tool ").css("display", "none");
                        $(plugin.element).children(".section").find("[data-weditable]").addClass("editable");
                        plugin._offEvents();
                        break;

                    case "design":
                        plugin._closeEditor("auto");
						plugin._closeAnimator("auto");
                        $("body").removeClass("edit content").addClass(bMode);
                        $(plugin.element).children(".section").find(".section-tool, .section-insert").removeAttr("style");
                        $(plugin.element).children(".section").find(".column-tool ").removeAttr("style");
                        $(plugin.element).children(".section").find("[data-weditable]").removeClass("editable");
                        plugin._onEvents();
                        var $live = $(plugin.element).find(".live");
                        if ($live.length) {
                            $live.removeClass("live");
                        }
                        break;
                }
            });
        },
		
        /**
         * Description
         * @method _insertPrepare
         * @return 
         */
        _insertPrepare: function() {
            var plugin = this;
            $(this.element).on('click', '.grid-insert, .column-tool a.c-insert, .columns.is_empty', function() {
                $("#section-helper").find(".hide-all").removeClass("active hide-all");
                $("#section-helper").find(".active").removeClass("active");

                if ($(this).is(".grid-insert")) {
                    $activeSection = $(this).parent('.section').attr("id", plugin.makeid());
                    $(plugin.element).css("pointerEvents", "none");
                    insertType = "section";

                    $("#section-helper .yoyo.tabs li a[yoyo-tab='rows']").parent().addClass("active");
                    $("#section-helper #rows").addClass("active");

                    $("#section-helper .yoyo.tabs li a[yoyo-tab='blocks']").parent().addClass("hide-all");
                    $("#section-helper #blocks").addClass("hide-all");

                } else if ($(this).is(".is_empty")) {
                    $activeColumn = $(this);
                    insertType = "column";

                    $("#section-helper .yoyo.tabs li a[yoyo-tab='blocks']").parent().addClass("active");
                    $("#section-helper #blocks").addClass("active");

                    $("#section-helper .yoyo.tabs li a[yoyo-tab='rows']").parent().addClass("hide-all");
                    $("#section-helper .yoyo.tabs li a[yoyo-tab='sections']").parent().addClass("hide-all");

                    $("#section-helper #rows").addClass("hide-all");
                    $("#section-helper #sections").addClass("hide-all");

                } else {
                    $activeElement = $(this).closest(".column-dummy");
                    insertType = "element";

                    $("#section-helper .yoyo.tabs li a[yoyo-tab='blocks']").parent().addClass("active");
                    $("#section-helper #blocks").addClass("active");

                    $("#section-helper .yoyo.tabs li a[yoyo-tab='rows']").parent().addClass("hide-all");
                    $("#section-helper .yoyo.tabs li a[yoyo-tab='sections']").parent().addClass("hide-all");

                    $("#section-helper #rows").addClass("hide-all");
                    $("#section-helper #sections").addClass("hide-all");
                }

                $("#section-helper").css("pointerEvents", "auto");
                $("#section-helper").transition("scale in");
            });

            $("#section-helper").on('click', 'a.close', function() {
                $("#section-helper").transition("scale out");
                $(plugin.element).css("pointerEvents", "");
                $("#section-helper").css("pointerEvents", "");
            });
        },
		
        /**
         * Description
         * @method _insert
         * @return 
         */
        _insert: function() {
            var plugin = this;
            $("#section-helper").on('click', '.content a', function() {
                switch ($(this).data("element")) {
                    case "modules":
                        plugin._insertModule($(this).data());
                        break;

                    case "uplugins":
					    plugin._insertUserPlugin($(this).data());
                        break;

                    case "plugins":
                        plugin._insertPlugin($(this).data());
                        break;

                    case "blocks":
                        plugin._insertBlock($(this).data());
                        break;

                    case "rows":
                        plugin.makeRows($(this).data("row"));

                        $("#section-helper").transition("scale out");
                        plugin._initSortableColumns();
                        plugin._doEmptyBlock();

                        $(plugin.element).css("pointerEvents", "");
                        $("#section-helper").css("pointerEvents", "");
                        break;

                    default:
                        var url = $.url(plugin.options.burl).segment(-1);
                        $.get(plugin.options.url + "/helper.php", {
                            doAction: 1,
                            page: "bsection",
                            file: url + '/' + $(this).data("html")
                        }).done(function(json) {
                            var jsonObj = JSON.parse(json);
                            if (jsonObj.status === "success") {
                                var section = $(jsonObj.html).filter('div.section');

                                section.addClass("loading").attr("data-id", "temp_s00001");
                                $(section[0].outerHTML).insertAfter($activeSection);

                                var $section = $("[data-id='temp_s00001']", plugin.element);

                                $section.prepend(wraps.sectionWrap);
                                $section.find('.columns').children("[data-weditable]").wrap("<div class=\"column-dummy\"></div>");
                                $section.find('.columns').children(".column-dummy").prepend(wraps.elementWrap);

                                $("#section-helper").transition("scale out");
                                plugin._initSortableColumns();
                                plugin._doEmptyBlock();

                                $(plugin.element).css("pointerEvents", "");
                                $("#section-helper").css("pointerEvents", "");

                                $section.attr("data-id", plugin.makeid()).removeClass("loading");
                            } else {
                                console.log("invalid block");
                            }

                        }, "json");
                        break;
                }
            });
        },
		
        /**
         * Description
         * @method _insertModule
         * @param {} data
         * @return 
         */
        _insertModule: function(data) {
            var plugin = this;
            $.get(plugin.options.url + "/helper.php", {
                doAction: 1,
                page: "builderModule",
                id: data.moduleModule_id,
            }).done(function(json) {
                var jsonObj = JSON.parse(json);
                if (jsonObj.status === "success") {
                    switch (insertType) {
                        case "element":
                            var parent = $activeElement.parent(".columns");
                            parent.addClass("loading").attr("data-id", "temp_b00001");

                            $("<div data-wmodule-alias=\"" + data.moduleAlias + "\" data-wmodule-module_id=\"" + data.moduleModule_id + "\" data-wmodule-id=\"" + data.moduleId + "\" " +
                                "data-mode=\"readonly\">" + jsonObj.html + "</div>").attr("data-id", "temp_pe" + data.moduleModule_id).insertAfter($activeElement);
						
								
                            var $element = $("[data-id='temp_pe" + data.moduleModule_id + "']", plugin.element);

                            $element.wrap("<div class=\"column-dummy\"></div>").prepend(wraps.elementWrap);
                            $element.find(".c-copy").remove();
							$element.find(".c-animate").remove();
                            $element.removeAttr("data-id");

                            $('.columns', plugin.element).sortable("refresh");
							
							plugin._initPlugin($element);
                            parent.removeClass("loading").attr("data-id", plugin.makeid());
                            break;

                        case "column":
                            $activeColumn.html("<div data-mode=\"readonly\" data-wmodule-alias=\"" + data.moduleAlias + "\" data-wmodule-module_id=\"" + data.moduleModule_id + "\" " +
                                "data-wmodule-id=\"" + data.moduleId + "\">" + jsonObj.html + "</div>").attr("data-id", "temp_pc" + data.moduleModule_id);

                            $activeColumn.children().wrap("<div class=\"column-dummy\"></div>");
                            $activeColumn.children(".column-dummy").prepend(wraps.elementWrap);

                            $activeColumn.removeClass("is_empty");
                            $activeColumn.find(".c-copy").remove();
							

                            //$('.columns', plugin.element).sortable("refresh");
							
							plugin._initPlugin($activeColumn);
                            $("[data-id='temp_mc" + data.moduleModule_id + "']", plugin.element).attr("data-id", plugin.makeid()).removeClass("loading");

                            break;
					
                        default:
                            $("<div class=\"section loading\" data-wmodule-alias=\"" + data.moduleAlias + "\" data-wmodule-module_id=\"" + data.moduleModule_id + "\" data-wmodule-id=\"" + data.moduleId + "\" " +
                                "data-mode=\"readonly\">Loading Module</div>").attr("data-id", "temp_m" + data.moduleModule_id).insertAfter($activeSection);

                            var $melement = $("[data-id='temp_m" + data.moduleModule_id + "']", plugin.element);
                            $melement.html(jsonObj.html);
                            $melement.prepend(wraps.sectionWrap);

                            plugin._initSortableColumns();
                            plugin._doEmptyBlock();
							
							plugin._initPlugin($melement);
                            $melement.attr("data-id", plugin.makeid()).removeClass("loading");
                            break;

                    }
                    $("#section-helper").transition("scale out");
                    $(plugin.element).css("pointerEvents", "");
                    $("#section-helper").css("pointerEvents", "");

                    $("#plugins [data-module-group='" + data.moduleGroup + "']").closest('.column').remove();
                } else {
                    console.log("invalid module");
                    $("[data-id='temp_m" + data.moduleId + "']", plugin.element).remove();
                    $("[data-id='temp_mc" + data.moduleId + "']", plugin.element).remove();
                    $("[data-id='temp_me" + data.moduleId + "']", plugin.element).remove();
                }

            }, "json");
        },
		
        /**
         * Description
         * @method _insertPlugin
         * @param {} data
         * @return 
         */
        _insertPlugin: function(data) {
            var plugin = this;
            $.get(plugin.options.url + "/helper.php", {
                doAction: 1,
                page: "builderPlugin",
                id: data.pluginId,
                string: '%%' + data.pluginAlias + '|plugin|' + data.pluginPlugin_id + '|' + data.pluginId + '%%'
            }).done(function(json) {
                var jsonObj = JSON.parse(json);
                if (jsonObj.status === "success") {
                    switch (insertType) {
                        case "element":
                            var parent = $activeElement.parent(".columns");
                            parent.addClass("loading").attr("data-id", "temp_b00001");

                            $("<div data-wplugin-alias=\"" + data.pluginAlias + "\" data-wplugin-plugin_id=\"" + data.pluginPlugin_id + "\" data-wplugin-id=\"" + data.pluginId + "\" " +
                                "data-mode=\"readonly\">" + jsonObj.html + "</div>").attr("data-id", "temp_pe" + data.pluginId).insertAfter($activeElement);

                            var $element = $("[data-id='temp_pe" + data.pluginId + "']", plugin.element);

                            $element.wrap("<div class=\"column-dummy\"></div>").prepend(wraps.elementWrap);
                            $element.find(".c-copy").remove();
							$element.find(".c-animate").remove();
                            $element.removeAttr("data-id");

                            $('.columns', plugin.element).sortable("refresh");
							
							plugin._initPlugin($element);
                            parent.removeClass("loading").attr("data-id", plugin.makeid());
                            break;

                        case "column":
                            $activeColumn.html("<div data-mode=\"readonly\" data-wplugin-alias=\"" + data.pluginAlias + "\" " +
                                "data-wplugin-id=\"" + data.pluginId + "\" data-wplugin-plugin_id=\"" + data.pluginPlugin_id + "\">" + jsonObj.html + "</div>").attr("data-id", "temp_pc" + data.pluginId);

                            $activeColumn.children().wrap("<div class=\"column-dummy\"></div>");
                            $activeColumn.children(".column-dummy").prepend(wraps.elementWrap);

                            $activeColumn.removeClass("is_empty");
                            $activeColumn.find(".c-copy").remove();
							$activeColumn.find(".c-animate").remove();

                            //$('.columns', plugin.element).sortable("refresh");
							
							plugin._initPlugin($activeColumn);
                            $("[data-id='temp_pc" + data.pluginId + "']", plugin.element).attr("data-id", plugin.makeid()).removeClass("loading");
                            break;

                        default:
                            $("<div class=\"section loading\" data-wplugin-alias=\"" + data.pluginAlias + "\" data-wplugin-plugin_id=\"" + data.pluginPlugin_id + "\" data-wplugin-id=\"" + data.pluginId + "\" " +
                                "data-mode=\"readonly\">Loading Plugin</div>").attr("data-id", "temp_p" + data.pluginId).insertAfter($activeSection);

                            var $celement = $("[data-id='temp_p" + data.pluginId + "']", plugin.element);

                            $celement.html(jsonObj.html);
                            $celement.prepend(wraps.sectionWrap);

                            plugin._initSortableColumns();
                            plugin._doEmptyBlock();
							
							plugin._initPlugin($celement);
                            $celement.attr("data-id", plugin.makeid()).removeClass("loading");
                            break;
                    }

                    $("#section-helper").transition("scale out");
                    $(plugin.element).css("pointerEvents", "");
                    $("#section-helper").css("pointerEvents", "");

                    $("#plugins [data-plugin-id='" + data.pluginId + "']").closest('.column').remove();
                } else {
                    console.log("invalid plugin");
                    $("[data-id='temp_p" + data.pluginId + "']", plugin.element).remove();
                    $("[data-id='temp_pc" + data.pluginId + "']", plugin.element).remove();
                    $("[data-id='temp_pe" + data.pluginId + "']", plugin.element).remove();
                }

            }, "json");
        },

        /**
         * Description
         * @method _insertUserPlugin
         * @param {} data
         * @return 
         */
        _insertUserPlugin: function(data) {
            var plugin = this;
            $.get(plugin.options.url + "/helper.php", {
                doAction: 1,
                page: "builderUserPlugin",
                id: data.pluginId,
            }).done(function(json) {
                var jsonObj = JSON.parse(json);
                if (jsonObj.status === "success") {
					switch (insertType) {
                        case "element":
                            var parent = $activeElement.parent(".columns");
                            parent.addClass("loading").attr("data-id", "temp_b00001");

                            $("<div data-wplugin-id=\"" + data.pluginId + "\">" + jsonObj.html + "</div>").attr("data-id", "temp_upe" + data.pluginId).insertAfter($activeElement);

                            var $element = $("[data-id='temp_upe" + data.pluginId + "']", plugin.element);
                            $element.wrap("<div class=\"column-dummy\"></div>").prepend(wraps.elementWrap);
                            $element.removeAttr("data-id");

                            //$('.columns', plugin.element).sortable("refresh");
							
							plugin._initPlugin($element);
                            parent.removeClass("loading").attr("data-id", plugin.makeid());
                            break;
							
                        case "column":
                            $activeColumn.html("<div data-wplugin-id=\"" + data.pluginId + "\">" + jsonObj.html + "</div>").attr("data-id", "temp_upc" + data.pluginId);

                            $activeColumn.children().wrap("<div class=\"column-dummy\"></div>");
                            $activeColumn.children(".column-dummy").prepend(wraps.elementWrap);
                            $activeColumn.removeClass("is_empty");

                            $('.columns', plugin.element).sortable("refresh");
							
							plugin._initPlugin($activeColumn);
                            $("[data-id='temp_upc" + data.pluginId + "']", plugin.element).attr("data-id", plugin.makeid()).removeClass("loading");
                            break;
							
                        default:
                            $("<div class=\"section loading\" data-wplugin-id=\"" + data.pluginId + "\">Loading Plugin</div>").attr("data-id", "temp_up" + data.pluginId).insertAfter($activeSection);

                            var $celement = $("[data-id='temp_up" + data.pluginId + "']", plugin.element);

                            $celement.html(jsonObj.html);
                            $celement.prepend(wraps.sectionWrap);

                            plugin._initSortableColumns();
                            plugin._doEmptyBlock();
							
							plugin._initPlugin($celement);

                            $celement.attr("data-id", plugin.makeid()).removeClass("loading");
                            break;
					}
                    $("#section-helper").transition("scale out");
                    $(plugin.element).css("pointerEvents", "");
                    $("#section-helper").css("pointerEvents", "");
                } else {
                    console.log("invalid plugin");
                    $("[data-id='temp_up" + data.pluginId + "']", plugin.element).remove();
                    $("[data-id='temp_upc" + data.pluginId + "']", plugin.element).remove();
                    $("[data-id='temp_upe" + data.pluginId + "']", plugin.element).remove();
                }
            }, "json");
        },
		
        /**
         * Description
         * @method _insertBlock
         * @param {} data
         * @return 
         */
        _insertBlock: function(data) {
            var plugin = this;
            var url = $.url(plugin.options.burl).segment(-1);

            $.get(plugin.options.url + "/helper.php", {
                doAction: 1,
                page: "bsection",
                file: url + '/' + data.html
            }).done(function(json) {
                var jsonObj = JSON.parse(json);
                if (jsonObj.status === "success") {
                    var block = $(jsonObj.html);
                    if (insertType === "column") {
                        $activeColumn.append(block[0].outerHTML);

                        $activeColumn.children().wrap("<div class=\"column-dummy\"></div>");
                        $activeColumn.children(".column-dummy").prepend(wraps.elementWrap);

                        $activeColumn.removeClass("is_empty");
                    } else {
                        var parent = $activeElement.parent(".columns");
                        parent.addClass("loading").attr("data-id", "temp_b00001");

                        $(block[0].outerHTML).insertAfter($activeElement);


                        parent.find('.column-dummy').children().unwrap();
                        parent.find('.column-tool').remove();

                        parent.children().wrap("<div class=\"column-dummy\"></div>");
                        parent.children(".column-dummy").prepend(wraps.elementWrap);

                        $("[data-id='temp_b00001']", plugin.element).removeAttr("data-id").removeClass("loading");
                    }
                    $('.columns', plugin.element).sortable("refresh");

                    $("#section-helper").transition("scale out");
                    $(plugin.element).css("pointerEvents", "");
                    $("#section-helper").css("pointerEvents", "");
                } else {
                    console.log("invalid block");
                }
            }, "json");
        },

        /**
         * Description
         * @method _initPlugin
         * @param {} element
         * @return 
         */
		_initPlugin: function(element) {
			$('.yoyo.carousel', element).each(function() {
				var set = $(this).data('wcarousel');
				$(this).owlCarousel(set);
			});

			$('.wSlider', element).each(function() {
				var set = $(this).data('wslider');
				$(this).owlCarousel({
					dots: set.buttons,
					nav: set.arrows,
					autoplay: false,
					margin: 0,
					loop: set.autoloop,
					"responsive": {
						"0": {
							"items": 1
						},
						"769": {
							"items": 1
						},
						"1024": {
							"items": 1
						}
					}
				});
			});
			
			$('.yoyo.blocks', element).waitForImages(true).done(function() {
				$(this).each(function() {
					var set = $(this).data('wblocks');
					$(this).pinto(set);
					var $this = $(this);
					setTimeout(function() {
						$this.addClass("loaded");
					}, 800);
				});
			});
		},
		
        /**
         * Description
         * @method _loadModules
         * @param {} alias
         * @return 
         */
        _loadModules: function(alias) {
            var plugin = this;
            $.get(plugin.options.url + "/helper.php", {
                doAction: 1,
                page: "loadBuilderModules",
                modalias: alias
            }).done(function(json) {
                var jsonObj = JSON.parse(json);
                if (jsonObj.status === "success") {
                    $("#modules").find(".row").append(jsonObj.html);
                }

            }, "json");
        },
		
        /**
         * Description
         * @method _editElements
         * @return 
         */
        _editElements: function() {
            var plugin = this;
            $activeElement = null;
            $(this.element).on('click', '[data-weditable]', function(event) {
                if (bMode === "edit") {
                    $(plugin.element).find('.live').removeClass("live");
                    $activeElement = $(event.target);
                    var temp = $activeElement.clone().wrap('<div>').parent().html();
                    $("#tempData").html(temp);
                    $activeElement.addClass("live");
					$elementStyle = $activeElement.attr("style");
					$elementClass = $activeElement.attr("class");

                    var node = event.target.nodeName.toLowerCase();
                    var type = $(event.target).attr("data-type");
                    if (type === "button" || type === "icon" || type === "gmap" ||
                        type === "video" || type === "sound" ||
                        type === "module" || type === "plugin" ||
                        type === "icon" || node === "a" || node === "img") {
                        plugin._prepareEditor(type, node);
                        if ($("#element-helper").is(".hidden")) {
                            plugin._openEditor("auto");
                        }
                    }
                    $('.rangers').on('asRange::change', function(event, el) {
                        switch (el.$element.prop('name')) {
                            case "iconSize":
                                plugin._parseIcon();
                                break;
                        }
                    });
                }
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
            $(this.element).on("click", ".section-tool a.s-edit", function() {
				plugin._closeAnimator("auto");
                $activeSection = $(this).closest(".section");
                var css = $activeSection.css(cssProp);
				$sectionStyle = $activeSection.attr("style");

                $activeRow = $activeSection.find(".row").first();
                if ($activeRow.length) {
                    var rowSpace = $activeRow.attr('class');

                    var mapObj = {
                        "row": "",
                        "align-middle": "",
                        "align-center": ""
                    };
                    var re = new RegExp(Object.keys(mapObj).join("|"), "gi");
                    rowSpace = rowSpace.replace(re, function(matched) {
                        return mapObj[matched];
                    });

                    $('input[name=space_size]').prop('checked', false);
                    switch (rowSpace) {
                        case "half-gutters":
                            $('input#space_both').prop('checked', true);
                            $("input[name=spaceWidth]").asRange('set', 1);
                            break;

                        case "gutters":
                            $('input#space_both').prop('checked', true);
                            $("input[name=spaceWidth]").asRange('set', 2);
                            break;

                        case "double-gutters":
                            $('input#space_both').prop('checked', true);
                            $("input[name=spaceWidth]").asRange('set', 3);
                            break;

                        case "half-horizontal-gutters":
                            $('input#space_horizontal').prop('checked', true);
                            $("input[name=spaceWidth]").asRange('set', 1);
                            break;

                        case "horizontal-gutters":
                            $('input#space_horizontal').prop('checked', true);
                            $("input[name=spaceWidth]").asRange('set', 2);
                            break;

                        case "double-horizontal-gutters":
                            $('input#space_horizontal').prop('checked', true);
                            $("input[name=spaceWidth]").asRange('set', 3);
                            break;

                        case "half-vertical-gutters":
                            $('input#space_vertical').prop('checked', true);
                            $("input[name=spaceWidth]").asRange('set', 1);
                            break;

                        case "vertical-gutters":
                            $('input#space_vertical').prop('checked', true);
                            $("input[name=spaceWidth]").asRange('set', 2);
                            break;

                        case "double-vertical-gutters":
                            $('input#space_vertical').prop('checked', true);
                            $("input[name=spaceWidth]").asRange('set', 3);
                            break;

                        default:
                            $('input#space_both').prop('checked', true);
                            $("input[name=spaceWidth]").asRange('set', 0);
                            break;
                    }
                }
                $('input[name=imageParalax]').prop('checked', ($activeSection.hasClass("paralax")) ? true : false);
                $('input[name=paddingTop]').asRange('set', parseInt(css.paddingTop));
                $('input[name=paddingBottom]').asRange('set', parseInt(css.paddingBottom));
                $('input[name=marginTop]').asRange('set', parseInt(css.marginTop));
                $('input[name=marginBottom]').asRange('set', parseInt(css.marginBottom));
                $('.rangers').on('asRange::change', function(event, el) {
                    switch (el.$element.prop('name')) {
                        case "paddingTop":
                        case "paddingBottom":
                        case "marginTop":
                        case "marginBottom":
                            plugin._cssProcess(el.$element.prop('name'), el.value);
                            break;

                        case "borderWidth":
                            bProps.width = el.value + 'px';
                            plugin._buildBorders();
                            break;

                        case "boxShadowHorizontal":
                            sProps.horizontal = el.value + 'px';
                            plugin._buildShadow();
                            break;

                        case "boxShadowVertical":
                            sProps.vertical = el.value + 'px';
                            plugin._buildShadow();
                            break;

                        case "boxShadowBlur":
                            sProps.blur = el.value + 'px';
                            plugin._buildShadow();
                            break;

                        case "boxShadowSpread":
                            sProps.spread = el.value + 'px';
                            plugin._buildShadow();
                            break;

                        case "gradientStart":
                            gProps.start = el.value;
                            plugin._buildGradient();
                            break;

                        case "gradientStop":
                            gProps.stop = el.value;
                            plugin._buildGradient();
                            break;

                        case "gradientDeg":
                            gProps.deg = el.value;
                            plugin._buildGradient();
                            break;

                        case "spaceWidth":
                            rowSize.size = el.value;
                            rowSize.space = $("input[name='space_size']:checked").val();
                            plugin._buildRowSpace();
                            break;
                    }
                });
				
                $("#borderStyle").find("button").removeClass("active");
                if (parseInt(css.borderLeftWidth) === 0) {
                    $("#borderColor").spectrum("set", "rgba(0, 0, 0, 1)");
                    $("button[name=borderNone]").addClass("active");
                    $("input[name=borderWidth]").asRange('set', 0);
                } else {
                    bProps.style = css.borderLeftStyle;
                    bProps.color = css.borderLeftColor;
                    bProps.width = css.borderLeftWidth;
                    var style = css.borderLeftStyle[0].toUpperCase() + css.borderLeftStyle.substring(1);
                    $("button[name=border" + style + "]").addClass("active");
                    $("#borderColor").spectrum("set", css.borderLeftColor);
                    $("input[name=borderWidth]").asRange('set', parseInt(css.borderLeftWidth));
                    plugin._buildBorders();
                }
                $("[data-corner=borderTopLeftRadius]").val(css.borderTopLeftRadius);
                $("[data-corner=borderTopRightRadius]").val(css.borderTopRightRadius);
                $("[data-corner=borderBottomLeftRadius]").val(css.borderBottomLeftRadius);
                $("[data-corner=borderBottomRightRadius]").val(css.borderBottomRightRadius);
                if (parseInt(css.borderTopLeftRadius) > 0 && parseInt(css.borderTopRightRadius) > 0 && parseInt(css.borderBottomLeftRadius) > 0 && parseInt(css.borderBottomRightRadius) > 0) {
                    radiusAll = true;
                    $("#bRadiusView").css("borderRadius", plugin._cleanCssValues(css.borderTopLeftRadius));
                    $("#bRadiusView").addClass("active");
                } else {
                    radiusAll = true;
                    $("#bRadiusView").css("borderRadius", "").removeClass("active");
                }
                $('.bShadow').removeClass("active");
                $("button[name=shadowInset], button[name=shadowOutset]").removeClass("active");
                if (css.boxShadow === "none") {
                    $("input[name=boxShadowHorizontal]").asRange('set', 0);
                    $("input[name=boxShadowVertical]").asRange('set', 0);
                    $("input[name=boxShadowBlur]").asRange('set', 0);
                    $("input[name=boxShadowSpread]").asRange('set', 0);
                    $("#shadowColor").spectrum("set", "rgba(0, 0, 0, .2)");
                    $("button[name=shadowOutset]").addClass("active");
                } else {
                    var bs = css.boxShadow;
                    bs = bs.split(/ (?![^\(]*\))/);
                    sProps = {
                        "horizontal": bs[1],
                        "vertical": bs[2],
                        "blur": bs[3],
                        "spread": bs[4],
                        "color": "rgba(0, 0, 0, .2)",
                        "position": (bs.length === 6) ? "inset" : "none"
                    };

                    $("input[name=boxShadowHorizontal]").asRange('set', parseInt(bs[1]));
                    $("input[name=boxShadowVertical]").asRange('set', parseInt(bs[2]));
                    $("input[name=boxShadowBlur]").asRange('set', parseInt(bs[3]));
                    $("input[name=boxShadowSpread]").asRange('set', parseInt(bs[4]));
                    $("#shadowColor").spectrum("set", bs[0]);
                    if ((bs.length === 6)) {
                        $("button[name=shadowInset]").addClass("active");
                    } else {
                        $("button[name=shadowOutset]").addClass("active");
                    }
                    plugin._buildShadow();
                }
				
                $("#bgImage, #bgGradient, #bgColor").removeClass("active");
                $("button[name=bgImage], button[name=bgGradient], button[name=bgColor]").removeClass("active");
                if (css.backgroundImage === "none") {
                    $("button[name=bgColor]").addClass("active");
                    $("#bgColor").addClass("active");
                } else {
                    if (css.backgroundImage.indexOf("url") >= 0) {
                        $("button[name=bgImage]").addClass("active");
                        $("#bgImage").addClass("active");
                        $("#bgImageWrap").html('<img src="' + css.backgroundImage.replace(/.*\s?url\([\'\"]?/, '').replace(/[\'\"]?\).*/, '') + '">');
                    } else {
                        $("button[name=bgGradient]").addClass("active");
                        $("#bgGradient").addClass("active");
                        var gr = plugin._getGradient(css.backgroundImage);
                        $("input[name=gradientStart]").asRange('set', parseInt(gr.colors[0].position));
                        $("input[name=gradientStop]").asRange('set', parseInt(gr.colors[1].position));
                        $("input[name=gradientDeg]").asRange('set', parseInt(gr.angle));
                        $("#gradColorStart").spectrum("set", gr.colors[0].color);
                        $("#gradColorStop").spectrum("set", gr.colors[1].color);

                        gProps = {
                            "deg": gr.angle,
                            "color1": gr.colors[0].color,
                            "color2": gr.colors[1].color,
                            "start": parseInt(gr.colors[0].position),
                            "stop": parseInt(gr.colors[1].position)
                        };
                        plugin._buildGradient();
                    }
                }

                if ($("#style-helper").is(".hidden")) {
                    plugin._openStyler();
                }
            });
			
            $("input[name='space_size']").on('change', function() {
                rowSize.space = $(this).val();
                plugin._buildRowSpace();
            });
			
            $('#borderStyle').on('click', "button", function() {
                $('#borderStyle button').removeClass("active");
                $(this).addClass("active");
                var type = $(this).data("type");
                bProps.style = type;
                plugin._buildBorders();
            });
			
            $('#bRadius').on('change', "input", function() {
                if (radiusAll) {
                    $("#bRadius").find("input").val($(this).val());
                    $("#bRadiusView").css("borderRadius", plugin._cleanCssValues($(this).val()));
                } else {
                    $("#bRadiusView").css($(this).attr("data-corner"), plugin._cleanCssValues($(this).val()));
                }
                plugin._buildRadius();
            });

            $('#bRadiusAll').on('click', function() {
                $(this).parent().toggleClass('active');
                if ($(this).parent().is(".active")) {
                    var val = $("input[data-corner='borderTopLeftRadius']").val();
                    $("#bRadius").find("input").val(val);
                    $("#bRadiusView").css("borderRadius", plugin._cleanCssValues(val));
                    radiusAll = true;
                } else {
                    radiusAll = false;
                }
                plugin._buildRadius();
            });
			
            $(".bShadow").on("click", function() {
                $(".bShadow").removeClass("active");
                $(this).addClass("active");
                $("button[name=shadowInset], button[name=shadowOutset]").removeClass("active");

                if ($(this).is(".preset1")) {
                    $activeSection.css("boxShadow", "none");
                    $("input[name=boxShadowHorizontal]").asRange('set', 0);
                    $("input[name=boxShadowVertical]").asRange('set', 0);
                    $("input[name=boxShadowBlur]").asRange('set', 0);
                    $("input[name=boxShadowSpread]").asRange('set', 0);
                    $("#shadowColor").spectrum("set", "rgba(0, 0, 0, .2)");
                } else {
                    var bs = $(this).children().css("boxShadow");
                    bs = bs.split(/ (?![^\(]*\))/);

                    $("input[name=boxShadowHorizontal]").asRange('set', parseInt(bs[1]));
                    $("input[name=boxShadowVertical]").asRange('set', parseInt(bs[2]));
                    $("input[name=boxShadowBlur]").asRange('set', parseInt(bs[3]));
                    $("input[name=boxShadowSpread]").asRange('set', parseInt(bs[4]));
                    $("#shadowColor").spectrum("set", "rgba(0, 0, 0, .2)");

                    if ((bs.length === 6)) {
                        $("button[name=shadowInset]").addClass("active");
                    } else {
                        $("button[name=shadowOutset]").addClass("active");
                    }
                    sProps = {
                        "horizontal": bs[1],
                        "vertical": bs[2],
                        "blur": bs[3],
                        "spread": bs[4],
                        "color": "rgba(0, 0, 0, .2)",
                        "position": (bs.length === 6) ? "inset" : "none"
                    };
                    plugin._buildShadow();
                }
            });

            $("button[name=shadowInset], button[name=shadowOutset]").on('click', function() {
                var type = $(this).data("type");
                $("button[name=shadowInset], button[name=shadowOutset]").removeClass("active");
                $(this).addClass("active");
                sProps.position = (type === "inset") ? "inset" : "none";
                plugin._buildShadow();
            });
			
            $("#bgImageWrap").off('click').on('click', function() {
                $.get(plugin.options.url + '/managerBuilder.php', {
                    pickFile: 1,
                    editor: true
                }).done(function(data) {
                    var modal =
                        '<div id="fileModal" class="yoyo fullscreen modal">' +
                        '<div class="yoyo content">' + data + '</div>' +
                        '</div>';
                    $(modal).modal('setting', 'onShow', function() {
                        var cmodal = this;
                        $("#result").on('click', '.is_file', function() {
                            var dataset = $(this).data('set');
                            if (dataset.image === "true") {
                                $("#bgImageWrap").html('<img src="' + plugin.options.surl + '/uploads/' + dataset.url + '">');
                                plugin._buildBgImage(dataset.url);
                                $(cmodal).modal('hide');
                            }
                        });
                    }).modal('setting', 'onHidden', function() {
                        $(this).remove();
                    }).modal('show');
                });
            });
			
            $("input[name=imageParalax]").on('change', function() {
                if ($(this).is(':checked')) {
                    $activeSection.addClass("paralax");
                } else {
                    $activeSection.removeClass("paralax");
                }
            });
			
            $("button[name=bgNone]").on('click', function() {
                plugin._buildBgColor("");
                $("#bgImageWrap").text("NO IMAGE");
            });
        },
		
        /**
         * Description
         * @method _cssProcess
         * @param {} cssProp
         * @param {} value
         * @return 
         */
        _cssProcess: function(cssProp, value) {
            if ($activeSection !== "undefined") {
                $activeSection.css(cssProp, parseInt(value));
            }
        },
		
        /**
         * Description
         * @method _buildRowSpace
         * @return 
         */
        _buildRowSpace: function() {
            var sizeString = '';
            var spaceString = '';

            switch (rowSize.space) {
                case "both":
                    spaceString = "";
                    break;

                case "horizontal":
                    spaceString = "horizontal-";
                    break;

                case "vertical":
                    spaceString = "vertical-";
                    break;
            }

            switch (rowSize.size) {
                case 3:
                    sizeString = "double-" + spaceString + "gutters";
                    break;

                case 2:
                    sizeString = spaceString + "gutters";
                    break;

                case 1:
                    sizeString = "half-" + spaceString + "gutters";
                    break;

                default:
                    sizeString = "";
                    break;
            }

            $activeRow.removeClass("half-gutters gutters double-gutters half-horizontal-gutters horizontal-gutters double-horizontal-gutters half-vertical-gutters vertical-gutters double-vertical-gutters");
            $activeRow.addClass(sizeString);

        },
		
        /**
         * Description
         * @method _buildBorders
         * @return 
         */
        _buildBorders: function() {
            var string = {};
            $("#bBorderView").css('border', '');
            if (bProps.style === "none") {
                string = {
                    "border": ""
                };
                $("input[name=borderWidth]").asRange('set', 0);
            } else {
                string = {
                    "border": bProps.width + ' ' + bProps.style + ' ' + bProps.color
                };
            }

            $("#bBorderView").css(string);
            $activeSection.css(string);
        },
		
        /**
         * Description
         * @method _buildRadius
         * @return 
         */
        _buildRadius: function() {
            var string = {
                "borderTopLeftRadius": this._cleanCssValues($("input[data-corner='borderTopLeftRadius']").val()),
                "borderTopRightRadius": this._cleanCssValues($("input[data-corner='borderTopRightRadius']").val()),
                "borderBottomLeftRadius": this._cleanCssValues($("input[data-corner='borderBottomLeftRadius']").val()),
                "borderBottomRightRadius": this._cleanCssValues($("input[data-corner='borderBottomRightRadius']").val()),
            };
            $activeSection.css(string);
        },
		
        /**
         * Description
         * @method _buildShadow
         * @return 
         */
        _buildShadow: function() {
            var string;
            if (sProps.position === "inset") {
                string = sProps.position + ' ' + sProps.horizontal + ' ' + sProps.vertical + ' ' + sProps.blur + ' ' + sProps.spread + ' ' + sProps.color;
            } else {
                string = sProps.horizontal + ' ' + sProps.vertical + ' ' + sProps.blur + ' ' + sProps.spread + ' ' + sProps.color;
            }

            $activeSection.css("boxShadow", string);
        },
		
        /**
         * Description
         * @method _buildGradient
         * @return 
         */
        _buildGradient: function() {
            var string = "linear-gradient(" + gProps.deg + "deg, " + gProps.color1 + " " + gProps.start + "%, " + gProps.color2 + " " + gProps.stop + "%)";
            $activeSection.css("backgroundImage", string);
        },
		
        /**
         * Description
         * @method _buildBgColor
         * @param {} color
         * @return 
         */
        _buildBgColor: function(color) {
            $activeSection.css({
                "background": "",
                "backgroundImage": "",
                "backgroundColor": color
            });
        },
		
        /**
         * Description
         * @method _buildBgImage
         * @param {} image
         * @return 
         */
        _buildBgImage: function(image) {
            $activeSection.css({
                "backgroundSize": "cover",
                "backgroundImage": "url(" + this.options.surl + "/uploads/" + image + ")",
            });
        },
		
        /**
         * Description
         * @method _buildImageFilter
         * @return 
         */
        _buildImageFilter: function() {
            var string = 'grayscale(' + fProps.grayscale + '%) blur(' + fProps.blur + 'px) brightness(' + fProps.brightness + '%) contrast(' + fProps.contrast + '%) hue-rotate(' + fProps.hueRotate + 'deg) opacity(' + fProps.opacity + '%) invert(' + fProps.invert + '%) saturate(' + fProps.saturate + '%) sepia(' + fProps.sepia + '%)';

            $activeElement.css("filter", string);
        },
		
        /**
         * Description
         * @method _linkList
         * @param {} element
         * @return 
         */
        _linkList: function(element) {
            var plugin = this;
            var list = '';
            if (element.is(':empty')) {
                $.get(plugin.options.url + "/helper.php", {
                    doAction: 1,
                    page: "getlinks",
                    is_builder: 1
                },
		 function(json) {
                    $.each(json.message, function(i, item) {
                        list += "<a class=\"item\" data-value=\"" + item.href + "\">" + item.name + "</a>";
                    });
                    element.html(list);
                }, "json");
            }
        },
		
        /**
         * Description
         * @method _cleanCssValues
         * @param {} text
         * @return 
         */
        _cleanCssValues: function(text) {
            var re = /^(\d+(?:\.\d*)?|\.\d+)([^\d\s]+)?$/m;
            var m;
            if (!(m = text.match(re))) {
                return '';
            }
            if (!m[1]) {
                return '';
            }
            var i = Math.floor(m[1]); // Compute integer portion.
            if (!m[2]) {
                return i + 'px';
            }
            switch (m[2]) {
                case 'em':
                case '%':
                    return m[1] + m[2];
                case 'px':
                    return i + 'px';
                default:
                    return i + 'px';
            }
        },
		
        /**
         * Description
         * @method _getGradient
         * @param {} input
         * @return result
         */
        _getGradient: function(input) {
            var result,
                regExpLib = this._generateRegExp(),
                rGradientEnclosedInBrackets = /.*gradient\s*\(((?:\([^\)]*\)|[^\)\(]*)*)\)/,
                match = rGradientEnclosedInBrackets.exec(input);
            if (match !== null) {
                result = this._parseGradient(regExpLib, match[1]);
            } else {
                result = "Failed to find gradient";
            }
            return result;
        },
		
        /**
         * Description
         * @method _generateRegExp
         * @return ObjectExpression
         */
        _generateRegExp: function() {
            var searchFlags = 'gi',
                rAngle = /(?:[+-]?\d*\.?\d+)(?:deg|grad|rad|turn)/,
                rSideCornerCapture = /to\s+((?:(?:left|right)(?:\s+(?:top|bottom))?))/,
                rComma = /\s*,\s*/,
                rColorHex = /\#(?:[A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})/,
                rDigits3 = /\(\s*(?:[0-9]{1,3}\s*,\s*){2}[0-9]{1,3}\s*\)/,
                rDigits4 = /\(\s*(?:[0-9]{1,3}\s*,\s*){3}(?:[.\d]+)\s*\)/,
                rValue = /(?:[+-]?\d*\.?\d+)(?:%|[a-z]+)?/,
                rKeyword = /[_A-Za-z-][_A-Za-z0-9-]*/,
                rColor = this._combineRegExp([
                    '(?:', rColorHex, '|', '(?:rgb|hsl)', rDigits3, '|', '(?:rgba|hsla)', rDigits4, '|', rKeyword, ')'
                ], ''),
                rColorStop = this._combineRegExp([rColor, '(?:\\s+', rValue, ')?'], ''),
                rColorStopList = this._combineRegExp(['(?:', rColorStop, rComma, ')*', rColorStop], ''),
                rLineCapture = this._combineRegExp(['(?:(', rAngle, ')|', rSideCornerCapture, ')'], ''),
                rGradientSearch = this._combineRegExp([
                    '(', rLineCapture, ')', rComma, '(', rColorStopList, ')'
                ], searchFlags),
                rColorStopSearch = this._combineRegExp([
                    '\\s*(', rColor, ')', '(?:\\s+', '(', rValue, '))?', '(?:', rComma, '\\s*)?'
                ], searchFlags);

            return {
                gradientSearch: rGradientSearch,
                colorStopSearch: rColorStopSearch
            };
        },
		
        /**
         * Description
         * @method _combineRegExp
         * @param {} regexpList
         * @param {} flags
         * @return NewExpression
         */
        _combineRegExp: function(regexpList, flags) {
            var i,
                source = '';
            for (i = 0; i < regexpList.length; i++) {
                if (typeof regexpList[i] === 'string') {
                    source += regexpList[i];
                } else {
                    source += regexpList[i].source;
                }
            }
            return new RegExp(source, flags);
        },
		
        /**
         * Description
         * @method _parseGradient
         * @param {} regExpLib
         * @param {} input
         * @return result
         */
        _parseGradient: function(regExpLib, input) {
            var result,
                matchGradient,
                matchColorStop,
                stopResult;

            matchGradient = regExpLib.gradientSearch.exec(input);
            if (matchGradient !== null) {
                result = {
                    original: matchGradient[0],
                    colors: []
                };

                if (!!matchGradient[1]) {
                    result.line = matchGradient[1];
                }
                if (!!matchGradient[2]) {
                    result.angle = matchGradient[2];
                }
                if (!!matchGradient[3]) {
                    result.sideCorner = matchGradient[3];
                }

                matchColorStop = regExpLib.colorStopSearch.exec(matchGradient[4]);
                while (matchColorStop !== null) {
                    stopResult = {
                        color: matchColorStop[1]
                    };

                    if (!!matchColorStop[2]) {
                        stopResult.position = matchColorStop[2];
                    }
                    result.colors.push(stopResult);
                    matchColorStop = regExpLib.colorStopSearch.exec(matchGradient[4]);
                }
            }

            return result;
        },
		
        /**
         * Description
         * @method _offEvents
         * @return 
         */
        _offEvents: function() {
            $(this.element).off('mouseenter mouseleave mouseover mouseout');
        },
		
        /**
         * Description
         * @method _onEvents
         * @return 
         */
        _onEvents: function() {
            $(this.element).on({
                mouseover: function(event) {
                    if (typeof $(event.target).attr("data-weditable") !== "undefined") {
                        $(event.target).addClass("active");
                        $(event.target).closest(".section").removeClass("active");
                    } else {
                        $(this).addClass("active");
                    }

                }, mouseout: function() {
                    $(this).removeClass("active");
                }
            }, '.section, [data-weditable]');
        },
		
        /**
         * Description
         * @method _doEmptyBlock
         * @return 
         */
        _doEmptyBlock: function() {
            $('.columns.ui-sortable', this.element).each(function() {
                $(this).removeClass("is_empty");
                if ($(this).children().length === 0) {
                    $(this).addClass("is_empty");
                }
            });
        },
		
        /**
         * Description
         * @method _copyBlock
         * @return 
         */
        _copyBlock: function() {
            $(this.element).on('click', '.column-tool a.c-copy', function() {
                var parent = $(this).closest(".column-dummy");
                var element = parent.clone().wrap('<div>').parent().html();
                $(element).insertAfter(parent);
            });
        },

        /**
         * Description
         * @method _undoAction
         * @return 
         */
        _undoAction: function($action) {
            switch ($action) {
				
			}
        },
		
        /**
         * Description
         * @method _animateBlock
         * @return 
         */
        _animateBlock: function() {
			var plugin = this;
            $(this.element).on('click', '.column-tool a.c-animate', function() {
                var parent = $(this).closest(".column-dummy");
                var element = parent.children("[data-weditable=true]:first");
                if (element.length) {
					plugin._closeStyler("auto");
					
					$(plugin.element).find(".live").removeClass("live");
					$activeBlock = $(element).addClass("live");
					
					var time = $(element).attr('data-duration');
					var delay = $(element).attr('data-delay');
					var animation = $(element).attr('data-wanimate');
					
					$('input[name=aniDuration]').asRange('set', 0);
					$('input[name=aniDurationText]').val(0);
					$('input[name=aniDelay]').asRange('set', 0);
					$('input[name=aniDelayText]').val(0);

					$("#anieditor .yoyo.fluid.icon.button").removeClass("positive");
					$("#anieditor").find(".selected").removeClass("selected");

					if(typeof animation !== "undefined" && animation !== false) {
						var active = $("#anieditor").find(".item[data-value='" + animation + "']").addClass("selected");
						$("#anieditor").find("[data-dropdown='#" + active.parent().attr("id") + "']").addClass("positive");
					} else {
						$("#anieditor .yoyo.fluid.icon.button.noani").addClass("positive");
					}
					
					if(typeof time !== "undefined" && time !== false) {
						$('input[name=aniDuration]').asRange('set', parseInt(time));
						$('input[name=aniDurationText]').val(parseInt(time));
					}

					if(typeof delay !== "undefined" && delay !== false) {
						$('input[name=aniDelay]').asRange('set', parseInt(delay));
						$('input[name=aniDelayText]').val(parseInt(delay));
					}
					
					if ($("#animation-helper").is(".hidden")) {
						plugin._openAnimator("auto");
					}
					
                    $('.rangers').on('asRange::change', function(event, el) {
                        switch (el.$element.prop('name')) {
                            case "aniDuration":
								$('input[name=aniDurationText]').val(parseInt(el.value));
								setTimeout(function() {
									$activeBlock.attr("data-duration", el.value);
								}, 500);
                                break;
								
	                            case "aniDelay":
								$('input[name=aniDelayText]').val(parseInt(el.value));
								setTimeout(function() {
									$activeBlock.attr("data-delay", el.value);
								}, 500);
                                break;
                        }
                    });
                }
            });
			
			//change animation
            $("#anieditor").on('click', '.item', function() {
                var selected = $(this).data("value");
				$("#anieditor .yoyo.fluid.icon.button").removeClass("positive");
                $('#anieditor').find(".selected.item").removeClass("selected");
				$("#anieditor").find("[data-dropdown='#" + $(this).parent().attr("id") + "']").addClass("positive");
                $(this).addClass("selected");

                if ($activeBlock.length) {
                    $activeBlock.attr("data-wanimate", selected);
                    var type = $activeBlock.attr('data-wanimate');
                    var time = $activeBlock.attr('data-duration');
                    var delay = $activeBlock.attr('data-delay');

                    if (!type) {
                        type = selected;
                    }
                    if (!time) {
                        time = 500;
                    }

                    if (!delay) {
                        delay = 200;
                    }

                    if (selected === "none") {
                        $activeBlock.removeAttr("data-wanimate data-duration data-delay");
                    } else {
                        var values = "animate " + type;
                        $activeBlock.addClass(values).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                            setTimeout(function() {
                                $activeBlock.removeClass(values);
                            }, 500);
                        });
                    }
                }
            });
			
			//play animation block
			$("#aniPlay").on('click', function() {
				if ($activeBlock.length) {
					var animation = $activeBlock.attr('data-wanimate');
                    if (animation) {
                        var values = "animate " + animation;
                        $activeBlock.addClass(values).on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                            setTimeout(function() {
                                $activeBlock.removeClass(values);
                            }, 500);
                        });
                    }
				}
            });

            //Animation time
            $("input[name='aniDurationText']").on('change', function() {
                var time = $(this).val().replace(/[^0-9\.]/g, 0);
				if ((time !== '') && (time.indexOf('.') === -1)) {
					time = Math.max(Math.min(time, 3000), 0);
					$(this).val(time);
					$('input[name=aniDuration]').asRange('set', parseInt(time));
				}
                var type = $activeBlock.attr('data-wanimate');
                if ($activeBlock.length) {
                    $activeBlock.attr("data-duration", time);
                    if (type) {
                        var values = "animate " + type;
                        $activeBlock.addClass(values).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                            setTimeout(function() {
                                $activeBlock.removeClass(values);
                            }, 500);
                        });
                    }
                }
            });
			
            //Animation delay
            $("input[name='aniDelayText']").on('change', function() {
                var delay = $(this).val().replace(/[^0-9\.]/g, 0);
				if ((delay !== '') && (delay.indexOf('.') === -1)) {
					delay = Math.max(Math.min(delay, 2000), 0);
					$(this).val(delay);
					$('input[name=aniDelay]').asRange('set', parseInt(delay));
				}
                var type = $activeBlock.attr('data-wanimate');
                if ($activeBlock.length) {
                    $activeBlock.attr("data-delay", delay);
                    if (type) {
                        var values = "animate " + type;
                        $activeBlock.addClass(values).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                            setTimeout(function() {
                                $activeBlock.removeClass(values);
                            }, 500);
                        });
                    }
                }
            });
        },
		
        /**
         * Description
         * @method _editHtml
         * @return 
         */
        _editHtml: function() {
            var plugin = this;
            $(this.element).on('click', '.column-tool a.c-edit', function() {
                var element = $(this).parent().next();
                var html = element[0].outerHTML;

                $("#tempHtml").val(html);
                plugin._formatSource($("#tempHtml"));

                $("#editSource").modal('setting', 'onApprove', function() {
                    element.html($("#tempHtml").val());
                }).modal('setting', 'onHidden', function() {
                    $("#tempHtml").val("");
                    $("#tempData").html("");
                }).modal('show');
            });
        },

        /**
         * Description
         * @method _editSectionHtml
         * @return 
         */
        _editSectionHtml: function() {
            var plugin = this;
            $(this.element).on('click', '.section-tool a.s-html', function() {
                var element = $(this).closest(".section.active");
                var html = element.html();

                $("#tempHtml").val(html);
                plugin._formatSource($("#tempHtml"));

                $("#editSource").modal('setting', 'onApprove', function() {
                    element.html($("#tempHtml").val());
                }).modal('setting', 'onHidden', function() {
                    $("#tempHtml").val("");
                    $("#tempData").html("");
                }).modal('show');
            });
        },
		
        /**
         * Description
         * @method _deleteBlock
         * @return 
         */
        _deleteBlock: function() {
            var plugin = this;
            $(this.element).on('click', '.column-tool a.c-delete, .section-tool a.s-delete', function() {
                var element = $(this).is(".c-delete") ? $(this).closest(".column-dummy") : $(this).closest(".section");
                var plugins = [];
                var modules = [];
				var $this = $(this);

                if ($(this).is(".c-delete")) {
					$activeColumn = $(this).closest(".columns");
                    $('[data-wplugin-id]', element).each(function() {
                        plugins.push($(this).attr("data-wplugin-id"));
                    });

                    $('[data-wmodule-id]', element).each(function() {
                        modules.push($(this).attr("data-wmodule-id"));
                    });
                } else {
                    $(element).each(function() {
                        plugins.push($(this).attr("data-wplugin-id"));
                    });

                    $(element).each(function() {
                        modules.push($(this).attr("data-wmodule-alias"));
                    });
                }

                if (plugins.length > 0) {}
                if (modules.length > 0) {
                    plugin._loadModules(modules);
                }

                element.transition({
                    animation: 'scale',
                    duration: '.6s',
                    onComplete: function() {
                        element.remove();
						if ($this.is(".c-delete")) {
							if($activeColumn.children().length === 0) {
								$activeColumn.addClass("is_empty");
							}
						}
                    }
                });
            });
        },

        /**
         * Description
         * @method _removeBlock
         * @return 
         */
        _removeBlock: function() {
            var plugin = this;
            $(this.element).on('click', '.column-tool a.c-remove', function() {
                var element = $(this).closest(".columns");
                var plugins = [];
                var modules = [];
					
				$activeColumn = $(this).closest(".columns");
				$('[data-wplugin-id]', element).each(function() {
					plugins.push($(this).attr("data-wplugin-id"));
				});

				$('[data-wmodule-id]', element).each(function() {
					modules.push($(this).attr("data-wmodule-id"));
				});


                if (plugins.length > 0) {}
                if (modules.length > 0) {
                    plugin._loadModules(modules);
                }

                element.transition({
                    animation: 'scale',
                    duration: '.6s',
                    onComplete: function() {
                        element.remove();
						if($activeColumn.children().length === 0) {
							$activeColumn.addClass("is_empty");
						}
                    }
                });
            });
        },
		
        /**
         * Description
         * @method _moveBlock
         * @return 
         */
        _moveBlock: function() {
            $(this.element).on('click', '.section-tool a.s-up, .section-tool a.s-down', function() {
                var element = $(this).closest('.section');
                if ($(this).is("a.s-up")) {
                    var previous = element.prev('.section');
                    if (previous.length !== 0) {
                        $(element).insertBefore(previous);
                    }
                } else {
                    var next = element.next('.section');
                    if (next.length !== 0) {
                        element.insertAfter(next);
                    }
                }
                return false;
            });
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

            var el = $("<div class=\"section\"><div class=\"yoyo-grid\"><div class=\"row gutters\">" + html + "</div></div></div>").prepend(wraps.sectionWrap);
            el.insertAfter($activeSection);
            el.transition({
                animation: 'scale in',
                duration: '1s',
                onComplete: function() {
                    el.removeClass("transition visible");
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
         * @method _openStyler
         * @return 
         */
        _openStyler: function() {
            $('#style-helper').transition('scale');
        },
		
        /**
         * Description
         * @method _closeStyler
         * @param {} auto
         * @return 
         */
        _closeStyler: function(auto) {
            if (auto === "auto" && $('#style-helper').is(".visible")) {
                $('#style-helper').transition('fade out');
            } else {
                $("#style-helper").on('click', 'a.close-styler', function(event) {
                    $('#style-helper').transition('fade out');
                    event.preventDefault();
                });
            }

        },
		
        /**
         * Description
         * @method _openEditor
         * @return 
         */
        _openEditor: function() {
            $('#element-helper').transition('scale');
        },
		
        /**
         * Description
         * @method _closeEditor
         * @param {} auto
         * @return 
         */
        _closeEditor: function(auto) {
            if (auto === "auto" && $('#element-helper').is(".visible")) {
                $('#element-helper').transition('fade out');
            } else {
                $("#element-helper").on('click', 'a.close-editor', function(event) {
                    $('#element-helper').transition('fade out');
                    event.preventDefault();
                });
            }
        },

        /**
         * Description
         * @method _openAnimator
         * @return 
         */
        _openAnimator: function() {
            $('#animation-helper').transition('scale');
        },
		
        /**
         * Description
         * @method _closeAnimator
         * @param {} auto
         * @return 
         */
        _closeAnimator: function(auto) {
            if (auto === "auto" && $('#animation-helper').is(".visible")) {
                $('#animation-helper').transition('fade out');
            } else {
                $("#animation-helper").on('click', 'a.close-animator', function(event) {
                    $('#animation-helper').transition('fade out');
                    event.preventDefault();
                });
            }
        },
		
        /**
         * Description
         * @method _prepareEditor
         * @param {} type
         * @param {} node
         * @return 
         */
        _prepareEditor: function(type, node) {
            var plugin = this;
            $("#element-helper .eltype").hide();
            if (typeof type === "undefined") {
                switch (node) {
                    case "a":
                        plugin._editUrl();
                        $("#element-helper [data-field='elementUrl']").show();
                        break;

                    case "img":
                        plugin._editImage();
                        $("#element-helper [data-field='elementImage']").show();
                        break;
                }
            } else {
                switch (type) {
                    case "button":
                        plugin._editButton();
                        $("#element-helper [data-field='elementButton']").show();
                        $("#element-helper [data-field='elementLink']").show();
                        $("#element-helper [data-field='elementIcon']").show();
                        break;

                    case "icon":
                        plugin._editIcon();
                        $("#element-helper [data-field='elementIcon']").show();
                        $("#element-helper [data-field='elementIconAssets']").show();
                        break;

                    case "gmap":
                        plugin._editMap();
                        $("#element-helper [data-field='elementMap']").show();
                        break;

                    case "video":
                        plugin._editVideo();
                        $("#element-helper [data-field='elementVideo']").show();
                        break;

                    case "sound":
                        plugin._editSound();
                        $("#element-helper [data-field='elementSound']").show();
                        break;
                }
            }
        },
		
        /**
         * Description
         * @method _editUrl
         * @return 
         */
        _editUrl: function() {
            var plugin = this;
            var title = $activeElement.attr("title");
            var url = $activeElement.attr("href");
            var target = $activeElement.attr("target");

            $("input[name='elementUrlTitle']").val(title);
            $("input[name='urlTrget']").prop("checked", (target === "_blank") ? true : false);

            if (typeof url !== "undefined") {
                $("#elementUrl").val(url);
            }

            $("input[name='elementUrlTitle']").on("keyup", function() {
                clearTimeout($.data(this, "timer"));
                var wait = setTimeout(function() {
                    plugin._parseUrl();
                }, 500);
                $(this).data("timer", wait);
            });

            $("#elementUrl").on("keyup", function() {
                clearTimeout($.data(this, "timer"));
                var wait = setTimeout(function() {
                    plugin._parseUrl();
                }, 500);
                $(this).data("timer", wait);
            });

            $("input[name='urlTrget']").on("change", function() {
                clearTimeout($.data(this, "timer"));
                var wait = setTimeout(function() {
                    plugin._parseUrl();
                }, 500);
                $(this).data("timer", wait);
            });
        },
		
        /**
         * Description
         * @method _parseUrl
         * @return 
         */
        _parseUrl: function() {
            var plugin = this;

            var title = plugin._htmlSpecialChars($("input[name='elementUrlTitle']").val());
            var url = plugin._htmlSpecialChars($("#elementUrl").val());
            var target = ($("input[name='urlTrget']").is(':checked')) ? "_blank" : "_self";

            if (url.trim() === "") {
                $activeElement.wrapInner('<span class="live"></span>');
                $activeElement = $activeElement.find("span");
                $activeElement.parent().contents().unwrap();
            } else {
                if ($activeElement.is("span")) {
                    var text = $activeElement.text();
                    var element = $('<a href="' + url + '">' + text + '</a>').attr({
                        title: title,
                        target: target
                    });

                    $activeElement.replaceWith(element);
                    $activeElement = element;
                } else {
                    $activeElement.attr({
                        href: url,
                        title: title,
                        target: target,
                    });
                }
            }
        },
		
        /**
         * Description
         * @method _editImage
         * @return 
         */
        _editImage: function() {
            var plugin = this;
            var title = $activeElement.attr("title");
            var alt = $activeElement.attr("alt");
            var img = $activeElement.attr("src");
            var classList = $activeElement.attr('class').split(/\s+/);
            var lightbox = ($activeElement).prev("a") ? true : false;

            $("[data-field='elementImage']").find("#imageStyles span").removeClass("outline");

            $.each(classList, function(index, item) {
                $("[data-field='elementImage']").find("#imageStyles span[data-value='" + item + "']").addClass("outline");
            });

            $("input[name='elementImageTitle']").val(title);
            $("input[name='elementImageDesc']").val(alt);
            $("#imageWrap").html('<img src="' + img + '">');
            $("#input[name='imageLightbox']").prop("checked", lightbox);

            $("[data-field='elementImage']").on("click", "#imageStyles span", function() {
                $("#imageStyles span").removeClass("outline");
                $(this).addClass("outline");
                plugin._parseImage();
            });

            $("input[name='elementImageTitle']").on("keyup", function() {
                clearTimeout($.data(this, "timer"));
                var wait = setTimeout(function() {
                    plugin._parseImage();
                }, 500);
                $(this).data("timer", wait);
            });

            $("input[name='elementImageDesc']").on("keyup", function() {
                clearTimeout($.data(this, "timer"));
                var wait = setTimeout(function() {
                    plugin._parseImage();
                }, 500);
                $(this).data("timer", wait);
            });

            $("input[name='imageLightbox']").on("change", function() {
                clearTimeout($.data(this, "timer"));
                var wait = setTimeout(function() {
                    plugin._parseImage();
                }, 500);
                $(this).data("timer", wait);
            });
            $('.rangers').on('asRange::change', function(event, el) {
                switch (el.$element.prop('name')) {
                    case "image_gs":
                        fProps.grayscale = el.value;
                        plugin._buildImageFilter();
                        break;

                    case "image_blur":
                        fProps.blur = el.value;
                        plugin._buildImageFilter();
                        break;

                    case "image_br":
                        fProps.brightness = el.value;
                        plugin._buildImageFilter();
                        break;

                    case "image_ct":
                        fProps.contrast = el.value;
                        plugin._buildImageFilter();
                        break;

                    case "image_hue":
                        fProps.hueRotate = el.value;
                        plugin._buildImageFilter();
                        break;

                    case "image_opacity":
                        fProps.opacity = el.value;
                        plugin._buildImageFilter();
                        break;

                    case "image_invert":
                        fProps.invert = el.value;
                        plugin._buildImageFilter();
                        break;

                    case "image_saturate":
                        fProps.saturate = el.value;
                        plugin._buildImageFilter();
                        break;

                    case "image_sepia":
                        fProps.sepia = el.value;
                        plugin._buildImageFilter();
                        break;
                }
            });

            $("button[name='imageFilterReset']").on("click", function() {
                clearTimeout($.data(this, "timer"));
                var wait = setTimeout(function() {
                    fProps = {
                        "grayscale": 0,
                        "blur": 0,
                        "brightness": 100,
                        "contrast": 100,
                        "hueRotate": 0,
                        "opacity": 100,
                        "invert": 0,
                        "saturate": 100,
                        "sepia": 0,
                    };
                    $activeElement.css("filter", "");

                    $('input[name=image_gs]').asRange('set', 0);
                    $('input[name=image_blur]').asRange('set', 0);
                    $('input[name=image_br]').asRange('set', 100);
                    $('input[name=image_ct]').asRange('set', 100);
                    $('input[name=image_hue]').asRange('set', 0);
                    $('input[name=image_opacity]').asRange('set', 100);
                    $('input[name=image_invert]').asRange('set', 0);
                    $('input[name=image_saturate]').asRange('set', 100);
                    $('input[name=image_sepia]').asRange('set', 0);
                }, 200);
                $(this).data("timer", wait);
            });
            $("#imageWrap").off('click').on('click', function() {
                $.get(plugin.options.url + '/managerBuilder.php', {
                    pickFile: 1,
                    editor: true
                }).done(function(data) {
                    var modal =
                        '<div id="fileModal" class="yoyo fullscreen modal">' +
                        '<div class="yoyo content">' + data + '</div>' +
                        '</div>';
                    $(modal).modal('setting', 'onShow', function() {
                        var cmodal = this;
                        $("#result").on('click', '.is_file', function() {
                            var dataset = $(this).data('set');
                            if (dataset.image === "true") {
                                $("#imageWrap").html('<img src="' + plugin.options.surl + '/uploads/' + dataset.url + '">');
                                plugin._parseImage();
                                $(cmodal).modal('hide');
                            }
                        });
                    }).modal('setting', 'onHidden', function() {
                        $(this).remove();
                    }).modal('show');
                });
            });
        },
		
        /**
         * Description
         * @method _parseImage
         * @return 
         */
        _parseImage: function() {
            var plugin = this;
            var style = $("[data-field='elementImage']").find("#imageStyles span.outline").data("value");

            var title = plugin._htmlSpecialChars($("input[name='elementImageTitle']").val());
            var alt = plugin._htmlSpecialChars($("input[name='elementImageDesc']").val());

            var img = $("#imageWrap").find("img").attr("src");

            if ($("input[name='imageLightbox']").is(':checked')) {
                if ($activeElement.parent().is("a")) {
                    $activeElement.unwrap();
                }
                $activeElement.wrap('<a href="' + img + '" class="lightbox"></a>');
            } else {
                if ($activeElement.parent().is("a")) {
                    $activeElement.unwrap();
                }
            }

            $activeElement.removeClass("basic rounded circular shadow");
            $activeElement.attr({
                src: img,
                title: title,
                alt: alt
            }).addClass(style);
        },
		
        /**
         * Description
         * @method _editButton
         * @return 
         */
        _editButton: function() {
            var plugin = this;
            var text = $activeElement.text();
            var url = $activeElement.attr("href");
            var icon = $activeElement.children(".icon");
            var classList = $activeElement.attr('class').split(/\s+/);

            $.each(classList, function(index, item) {
                $("[data-field='elementButton']").find("button[data-value='" + item + "']").addClass("active");
                if (item !== "mini" || item !== "small" || item !== "big") {
                    $("[data-field='elementButton']").find("button[name='buttonSize'][data-value='']").addClass("active");
                }

                if (item !== "rounded" || item !== "circular") {
                    $("[data-field='elementButton']").find("button[name='buttonStyle'][data-value='']").addClass("active");
                }

                if (item !== "spinning") {
                    $("[data-field='elementButton']").find("button[name='buttonSpin'][data-value='0']").addClass("active");
                }

                if (item !== "fluid") {
                    $("[data-field='elementButton']").find("button[name='buttonWidth'][data-value='']").addClass("active");
                }

                if (item === "icon" && item === "right") {
                    $("[data-field='elementButton']").find("button[name='buttonIcon'][data-value='right']").addClass("active");
                } else {
                    $("[data-field='elementButton']").find("button[name='buttonIcon'][data-value='']").addClass("active");
                }
            });
			
            $("#buttonText").val(text);

            if (typeof url === "undefined") {
                $("[data-field='elementButton']").find("button[name='buttonType'][data-value='button']").addClass("active");
            } else {
                $("#elementLink").val(url);
                $("[data-field='elementButton']").find("button[name='buttonType'][data-value='a']").addClass("active");
                $("[data-field='elementButton']").find("button[name='buttonType'][data-value='button']").removeClass("active");
            }
            if (icon.length) {
                var iclass = icon.attr("class");
                $("#elementIcon").find("a[data-icon=" + iclass.replace(/ /g, "-") + "]").addClass("active");
            }

            $("button[name='buttonSpin']").click(function() {
                if ($(this).data("value") === 1) {
                    $("[name='buttonSpin']:eq(0) .icon").addClass("positive");
                    $("[name='buttonSpin']:eq(1) .icon").removeClass("negative");
                } else {
                    $("[name='buttonSpin']:eq(0) .icon").removeClass("positive");
                    $("[name='buttonSpin']:eq(1) .icon").addClass("negative");
                }
            });

            $("[data-field='elementButton']").on("click", "button", function() {
                var name = $(this).prop("name");
				$("button[name='" + name + "']").removeClass("active");
				$(this).addClass("active");	
                plugin._parseButton();
            });

            $("#elementIcon a").on("click", function() {
                $("#elementIcon a").removeClass("active");
                $(this).addClass("active");
                plugin._parseButton();
                return false;
            });

            $("#buttonText").on("keyup", function() {
                clearTimeout($.data(this, "timer"));
                var wait = setTimeout(function() {
                    plugin._parseButton();
                }, 500);
                $(this).data("timer", wait);
            });

            $("#elementLink").on("keyup", function() {
                clearTimeout($.data(this, "timer"));
                var wait = setTimeout(function() {
                    $("[data-field='elementButton']").find("button[name='buttonType'][data-value='a']").addClass("active");
                    $("[data-field='elementButton']").find("button[name='buttonType'][data-value='button']").removeClass("active");
                    plugin._parseButton();
                }, 500);
                $(this).data("timer", wait);
            });

            plugin._linkList($("#linkDrop .scrolling"));

            $("#linkDrop").on('click', 'a.item', function() {
                $("#linkDrop").find("a.item").removeClass("selected");
                var val = $(this).data("value");
                if (val === "none") {
                    $("[data-field='elementButton']").find("button[name='buttonType'][data-value='a']").removeClass('active');
                    $("[data-field='elementButton']").find("button[name='buttonType'][data-value='button']").addClass('active');
                } else {
                    $('#elementLink').val($(this).data("value"));
                    $("[data-field='elementButton']").find("button[name='buttonType'][data-value='a']").addClass('active');
                    $("[data-field='elementButton']").find("button[name='buttonType'][data-value='button']").removeClass('active');
                }
                plugin._parseButton();
            });
        },
		
        /**
         * Description
         * @method _parseButton
         * @return 
         */
        _parseButton: function() {
            var plugin = this;
            var size = $("button[name='buttonSize'].active").data("value");
            var color = $("button[name='buttonColor'].active").data("value");
            var type = $("button[name='buttonType'].active").data("value");
            var style = $("button[name='buttonStyle'].active").data("value");
            var fluid = $("button[name='buttonWidth'].active").data("value");
            var iconplace = $("button[name='buttonIcon'].active").data("value");
            var spin = $("button[name='buttonSpin'].active").data("value");
            var icon = $('#elementIcon .active .icon');
            var icon_html = (icon.length) ? '<i class="' + icon.attr("class") + (spin === 1 ? ' spinning' : '') + '"></i>' : '';

            var text = plugin._htmlSpecialChars($("#buttonText").val());
            var single = (text ? "" : "icon ");
            var is_url = (type === "a") ? $("#elementLink").val() : "#";

            var icon_space = '';
            if (iconplace === "right") {
                icon_space = text + icon_html;
            } else {
                icon_space = icon_html + text;
            }

            var button = $("<" + type + (type === 'a' ? ' href="' + is_url + '"' : '') + ">").attr({
                'data-type': "button",
                'class': "yoyo button " + single + color + " " + style + (fluid ? " " + fluid : "") + (size ? " " + size : "") + " " + iconplace + " live",
            }).html(icon_space);

            $activeElement.replaceWith(button);
            $activeElement = button;
        },
		
        /**
         * Description
         * @method _editIcon
         * @return 
         */
        _editIcon: function() {
            var plugin = this;
            var classList = $activeElement.attr('class').split(/\s+/);
            var size = '';
            var rev = plugin._inverseArray(iconSize);
            var arr = ['tiny', 'small', 'medium', 'large', 'big', 'huge'];

            $.each(classList, function(index, item) {
                $("[data-field='elementIconAssets']").find("button[data-value='" + item + "']").addClass('active');
                if (arr.indexOf(item) !== -1) {
                    size = rev[item];
                }
                if (item !== 'rounded' || item !== 'circular') {
                    $("[data-field='elementIconAssets']").find("button[name='iconStyle'][data-value='']").addClass('active');
                }
            });

            var iclass = $activeElement.attr("class");
            iclass = iclass.replace(/huge|big|large|medium|small|tiny|rounded|circular|primary|secondary|positive|negative|inverted|normal|live/g, '').replace(/  +/g, ' ');
            iclass = $.trim(iclass).replace(/ /g, "-");
            $("#elementIcon").find("a[data-icon=" + iclass + "]").addClass("active");

            $("[data-field='elementIconAssets']").on("click", "button", function() {
                var name = $(this).prop("name");
                $("button[name='" + name + "']").removeClass("active");
                $(this).addClass('active');

                if ($(this).data("value") === "normal") {
                    $("button[name='iconStyle']").removeClass("active");
                }

                plugin._parseIcon();
            });

            $("#elementIcon a").on("click", function() {
                $("#elementIcon a").removeClass("active");
                $(this).addClass("active");
                plugin._parseIcon();
                return false;
            });
        },
		
        /**
         * Description
         * @method _parseIcon
         * @return 
         */
        _parseIcon: function() {
            var size = iconSize[$("input[name='iconSize']").val()];
            var color = $("button[name='iconColor'].active").data("value");
            var icon = $('#elementIcon .active .icon').attr("class");
            var type = $("button[name='iconType'].active").data("value");
            var style = $("button[name='iconStyle'].active").data("value");

            var element = $("<i>").attr({
                'data-type': "icon",
                'class': icon + ' ' + color + ' ' + (size ? ' ' + size : '') + ' ' + (type ? ' ' + type : '') + ' ' + (style ? ' ' + style : ''),
            });

            $activeElement.replaceWith(element);
            $activeElement = element;
        },
		
        /**
         * Description
         * @method _editMap
         * @return 
         */
        _editMap: function() {
            var plugin = this;
            $activeElement = $activeElement.children("iframe");
            var url = $activeElement.attr('src');
            $("textarea[name=elementMapUrl]").val("<iframe src=\"" + url + "\"></iframe>");

            $("button[name='elementMap']").on("click", function() {
                var string = $("textarea[name=elementMapUrl]").val();
                if ($.trim(string).length > 1) {
                    if (string.match(/(<iframe.+?<\/iframe>)/g)) {
                        plugin._parseMap($(string).url());
                    } else {
                        $.notice(plugin.options.lang.msgUrlError, {
                            autoclose: 5000,
                            type: "error",
                            title: "Error"
                        });
                    }
                }
            });
        },
		
        /**
         * Description
         * @method _parseMap
         * @param {} url
         * @return 
         */
        _parseMap: function(url) {
            var iframe = $("<iframe>").attr({
                'src': url.data.attr.base + url.data.attr.path + '?' + url.data.attr.query,
            });

            $activeElement.replaceWith(iframe);
            $activeElement = iframe;
        },
		
        /**
         * Description
         * @method _editVideo
         * @return 
         */
        _editVideo: function() {
            var plugin = this;
            $activeElement = $activeElement.children("iframe");
            var url = $activeElement.attr('src');
            $("input[name=elementVideoUrl]").val(url);

            $("button[name='elementVideo']").on("click", function() {
                var string = $("input[name=elementVideoUrl]").val();
                if ($.trim(string).length > 1) {
                    var object = plugin._videoUrl(string);
                    if (object.id) {
                        plugin._parseVideo(object);
                    } else {
                        $.notice(plugin.options.lang.msgUrlError, {
                            autoclose: 5000,
                            type: "error",
                            title: "Error"
                        });
                    }
                }
            });
        },
		
        /**
         * Description
         * @method _parseVideo
         * @param {} videoObj
         * @return 
         */
        _parseVideo: function(videoObj) {
            var $iframe = $('<iframe>', {
                width: "100%"
            });
            $iframe.attr('frameborder', 0);

            switch (videoObj.type) {
                case "youtube":
                    $iframe.attr('src', '//www.youtube.com/embed/' + videoObj.id);
                    break;

                case "vimeo":
                    $iframe.attr('src', '//player.vimeo.com/video/' + videoObj.id);
                    break;

                case "dailymotion":
                    $iframe.attr('src', '//www.dailymotion.com/embed/video/' + videoObj.id);
                    break;

            }
            $activeElement.replaceWith($iframe);
            $activeElement = $iframe;
        },
		
        /**
         * Description
         * @method _editSound
         * @return 
         */
        _editSound: function() {
            var plugin = this;
            $activeElement = $activeElement.children("iframe");
            var url = $activeElement.attr('src');
            $("input[name=elementSoundUrl]").val(url);

            $("button[name='elementSound']").on("click", function() {
                var string = $("input[name=elementSoundUrl]").val();
                if ($.trim(string).length > 1) {
                    var object = plugin._soundUrl(string);
                    if (object.id) {
                        plugin._parseSound(object);
                    } else {
                        $.notice(plugin.options.lang.msgUrlError, {
                            autoclose: 5000,
                            type: "error",
                            title: "Error"
                        });
                    }
                }
            });
        },
		
        /**
         * Description
         * @method _parseSound
         * @param {} soundObj
         * @return 
         */
        _parseSound: function(soundObj) {
            $.getJSON('//soundcloud.com/oembed?format=js&url=https://soundcloud.com/' + soundObj.id + '&iframe=true&callback=?', function(response) {
                $activeElement.replaceWith(response.html);
                $activeElement = $(response.html);
            });
        },
		
        /**
         * Description
         * @method _inverseArray
         * @param {} obj
         * @return new_obj
         */
        _inverseArray: function(obj) {
            var new_obj = {};
            for (var prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    new_obj[obj[prop]] = prop;
                }
            }

            return new_obj;
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
         * @method _videoUrl
         * @param {} url
         * @return ObjectExpression
         */
        _videoUrl: function(url) {
            url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com)|dailymotion.com)\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);
            var type = '';
            if (RegExp.$3.indexOf('youtu') > -1) {
                type = 'youtube';
            } else if (RegExp.$3.indexOf('vimeo') > -1) {
                type = 'vimeo';
            } else if (RegExp.$3.indexOf('dailymotion') > -1) {
                type = 'dailymotion';
            } else {
                type = false;
            }

            return {
                type: type,
                id: RegExp.$6
            };
        },
		
        /**
         * Description
         * @method _soundUrl
         * @param {} url
         * @return ObjectExpression
         */
        _soundUrl: function(url) {
            var regexp = /^https?:\/\/(soundcloud\.com|snd\.sc)\/(.*)$/;
            var type, id = '';
            if (url.match(regexp)[1] === "soundcloud.com") {
                type = url.match(regexp)[1];
                id = url.match(regexp)[2];
            }
            return {
                type: type,
                id: id
            };
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
            code.css('white-space', 'pre').val(this._undoTags(el.html()));

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
		
		
        /**
         * Description
         * @method destroy
         * @return 
         */
        destroy: function() {
            this.unbindEvents();
            this.$element.removeData();
        }
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
        burl: "",
		pagename: "",
        lang: {
            btnOk: "ok",
            btnCancel: "cancel",
            msgUndone: "Are you sure you want to restore it, this action can not be undone!",
            msgUrlError: "Invalid url detected!!!",
        }
    };

})(jQuery, window, document);