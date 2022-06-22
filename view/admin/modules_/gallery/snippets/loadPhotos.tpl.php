<?php
  /**
   * Load Photos
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php if($this->photos):?>
<?php foreach($this->photos as $row):?>
<div class="item" id="item_<?php echo $row->id;?>" data-id="<?php echo $row->id;?>">
  <div class="yoyo attached card">
    <div class="image photo">
      <img src="<?php echo FMODULEURL . Gallery::GALDATA . $this->data->dir. '/thumbs/' . $row->thumb;?>" class="yoyo basic image"></div>
    <div class="content-center">
      <div class="description" id="description_<?php echo $row->id;?>">
        <h4><?php echo $row->{'title' . Lang::$lang};?></h4>
        <p><?php echo $row->{'description' . Lang::$lang};?></p>
      </div>
    </div>
    <div class="content">
      <div class="row align-middle">
        <div class="column">
          <div class="yoyo small basic button">
            <?php echo Lang::$word->LIKES;?>
            <?php echo $row->likes;?>
          </div>
        </div>
        <div class="column content-right">
          <a data-dropdown="#photoMenu_<?php echo $row->id;?>" class="yoyo white icon circular button">
            <i class="icon vertical ellipsis"></i>
          </a>
          <div class="yoyo small dropdown menu top-right pointing" id="photoMenu_<?php echo $row->id;?>">
            <a class="item addAction" data-set='{"option":[{"doAction": 1, "processItem": 1,"page":"editPhoto", "editPhoto": 1,"id": <?php echo $row->id;?>}], "label":"<?php echo Lang::$word->EDIT;?>", "url":"modules_/gallery/controller.php", "parent":"#description_<?php echo $row->id;?>", "action":"replace", "modalclass":"small"}'><i class="icon pencil"></i>
              <span class="padding-left"><?php echo Lang::$word->EDIT;?></span></a>
            <a class="item <?php echo ($this->data->poster == $row->thumb) ? 'disabled' : 'poster';?>" data-poster="<?php echo $row->thumb;?>"><i class="icon <?php echo ($this->data->poster == $row->thumb) ? 'check' : 'photo' ;?>"></i>
              <span class="padding-left"><?php echo Lang::$word->_MOD_GA_POSTER;?></span></a>
            <div class="yoyo basic divider"></div>
            <a class="item action" data-set='{"option":[{"delete": "deletePhoto","title": "<?php echo $row->{'title' . Lang::$lang};?>","id":<?php echo $row->id;?>, "dir":"<?php echo $this->data->dir;?>"}],"action":"delete","parent":"#item_<?php echo $row->id;?>","url":"modules_/gallery"}'><i class="icon trash"></i>
              <span class="padding-left"><?php echo Lang::$word->DELETE;?></span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endforeach;?>
<?php endif;?>