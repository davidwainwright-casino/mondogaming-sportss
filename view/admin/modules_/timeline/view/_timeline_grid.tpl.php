<?php
  /**
   * Timeline
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="row gutters align-middle">
  <div class="column mobile-100 phone-100">
    <h3><?php echo Lang::$word->_MOD_TML_TITLE;?></h3>
  </div>
  <div class="column shrink mobile-100 phone-100">
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i>
      <?php echo Lang::$word->_MOD_TML_NEW;?></a>
  </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small bold caps text"><?php echo Lang::$word->_MOD_TML_NOTML;?></p>
</div>
<?php else:?>
<div class="row screen-block-3 tablet-block-2 mobile-block-2 mobile-block-1 phone-block-1 gutters align-center">
  <?php foreach($this->data as $row):?>
  <div class="column">
    <div class="yoyo attached segment" id="item_<?php echo $row->id;?>">
      <a data-dropdown="#dropdown-tmMenu_<?php echo $row->id;?>" class="yoyo white icon simple circular top right spaced attached button">
        <i class="icon vertical ellipsis"></i>
      </a>
      <div class="yoyo small dropdown menu top-right" id="dropdown-tmMenu_<?php echo $row->id;?>">
        <a class="item" href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>"><i class="icon pencil"></i>
          <span class="padding-left"><?php echo Lang::$word->EDIT;?></span></a>
        <?php if($row->type == "custom"):?>
        <a class="item" href="<?php echo Url::url(Router::$path, "items/" . $row->id);?>"><i class="icon sliders horizontal"></i>
          <span class="padding-left"><?php echo Lang::$word->ITEMS;?></span></a>
        <?php endif;?>
        <a class="item action" data-set='{"option":[{"delete": "deleteTimeline","title": "<?php echo Validator::sanitize($row->name, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>","url":"modules_/timeline"}'><i class="icon trash"></i>
          <span class="padding-left"><?php echo Lang::$word->DELETE;?></span></a>
      </div>
      <div class="content-center">
        <a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>">
          <img src="<?php echo AMODULEURL . 'timeline/view/images/' . $row->type . '.png';?>" class="yoyo basic image">
        </a>
        <div class="yoyo space divider"></div>
        <h4>
          <a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>"><?php echo $row->name;?></a>
        </h4>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>