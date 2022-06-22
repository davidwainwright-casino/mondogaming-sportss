<?php
  /**
   * Edit Role
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="popplug scrollbox_right" style="height:400px;">
  <?php if($this->data):?>
  <div data-section="<?php echo $this->section;?>" class="yoyo divided relaxed selection list">
    <?php foreach($this->data as $row):?>
    <div class="item" data-id="<?php echo $row->id;?>"><?php echo $row->title;?></div>
    <?php endforeach;?>
  </div>
  <?php endif;?>
</div>
<div class="yoyo double divider"></div>
<div class="content-center">
  <button class="yoyo simple small cancel button"><?php echo Lang::$word->CANCEL;?></button>
  <button class="yoyo small primary button insert"><?php echo Lang::$word->INSERT;?></button>
</div>