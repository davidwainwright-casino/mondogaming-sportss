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
<div class="yoyo small form content">
  <form method="post" id="modal_form" name="modal_form">
    <div class="yoyo block fields">
      <div class="field">
        <label><?php echo Lang::$word->NAME;?></label>
        <input type="text" value="<?php echo $this->data->name;?>" name="name">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->DESCRIPTION;?></label>
        <textarea  name="description"><?php echo $this->data->description;?></textarea>
      </div>
    </div>
  </form>
</div>