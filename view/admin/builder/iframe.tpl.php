<?php
  /**
   * iFrame
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);

  require_once("../../../init.php");
  if (!App::Auth()->is_Admin()) {
	  exit; 
  }
		   
  if(!Auth::hasPrivileges('manage_pages')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
 ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Page Builder</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description" content="">
<link href="<?php echo THEMEURL;?>/cache/master_main_ltr.css" rel="stylesheet" type="text/css">
<script src="<?php echo SITEURL;?>/assets/jquery.js" type="text/javascript"></script>
<script src="<?php echo SITEURL;?>/assets/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo SITEURL;?>/assets/global.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo THEMEURL . '/plugins/cache/' . Cache::pluginJsCache(THEMEBASE . '/plugins');?>"></script>
<script type="text/javascript" src="<?php echo THEMEURL . '/modules/cache/' . Cache::moduleJsCache(THEMEBASE . '/modules');?>"></script>
<link href="<?php echo THEMEURL . '/plugins/cache/' . Cache::pluginCssCache(THEMEBASE . '/plugins');?>" rel="stylesheet" type="text/css">
<link href="<?php echo THEMEURL . '/modules/cache/' . Cache::moduleCssCache(THEMEBASE . '/modules');?>" rel="stylesheet" type="text/css">
<link href="<?php echo SITEURL;?>/assets/builder/iframe.css" rel="stylesheet" type="text/css" />
</head>
<body id="builderFrame"> </body>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/builder/editor.js"></script> 
<script type="text/javascript">
  $(document).ready(function($) {
    $(parent.document).on('change', 'input[name="bmode"]', function() {
		bMode = $(this).val();
		switch (bMode) {
			case "edit":
				$("#builderFrame").attr("contenteditable", true).popline();
				break;

			case "design":
				$("#builderFrame").removeAttr("contenteditable");
				$("#builderFrame").popline("destroy");
				break;
		}
	});
  });
</script>
</html>