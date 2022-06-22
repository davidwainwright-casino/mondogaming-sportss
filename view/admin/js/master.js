(function($) {
    "use strict";
    $.Master = function(settings) {
        var config = {
            weekstart: 0,
            ampm: 0,
            url: '',
			aurl: '',
            editorCss: '',
            lang: {
                monthsFull: '',
                monthsShort: '',
                weeksFull: '',
                weeksShort: '',
                weeksMed: '',
                today: "Today",
                now: "Now",
                selPic: "Select Picture",
                delBtn: "Delete Record",
                trsBtn: "Move to Trash",
                arcBtn: "Move to Archive",
                uarcBtn: "Restore From Archive",
                restBtn: "Restore Item",
                canBtn: "Cancel",
                clear: "Clear",
				sellected: "Selected",
				allBtn: "Select All",
				allSel: "all selected",
				sellOne: "Select option",
				doSearch: "Search ...",
				noMatch: "No matches for",
				ok: "OK",
                delMsg1: "Are you sure you want to delete this record?",
                delMsg2: "This action cannot be undone!!!",
                delMsg3: "Trash",
                delMsg5: "Move [NAME] to the archive?",
                delMsg6: "Remove [NAME] from the archive?",
                delMsg7: "Restore [NAME]?",
                delMsg8: "The item will remain in Trash for 30 days. To remove it permanently, go to Trash and empty it.",
                working: "working..."
            }
        };

        if (settings) {
            $.extend(config, settings);
        }
        
        /* == Nav == */
        $("nav li.active").addClass("open").children("ul").show();
        $("nav li.has-sub > a").on("click", function() {
            $(this).removeAttr("href");
            var element = $(this).parent("li");
            if (element.hasClass("open")) {
                element.removeClass("open");
                element.find("li").removeClass("open");
                element.find("ul").slideUp(200);
            } else {
                element.addClass("open");
                element.children("ul").slideDown(200);
                element.siblings("li").children("ul").slideUp(200);
                element.siblings("li").removeClass("open");
                element.siblings("li").find("li").removeClass("open");
                element.siblings("li").find("ul").slideUp(200);
            }
        });

        $('#mtoggle').on('click', function() {
            $(this).toggleClass('active');
            $("body").toggleClass("active");
            $('aside').transition('slide right');
        });

        /* == Bg Changer == */
		$("a.sbg").on('click', function() {
			var img = $(this).data("img");
			$("a.sbg").removeClass("active");
			$(this).addClass("active");
			$(".bg").css("backgroundImage","url(" + config.url + "/images/" + img + ")");
			
            $.cookie("ABG_CMS", img, {
                expires: 120,
                path: '/'
            });
		});

		$('select').yoyoSelect({
			placeholder: config.lang.sellOne,
			captionFormat: '{0} ' + config.lang.sellected,
			captionFormatAllSelected: '{0} ' + config.lang.allSel,
			searchText: config.lang.doSearch,
			noMatch: config.lang.noMatch + ' "{0}"',
			locale: [config.lang.ok, config.lang.canBtn, config.lang.allBtn],
		});
		
		$('.optiscroll').optiscroll();
		$(".yoyo.tags").tagsinput();
		$('.yoyo.progress').progress();
		$('.yoyo.accordion').accordion();
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

		$('.spinner').yoyoSpinner();
        $('[data-content]').popup({
            variation: "mini inverted",
			inline:true,
			
        });
		/* == Responsive Tables == */
		//$('.yoyo.table:not(.unstackable)').responsiveTable(); 
		$("table.sorting").tablesort();
		
		$('#randPass').click(function() {
			$(this).prev('input').val($.password(8));
		});

        /* == Transitions == */
		$(document).on('click', '[data-transition="true"]', function () {
			var type = $(this).data('type');
			var hide = $(this).data('hide');
			var show = $(this).data('show');
			var trigger = $(this).data('trigger');
			var $icon = $(this).find(".icon");
			$icon.toggleClass("up down");
			
			if (hide) {
				$(hide).transition('fade');
			}
			if (show) {
				$(show).transition('fade');
			}
			$(trigger).transition(type);
		});

        /* == Datepicker == */
        $('[data-datepicker]').calendar({
            firstDayOfWeek: config.weekstart,
            today: true,
            type: 'date',
            text: {
                days: config.lang.weeksShort,
                months: config.lang.monthsFull,
                monthsShort: config.lang.monthsShort,
                today: config.lang.today,
            }
        });

        /* == Time Picker == */
        $('[data-timepicker]').calendar({
            firstDayOfWeek: config.weekstart,
            today: true,
            type: 'time',
            ampm: false,
			formatter: {
				time: function (date) {
					return String("00" + date.getHours()).slice(-2) + ':' + String("00" + date.getMinutes()).slice(-2);
				}
			},
            text: {
                days: config.lang.weeksShort,
                months: config.lang.monthsFull,
                monthsShort: config.lang.monthsShort,
                now: config.lang.now
            },
        });

		/* == Input focus == */
		$('.yoyo.input input, .yoyo.input textarea').focusout(function () {
			$('.yoyo.input').removeClass('focus');
		});
		$('.yoyo.input input, .yoyo.input textarea').focusin(function () {
			$(this).closest('.input').addClass('focus');
		});
		
		/* == Tabs == */
		$(".yoyo.tab.item").hide();
		$(".yoyo.tab.item:first").show();
		$(".yoyo.tabs:not(.responsive) a").on('click', function () {
			$(".yoyo.tabs:not(.responsive) li").removeClass("active");
			$(this).parent().addClass("active");
			$(".yoyo.tab.item").hide();
			var activeTab = $(this).data("tab");
			if ($(activeTab).is(':first-child')) {
				$(activeTab).parent().addClass('tabbed');
			} else {
				$(activeTab).parent().removeClass('tabbed');
			}
			$(activeTab).show();
			return false;
		});

		/* == Dimmable content == */
		$(document).on('change', '.is_dimmable', function () {
			var dataset = $(this).data('set');
			var status = $('input[type=checkbox]', this).is(':checked') ? 1 : 0;
			var result = $.extend(true, dataset.option[0], {
				"active": status
			});
			$.post(config.url + "/helper.php", result);
			$(dataset.parent).dimmer({
				variation: "inverted",
				closable: false
			}).dimmer('toggle');
		});

        /* == Fluid Grid == */
		$('.yoyo.blocks').waitForImages().done(function() {
			$('.yoyo.blocks').pinto();
		});
		
        /* == Basic color picker == */
        $('[data-color="true"]').spectrum({
            showPaletteOnly: true,
            showPalette:true,
			move: function(color) {
				var newcolor = color.toHexString();
				$(this).css('background', newcolor);
				$(this).prev('input').val(newcolor);
			}
        });

        /* == Advanced color picker == */
      $('[data-adv-color="true"]').spectrum({
          showInput: true,
          showAlpha: true,
          move: function(color) {
              var rgba = "transparent";
              if (color) {
                  rgba = color.toRgbString();
				  var el = $(this).data('element') === "child" ? $(this).children() : $(this);
				  el.css($(this).data('color'), rgba);
				  $(this).prev('input').val(rgba);
              }
          },
      });
	  
        /* == Avatar Upload == */
        $('[data-type="image"]').ezdz({
            text: config.lang.selPic,
            validators: {
                maxWidth: 3200,
                maxHeight: 1800
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
					$.notice(decodeURIComponent(file.name + ' must be an image.'), {
						autoclose: 4000,
						type: "error",
						title: 'Error'
					});
                }
                if (errors.maxWidth || errors.maxHeight) {
					$.notice(decodeURIComponent(file.name + ' must be width:3200px, and height:1800px  max.'), {
						autoclose: 4000,
						type: "error",
						title: 'Error'
					});
                }
            }
        });
					
        /* == Editor == */
        $('.bodypost').redactor({
            replaceTags: {
                'b': 'strong',
                'strike': 'del'
            },
            plugins: ['alignment', 'fontcolor', 'wicons', 'video', 'imagemanager', 'definedlinks', 'fullscreen'],
            definedlinks: config.url + "/helper.php?doAction=1&page=getlinks",
            imageUpload: config.url + "/process/managerController.php",
            imageManagerJson: config.url + "/process/managerController.php?action=getImages",
            imageData: {
                action: "eupload",
            },
			iconManagerJson: config.url + "/snippets/iconset.json",
            callbacks: {
                image: {
                    uploadError: function(json) {
                        $.notice(json.message, {
                            autoclose: 12000,
                            type: json.type,
                            title: json.title
                        });
                    }
                },
                file: {
                    uploadError: function(json) {
                        $.notice(json.message, {
                            autoclose: 12000,
                            type: json.type,
                            title: json.title
                        });
                    }
                }
            }
        });

        $('.altpost').redactor({
			minHeight: "200px",
			plugins: ['alignment', 'fontcolor', 'definedlinks', 'fullscreen'],
        });

        /* == Simple Status Actions == */
        $(document).on('click', '.simpleAction', function() {
            var dataset = $(this).data("set");
            var $parent = dataset.parent;
            $.ajax({
                type: 'POST',
                url: config.url + dataset.url,
                dataType: 'json',
                data: dataset.option[0]
            }).done(function(json) {
                if (json.type === "success") {
                    switch (dataset.after) {
                        case "remove":
                            $($parent).transition({
                                animation: 'scale',
                                onComplete: function() {
                                    $($parent).remove();
                                }
                            });
						break;
						
                        case "replace":
                            $($parent).html(json.html).transition('fade in');
                        break;
						
                        case "prepend":
							$($parent).prepend(json.html).transition('fade in');
                        break;
						
                    }
									
                    if (dataset.redirect) {
						setTimeout(function() {
							$("body").transition({
								animation: 'scale'
							});
							window.location.href = json.redirect;
						}, 800);
                    }
                }
				
				if (json.message) {
					$.notice(decodeURIComponent(json.message), {
						autoclose: 12000,
						type: json.type,
						title: json.title
					});
				}
            });
        });
		
        /* == Inline Edit == */
        $('#editable, .wedit').editableTableWidget();
        $('#editable, .wedit')
            .on('validate', '[data-editable]', function(e, val) {
                if (val === "") {
                    return false;
                }
            })
            .on('change', '[data-editable]', function(e, val) {
                var data = $(this).data('set');
                var $this = $(this);
                $.ajax({
                    type: "POST",
                    url: (data.url) ? data.url : config.url + "/helper.php",
                    dataType: "json",
                    data: ({
                        'title': val,
                        'type': data.type,
                        'key': data.key,
                        'path': data.path,
                        'id': data.id,
                        'quickedit': 1
                    }),
                    beforeSend: function() {
                        $this.html('<i class="icon spinning spinner circles"></i>').animate({
                            opacity: 0.2
                        }, 800);
                    },
                    success: function(json) {
                        $this.animate({
                            opacity: 1
                        }, 800);
                        setTimeout(function() {
                            $this.html(json.title).fadeIn("slow");
                        }, 1000);
                    }
                });
            });
			
        /* == Clear Session Debug Queries == */
        $("#debug-panel").on('click', 'a.clear_session', function() {
            $.get(config.url + '/helper.php', {
                ClearSessionQueries: 1
            });
            $(this).css('color', '#222');
        });
			
        /* == From/To date range == */
        $('#fromdate').calendar({
            weekStart: config.weekstart,
            type: 'date',
            endCalendar: $('#enddate')
        });
        $('#enddate').calendar({
            weekStart: config.weekstart,
            type: 'date',
            startCalendar: $('#fromdate')
        });

        $('#fromtime').calendar({
            weekStart: config.weekstart,
            type: 'time',
			ampm: config.ampm,
            endCalendar: $('#endtime')
        });
        $('#endtime').calendar({
            weekStart: config.weekstart,
            type: 'time',
			ampm: config.ampm,
            startCalendar: $('#fromtime')
        });
		
		/* == Single File Picker == */
		$(document).on('click', '.filepicker', function() {
		    var parent = $(this).data('parent');
		    var type = $(this).data('ext');
			var update = $(this).data('update');
		    $.get(config.url + '/managerBuilder.php', {
		        pickFile: 1,
				editor: true
		    }).done(function(data) {
		        var modal =
		            '<div id="fileModal" class="yoyo large modal">' +
		            '<div class="scrolling content">' + data + '</div>' +
		            '</div>';
		        $(modal).modal('setting', 'onShow', function() {
		            var cmodal = this;
		            $("#result").on('click', '.is_file', function() {
		                var dataset = $(this).data('set');
		                switch (type) {
		                    case "images":
		                        if (dataset.image === "true") {
		                            $(parent).val(dataset.url);
		                            $(cmodal).modal('hide');
		                        }
		                        break;
		                    case "videos":
		                        if (dataset.ext === "mp4" || dataset.ext === "ogv" || dataset.ext === "wembm") {
		                            $(parent).val(dataset.url);
		                            $(cmodal).modal('hide');
		                        }
		                        break;
								
		                }
						if(update) {
							$(update.id).attr('src', update.src + dataset.url);
						}

		            });
		        }).modal('setting', 'onHidden', function() {
		            $(this).remove();
		        }).modal('show');
		    });
		});
		
        /* == Search == */
		var timeout;
        $(document).on('keyup', '#masterSearch', function() {
			window.clearTimeout(timeout);
			var $button = $(this).next(".button");
            var srch_string = $(this).val();
			
			var page = $.url().segment(-1);
			var type = $.url().segment(-2);
			var url = '';
			
			if(type === "modules" || type === "plugins") {
				url = config.url + '/' + type + '_/' + page + '/controller.php';
			} else {
				url = config.url + '/helper.php';
			}
		
            if (srch_string.length > 3) {
				$button.addClass('loading');
				timeout = window.setTimeout(function(){
					$.ajax({
						type: "get",
						dataType: 'json',
						url: url,
						data: {
							liveSearch: 1,
							value: srch_string,
							type: page
						},
						success: function(json) {
							if(json.status === "success") {
								$('#mResults .padding').html(json.html);
								$('#mResults').dimmer({
									opacity: '.97',
									transition: 'scale'
								}).dimmer('show');
								
								$('#mResults a.close').click(function() {
									$('#mResults').dimmer('hide');
								});
							}
							$button.removeClass('loading');
						},
						error: function () {
							$button.removeClass('loading');
						}
					});
				},700);
            }
            return false;
        });

        /* == Master Form == */
        $(document).on('click', 'button[name=dosubmit]', function() {
            var $button = $(this);
            var action = $(this).data('action');
			var $form = $(this).closest("form");
			var asseturl = $(this).data('url');

            function showResponse(json) {
                setTimeout(function() {
                    $($button).removeClass("loading").prop("disabled", false);
                }, 500);
                $.notice(json.message, {
                    autoclose: 12000,
                    type: json.type,
                    title: json.title
                });
                if (json.type === "success" && json.redirect) {
					$('#wrapper')
					  .transition({
						animation  : 'scale',
						duration   : '1s',
						onComplete : function() {
						  window.location.href = json.redirect;
						}
					  });
                }
            }

            function showLoader() {
                $($button).addClass("loading").prop("disabled", true);
            }
            var options = {
                target: null,
                beforeSubmit: showLoader,
                success: showResponse,
                type: "post",
                url: asseturl ? config.url + "/" + asseturl + "/controller.php" : config.url + "/controller.php",
                data: {
                    action: action
                },
                dataType: 'json'
            };

            $($form).ajaxForm(options).submit();
        });
		
        /* == Add/Edit Modal Actions == */
        $(document).on('click', '.addAction', function() {
            var dataset = $(this).data("set");
            var $parent = dataset.parent;
            var $this = $(this);
			var actions = '';
			var asseturl = dataset.url;
			var closeb = dataset.buttons === false ? '<a class="close"><i class="icon close"></i></a>' : '';
			var url = asseturl ? config.url + "/" + asseturl : config.url + "/controller.php";

            $.get(url, dataset.option[0], function(data) {
				if(dataset.buttons !== false) {
					actions += '' +
                    '<div class="actions">' +
					'<div class="yoyo simple small cancel button">' + config.lang.canBtn + '</div>' +
					'<div class="yoyo ok small primary button">' + dataset.label + '</div>' +
                    '</div>';
				}
                var $modal = $('<div class="yoyo ' + dataset.modalclass + ' modal">' +
                    '' + closeb + '' +
					'' + data + '' +
                    '' + actions + '' +
                    '</div>');
					$($modal).modal('setting', 'onShow', function() {
					    $('select').yoyoSelect();
						$('[data-datepicker]').calendar({
							firstDayOfWeek: config.weekstart,
							today: true,
							type: 'date',
							text: {
								days: config.lang.weeksShort,
								months: config.lang.monthsFull,
								monthsShort: config.lang.monthsShort,
								today: config.lang.today,
							},
							onChange: function(date, text) {
								if (!date) {
									return '';
								}
								var day = date.getDate();
								var month = config.lang.monthsFull[date.getMonth()];
								var year = date.getFullYear();
								var formatted = month + ' ' + day + ', ' + year;
				
								var element = $(this).data('element');
								var parent = $(this).data('parent');
								$(parent).html(text);
								if ($(element).is(":input")) {
									$(element).val(text);
								} else {
									$(element).html(formatted);
								}
							}
						});
					}).modal('show').modal('setting', 'onApprove', function() {
                    var modal = this;
		
                    $('.ok.button', this).addClass('loading').prop("disabled", true);
                    function showResponse(json) {
                        setTimeout(function() {
                            $('.ok.button', modal).removeClass('loading').prop("disabled", false);
                            if (json.message) {
                                $.notice(decodeURIComponent(json.message), {
                                    autoclose: 12000,
                                    type: json.type,
                                    title: json.title
                                });
                            }
                            if (json.type === "success") {
                                if (dataset.redirect) {
                                    setTimeout(function() {
                                        $("body").transition({
                                            animation: 'scale'
                                        });
                                        window.location.href = json.redirect;
                                    }, 800);
                                } else {
                                    switch (dataset.action) {
										case "replace":
											$($parent).html(json.html).transition('fade in');
											break;
										case "replaceWith":
											$($this).replaceWith(json.html).transition('fade in');
											break;
										case "append":
											$($parent).append(json.html).transition('bounce');
											break;
										case "prepend":
											$($parent).prepend(json.html).transition('bounce');
											break;
										case "update":
											$($parent).replaceWith(json.html).transition('fade in');
											break;
										case "insert":
											if (dataset.mode === "append") {
												$($parent).append(json.html);
											}
											if (dataset.mode === "prepend") {
												$($parent).prepend(json.html);
											}
											break;
										case "highlite":
											$($parent).addClass('highlite');
											break;
										default:
											break;
                                    }
									$('select').yoyoSelect();
									$(modal).modal('hide');
                                }
                            }

                        }, 500);
                    }

                    var options = {
                        target: null,
                        success: showResponse,
                        type: "post",
						url: url,
                        data: dataset.option[0],
                        dataType: 'json'
                    };
                    $('#modal_form').ajaxForm(options).submit();

                    return false;
                }).modal('setting', 'onHidden', function() {
                    $(this).remove();
                });
            });
        });
		
        /* == Modal Delete/Archive/Trash Actions == */
        $(document).on('click', 'a.action', function() {
            var dataset = $(this).data("set");
            var $parent = $(this).closest(dataset.parent);
            var asseturl = dataset.url;
            var subtext = dataset.subtext;
            var children = dataset.children ? dataset.children[0] : null;
            var header;
            var content;
            var icon;
            var btnLabel;
            switch (dataset.action) {
                case "trash":
                    icon = "trash";
                    btnLabel = config.lang.trsBtn;
                    subtext = '<span class="yoyo semi text">' + config.lang.delMsg8 + '</span>';
                    header = config.lang.delMsg3 + " <span class=\"yoyo secondary text\">" + dataset.option[0].title + "?</span>";
                    content = "<i class=\"big circular icon negative trash\"></i>";
                    break;

                case "archive":
                    icon = "briefcase";
                    btnLabel = config.lang.arcBtn;
                    header = config.lang.delMsg5.replace('[NAME]', '<span class=\"yoyo secondary text\">' + dataset.option[0].title + '</span>');
                    content = "<i class=\"big circular icon negative briefcase\"></i>";
                    break;

                case "unarchive":
                    icon = "briefcase alt";
                    btnLabel = config.lang.uarcBtn;
                    header = config.lang.delMsg6.replace('[NAME]', '<span class=\"yoyo secondary text\">' + dataset.option[0].title + '</span>');
                    content = "<i class=\"big circular icon positive briefcase alt\"></i>";
                    break;

                case "restore":
                    icon = "undo";
                    btnLabel = config.lang.restBtn;
                    subtext = '';
                    header = config.lang.delMsg7.replace('[NAME]', '<span class=\"yoyo secondary text\">' + dataset.option[0].title + '</span>');
                    content = "<i class=\"big circular icon positive undo\"></i>";
                    break;

                case "delete":
                    icon = "trash";
                    btnLabel = config.lang.delBtn;
                    subtext = '<span class="yoyo semi text">' + config.lang.delMsg2 + '</span>';
                    header = config.lang.delMsg1;
                    content = "<i class=\"big circular icon negative trash\"></i>";
                    break;
            }

            $('<div class="yoyo tiny modal">' +
                '<div class="header">' + header + '</div>' +
                '<div class="content content-center">' + content + '' +
                '<p class="half-top-padding">' + subtext + '</p>' +
                '</div>' +
                '<div class="actions">' +
                '<div class="yoyo simple small cancel button">' + config.lang.canBtn + '</div>' +
                '<div class="yoyo ok small primary button">' + btnLabel + '</div>' +
                '</div>' +
                '</div>').modal('show').modal('setting', 'onApprove', function() {
                var $this = $(this);
			    var button = $('.ok', $this);
				button.addClass('loading');
			  
                $.ajax({
                    type: 'POST',
                    url: asseturl ? config.url + "/" + asseturl + "/controller.php" : config.url + "/controller.php",
                    dataType: 'json',
                    data: dataset.option[0]
                }).done(function(json) {
                    if (json.type === "success") {
                        if (dataset.redirect) {
                            $this.modal('hide');
                            $.notice(decodeURIComponent(json.message), {
                                autoclose: 4000,
                                type: json.type,
                                title: json.title
                            });
                            setTimeout(function() {
                                $("body").transition({
                                    animation: 'scale'
                                });
                                window.location.href = dataset.redirect;
                            }, 1200);
                        } else {
                            $($parent).transition({
                                animation: 'fade',
                                duration: '1s',
                                onComplete: function() {
                                    $($parent).slideUp();
									$($parent).remove();
									if(dataset.redraw) {
										$(dataset.redraw).pinto();
									}
                                }
                            });
                            if (children) {
                                $.each(children, function(i, values) {
                                    $.each(values, function(k, v) {
                                        if (v === "html") {
                                            $(i).html(json[k]);
                                        } else {
                                            $(i).val(json[k]);
                                        }
                                    });
                                });
                            }

                            $('.big.icon', $this).toggleClass('negative ' + icon + ' positive check transition hidden').transition('pulse').transition({
                                animation: 'fade out',
                                duration: '1s',
                                onComplete: function() {
                                    $this.modal('hide').remove();
                                    $.notice(decodeURIComponent(json.message), {
                                        autoclose: 4000,
                                        type: json.type,
                                        title: json.title
                                    });
                                }
                            });
                        }
                    } else {
						$this.modal('hide').remove();
						$.notice(decodeURIComponent(json.message), {
							autoclose: 4000,
							type: json.type,
							title: json.title
						});
					}
					button.removeClass('loading');
                });
                return false;
            }).modal('setting', 'onHidden', function() {
                $(this).remove();
            });
        });
    };
})(jQuery);