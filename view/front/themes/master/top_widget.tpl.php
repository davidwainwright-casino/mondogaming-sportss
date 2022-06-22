<?php
  /**
   * Top Widget
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php if($this->layout->topCount):?>
<div class="topwidget clearfix">
  <?php if($this->layout->topCount > 1 and $this->layout->tcounter == 0):?>
  <div class="yoyo-grid">
    <div class="row gutters">
      <?php endif;?>
      <?php foreach ($this->layout->topWidget as $row): ?>
      <?php if($this->layout->topCount > 1 and $this->layout->tcounter):?>
      <div class="row">
        <?php endif;?>
        <div class="columns screen-<?php echo $row->space;?>0 tablet-<?php echo $row->space;?>0 mobile-100 phone-100">
          <div class="topwidget-wrap <?php echo ($row->alt_class) ? $row->alt_class : '';?>">
            <?php if ($row->show_title):?>
            <h4 class="yoyo header"><?php echo $row->title;?></h4>
            <?php endif;?>
            <?php if ($row->body):?>
            <div class="topwidget-body"><?php echo Url::out_url($row->body);?></div>
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
        </div>
        <?php if($this->layout->topCount > 1 and $this->layout->tcounter):?>
      </div>
      <?php endif;?>
      <?php endforeach; ?>
      <?php unset($row);?>
      <?php if($this->layout->topCount > 1 and $this->layout->tcounter == 0):?>
    </div>
  </div>
  <?php endif;?>
</div>
<?php endif;?>