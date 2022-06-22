<?php
  /**
   * Events
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::checkModAcl('events')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments, 3)): case "edit": ?>
<!-- Start edit -->
<?php include("_events_edit.tpl.php");?>
<?php break;?>
<?php case "new": ?>
<!-- Start new -->
<?php include("_events_new.tpl.php");?>
<?php break;?>
<?php case "calendar": ?>
<!-- Start calendar -->
<?php include("_events_calendar.tpl.php");?>
<?php break;?>
<?php case "grid": ?>
<!-- Start grid -->
<?php include("_events_grid.tpl.php");?>
<?php break;?>
<?php default: ?>
<!-- Start default -->
<?php include("_events_list.tpl.php");?>
<?php break;?>
<?php endswitch;?>