(function($) {
    "use strict";
    $.Language = function(settings) {
        var config = {
            url: "",
        };
        if (settings) {
            $.extend(config, settings);
        }

        $("#filter").on("keyup", function() {
            var filter = $(this).val(),
                count = 0;
            $("span[data-editable=true]").each(function() {
                if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                    $(this).parents('.item').fadeOut();
                } else {
                    $(this).parents('.item').fadeIn();
                    count++;
                }
            });
        });

        $('#pgroup').on('change', function() {
            var sel = $("#pgroup option:selected").val();
            var type = $("#pgroup option:selected").data('type');
			var abbr = $(this).data('abbr');
            $('#pgroup').parent().addClass('loading');
            $.get(config.url + "/helper.php", {
				doAction:1,
                page: "languagesection",
                type: type,
                section: sel,
				abbr:abbr
            }, function(json) {
                $("#editable").html(json.html).fadeIn("slow");
                $('#editable').editableTableWidget();
                $('#pgroup').parent().removeClass('loading');
            }, "json");
        });

        $('#group').on('change', function() {
            var sel = $("#group option:selected").val();
            var type = $("#group option:selected").data('type');
			var abbr = $(this).data('abbr');
            $('#group').parent().addClass('loading');
            $.get(config.url + "/helper.php", {
				doAction:1,
                page: "languagefile",
                type: type,
                section: sel,
				abbr:abbr
            }, function(json) {
                if (json.type === "success") {
                    $("#editable").html(json.html).fadeIn("slow");
                    $('#editable').editableTableWidget();
                } else {
                    $.notice(decodeURIComponent(json.message), {
                        autoclose: 12000,
                        type: json.type,
                        title: json.title
                    });
                }

                $('#group').parent().removeClass('loading');
            }, "json");
        });

        $('[data-lang-color="true"]').spectrum({
            showPaletteOnly: true,
            showPalette:true,
			move: function(color) {
				var newcolor = color.toHexString();
                if ($.inArray("edit", $.url().segment()) || $.inArray("new", $.url().segment())) {
                    $(this).css('background', newcolor);
                    $(this).prev('input').val(newcolor);
                } else {
                    $(this).css('background', newcolor);
                    $.post(config.url + "/helper.php", {
						simpleAction:1,
                        action: "langcolor",
						color: newcolor,
                        id: $(this).data('id')
                    });
                }
			}
        });
    };
})(jQuery);