<?php
  /**
   * Gmaps
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::checkModAcl('gmaps')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments, 3)): case "edit": ?>
<!-- Start edit -->
<?php include("_gmaps_edit.tpl.php");?>
<?php break;?>
<?php case "new": ?>
<!-- Start new -->
<?php include("_gmaps_new.tpl.php");?>
<?php break;?>
<?php default: ?>
<!-- Start default -->
<?php include("_gmaps_grid.tpl.php");?>
<?php break;?>
<?php endswitch;?>