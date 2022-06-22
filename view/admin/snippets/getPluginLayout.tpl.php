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
<form class="layform" name="layform">
  <div class="poplayout half-padding">
    <?php if($this->data):?>
    <div data-section="<?php echo $this->section;?>" class="yoyo very relaxed list">
      <?php foreach($this->data as $row):?>
      <div class="item">
        <div class="content">
          <div class="yoyo small text header" data-id="<?php echo $row->id;?>"><?php echo $row->title;?></div>
          <div class="description margin-top">
            <input type="text" name="space[<?php echo $row->id;?>]" value="<?php echo $row->space;?>" class="rangeslider">
          </div>
        </div>
      </div>
      <div class="yoyo space divider"></div>
      <?php endforeach;?>
    </div>
    <?php endif;?>
  </div>
</form>
<div class="yoyo double divider"></div>
<div class="content-center">
  <button class="yoyo small simple button cancel"><?php echo Lang::$word->CANCEL;?></button>
  <button class="yoyo small primary button update"><?php echo Lang::$word->UPDATE;?></button>
</div>