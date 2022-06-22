<?php
  /**
   * Builder Plugins
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php if($this->data):?>
<?php foreach($this->data as $row):?>
<div class="column">
  <div id="plg_<?php echo $row->id;?>" class="pluginlist button"> <img data-content="<?php echo htmlspecialchars($row->title);?>" data-text="<?php echo htmlspecialchars($row->title);?>" data-element="plugin" src="<?php echo APLUGINURL . $row->icon;?>"/>
    <div class="phidden">
      <div class="column-wrap">
        <div class="column-dummy">
          <div id="newPlugin_<?php echo $row->id;?>" data-mode="readonly" data-plugin-id="<?php echo $row->id;?>" data-plugin-plugin_id="<?php echo $row->plugin_id;?>" data-plugin-name="<?php echo htmlspecialchars($row->title);?>" data-plugin-alias="<?php echo $row->plugalias;?>">
            <p class="yoyo bold text content-center">::<?php echo $row->title;?>::</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endforeach;?>
<?php endif;?>