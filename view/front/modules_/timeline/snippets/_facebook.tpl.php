<?php
  /**
   * Timeline Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(AMODPATH . 'timeline/'));
?>
<!-- Start Timeline Manager -->
<div id="timeline"></div>
<?php if(isset($data['id'])):?>
<?php if($row = App::Timeline()->render($data['id'])):?>
<script type="text/javascript" src="https://connect.facebook.net/en_US/sdk.js"></script>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function() {
		var timeline = new Timeline($('#timeline'));
		timeline.setOptions({
			columnMode:          '<?php echo $row->colmode;?>',
			facebookPageId:      '<?php echo $row->fbpage;?>',
			facebookAppId:       '<?php echo $row->fbid;?>',
			facebookAccessToken: '<?php echo $row->fbtoken;?>' 
		});
		timeline.display();
});
// ]]>
</script>
<?php endif;?>
<?php endif;?>