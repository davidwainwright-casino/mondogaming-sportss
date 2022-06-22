<?php
  /**
   * Misc Helper
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="yoyo modal" id="editSource">
  <div class="header">Edit Html</div>
  <div class="sub-header">
    <p class="yoyo icon text middle"><i class="icon warning sign"></i> Be careful what you doing here.</p>
  </div>
  <div class="yoyo form content">
    <textarea id="tempHtml" style="min-height:400px;"></textarea>
  </div>
  <div class="actions">
    <button class="yoyo simple small cancel button">cancel</button>
    <button class="yoyo ok small primary button">ok</button>
  </div>
</div>