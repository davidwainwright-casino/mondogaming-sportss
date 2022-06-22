<?php
  /**
   * Timeline
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::checkModAcl('timeline')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments, 3)): case "edit": ?>
<!-- Start edit -->
<?php include("_timeline_edit.tpl.php");?>
<?php break;?>
<?php case "new": ?>
<!-- Start new -->
<?php include("_timeline_new.tpl.php");?>
<?php break;?>
<?php case "items": ?>
<!-- Start items -->
<?php include("_timeline_items.tpl.php");?>
<?php break;?>
<?php case "inew": ?>
<!-- Start inew -->
<?php include("_timeline_inew.tpl.php");?>
<?php break;?>
<?php case "iedit": ?>
<!-- Start iedit -->
<?php include("_timeline_iedit.tpl.php");?>
<?php break;?>
<?php default: ?>
<!-- Start default -->
<?php include("_timeline_grid.tpl.php");?>
<?php break;?>
<?php endswitch;?>
<script src="<?php echo AMODULEURL;?>timeline/view/js/timeline.js"></script> 
<script type="text/javascript"> 
// <![CDATA[  
  $(document).ready(function() {
	  $.Timeline({
		  url: "<?php echo ADMINVIEW;?>",
		  upUrl: "<?php echo UPLOADURL;?>",
	  });
  });
// ]]>
</script>