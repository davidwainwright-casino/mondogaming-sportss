<?php
  /**
   * iFrame
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  

  define("_YOYO", true);

  require_once("../../../../../init.php");
  if (!App::Auth()->is_Admin()) {
	  exit; 
  }
		   
  if(!Auth::checkPlugAcl('slider')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
 ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Slider Builder</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description" content="">
<link href="<?php echo THEMEURL;?>/cache/master_main_ltr.css" rel="stylesheet" type="text/css">
<link href="<?php echo ADMINVIEW;?>/plugins_/slider/builder/iframe.css" rel="stylesheet" type="text/css" />
<script src="<?php echo SITEURL;?>/assets/jquery.js" type="text/javascript"></script>
<script src="<?php echo SITEURL;?>/assets/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo SITEURL;?>/assets/global.js" type="text/javascript"></script>
</head>
<body id="builderFrame"></body>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/editor.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/fontcolor.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/alignment.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/fontsize.js"></script> 
<script type="text/javascript">
  $(document).ready(function($) {
    $(parent.document).on('click', '.is_edit.editor', function() {
		$("#builderFrame").find(".ws-layer.active").redactor({
			air:true,
			plugins: ['alignment', 'fontcolor', 'fontsize'],
			 buttons: ['html', 'format', 'fontsize', 'fontcolor', 'bold', 'italic', 'deleted', 'deleted', 'link','alignment']
		});
	});
    $(parent.document).on('click', '#builderAside .save', function() {
		$("#builderFrame").find(".ws-layer.active").redactor('destroy');
	});
  });
</script>
</html>