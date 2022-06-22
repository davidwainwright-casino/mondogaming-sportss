(function($, window, document, undefined) {
    "use strict";
    var pluginName = "Comments",
        defaults = {
            url: "value"
        };

    function Comments(element, options) {
        this.element = element;

        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    $.extend(Comments.prototype, {
        init: function() {
            this.bind();
        },
        bind: function() {
            var base = this;

            //vote
            $(this.element).on('click', 'a.down, a.up', function() {
                var type = $(this).attr('class').replace("item ", "");
                var id = $(this).data('id');
                var icon = $(this).children('.icon');
                var score = $(this).children('span');
                var down = $(this).data('down');
                var up = $(this).data('up');

                icon.removeClass("chevron up down").addClass("check").transition('fade in');
                $(this).removeClass("up down");

                $.post(base.settings.url + 'controller.php', {
                    action: "vote",
                    type: type,
                    id: id
                }, function(json) {
                    if (json.status === "success") {
                        if (json.type === "down") {
                            score.text(parseInt(down) - 1);
                        } else {
                            score.text(parseInt(up) + 1);
                        }
                    }
                }, "json");

            });

            //load reply form
            $(this.element).on('click', 'a.replay', function() {
                $("#replyform, #pError").remove();
                var id = $(this).data('id');
                $.get(base.settings.url + 'snippets/replyForm.tpl.php', {
                    id: id
                }, function(data) {
                    var comment = $("#comment_" + id, base.element).children('.content');
                    comment.append(data);
                    $("#replyform").transition('fade in');
                });
            });

            //reply
            $(this.element).on('click', 'button[name=doReply]', function() {
                var id = $(this).closest('.comment').data('id');
                $(this).addClass('loading').prop('disabled', true);

                var data = {
                    id: id,
                    parent_id: $("input[name=parent_id]").val(),
                    section: $("input[name=section]").val(),
                    message: $("textarea[name=replybody]").val(),
                    username: $("input[name=replayname]").val(),
                    captcha: $("input[name=captcha]").val(),
					url: $("input[name=url]").val(),
                    action: "reply"
                };

                base.submitComment(data);
            });

            //new
            $(document).on('click', 'button[name=doComment]', function() {
                $(this).addClass('loading').prop('disabled', true);

                var data = {
                    id: 0,
                    parent_id: $("input[name=parent_id]").val(),
                    section: $("input[name=section]").val(),
                    message: $("textarea[name=body]").val(),
                    username: $("input[name=name]").val(),
                    captcha: $("input[name=captcha]").val(),
					star: $("input[name=star]:checked").val(),
					url: $("input[name=url]").val(),
                    action: "comment"
                };

                base.submitComment(data);
            });

            //delete
            $(this.element).on('click', 'a.delete', function() {
                var id = $(this).closest('.comment').data('id');
                $.post(base.settings.url + 'controller.php', {
					action: "delete",
                    id: id
                }, function() {
                    var comment = $("#comment_" + id, base.element);
                    $(comment).transition('fade out');
                });
            });
			
            //char counter
            $(document).on('keyup paste', '#combody, #replybody', function() {
                var characters = $(this).attr('data-counter');
                if ($(this).val().length > characters) {
                    $(this).val($(this).val().substr(0, characters));
                }
                var id = $(this).attr('id');
                var remaining = characters - $(this).val().length;
                $("." + id + "_counter span").html(remaining);
                if (remaining <= 10) {
                    $("." + id + "_counter span").addClass('negative').removeClass('positive');
                } else {
                    $("." + id + "_counter span").removeClass('negative').addClass('positive');
                }
            });
        },

        //process comment
        submitComment: function(data) {
            var base = this;
            $.post(this.settings.url + 'controller.php', data, function(json) {
                if (json.type === "success") {
                    $("#replyform").remove();
                    if (json.html) {
                        if (data.action === "reply") {
                            $("#comment_" + data.id).children('.content').append(json.html);
                        } else {
                            $(base.element).prepend(json.html);
							$('html, body').animate({
								scrollTop: $(base.element).offset().top
							}, 500);
                            $("#combody").val('');
                        }
                    }
                }
                $.notice(json.message, {
                    autoclose: 12000,
                    type: json.type,
                    title: json.title

                });
                $("button[name=doReply], button[name=doComment]").removeClass('loading').prop('disabled', false);
            }, "json");
        }
    });

    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, pluginName)) {
                $.data(this, pluginName, new Comments(this, options));
            }
        });
    };
})(jQuery, window, document);