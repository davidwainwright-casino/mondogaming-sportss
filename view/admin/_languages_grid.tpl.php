<?php
  /**
   * Language Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="row half-gutters align-middle">
  <div class="column mobile-100">
      <h3><?php echo Lang::$word->LG_TITLE;?></h3>
      <?php echo Lang::$word->LG_SUB;?>
  </div>
  <div class="columns shrink mobile-100"> <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->LG_SUB5;?></a> </div>
</div>
<div class="row phone-block-1 tablet-block-2 mobile-block-1 screen-block-2 gutters align-center">
  <?php foreach ($this->data as $row):?>
  <div class="column" id="item_<?php echo $row->id;?>">
    <div class="yoyo segment"> <img src="<?php echo ADMINVIEW;?>/images/langbg.jpg">
      <div class="yoyo divider"></div>
      <div class="basic footer">
        <div class="row align-middle half-vertical-gutters no-gutters">
          <div class="columns">
            <div class="yoyo simple buttons"> <a href="<?php echo Url::url(Router::$path . "/edit", $row->id);?>" class="yoyo button"> <span class="flag icon <?php echo $row->abbr;?>"></span> <?php echo $row->name;?></a> <a href="<?php echo Url::url(Router::$path . "/translate", $row->id);?>" class="yoyo icon button"> <i class="icon positive chat"></i> </a> <a data-set='{"option":[{"delete": "deleteLanguage","title": "<?php echo Validator::sanitize($row->name, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>"}' class="yoyo icon button action"> <i class="icon negative trash"></i> </a> </div>
          </div>
          <div class="columns shrink"> <a data-lang-color="true" data-id="<?php echo $row->id;?>" class="yoyo empty big label" style="background-color:<?php echo $row->color;?>"></a> </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>