<?php
  /**
   * Animation Helper
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div id="animation-helper" class="transition hidden">
  <div class="header">
    <i class="icon white spin circles"></i>
    <h3 class="handle"> Animation Editor</h3>
    <a class="close-animator"><i class="icon white delete"></i></a>
  </div>
  <div class="yoyo form wrapper" id="anieditor">
    <div class="row gutters">
      <div class="columns content-center">
        <a class="yoyo fluid icon button item noani" data-value="none"><i class="icon medium ban"></i></a>
        <small>none</small>
      </div>
      <div class="columns content-center">
        <a data-dropdown="#animationFade" class="yoyo fluid icon button"><i class="icon medium wysiwyg fade"></i></a>
        <small>fade</small>
        <div class="yoyo small dropdown dark menu top-center" id="animationFade">
          <a class="item" data-value="fadeIn">fadeIn</a>
          <a class="item" data-value="fadeInLeft">fadeInLeft</a>
          <a class="item" data-value="fadeInRight">fadeInRight</a>
          <a class="item" data-value="fadeInTop">fadeInTop</a>
          <a class="item" data-value="fadeInBottom">fadeInBottom</a>
        </div>
      </div>
      <div class="columns content-center">
        <a data-dropdown="#animationSlide" class="yoyo fluid icon button"><i class="icon medium wysiwyg slide"></i></a>
        <small>slide</small>
        <div class="yoyo small dropdown dark menu top-center" id="animationSlide">
          <a class="item" data-value="driveInLeft">slideInLeft</a>
          <a class="item" data-value="driveInRight">slideInRight</a>
          <a class="item" data-value="driveInTop">slideInTop</a>
          <a class="item" data-value="driveInBottom">slideInBottom</a>
        </div>
      </div>
      <div class="columns content-center">
        <a data-dropdown="#animationBounce" class="yoyo fluid icon button"><i class="icon medium wysiwyg bounce"></i></a>
        <small>bounce</small>
        <div class="yoyo small dropdown dark menu top-center" id="animationBounce">
          <a class="item" data-value="popIn">bounceIn</a>
          <a class="item" data-value="popInLeft">bounceInLeft</a>
          <a class="item" data-value="popInRight">bounceInRight</a>
          <a class="item" data-value="popInTop">bounceInTop</a>
          <a class="item" data-value="popInBottom">bounceInBottom</a>
        </div>
      </div>
    </div>
    <div class="row gutters">
      <div class="columns content-center">
        <a data-dropdown="#animationZoom" class="yoyo fluid icon button"><i class="icon medium wysiwyg zoom"></i></a>
        <small>zoom</small>
        <div class="yoyo small dropdown dark menu top-center" id="animationZoom">
          <a class="item" data-value="popIn">zoomIn</a>
          <a class="item" data-value="popInLeft">zoomInLeft</a>
          <a class="item" data-value="popInRight">zoomInRight</a>
          <a class="item" data-value="popInTop">zoomInTop</a>
          <a class="item" data-value="popInBottom">zoomInBottom</a>
        </div>
      </div>
      <div class="columns content-center">
        <a data-dropdown="#animationFlip" class="yoyo fluid icon button"><i class="icon medium wysiwyg flip"></i></a>
        <small>flip</small>
        <div class="yoyo small dropdown dark menu top-center" id="animationFlip">
          <a class="item" data-value="flip">flip</a>
          <a class="item" data-value="flipInX">flipInX</a>
          <a class="item" data-value="flipInY">flipInY</a>
        </div>
      </div>
      <div class="columns content-center">
        <a data-dropdown="#animationFold" class="yoyo fluid icon button"><i class="icon medium wysiwyg fold"></i></a>
        <small>fold</small>
        <div class="yoyo small dropdown dark menu top-center" id="animationFold">
          <a class="item" data-value="fold">fold</a>
          <a class="item" data-value="unfold">unfold</a>
        </div>
      </div>
      <div class="columns content-center">
        <a data-dropdown="#animationRoll" class="yoyo fluid icon button"><i class="icon medium wysiwyg roll"></i></a>
        <small>roll</small>
        <div class="yoyo small dropdown dark menu top-center" id="animationRoll">
          <a class="item" data-value="rollInLeft">rollInLeft</a>
          <a class="item" data-value="rollInRight">rollInRight</a>
          <a class="item" data-value="rollInTop">rollInTop</a>
          <a class="item" data-value="rollInBottom">rollInBottom</a>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="seven wide field">
        <label>Animation Duration in (ms)</label>
        <input class="rangers" type="text" value="0" name="aniDuration" data-ranger='{"step":100,"from":0, "to":3000, "format":"ms", "tip": false, "range":false}'>
      </div>
      <div class="field">
        <div class="yoyo tiny fluid input">
          <input type="text" value="0" name="aniDurationText">
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="seven wide field">
        <label>Animation Delay in (ms)</label>
        <input class="rangers" type="text" value="0" name="aniDelay" data-ranger='{"step":100,"from":0, "to":2000, "format":"ms", "tip": false, "range":false}'>
      </div>
      <div class="field">
        <div class="yoyo tiny fluid input">
          <input name="aniDelayText" type="text" value="0" step="100" min="0" max="2000" name="aniDelayText">
        </div>
      </div>
    </div>
    <div class="content-center">
      <button class="yoyo primary button" id="aniPlay"><i class="icon play"></i> Play</button>
    </div>
  </div>
</div>