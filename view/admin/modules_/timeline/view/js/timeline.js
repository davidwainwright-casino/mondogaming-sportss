(function($) {
    "use strict";
    $.Timeline = function(settings) {
        var config = {
            url: "",
            upUrl: "",
        };
        if (settings) {
            $.extend(config, settings);
        }

        // select type mode
        $("select[name=type]").on('change', function() {
            switch ($(this).val()) {
                case "facebook":
                    $('#fbconf').show();
                    $('#rssconf').hide();
                    break;
                case "rss":
                    $('#fbconf').hide();
                    $('#rssconf').show();
                    break;
                default:
                    $('#fbconf, #rssconf').hide();
                    break;
            }
        });

        $("#sortable").sortables({
            ghostClass: "ghost",
            draggable: ".column",
            filter: ".remove",
            animation: 600,
            onFilter: function(ui) {
                $(ui.item).remove();
            }
        });

        //change type
        $('#tmType').change(function() {
            var selected = $(this).val();
            switch (selected) {
                case "iframe":
                    $('#iframe').show();
                    $('#imgfield, #bodyfield').hide();
                    break;

                case "gallery":
                    $('#iframe, #bodyfield').hide();
                    $('#imgfield').show();
                    break;

                default:
                    $('#iframe').hide();
                    $('#bodyfield').show();
                    break;
            }
        });

        //select images
        $('.multipick').on('click', function() {
            $.get(config.url + '/managerBuilder.php', {
                pickFile: 1,
                editor: true
            }).done(function(data) {
                var modal = '<div id="fileModal" class="yoyo large modal">' + data + '</div>';
                $(modal).modal('setting', 'onShow', function() {
                    var cmodal = this;
                    $("#result").on('click', '.is_file', function() {
                        var dataset = $(this).data('set');
                        if (dataset.image === "true") {
                            var iparent = $(this).closest('.selectable');
                            if ($(iparent).hasClass('highlite')) {
                                $(iparent).removeClass('highlite');
                            } else {
                                $(iparent).addClass('highlite');
                            }
                        }
                        if ($("#result .highlite").length > 0) {
                            $("#fInsert").removeClass('hidden');
                        } else {
                            $("#fInsert").addClass('hidden');
                        }
                    });

                    $("#fInsert").on('click', function() {
                        var html = '';
                        $("#result .highlite").each(function() {
                            var dataset = $(this).find('.is_file').data('set');
                            html +=
                                '<div class="column"> ' +
                                '<div class="yoyo simple attached card"> ' +
								'<a class="yoyo middle small white icon attached button remove"><i class="icon negative trash"></i></a> ' +
                                '<img src="' + config.upUrl + '/' + dataset.url + '" alt="" class="yoyo shadow rounded image"> ' +
                                '<input type="hidden" name="images[]" value="' + dataset.url + '">' +
                                '</div> ' +
                                '</div>';

                        });
                        $("#sortable").prepend(html);

                        $(cmodal).modal('hide');
                    });
                }).modal('setting', 'onHidden', function() {
                    $(this).remove();
                }).modal('show');
            });
        });
    };
})(jQuery);