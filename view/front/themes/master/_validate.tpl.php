<?php
  /**
   * Payment Validate
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php if(Validator::get('order_id')):?>
<div class="yoyo-grid">
  <div class="yoyo loading segment">
    <h4 class="content-center"><?php echo Lang::$word->STR_POK1;?></h4>
  </div>
</div>
<?php else:?>
<?php Url::redirect(SITEURL);?>
<?php endif;?>
<?php if($type = Validator::get('order_id')):?>
<?php
  switch (substr($type, 0, 3)) {
      case "DSH":
          $url = "/gateways/ideal/digishop/ipn.php";
		  $link = "digishop";
          break;

      case "SSH":
          $url = "/gateways/ideal/shop/ipn.php";
		  $link = "shop";
          break;
          
      default:
          $url = "/gateways/ideal/ipn.php";
		  $link = "history";
          break;
  }
?>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function() {
    $.ajax({
        type: 'GET',
        url: "<?php echo SITEURL . $url;?>",
        dataType: 'json',
        data: {
            "order_id": "<?php echo Validator::sanitize(Validator::get('order_id'), "db");?>"
        }
    }).done(function(json) {
        if (json.type === "success") {
			$('body').transition({
				animation  : 'scale',
				duration   : '4s',
				onComplete : function() {
				  window.location.href = '<?php echo Url::url('/' . App::Core()->system_slugs->account[0]->{'slug' . Lang::$lang}, $link);?>';
				}
			  });
        }
		$.notice(decodeURIComponent(json.message), {
			autoclose: 12000,
			type: json.type,
			title: json.title
		});
    });
});
// ]]>
</script>
<?php endif;?>