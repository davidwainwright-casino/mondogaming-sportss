<?php
  /**
   * Yplayer
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(APLUGPATH . 'yplayer/'));
?>
<?php if($row = App::Yplayer()->render($data['plugin_id'])):?>
<div id="wPlayer_<?php echo $row->id;?>"></div>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function() {
    $("#wPlayer_<?php echo $row->id;?>").wojo_tube(<?php echo str_replace("YTKEY", App::Core()->ytapi, $row->config);?>);
});
// ]]>
</script>
<?php endif;?>