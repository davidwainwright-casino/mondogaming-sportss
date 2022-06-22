(function($, window, document, undefined) {
    "use strict";
    var pluginName = 'Manager';

    function Plugin(element, options) {
        this.element = element;
        this._name = pluginName;
        this._defaults = $.fn.Manager.defaults;
        this.options = $.extend({}, this._defaults, options);
        this.init();
    }

    $.extend(Plugin.prototype, {
        init: function() {
            this.buildList();
            this.bindEvents();
        },

        buildList: function(dirname, type, ext, sorting) {
            var plugin = this;
            var element = this.element;
            //plugin.addLoader();

			$.ajax({
				type: 'GET',
				url: this.options.url + "/process/managerController.php",
				dataType: 'json',
				async: true,
				data: {action: "getFiles",layout: type,dir: dirname,sorting: sorting}
			}).done(function(json) {
				//var obj = $.parseJSON(json);
				var template = plugin.renderTemplate(type, json);
				$(element).html(template).transition('fade in');
				$("#tsizeDir span").html(json.dirsize);
				$("#tsizeFile span").html(json.filesize);
				
				if($("#fileModal:visible").length > 0) {
					$("#fileModal").modal('refresh');
				}
			});
        },

        // Bind events that trigger methods
        bindEvents: function() {
            var plugin = this;
            var element = this.element;
			var lang = plugin.options.lang;

            $('#togglePreview').on('click', function() {
                var icon = $(this).children();
                $(icon).toggleClass('expand compress');
                $("#preview").toggle();
                //var type = $('#displyType a.active').data('type');
            });

            $('#fm').on('click', 'a.is_file', function() {
                var dataset = $(this).data('set');
                var url = plugin.options.dirurl + '/' + dataset.url;
                var murl = plugin.options.url + '/images/mime/' + dataset.ext + '.png';

                var is_image = (dataset.image === "true") ? url : murl;
                if (dataset.name) {
                    var template = '' +
                        '<img src="' + is_image + '" class="yoyo medium basic image" alt=""> ' +
                        '<div class="yoyo relaxed divided flex list"> ' +
                        '<div class="item"><div class="header">' + plugin.maxLength(dataset.name, 20) + '</div></div> ' +
                        '<div class="item">' + lang.size + ': ' + dataset.size + '</div> ' +
                        '<div class="item">' + lang.lastm + ': ' + dataset.ftime + '</div> ' +
                        '<a href="' + url + '" class="yoyo small simple positive fluid button item">' + lang.download + ' </a> ' +
                        '';
                    if (dataset.ext === "zip") {
                        template += '' +
                            '<a data-url="' + dataset.url + '/" class="yoyo small fluid pisitive button unzip"> ' + lang.unzip + '</a>';
                    }
                    template += '' +
                        '<a data-url="' + dataset.url + '" data-name="' + dataset.name + '" data-type="file" class="yoyo small simple negative button item delSingle">' + lang.delete + '</a>' +
                        '<div class="yoyo basic divider"></div>' +
                        '</div>';
						
                    if (plugin.options.is_mce) {
                        template += '' +
                            '<div class="content-center"><a class="yoyo small primary button insertMCE" data-url="' + url + '"> ' + lang.insert + ' </a></div>';
                    }
                    $("#preview").html(template);
                }
            });

            //Browse directories
            $('#fm').on('click', 'a.is_dir', function() {
                var dataset = $(this).data('set');
                var items = plugin.filterDisplay();
                var folder = (dataset.files > 0) ? 'open' : 'closed';
                plugin.buildList(dataset.url, items.layout, items.filter, items.sorting);
                $("#fcrumbs").html('<a class="is_dir" data-set=\'{"url":""}\'>' + lang.home + '</a>  / ' + plugin.getCrumbs(dataset.url));
                if (dataset.name) {
                    var template = '' +
                        '<img src="' + plugin.options.url + '/images/mime/' + folder + '_folder.png" class="yoyo basic medium image" alt=""> ' +
                        '<div class="yoyo relaxed divided flex list"> ' +
                        '<div class="item"><div class="header">' + plugin.maxLength(dataset.name, 20) + '</div></div> ' +
                        '<div class="item"><div class="header">' + lang.items + ': ' + dataset.files + '</div></div> ' +
                        '<a data-url="' + dataset.url + '" data-name="' + dataset.name + '" data-type="dir" class="yoyo small simple negative fluid button item delSingle"> ' + lang.delete + '</a> ' +
                        '<div class="yoyo basic divider"></div>' +
                        '</div>';
                    $("#preview").html(template);
                    $("input[name=dir]").val(dataset.url);
                }
            });

            //Delete multiple files/folders
            $('#fm').on('click', '.is_delete', function() {
                var $this = $(this);
                var checkedValues = $('#listView input:checkbox:checked').map(function() {
                    return this.value;
                }).get();
                if (!$.isEmptyObject(checkedValues)) {
                    $this.addClass('loading');
                    $.post(plugin.options.url + "/process/managerController.php", {
                        action: "delete",
                        items: checkedValues,
                    }, function(json) {
                        if (json.type === "success") {
                            $('#listView tr').each(function() {
                                if ($(this).find('input:checked').length) {
                                    $(this).fadeOut(400, function() {
                                        $(this).remove();
                                    });
                                    $this.removeClass('loading');
                                }
                            });

                        }
                    }, "json");
                }
            });

            //Delete single files/folders
            $('#fm').on('click', '.delSingle', function() {
                var dir = $(this).data('url');
                var type = $(this).data('type');
                $.post(plugin.options.url + "/process/managerController.php", {
                    action: "delete",
                    items: [dir],
                }, function(json) {
                    if (json.type === "success") {
                        //var layout = plugin.filterDisplay();
                        if (type === "dir") {
                            $(element).html('<div class="yoyo basic centered image"><img src="' + plugin.options.url + '/images/search_empty.png" alt=""></div>').transition('fade in');
                            $("#preview").html('');
                        } else {
                            $(element).find("[data-id='" + dir + "']").remove();
                            $("#preview").html('<img class="yoyo medium basic image" src="' + plugin.options.url + '/images/search_empty.png" alt="">');
                        }

                    }
                }, "json");
            });

            //New Folder
            $('#fm').on('click', '#addFolder', function() {
                var $parent = $(this).parent('.input');
                var $field = $("input[name=foldername]");
                var items = plugin.filterDisplay();

                if ($field.val().length > 0) {
                    $parent.addClass('loading');
                    $.post(plugin.options.url + "/process/managerController.php", {
                        action: "newFolder",
                        name: $field.val(),
                        dir: items.dir
                    }, function(json) {
                        if (json.type === "success") {
                            plugin.buildList(items.dir, items.layout, items.filter, items.sorting);
                            $parent.removeClass('loading');
                        }
                    }, "json");
                }

            });

            /* == Unzip == */
            $('#fm').on('click', '.unzip', function() {
                var url = $(this).data('url');
                $.post(plugin.options.url + "/process/managerController.php", {
                    action: "unzip",
                    item: url,
                }, function(json) {
                    if (json.type === "success") {
                        var items = plugin.filterDisplay();
                        plugin.buildList(items.dir, items.layout, items.filter, items.sorting);
                    }
                }, "json");
            });

            /* == TinyMCE insert == */
            $('#fm').on('click', 'a.insertMCE', function() {
                var filename = $(this).data('url');
                var id = $.url().param('field_name');
                window.parent.$("#" + id).val(filename);
                if (typeof parent.tinyMCE !== "undefined") {
                    parent.tinyMCE.activeEditor.windowManager.close();
                }
                if (window.opener) {
                    window.close();
                }
            });
			
            /* == Check All == */
            $('#fm').on('change', '#selectAll', function() {
				var $checkbox = $("#listView").find(':checkbox');
				$checkbox.prop('checked', !$checkbox.prop('checked'));
				if ($checkbox.is(':checked')) {
					$(".is_delete").removeClass("disabled");
				} else {
					$(".is_delete").addClass("disabled");
				}
            });
			
			$('#result').on('change', 'input[type="checkbox"]', function() {
				if ($("#listView").find(':checkbox').is(':checked')) {
					$(".is_delete").removeClass("disabled");
				} else {
					$(".is_delete").addClass("disabled");
				}
            });
			
            //Type filter
            $('#ftype').on('click', 'a', function() {
                $('#ftype a').removeClass('active');
                var filter = $(this).data('type');
                $(this).addClass('active');
                var items = plugin.filterDisplay();
                plugin.buildList(items.dir, items.layout, filter, items.sorting);
            });

            //Sorting type
            $('.fileSort').on('change', function() {
                var sorting = $(this).val();
                var items = plugin.filterDisplay();
                plugin.buildList(items.dir, items.layout, items.filter, sorting);
            });

            //Display type
            $('#displyType').on('click', 'a', function() {
                $('#displyType a').removeClass('active');
                var layout = $(this).data('type');
                $(this).addClass('active');
                var items = plugin.filterDisplay();
                plugin.buildList(items.dir, layout, items.filter, items.sorting);
            });

            //File Upload
            $('#drag-and-drop-zone').on('click', function() {
                var items = plugin.filterDisplay();
                $(this).yoyoUpload({
                    url: plugin.options.url + "/process/managerController.php",
                    dataType: 'json',
                    extraData: {
                        action: "upload",
                        dir: items.dir
                    },
                    allowedTypes: '*',
                    onBeforeUpload: function(id) {
                        plugin.update_file_status(id, 'primary', 'Uploading...');
                    },
                    onNewFile: function(id, file) {
                        plugin.add_file(id, file);
                    },
                    onUploadProgress: function(id, percent) {
                        plugin.update_file_progress(id, percent);
                    },
                    onUploadSuccess: function(id, data) {
                        if (data.type === "error") {
                            plugin.update_file_status(id, 'negative', data.message);
                            plugin.update_file_progress(id, 0);
                        } else {
                            plugin.update_file_status(id, 'positive', '<i class="icon circle check"></i>');
                            plugin.update_file_progress(id, 100);
                            $('<img class="yoyo basic upload image" src="' + data.filename + '">').insertBefore('#contentFile_' + id);
                        }
                    },
                    onUploadError: function(id, message) {
                        plugin.update_file_status(id, 'negative', message);
                    },
                    onFallbackMode: function(message) {
                        alert('Browser not supported: ' + message);
                    },

                    onComplete: function() {
                        $("#done").append('<a class="yoyo tiny basic black button">' + lang.done + '</a>');
                        $("#done").on('click', 'a', function() {
                            plugin.buildList(items.dir, items.layout, items.filter, items.sorting);
                            $('#fileList').html('');
							$("#done a").remove();
                        });
                    }
                });
            });
        },

        addLoader: function() {
            $(this.element).prepend('<i class="icon large round chart spinning disabled"></i>');
        },

        add_file: function(id, file) {
            var template = '' +
                '<div class="item relative" id="uploadFile_' + id + '">' +
                '<div class="right floated content"><span class="yoyo small medium text primary">Waiting</span></div>' +
                '<div class="content" id="contentFile_' + id + '">' +
                '<div class="header">' + file.name + '</div>' +
                '<div id="description_' + id + '" class="description yoyo small text"></div>' +
                '</div>' +
                '<div class="yoyo bottom attached indicating progress" data-percent="0">' +
                '<div class="bar" style="width:100%"></div>' +
                '</div>' +
                '</div>';

            $('#fileList').prepend(template);
        },

        update_file_status: function(id, status, message) {
            $('#uploadFile_' + id).find('span').html(message).toggleClass(status);
        },

        update_file_progress: function(id, percent) {
            $('#uploadFile_' + id).find('.progress').attr("data-percent", percent);
        },

        // trim long filenames
        maxLength: function(title, chars) {
            return (title.length > chars) ? title.substr(0, (chars - 3)) + '...' : title;
        },

        // display filter
        filterDisplay: function() {
            var layout = $('#displyType a.active').data('type');
            var filter = $('#ftype a.active').data('type');
            var dir = $("input[name=dir]").val();
            var sorting = $(".fileSort option:selected").val();
            return {
                "dir": dir,
                "layout": layout,
                "filter": filter,
                "sorting": sorting
            };
        },

        //do crumbs
        getCrumbs: function(dir) {
            var crumbs = [];
            crumbs = dir.split('/');
            crumbs = $.grep(crumbs, function(n) {
                return (n !== "" && n !== null);
            });
            var nav = '';
            $.each(crumbs, function(u, path) {
                if ((crumbs.length - 1) !== u) {
                    nav += '<a class="is_dir" data-set=\'{"url":"' + path + '"}\'>' + path.substr(0, 1).toUpperCase() + path.substr(1) + '</a> <i class="icon long right arrow"></i> ';
                } else {
                    nav += path.substr(0, 1).toUpperCase() + path.substr(1);
                }

            });

            return nav;
        },

        //Template
        renderTemplate: function(type, obj) {
            var plugin = this;
            var template = '';
            switch (type) {
                case "list":
                    template += '<div class="row horizontal-gutters phone-block-1 mobile-block-1 tablet-block-2 screen-block-2">';
                    if (obj.directory) {
                        $.each(obj.directory, function() {
                            var folder = (this.total > 0) ? 'folder open' : 'folder';
                            template += '<div class="column" data-id="' + this.name + '">' +
                                '<a class="yoyo grid list is_dir" data-set=\'{"name":"' + this.name + '", "files":"' + this.total + '", "url":"' + this.path + '"}\'> ' +
                                '<div class="content shrink"><i class="icon black ' + folder + '"></i></div> ' +
                                '<div class="content"> ' +
                                '' + this.name + '' +
                                '</div> ' +
                                '<div class="content yoyo small black text shrink">' + this.total + ' files</div> ' +
                                '</a>' +
                                '</div>';
                        });
                    }
                    if (obj.files) {
                        $.each(obj.files, function() {
                            var is_image = (this.is_image) ? plugin.options.dirurl + '/thumbs/' + this.name : plugin.options.url + '/images/mime/' + this.extension + '.png';
                            template += '<div class="column" data-id="' + this.name + '">' +
                                '<div class="selectable"> ' +
								'<a class="yoyo grid list is_file" data-set=\'{"name":"' + this.name + '", "image":"' + this.is_image + '", "ext":"' + this.extension + '", "ftime":"' + this.ftime + '", "size":"' + this.size + '", "url":"' + this.url + '"}\'> ' +
                                '<div class="content shrink"><img src="' + is_image + '" alt="" class="yoyo tiny image"> </div>' +
                                '<div class="content"> ' +
                                '' + plugin.maxLength(this.name, 20) + '' +
                                '</div> ' +
                                '<div class="content yoyo small black text shrink">' + this.size + '</div> ' +
                                '</div></a>' +
                                '</div>';

                        });
                    }
                    template += '</div>';
                    break;

                case "grid":
                    template += '<div class="masonry">';
                    if (obj.directory) {
                        $.each(obj.directory, function() {
                            var folder = (this.total > 0) ? 'open' : 'closed';
                            template += '<div class="item" data-id="' + this.name + '">' +
                                '<div class="yoyo attached segment"> ' +
                                '<div class="content-center">' +
                                '<a data-set=\'{"name":"' + this.name + '", "files":"' + this.total + '", "url":"' + this.path + '"}\' class="is_dir"> ' +
                                '<img alt="" src="' + plugin.options.url + '/images/mime/' + folder + '_folder.png" class="yoyo basic image"> ' +
                                '</a> ' +
                                '</div> ' +
                                '<div class="yoyo divider"></div>' +
                                '<span class="yoyo semi text">' + this.name + '</span>' +
                                '<p class="yoyo semi text">' + this.total + ' files</p>' +
                                '</div> ' +
                                '</div>';
                        });
                    }

                    if (obj.files) {
                        $.each(obj.files, function() {
							var dir = (this.extension === "svg") ? this.dir + "/" : "/thumbs/";
                            var is_image = (this.is_image) ? plugin.options.dirurl + dir + this.name : plugin.options.url + '/images/mime/' + this.extension + '.png';
							
                            template += '<div class="item" data-id="' + this.name + '">' +
                                '<div class="yoyo attached segment selectable">' +
                                '<div class="content-center">' +
                                '<a class="is_file" data-set=\'{"name":"' + this.name + '", "image":"' + this.is_image + '", "ext":"' + this.extension + '", "ftime":"' + this.ftime + '", "size":"' + this.size + '", "url":"' + this.url + '"}\'>' +
                                '<img alt="" src="' + is_image + '" class="yoyo basic medium image"></a>' +
                                '</div>' +
                                '<div class="yoyo divider"></div>' +
                                '<span class="yoyo semi text">' + plugin.maxLength(this.name, 20) + '</span>' +
                                '<p class="yoyo semi text">' + this.size + '</p>' +
                                '</div>' +
                                '</div>';

                        });
                    }

                    template += '</div>';
                    break;

                default:
                    template += '<table class="yoyo basic striped table">';
					if(!plugin.options.is_editor) {
						template += '' +
                        '<thead> ' +
                        ' <tr> ' +
                        '<th colspan="4" class="collapsing"><div class="yoyo toggle checkbox fitted inline"> ' +
                        '<input type="checkbox" name="master" value="1" id="selectAll"> ' +
                        '<label for="selectAll">&nbsp;</label> ' +
                        '</div></th> ' +
                        '</tr> ' +
                        '</thead>';
					}
                    template += '<tbody id="listView">';
                    if (obj.directory) {
                        $.each(obj.directory, function(key) {
                            var folder = (this.total > 0) ? 'folder open' : 'folder';
                            template += '<tr data-id="' + this.name + '">';
							    if(!plugin.options.is_editor) {
									template += '' +
                                '<td class="collapsing"><div class="yoyo small checkbox fitted inline">' +
                                '<input type="checkbox" name="' + this.name + '" value="' + this.path + '" id="dirView_' + key + '">' +
                                '<label for="dirView_' + key + '"></label>' +
                                '</div>' +
                                '</td>';
								}
								template += '' +
                                '<td class="collapsing"><i class="icon primary ' + folder + '"></i></td> ' +
                                '<td><a class="black is_dir" data-set=\'{"name":"' + this.name + '", "files":"' + this.total + '", "url":"' + this.path + '"}\'>' + this.name + '</a></td> ' +
                                '<td class="collapsing">' + this.total + ' <small>(items)</small></td>';
                            template += '</tr>';
                        });
                    }

                    if (obj.files) {
                        $.each(obj.files, function(key) {
                            var mime = this.mime.split('/');
                            var icon = '';
                            switch (mime[0]) {
                                case "image":
                                    icon = '<i class="icon photo"></i>';
                                    break;
                                case "video":
                                    icon = '<i class="icon camera retro"></i>';
                                    break;
                                case "audio":
                                    icon = '<i class="icon volume"></i>';
                                    break;
                                default:
                                    icon = '<i class="icon file"></i>';
                                    break;
                            }

                            template += '<tr data-id="' + this.name + '" class="selectable">';
							   if(!plugin.options.is_editor) {
								   template += '' +
                                '<td class="collapsing"><div class="yoyo small checkbox fitted inline">' +
                                '<input type="checkbox" name="' + this.name + '" value="' + this.url + '" id="fileView_' + key + '">' +
                                '<label for="fileView_' + key + '"></label>' +
                                '</div>' +
                                '</td>';
							   }
							   template += '' +
                                '<td class="collapsing">' + icon + '</td>' +
                                '<td><a class="black is_file" data-set=\'{"name":"' + this.name + '", "image":"' + this.is_image + '", "ext":"' + this.extension + '", "ftime":"' + this.ftime + '", "size":"' + this.size + '", "url":"' + this.url + '"}\'>' + this.name + '</a></td>' +
                                '<td class="collapsing">' + this.size + '</td>';
                            template += '</tr>';
                        });
                    }

                    template += '</tbody>';
                    template += '</table>';
                    break;

            }

            return template;
        }

    });

    $.fn.Manager = function(options) {
        this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });

        return this;
    };

    $.fn.Manager.defaults = {
        url: "",
        dirurl: "",
		is_editor: false,
		is_mce: false,
        gridSize: 220,
        lang: {
            delete: "Delete",
			insert: "Insert",
			download: "Download",
			unzip: "Unzip",
			size: "Size",
			lastm: "Last Modified",
			items: "items",
			done: "Done",
			home: "Home",
        }
    };

})(jQuery, window, document);