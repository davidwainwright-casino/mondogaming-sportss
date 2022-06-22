<?php
  /**
   * Header
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  if (!App::Auth()->is_Admin()) {
	  Url::redirect(SITEURL . '/admin/login/'); 
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
<link href="<?php echo ADMINVIEW;?>/cache/master_main_ltr.css" rel="stylesheet" type="text/css">
<link href="<?php echo SITEURL;?>/assets/builder/builder.css" rel="stylesheet" type="text/css" />
<link href="<?php echo THEMEURL . '/plugins/cache/' . Cache::pluginCssCache(THEMEBASE . '/plugins');?>" rel="stylesheet" type="text/css">
<link href="<?php echo THEMEURL . '/modules/cache/' . Cache::moduleCssCache(THEMEBASE . '/modules');?>" rel="stylesheet" type="text/css">
<link href="<?php echo SITEURL;?>/assets/builder/editor.css" rel="stylesheet" type="text/css" />
<script src="<?php echo SITEURL;?>/assets/jquery.js" type="text/javascript"></script>
<script src="<?php echo SITEURL;?>/assets/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo SITEURL;?>/assets/global.js" type="text/javascript"></script>
</head>
<body class="design">
<div id="master-loader">
  <div class="wanimation"></div>
  <div class="curtains left"></div>
  <div class="curtains right"></div>
</div>
<div id="builderHeader">
  <div class="row align-middle horizontal-gutters">
    <div class="columns shrink">
      <div class="yoyo toggle checkbox radio fitted">
        <input name="bmode" type="radio" value="design" id="bdesign" checked="checked">
        <label for="bdesign">Design</label>
      </div>
    </div>
    <div class="columns shrink">
      <div class="yoyo toggle checkbox radio fitted">
        <input name="bmode" type="radio" value="edit" id="bedit">
        <label for="bedit">Details</label>
      </div>
    </div>
    <div class="columns">
      <h4 class="yoyo white text"><?php echo $this->data->{'title' . Lang::$lang};?></h4>
    </div>
    <div class="column shrink reswitch">
      <a data-mode="screen" class="yoyo transparent icon button action"><i class="icon primary desktop"></i></a>
      <a data-mode="tablet" class="yoyo transparent icon button action"><i class="icon laptop"></i></a>
      <a data-mode="phone" class="yoyo transparent icon button action"><i class="icon smartphone"></i></a>
    </div>
    <?php if(count($this->langlist) > 1):?>
    <div class="column shrink horizontal-padding">
      <a data-dropdown="#dropdown-langMenu" class="yoyo transparent icon button">
        <i class="icon flag"></i>
      </a>
      <div class="yoyo dropdown menu top-right" id="dropdown-langMenu">
        <?php foreach($this->langlist as $lang):?>
        <?php if($lang->abbr == $this->segments[2]):?>
        <a data-value="<?php echo $lang->abbr;?>" class="item selected"><span class="flag icon <?php echo $lang->abbr;?>"></span>
          <span class="padding-left"><?php echo $lang->name;?></span></a>
        <?php else:?>
        <a data-value="<?php echo $lang->abbr;?>" class="item" href="<?php echo Url::url("/admin/builder/" . $lang->abbr, $this->segments[3]);?>"><span class="flag icon <?php echo $lang->abbr;?>"></span>
          <span class="padding-left"><?php echo $lang->name;?></span></a>
        <?php endif;?>
        <?php endforeach;?>
      </div>
    </div>
    <?php endif;?>
    <div class="column shrink">
      <a class="yoyo negative icon button" href="<?php echo Url::url("/admin/pages/edit", $this->segments[3]);?>">
        <i class="icon delete"></i></a>
      <a id="saveAll" class="yoyo secondary icon button">
        <i class="icon floppy"></i></a>
      <span class="yoyo separator"></span>
      <div class="yoyo checkbox toggle fitted inline">
        <input name="langall" type="checkbox" value="1" id="langall">
        <label for="langall">All Language</label>
      </div>
    </div>
  </div>
</div>