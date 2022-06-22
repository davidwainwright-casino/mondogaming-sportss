<?php
  /**
   * Language Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_languages')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments)): case "edit": ?>
<!-- Start edit -->
<?php include("_languages_edit.tpl.php");?>
<?php break;?>
<!-- Start new -->
<?php case "new": ?>
<?php include("_languages_new.tpl.php");?>
<?php break;?>
<!-- Start translate -->
<?php case "translate": ?>
<?php include("_languages_translate.tpl.php");?>
<?php break;?>
<!-- Start default -->
<?php default: ?>
<?php include("_languages_grid.tpl.php");?>
<?php break;?>
<?php endswitch;?>
<script src="<?php echo ADMINVIEW;?>/js/language.js"></script> 
<script type="text/javascript"> 
// <![CDATA[	
  $(document).ready(function() {
	  $.Language({
		  url: "<?php echo ADMINVIEW;?>",
	  });
  });
// ]]>
</script> 