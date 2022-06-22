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
		   
  if(!Auth::checkPlugAcl('slider')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
 ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Slider Builder</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description" content="">
<link href="<?php echo ADMINVIEW;?>/cache/master_main_ltr.css" rel="stylesheet" type="text/css">
<link href="<?php echo ADMINVIEW;?>/plugins_/slider/builder/builder.css" rel="stylesheet" type="text/css" />
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
      <a class="yoyo small transparent icon button is_align disabled" data-mode="align-left">
        <i class="icon grid align top left"></i></a>
      <a class="yoyo small transparent icon button is_align disabled" data-mode="align-center">
        <i class="icon grid align top center"></i></a>
      <a class="yoyo small transparent icon button is_align disabled" data-mode="align-right">
        <i class="icon grid align top right"></i></a>
    </div>
    <div class="columns shrink">
      <a class="yoyo small transparent icon button is_position" data-mode="align-center">
        <i class="icon grid align center left"></i></a>
      <a class="yoyo small transparent icon button is_position" data-mode="align-center align-middle">
        <i class="icon grid align middle center"></i></a>
      <a class="yoyo small transparent icon button is_position" data-mode="align-bottom align-center">
        <i class="icon grid align center right"></i></a>
    </div>
    <div class="columns shrink">
      <a class="yoyo small transparent icon button is_size disabled" data-mode="shrink">
        <i class="icon checkbox checked alt"></i></a>
      <a class="yoyo small transparent icon button is_size disabled" data-mode="full">
        <i class="icon checkbox"></i></a>
    </div>
    <div class="columns shrink" style="min-width:200px">
      <a data-dropdown="#anipack" class="yoyo small fluid transparent right button">
        <span class="text">Animations</span>
        <i class="icon chevron down"></i>
      </a>
      <div class="yoyo small dropdown dark menu top-left nowrap" id="anipack">
        <div class="scrolling">
          <p class="yoyo tiny semi text">Static Animations</p>
          <a class="item" data-html="None" data-value="none">None</a>
          <a class="item" data-html="ball" data-value="ball">ball</a>
          <a class="item" data-html="pulsate" data-value="pulsate">pulsate</a>
          <a class="item" data-html="blink" data-value="blink">blink</a>
          <a class="item" data-html="hitLeft" data-value="hitLeft">hitLeft</a>
          <a class="item" data-html="hitRight" data-value="hitRight">hitRight</a>
          <a class="item" data-html="shake" data-value="shake">shake</a>
          <p class="yoyo tiny semi text">Pop Enter Animations</p>
          <a class="item" data-html="popIn" data-value="popIn">popIn</a>
          <a class="item" data-html="popInLeft" data-value="popInLeft">popInLeft</a>
          <a class="item" data-html="popInRight" data-value="popInRight">popInRight</a>
          <a class="item" data-html="popInTop" data-value="popInTop">popInTop</a>
          <a class="item" data-html="popInBottom" data-value="popInBottom">popInBottom</a>
          <p class="yoyo tiny semi text">Pop Exit Animations</p>
          <a class="item" data-html="popOut" data-value="popOut">popOut</a>
          <a class="item" data-html="popOutLeft" data-value="popOutLeft">popOutLeft</a>
          <a class="item" data-html="popOutRight" data-value="popOutRight">popOutRight</a>
          <a class="item" data-html="popOutTop" data-value="popOutTop">popOutTop</a>
          <a class="item" data-html="popOutBottom" data-value="popOutBottom">popOutBottom</a>
          <p class="yoyo tiny semi text">Flip Animations</p>
          <a class="item" data-html="flip" data-value="flip">flip</a>
          <a class="item" data-html="flipInX" data-value="flipInX">flipInX</a>
          <a class="item" data-html="flipInY" data-value="flipInY">flipInY</a>
          <a class="item" data-html="flipOutX" data-value="flipOutX">flipOutX</a>
          <a class="item" data-html="flipOutY" data-value="flipOutY">flipOutY</a>
          <p class="yoyo tiny semi text">Jump Animations</p>
          <a class="item" data-html="jumpInLeft" data-value="jumpInLeft">jumpInLeft</a>
          <a class="item" data-html="jumpInRight" data-value="jumpInRight">jumpInRight</a>
          <a class="item" data-html="jumpOutLeft" data-value="jumpOutLeft">jumpOutLeft</a>
          <a class="item" data-html="jumpOutRight" data-value="jumpOutRight">jumpOutRight</a>
          <p class="yoyo tiny semi text">Swoop Enter Animations</p>
          <a class="item" data-html="swoopInLeft" data-value="swoopInLeft">swoopInLeft</a>
          <a class="item" data-html="swoopInRight" data-value="swoopInRight">swoopInRight</a>
          <a class="item" data-html="swoopInTop" data-value="swoopInTop">swoopInTop</a>
          <a class="item" data-html="swoopInBottom" data-value="swoopInBottom">swoopInBottom</a>
          <p class="yoyo tiny semi text">Swoop Exit Animations</p>
          <a class="item" data-html="swoopOutLeft" data-value="swoopOutLeft">swoopOutLeft</a>
          <a class="item" data-html="swoopOutRight" data-value="swoopOutRight">swoopOutRight</a>
          <a class="item" data-html="swoopOutTop" data-value="swoopOutTop">swoopOutTop</a>
          <a class="item" data-html="swoopOutBottom" data-value="swoopOutBottom">swoopOutBottom</a>
          <p class="yoyo tiny semi text">Drive Enter Animations</p>
          <a class="item" data-html="driveInLeft" data-value="driveInLeft">driveInLeft</a>
          <a class="item" data-html="driveInRight" data-value="driveInRight">driveInRight</a>
          <a class="item" data-html="driveInTop" data-value="driveInTop">driveInTop</a>
          <a class="item" data-html="driveInBottom" data-value="driveInBottom">driveInBottom</a>
          <p class="yoyo tiny semi text">Drive Exit Animations</p>
          <a class="item" data-html="driveOutBottom" data-value="driveOutBottom">driveOutBottom</a>
          <a class="item" data-html="driveOutTop" data-value="driveOutTop">driveOutTop</a>
          <a class="item" data-html="driveOutLeft" data-value="driveOutLeft">driveOutLeft</a>
          <a class="item" data-html="driveOutRight" data-value="driveOutRight">driveOutRight</a>
          <p class="yoyo tiny semi text">Fade Enter Animations</p>
          <a class="item" data-html="fadeIn" data-value="fadeIn">fadeIn</a>
          <a class="item" data-html="fadeInLeft" data-value="fadeInLeft">fadeInLeft</a>
          <a class="item" data-html="fadeInRight" data-value="fadeInRight">fadeInRight</a>
          <a class="item" data-html="fadeInTop" data-value="fadeInTop">fadeInTop</a>
          <a class="item" data-html="fadeInBottom" data-value="fadeInBottom">fadeInBottom</a>
          <p class="yoyo tiny semi text">Fade Exit Animations</p>
          <a class="item" data-html="fadeOut" data-value="fadeOut">fadeOut</a>
          <a class="item" data-html="fadeOutLeft" data-value="fadeOutLeft">fadeOutLeft</a>
          <a class="item" data-html="fadeOutRight" data-value="fadeOutRight">fadeOutRight</a>
          <a class="item" data-html="fadeOutTop" data-value="fadeOutTop">fadeOutTop</a>
          <a class="item" data-html="fadeOutBottom" data-value="fadeOutBottom">fadeOutBottom</a>
          <p class="yoyo tiny semi text">Roll Enter Animations</p>
          <a class="item" data-html="rollInLeft" data-value="rollInLeft">rollInLeft</a>
          <a class="item" data-html="rollInLeft" data-value="rollInRight">rollInRight</a>
          <a class="item" data-html="rollInTop" data-value="rollInTop">rollInTop</a>
          <a class="item" data-html="rollInBottom" data-value="rollInBottom">rollInBottom</a>
          <p class="yoyo tiny semi text">Roll Out Animations</p>
          <a class="item" data-html="rollOutLeft" data-value="rollOutLeft">rollOutLeft</a>
          <a class="item" data-html="rollOutRight" data-value="rollOutRight">rollOutRight</a>
          <a class="item" data-html="rollOutTop" data-value="rollOutTop">rollOutTop</a>
          <a class="item" data-html="rollOutBottom" data-value="rollOutBottom">rollOutBottom</a>
          <p class="yoyo tiny semi text">Spin Animations</p>
          <a class="item" data-html="spin" data-value="spin">spin</a>
          <a class="item" data-html="spinIn" data-value="spinIn">spinIn</a>
          <a class="item" data-html="spinOut" data-value="spinOut">spinOut</a>
          <p class="yoyo tiny semi text">Pull Animations</p>
          <a class="item" data-html="pullUp" data-value="pullUp">pullUp</a>
          <a class="item" data-html="pullDown" data-value="pullDown">pullDown</a>
          <a class="item" data-html="pullLeft" data-value="pullLeft">pullLeft</a>
          <a class="item" data-html="pullRight" data-value="pullRight">pullRight</a>
          <p class="yoyo tiny semi text">Fold Animations</p>
          <a class="item" data-html="fold" data-value="fold">fold</a>
          <a class="item" data-html="unfold" data-value="unfold">unfold</a>
        </div>
      </div>
    </div>
    <div class="columns shrink">
      <div class="yoyo small black fluid right action input">
        <input id="duration" type="text" name="time" value="0" data-content="duration in milliseconds max 5000">
        <span class="yoyo tiny simple label">ms</span>
      </div>
    </div>
    <div class="columns shrink">
      <div class="yoyo small black fluid right action input">
        <input id="delay" type="text" name="delay" value="0" data-content="delay in milliseconds max 5000">
        <span class="yoyo tiny simple label">ms</span>
      </div>
    </div>
    <div class="columns">
      <a id="play" class="yoyo small transparent icon button"><i class="icon positive play"></i></a>
    </div>
    <div class="column shrink reswitch">
      <a data-mode="screen" class="yoyo transparent icon button action"><i class="icon primary desktop"></i></a>
      <a data-mode="tablet" class="yoyo transparent icon button action"><i class="icon laptop"></i></a>
      <a data-mode="phone" class="yoyo transparent icon button action"><i class="icon smartphone"></i></a>
    </div>
    <div class="column shrink source">
      <a class="yoyo negative icon button" href="<?php echo Url::url("/admin/plugins/slider/edit", $this->data->parent_id);?>">
        <i class="icon delete"></i></a>
      <a id="saveAll" class="yoyo secondary icon button">
        <i class="icon floppy"></i></a>
    </div>
  </div>
</div>
<div id="builderAside">
  <!--  <a class="yoyo small transparent icon button editCanvas" data-content="Canvas Properties">
    <i class="icon cogs"></i></a>-->
  <a class="yoyo small transparent icon button editHtml" data-content="Canvas Html">
    <i class="icon code"></i></a>
  <a class="yoyo small transparent icon button disabled is_edit element">
    <i class="icon positive pencil"></i></a>
  <a class="yoyo small transparent icon button disabled is_edit editor">
    <i class="icon wysiwyg font"></i></a>
  <a class="yoyo small transparent icon button disabled is_edit html">
    <i class="icon code alt"></i></a>
  <a class="yoyo small positive icon button disabled save">
    <i class="icon check"></i></a>
  <a class="yoyo small transparent icon button disabled is_edit is_trash">
    <i class="icon negative trash"></i></a>
</div>