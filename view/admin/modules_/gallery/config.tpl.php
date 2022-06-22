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

  Bootstrap::Autoloader(array(AMODPATH . 'gallery/'));
?>
<label><?php echo Lang::$word->_MOD_GA_SUB7;?></label>
<select name="module_data" class="yoyo fluid dropdown">
  <option value="0"><?php echo Lang::$word->_MOD_GA_SUB6;?></option>
  <?php echo Utility::loopOptions(Gallery::getGalleryList(), "id", "title" . Lang::$lang, $this->data);?>
</select>