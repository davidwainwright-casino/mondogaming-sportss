<?php
  /**
   * Gmaps
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
    <h3><?php echo Lang::$word->_MOD_GM_TITLE;?></h3>
  </div>
  <div class="column shrink mobile-100 phone-100"> <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i> <?php echo Lang::$word->_MOD_GM_NEW;?></a> </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small thick caps text"><?php echo Lang::$word->_MOD_GM_NOMAPS;?></p>
</div>
<?php else:?>
<div class="row phone-block-1 mobile-block-1 tablet-block-2 screen-block-3 gutters align-center">
  <?php foreach($this->data as $row):?>
  <div class="column" id="<?php echo $row->id;?>">
    <div class="yoyo card content-center">
      <div class="header">
        <h4><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="white"><?php echo $row->name;?></a></h4>
        <p class="yoyo white text"><?php echo $row->body;?></p>
      </div>
      <div class="small content"> <a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="yoyo positive icon button"><i class="icon pencil"></i></a> <a data-set='{"option":[{"delete": "deleteMap","title": "<?php echo Validator::sanitize($row->name, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>","url":"modules_/gmaps"}' class="yoyo negative icon button action"> <i class="icon trash"></i></a> </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>