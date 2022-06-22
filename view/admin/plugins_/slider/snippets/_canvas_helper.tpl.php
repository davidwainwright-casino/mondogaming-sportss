<?php
  /**
   * Canvas Helper
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div id="canvas-helper" class="transition hidden">
  <div class="header">
    <i class="icon white note"></i>
    <h3 class="handle"> Canvas Editor</h3>
    <a class="close-styler"><i class="icon white delete"></i></a>
  </div>
  <div class="yoyo form">
    <article class="yoyo accordion">
      <div class="header"><span>Padding</span>
        <i class="icon angle down"></i></div>
      <div class="content">
        <div class="yoyo block fields">
          <div class="field">
            <label>Padding Top</label>
            <input class="rangers" type="text" value="0" name="paddingTop" data-ranger='{"step":4,"from":0, "to":400, "format":"px", "tip": false, "range":false}'>
          </div>
          <div class="field">
            <label>Padding Bottom</label>
            <input class="rangers" type="text" value="0" name="paddingBottom" data-ranger='{"step":4,"from":0, "to":400, "format":"px", "tip": false, "range":false}'>
          </div>
        </div>
      </div>
    </article>
  </div>
</div>