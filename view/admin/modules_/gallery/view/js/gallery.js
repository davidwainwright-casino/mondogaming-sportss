(function($) {
    "use strict";
    $.Gallery = function(settings) {
        var config = {
            url: "",
            dir: "",
            grid: '.masonry',
            sortable: '#sortable',
            lang: {
                done: 'Done',
            }
        };
        if (settings) {
            $.extend(config, settings);
        }

        // Resize images
        $('#doResize').on('click', function() {
            var $button = $(this);
            var w = $("input[name=thumb_w]").val();
            var h = $("input[name=thumb_h]").val();
            var method = $('input[name=resize]:checked').val();
            var dir = $("input[name=dir]").val();
            $button.addClass('loader').prop('disabled', true);
            $.post(config.url, {
                action: 'resizeImages',
                resize: method,
                thumb_w: w,
                thumb_h: h,
                dir: dir,
            }, function(json) {
                setTimeout(function() {
                    $($button).removeClass("loader").prop("disabled", false);
                }, 500);
                $.notice(json.message, {
                    autoclose: 12000,
                    type: json.type,
                    title: json.title
                });
            }, "json");
        });

        // sort albums/photos
        $('#reorder').on('click', function() {
            if ($(this).children().hasClass('apps')) {
                $(this).children().toggleClass('apps check');
				$("#dragNotice").show();
                $('.content, .content-center', config.sortable).hide();
                $(config.sortable).removeClass('masonry');
                $(config.sortable).addClass('row screen-block-5 tablet-block-4 mobile-block-3 phone-block-1 gutters');
                $('.item', config.sortable).addClass('column');
                var type = ($.inArray("photos", $.url().segment()) === -1) ? 'sortAlbums' : 'sortPhotos';
                $(config.sortable).sortables({
                    ghostClass: "ghost",
                    animation: 600,
                    onUpdate: function() {
                        var order = this.toArray();
                        $.post(config.url, {
                            action: "sortItems",
							type: type,
                            sorting: order
                        }, function() {}, "json");

                    }
                });
            } else {
                $(config.sortable).addClass('loader');
                $(this).children().toggleClass('check apps');
				$("#dragNotice").hide();
                $('.content, .content-center', config.sortable).show();
                $(config.sortable).removeClass('row screen-block-5 tablet-block-4 mobile-block-3 phone-block-1 gutters');
                $(config.sortable).addClass('masonry');
                $('.item', config.sortable).removeClass('column');
                $(config.sortable).removeClass('loader');
            }
        });
		
        // assign poster
        $(config.sortable).on('click', '.poster', function() {
			var $this = $(this);
			var $icon = $(this).children('.icon');
            $.post(config.url, {
                action: "setPoster",
				thumb: $(this).data('poster'),
				id: $.url().segment(-1)
            }, function(json) {
                if (json.type === "success") {
					var $item = $(config.sortable).find('.menu .item.disabled');
					$item.children().toggleClass('check photo');
					$item.toggleClass('disabled poster');
					$this.toggleClass('poster disabled');
					$icon.toggleClass('photo check');
                }
            }, "json");
        });
			
        //File Upload
        $('#drag-and-drop-zone').on('click', function() {
            $(this).yoyoUpload({
                url: config.url,
                dataType: 'json',
                extraData: {
                    action: "upload",
                    dir: config.dir
                },
                allowedTypes: '*',
                onBeforeUpload: function(id) {
                    update_file_status(id, 'primary', 'Uploading...');
                },
                onNewFile: function(id, file) {
                    add_file(id, file);
                },
                onUploadProgress: function(id, percent) {
                    update_file_progress(id, percent);
                },
                onUploadSuccess: function(id, data) {
                    if (data.type === "error") {
                        update_file_status(id, 'negative', data.message);
                        update_file_progress(id, 0);
                    } else {
                        update_file_status(id, 'positive', '<i class="icon circle check"></i>');
                        update_file_progress(id, 100);
                        $('<img class="yoyo basic upload image" src="' + data.filename + '">').insertBefore('#contentFile_' + id);
                    }
                },
                onUploadError: function(id, message) {
                    update_file_status(id, 'negative', message);
                },
                onFallbackMode: function(message) {
                    alert('Browser not supported: ' + message);
                },

                onComplete: function() {
                    $("#fileList").after('<div id="done" class="content-center padding"><a class="yoyo small primary button"><i class="icon check"></i>' + config.lang.done + '</a></div>');
                    $("#done").on('click', 'a', function() {
                        buildList($.url().segment(-1));
                        $('#fileList').html('');
                        $("#done").remove();
                    });
                }
            });
        });

        function add_file(id, file) {
            var template = '' +
                '<div class="item relative" id="uploadFile_' + id + '">' +
                '<div class="right floated content"><span class="yoyo small medium text primary">Waiting</span></div>' +
                '<div class="content" id="contentFile_' + id + '">' +
                '<div class="header">' + file.name + '</div>' +
                '<div id="description_' + id + '" class="description yoyo tiny text"></div>' +
                '</div>' +
                '<div class="yoyo bottom attached indicating progress" data-percent="0">' +
                '<div class="bar" style="width:100%"></div>' +
                '</div>' +
                '</div>';

            $('#fileList').prepend(template);
        }

        function update_file_status(id, status, message) {
            $('#uploadFile_' + id).find('span').html(message).toggleClass(status);
        }

        function update_file_progress(id, percent) {
            $('#uploadFile_' + id).find('.progress').attr("data-percent", percent);
        }

        function buildList(id) {
			$(config.grid).addClass('loader');
            $.get(config.url, {
                action: "loadPhotos",
                id: id,
            }, function(json) {
                if (json.type === "success") {
					$(config.grid).html(json.html);
                }
				$(config.grid).removeClass('loader');
            }, "json");
        }
    };
})(jQuery);