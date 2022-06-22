$(function() {
    "use strict";
    //add to cart
    $("#digishop").on('click', 'a.add-digishop', function() {
        var id = $(this).data('id');
        var button = $(this);
        var url = $("#digishop").attr('action');
        button.addClass('loading');

        $.post(url + 'digishop/controller.php', {
            action: "add",
            id: id,
        }, function(json) {
            if (json.status === "success") {
                $("#cartList").html(json.html);
            } else {
                $.notice(decodeURIComponent("Ooops, there was an error selecting this item."), {
                    autoclose: 12000,
                    type: "error",
                    title: 'Error'
                });
            }
            setTimeout(function() {
                button.removeClass('loading');
            }, 1200);

        }, 'json');

    });

    //delete from cart
    $("#cartList").on('click', 'a.deleteItem', function() {
        var id = $(this).data('id');
        var item = $(this).closest('.item');
        var url = $("#cartList").attr('action');

        $.post(url + 'digishop/controller.php', {
            action: "remove",
            id: id,
        }, function(json) {
            if (json.status === "success") {
			  item.transition({
				  animation  : 'slide up out',
				  duration   : '0.4s',
				  onComplete : function() {
					$("#cartList").html(json.html);
				  }
				});
            }

        }, 'json');
    });

    //like item
    $('#digishop').on('click', '.digishopLike', function() {
        var id = $(this).attr('data-digishop-like');
        var total = $(this).attr('data-digishop-total');
        var score = $(this).parent().find('.likeTotal');
        var url = $("#digishop").attr('action');
		var $this = $(this);
        score.html(parseInt(total) + 1);

		$(this).transition({
			animation  : 'scale',
			duration   : '.8s',
			onComplete : function() {
                $this.replaceWith('<i class="icon check"></i>');
                $.post(url + 'digishop/controller.php', {
                    action: "like",
                    id: id
                });
			}
		  });
    });

    //load gateway
    $('#digishop').on('change', 'input[name=gateway]', function() {
        var id = $(this).val();
        var url = $("#digishop").attr('action');

        $.get(url + 'digishop/controller.php', {
            action: "gateway",
            id: id
        }, function(json) {
            $("#dCheckout").html(json.message);
			$("html,body").animate({
				scrollTop: $("#dCheckout").offset().top
			}, 1000);
        }, "json");
    });
});