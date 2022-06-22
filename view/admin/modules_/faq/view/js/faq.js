(function($) {
    "use strict";
    $.Faq = function(settings) {
        var config = {
            url: "",
			grid: '.yoyo.blocks',
            lang: {
                delMsg3: "",
                delMsg8: "",
                canBtn: "",
                trsBtn: "",
				err: "",
				err1: ""
            }
        };
        if (settings) {
            $.extend(config, settings);
        }

        $(config.grid).waitForImages().done(function() {
            $(config.grid).pinto({
                itemWidth: 500,
                align: "center",
                gapX: 48,
                gapY: 48
            });
        });
		
		$(".sortable_faq").sortables({
			ghostClass: "ghost",
			handle: ".draggable",
			animation: 600,
			onUpdate: function() {
				var order = this.toArray();
				$.ajax({
					type: 'post',
					url: config.url,
					dataType: 'json',
					data: {
						action: "sortItems",
						sorting: order
					}
				});
			}
		});
	
        // sort categories
        if ($.inArray("category", $.url().segment()) !== -1 || $.inArray("categories", $.url().segment()) !== -1) {
            $('#sortlist').nestable({
                maxDepth: 1
            }).on('change', function() {
                var json_text = $('#sortlist').nestable('serialize');
                $.ajax({
                    cache: false,
                    type: "post",
                    url: config.url,
                    dataType: "json",
                    data: {
                        action: "sortCategories",
                        sortlist: JSON.stringify(json_text)
                    }
                });
            }).nestable('collapseAll');
        }
    };
})(jQuery);