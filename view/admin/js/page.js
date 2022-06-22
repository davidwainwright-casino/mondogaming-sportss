(function($) {
    "use strict";
    $.Page = function(settings) {
        var config = {
            url: "",
            lang: {
                nomemreq: "",

            }
        };
        if (settings) {
            $.extend(config, settings);
        }

        $('#access_id').on('change', function() {
            var type = $(this).val();
            if (type === "Membership") {
                $(".access_id").addClass('loading');
                $.get(config.url, {
                    doAction: 1,
                    page: "membershiplist",
					type: type,
                }, function(json) {
                    if (json.status === "success") {
                        var template = '<select name="membership_id[]" class="yoyo fluid dropdown" multiple>';
                        template += json.html;
                        template += '</select>';
                        $("#membership").html(template);
                        $("#membership select").yoyoSelect();
                    }
                    $(".access_id").removeClass('loading');
                }, "json");
            } else {
                $('#membership').html('<input disabled="disabled" type="text" placeholder="' + config.lang.nomemreq + '" name="na">');
            }
        });
        $('.removebg').on('click', function() {
            var parent = $(this).prev('input');
            $(parent).val('');
        });

    };
})(jQuery);