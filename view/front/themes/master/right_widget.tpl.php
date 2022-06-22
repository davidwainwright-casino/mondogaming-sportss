<?php
  /**
   * Right Widget
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php if($this->layout->rightCount):?>
<aside class="rightwidget">
  <?php foreach ($this->layout->rightWidget as $row): ?>
  <div class="rightwidget-wrap <?php echo ($row->alt_class) ? $row->alt_class : '';?>">
    <?php if ($row->show_title):?>
    <h4 class="yoyo header"><?php echo $row->title;?></h4>
    <?php endif;?>
    <?php if ($row->body):?>
    <div class="rightwidget-body"><?php echo Url::out_url($row->body);?></div>
    <?php endif;?>
    <?php if ($row->jscode):?>
	  <script>
      <?php Validator::cleanOut($row->jscode);?>
      </script>
    <?php endif;?>
    <?php if ($row->system):?>
    <?php echo Plugins::loadPluginFile(array($row->plugalias, $row->plugin_id, $row->plug_id, $this->plugins));?>
    <?php endif;?>
  </div>
  <?php endforeach; ?>
  <?php unset($row);?>
</aside>
<?php endif;?>