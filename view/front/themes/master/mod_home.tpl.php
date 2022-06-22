<?php
  /**
   * Module Index
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<main>
  <?php if(File::is_File(FMODPATH . $this->module . "/themes/" . $this->core->theme . "/mod_home.tpl.php")):?>
  <?php include FMODPATH . $this->module . "/themes/" . $this->core->theme . "/mod_home.tpl.php";?>
  <?php else:?>
	<?php if ($this->layout->topWidget): ?>
    <!-- Top Widgets -->
    <div id="topwidget">
      <?php include(THEMEBASE . "/top_widget.tpl.php");?>
    </div>
    <!-- Top Widgets /-->
    <?php endif;?>
    <?php switch(true): case $this->layout->leftWidget and $this->layout->rightWidget: ?>
    <!-- Left and Right Layout -->
    <div class="yoyo-grid">
      <div class="row horizontal-gutters">
        <div class="columns screen-20 tablet-25 mobile-100 phone-100">
          <?php include(THEMEBASE . "/left_widget.tpl.php");?>
        </div>
        <div class="columns screen-60 tablet-50 mobile-100 phone-100">
          <?php include_once(Modules::render($this->module));?>
        </div>
        <div class="columns screen-20 tablet-25 mobile-100 phone-100">
          <?php include(THEMEBASE . "/right_widget.tpl.php");?>
        </div>
      </div>
    </div>
    <!-- Left and Right Layout /-->
    <?php break;?>
    <?php case $this->layout->leftWidget: ?>
    <!-- Left Layout -->
    <div class="yoyo-grid">
      <div class="row horizontal-gutters">
        <div class="columns screen-30 tablet-40 mobile-100 phone-100">
          <?php include(THEMEBASE . "/left_widget.tpl.php");?>
        </div>
        <div class="columns screen-70 tablet-60 mobile-100 phone-100">
          <?php include_once(Modules::render($this->module));?>
        </div>
      </div>
    </div>
    <!-- Left Layout /-->
    <?php break;?>
    <?php case $this->layout->rightWidget: ?>
    <!-- Right Layout -->
    <div class="yoyo-grid">
      <div class="row horizontal-gutters">
        <div class="columns screen-70 tablet-60 mobile-100 phone-100">
          <?php include_once(Modules::render($this->module));?>
        </div>
        <div class="columns screen-30 tablet-40 mobile-100 phone-100">
          <?php include(THEMEBASE . "/right_widget.tpl.php");?>
        </div>
      </div>
    </div>
    <!-- Right Layout /-->
    <?php break;?>
    <?php default:?>
    <!-- Full Layout -->
    <div class="yoyo-grid">
      <?php include_once(Modules::render($this->module));?>
    </div>
    <!-- Full Layout /-->
    <?php break;?>
    <?php endswitch;?>
    <?php if ($this->layout->bottomWidget): ?>
    <!-- Bottom Widgets -->
    <div id="bottomwidget">
      <?php include(THEMEBASE . "/bottom_widget.tpl.php");?>
    </div>
    <!-- Bottom Widgets /-->
    <?php endif;?>
  <?php endif;?>
</main>