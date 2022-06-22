$(function() {
    "use strict";
    //like item
    $(document).on('click', '.blogLike', function() {
        //var like =  typeof $(this).data().hasOwnProperty("thumbs-up")  ? "up" : "down";
		var like =  ($(this).data() === "thumbsUp")  ? "up" : "down";
        var url = $(this).data('url');
        var id = $(this).data('id');
        var $this = $(this);

        $(this).transition({
            animation: 'scale',
            duration: '2s',
            onComplete: function() {
                $this.replaceWith('<i class="icon check"></i>');
				$.post(url + 'blog/controller.php', {
					action: "like",
					id: id,
					type: like
				}, function(json) {
					if(json.status === "success") {
					  $.cookie("BLOG_voted", id, {
						  expires: 120,
						  path: '/'
					  });
					}
				}, "json");
            }
        });
    });
});