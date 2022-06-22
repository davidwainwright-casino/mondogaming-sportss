<?php
  /**
   * User Plugins
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php if($this->row):?>
<article class="plugin-wrap <?php echo ($this->row->alt_class) ? $this->row->alt_class : '';?>">
  <?php if ($this->row->show_title):?>
  <h3><?php echo $this->row->title;?></h3>
  <?php endif;?>
  <?php if ($this->row->body):?>
  <div class="plugin-body"><?php echo Url::out_url($this->row->body);?></div>
  <?php endif;?>
</article>
<?php endif;?>