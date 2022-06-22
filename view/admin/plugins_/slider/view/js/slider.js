(function($) {
    "use strict";
    $.Slider = function(settings) {
        var config = {
            url: "",
            aurl: "",
            surl: "",
            element: "",
            ytapi: "",
            adistance: 5,
            lang: {
                canBtn: "",
                updBtn: "",
            }
        };
        var $activeCard;
        if (settings) {
            $.extend(config, settings);
        }

        //Slide Holder actions
        $(".eMenu").on('click', 'a.button', function() {
            var $this = $(this);
            $("#sortable .card").removeClass("active");
            $activeCard = $this.closest('.card');
            var set = $(this).data('set');

            $activeCard.addClass('loading active');
            $("#sortable").addClass("read-only");

            switch (set.mode) {
                case "prop":
                    $.post(config.url, {
                        action: "propSlide",
                        id: set.id,
                    }, function(json) {
                        if (json.type === "success") {
                            $(':radio[value=' + json.mode + ']', "#source").prop('checked', true);
                            $("#bg_img").val(json.image);
                            $("#bg_color").val(json.color);
                            $("[data-id=cl_asset] .button", "#source").css('backgbound-color', json.color);
                            $('[data-id=' + json.mode + '_asset]').show();
                            $("#source").transition('slide down');
                            $activeCard.removeClass('loading');

                        }
                    }, "json");
                    break;

                case "duplicate":
                    $.post(config.url, {
                        action: "duplicateSlide",
                        id: set.id,
                    }, function(json) {
                        if (json.type === "success") {
                            $(json.thumb).insertAfter($activeCard.parent());
                            $('wedit').editableTableWidget();
                            $activeCard.removeClass('loading active').css("pointerEvents", "auto");
							$("#sortable").removeClass("read-only");
                        }

                    }, "json");
                    break;

                case "delete":
                    $.post(config.url, {
                        action: "deleteSlide",
                        id: set.id
                    }, function(json) {
                        if (json.type === "success") {
                            $activeCard.parent().transition({
                                animation: 'drop',
                                onComplete: function() {
                                    $activeCard.parent().remove();
									$("#sortable").removeClass("read-only");
                                }
                            });
                        }

                    }, "json");
                    break;

            }
        });

        $("#source").on('change', 'input[type=radio]', function() {
            var image = $('#bg_img').val();
            var value = $(this).val();
            if ($activeCard.length) {
                switch (value) {
                    case "bg":
                        $('[data-id=cl_asset]').hide();
                        $('[data-id=bg_asset]').show();
                        $activeCard.removeClass("trans").addClass("photo").css({
                            'backgroundImage': 'url(' + config.surl + '/uploads/thumbs/' + image.replace(/.*\//, '') + ')',
                            'backgroundColor': '',
                        });
                        break;

                    case "tr":
                        $('[data-id=cl_asset],[data-id=bg_asset]').hide();
                        $activeCard.removeAttr("style").removeClass("photo").addClass("trans");
                        break;

                    case "cl":
                        $('[data-id=bg_asset]').hide();
                        $('[data-id=cl_asset]').show();
                        var color = $('#bg_color').val();
                        $activeCard.removeAttr("style").removeClass("photo trans").css('backgroundColor', color);

                        $("[data-id=cl_asset] .button").spectrum({
                            showInput: true,
                            showAlpha: true,
                            move: function(color) {
                                var rgba = "transparent";
                                if (color) {
                                    rgba = color.toRgbString();
                                }

                                $(this).css("color", rgba);
                                $activeCard.css("backgroundColor", rgba);

                            },
                            change: function(color) {
                                var rgba = "transparent";
                                if (color) {
                                    rgba = color.toRgbString();
                                }
                                $('#bg_color').val(rgba);
                            }
                        });
                        break;
                }
            }
        });

        //change image
        $("#source").on('click', '.bg_image', function() {
            $.get(config.aurl + '/managerBuilder.php', {
                pickFile: 1,
                editor: true
            }).done(function(data) {
                var modal =
                    '<div id="fileModal" class="yoyo large modal">' +
                    '<div class="yoyo content">' + data + '</div>' +
                    '</div>';
                $(modal).modal('setting', 'onShow', function() {
                    var cmodal = this;
                    $("#result").on('click', '.is_file', function() {
                        var dataset = $(this).data('set');
                        if (dataset.image === "true") {
                            $("#bg_img").val(dataset.url);
                            $activeCard.css("backgroundImage", "url('" + config.surl + "/uploads/thumbs/" + dataset.name + "')");
                            $(cmodal).modal('hide');
                        }
                    });
                }).modal('setting', 'onHidden', function() {
                    $(this).remove();
                }).modal('show');
            });
        });

        //Close source
        $("#source").on('click', 'a#closeSource', function() {
            $("#source").transition('slide up');
            var id = $activeCard.parent().data("id");
            var mode = $activeCard.data("mode");
            var color = $activeCard.data("color");

            $.post(config.url, {
                action: "updateSlide",
                id: id,
                mode: mode,
                image: $("#bg_img").val(),
                color: color,
            }, function(json) {
                if (json.type === "success") {
                    $activeCard.removeClass("active");
                    $("#sortable").removeClass("read-only");
                }
            }, "json");
        });

        //Add new slide
        $("a#addnew").on('click', function() {
            $("#sortable .card").removeClass("active");
            $.get(config.aurl + '/managerBuilder.php', {
                pickFile: 1,
                editor: true
            }).done(function(data) {
                var modal =
                    '<div id="fileModal" class="yoyo large modal">' +
                    '<div class="yoyo content">' + data + '</div>' +
                    '</div>';
                $(modal).modal('setting', 'onShow', function() {
                    var cmodal = this;
                    $("#result").on('click', '.is_file', function() {
                        var dataset = $(this).data('set');
                        if (dataset.image === "true") {
                            $.post(config.url, {
                                action: "newSlide",
                                id: $.url().segment(-1),
                                image: dataset.url
                            }, function(json) {
                                if (json.type === "success") {
                                    $("#sortable").addClass("read-only");
                                    $("#sortable").append(json.thumb);
                                    $activeCard = $("#sortable .card:last").addClass("active");

                                    $("#bg_img").val(json.image);
                                    $(':radio[value=' + json.mode + ']', "#source").prop('checked', true);
                                    $('[data-id=' + json.mode + '_asset]').show();
                                    $("#source").transition('slide down');
                                    $('.wedit').editableTableWidget();
                                }
                            }, "json");

                            $(cmodal).modal('hide');
                        }
                    });
                }).modal('setting', 'onHidden', function() {
                    $(this).remove();
                }).modal('show');
            });
        });

        $("#sortable").sortables({
            ghostClass: "ghost",
            handle: ".handle",
            animation: 600,
            onUpdate: function() {
                var order = this.toArray();
                $.ajax({
                    type: 'post',
                    url: config.url,
                    dataType: 'json',
                    data: {
                        action: "slideOrder",
                        sorting: order
                    }
                });
            }
        });
		
		//Global Configuration
		$("#layoutMode").on('click', 'a', function() {
			$("#layoutMode .segment").removeClass('active');
			$(this).parent().addClass('active');
			$("input[name=layout]").val($(this).data('type'));
		});
    };
})(jQuery);