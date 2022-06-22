(function($) {
    "use strict";
    $.Menu = function(settings) {
        var config = {
            url: "",
            lang: {
                delMsg3: "Trash",
                delMsg8: "The item will remain in Trash for 30 days. To remove it permanently, go to Trash and empty it.",
                canBtn: "Cancel",
				nonBtn: "None",
                trsBtn: "Move to Trash",
            }
        };
        if (settings) {
            $.extend(config, settings);
        }
        
		$('#mIcons').find('i[class="' + $("input[name=icon]").val() + '"]').parent('.button').addClass('primary');
		
        $('#contenttype').on('change', function() {
            var $icon = $(this).parent();
            var option = $(this).val();
			if(option === "") {
				$("#contentid").show();
				$("#webid").hide();
				$('#page_id').html('<option value="0">' + config.lang.nonBtn + '</option>')[0].wselect.unload();
				$('#page_id').yoyoSelect();
				$('#page_id').prop('name', 'page_id');
			} else {
				$icon.addClass('loading');
				$.get(config.url + "/helper.php", {
					doAction: 1,
					page : "contenttype",
					type: option,
				}, function(json) {
					switch (json.type) {
						case "page":
							$("#contentid").show();
							$("#webid").hide();
							$('#page_id').html(json.message)[0].wselect.unload();
							$('#page_id').yoyoSelect();
							$('#page_id').prop('name', 'page_id');
							break;
	
						case "module":
							$("#contentid").show();
							$("#webid").hide();
							$('#page_id').html(json.message)[0].wselect.unload();
							$('#page_id').yoyoSelect();
							$('#page_id').prop('name', 'mod_id');
							break;
	
						default:
							$("#contentid").hide();
							$("#webid").show();
							$('#page_id').prop('name', 'web_id');
							break;
					}
	
					$icon.removeClass('loading');
				}, "json");
			}
        });

        $(document).on('click', 'a.delMenu', function() {
            var dataset = $(this).data("set");
            var $parent = $(this).closest(dataset.parent);
            $('<div class="yoyo tiny modal">' +
                '<div class="header">' + config.lang.delMsg3 + ' <span class=\"yoyo secondary text\">' + dataset.option[0].title + '?</span></div>' +
                '<div class="content content-center"><i class=\"huge circular icon negative trash\"></i>' +
                '<p class="half-top-padding"><span class="yoyo bold text">' + config.lang.delMsg8 + '</span></p>' +
                '</div>' +
                '<div class="actions">' +
                '<div class="yoyo cancel button"> ' + config.lang.canBtn + '</div>' +
                '<div class="yoyo ok secondary button">' + config.lang.trsBtn + '</div>' +
                '</div>' +
                '</div>').modal('show').modal('setting', 'onApprove', function() {
                var $this = $(this);
                $.ajax({
                    type: 'POST',
                    url: config.url + "/controller.php",
                    dataType: 'json',
                    data: dataset.option[0]
                }).done(function(json) {
                    if (json.type === "success") {
                        $($parent).transition({
                            animation: 'fade',
                            duration: '1s',
                            onComplete: function() {
                                $($parent).slideUp();
                            }
                        });

						$("#parent_id").html(json.menu)[0].wselect.unload();
						$('#parent_id').yoyoSelect();
                        $("#parent_id").parent().addClass('loading');

                        $('.huge.icon', $this).toggleClass('negative trash positive check transition hidden').transition('pulse').transition({
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
                        setTimeout(function() {
                            $("#parent_id").parent().removeClass('loading');
                        }, 1200);
                    }
                });
                return false;
            }).modal('setting', 'onHidden', function() {
                $(this).remove();
            });
        });

        /* == Toggle Menu icons == */
        $('#mIcons').on('click', '.button', function() {
            var micon = $("input[name=icon]");
            $('#mIcons .button.primary').not(this).removeClass('primary');
            $(this).toggleClass("primary");
            micon.val($(this).hasClass('primary') ? $(this).children().attr('class') : "");
        });

        $('#sortlist').nestable({
            maxDepth: 4
        }).on('change', function() {
            var json_text = $('#sortlist').nestable('serialize');
            $.ajax({
                cache: false,
                type: "post",
                url: config.url + "/helper.php",
                dataType: "json",
                data: {
					processItem: 1,
                    page: "sortMenus",
                    sortlist: JSON.stringify(json_text)
                }
            });
        }).nestable('collapseAll');
    };
})(jQuery);