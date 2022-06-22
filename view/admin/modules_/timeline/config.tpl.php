<?php
  /**
   * Configuration
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  Bootstrap::Autoloader(array(AMODPATH . 'timeline/'));
?>
<label><?php echo Lang::$word->_MOD_TML_LSELTIME;?></label>
<select name="module_data" class="yoyo fluid selection dropdown">
  <option value="0"><?php echo Lang::$word->_MOD_TML_LSELTIME;?></option>
  <?php echo Utility::loopOptions(Db::run()->select(Timeline::mTable, null, null, "ORDER BY created DESC")->results(), "id", "name", $this->data);?>
</select>