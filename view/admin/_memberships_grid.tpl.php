<?php
  /**
   * Membership Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="row half-gutters align-middle">
  <div class="column mobile-100 phone-100">
    <h3><?php echo Lang::$word->META_T4;?></h3>
    <p class="yoyo small text"><?php echo Lang::$word->MEM_SUB;?></p>
  </div>
  <div class="column shrink mobile-100 phone-100"> <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->MEM_SUB1;?></a> </div>
</div>
<div class="yoyo big space divider"></div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small thick caps text"><?php echo Lang::$word->MEM_NOMEM;?></p>
</div>
<?php else:?>
<div class="row screen-block-3 tablet-block-2 mobile-block-1 phone-block-1 double-gutters align-center">
  <?php foreach($this->data as $row):?>
  <div class="column" id="item_<?php echo $row->id;?>">
    <div class="yoyo attached segment content-center relative">
      <?php if($row->thumb):?>
      <img src="<?php echo UPLOADURL;?>/memberships/<?php echo $row->thumb;?>" alt="">
      <?php else:?>
      <img src="<?php echo UPLOADURL;?>/memberships/default.png" alt="">
      <?php endif;?>
      <div class="yoyo space divider"></div>
      <h4 class="content-center"><?php echo Utility::formatMoney($row->price);?> <?php echo $row->title;?></h4>
      <p class="yoyo tiny text half-bottom-padding"><?php echo Validator::truncate($row->description,40);?></p>
      <a href="<?php echo Url::url(Router::$path, "history/" . $row->id);?>" class="yoyo small label"><?php echo $row->total;?> <?php echo Lang::$word->TRX_SALES;?></a>
      <div class="yoyo divider"></div>
      <a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="yoyo icon positive small button"><i class="icon pencil"></i></a> <a data-set='{"option":[{"trash": "trashMembership","title": "<?php echo Validator::sanitize($row->title, "chars");?>","id":<?php echo $row->id;?>}],"action":"trash","parent":"#item_<?php echo $row->id;?>"}' class="yoyo icon negative small button action"> <i class="icon trash"></i> </a> </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>