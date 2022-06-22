(function($) {
    "use strict";
    $.Poll = function(settings) {
        var config = {
            url: "",
            lang: {
                optext: "",

            }
        };
        if (settings) {
            $.extend(config, settings);
        }
        $('#btnAdd').on('click', function() {
            var html = ('' +
                '<div class="field new">' +
                '<div class="yoyo fluid right icon input">' +
                '<input type="text" placeholder="' + config.lang.optext + '" value="' + config.lang.optext + '" name="value[]">' +
                '<i class="icon small negative delete link"></i>' +
                '</div>' +
                '</div>');
            $('#item').append(html);
        });
        $("#item").on('click', '.icon.delete', function() {
            $(this).parents('.field').remove();
			var id = $(this).prev('input').data('id');
            if ($.inArray("edit", $.url().segment()) && id) {
				$.post(config.url, {
					action: "deleteOption",
					id: id
				});
            }
        });
        var timeout;
        $("#item").on('keyup', '.old input', function() {
            window.clearTimeout(timeout);
            var value = $(this).val();
            var id = $(this).data('id');
            if (value.length > 1) {
                timeout = window.setTimeout(function() {
                    $.post(config.url, {
                        action: "updateOption",
                        id: id,
                        value: value
                    });
                }, 700);
            }
        });

    };
})(jQuery);