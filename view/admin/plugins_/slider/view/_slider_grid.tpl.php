<?php
  /**
   * Slider
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
    <h3><?php echo Lang::$word->_PLG_SL_TITLE;?></h3>
    <p class="yoyo small text"><?php echo Lang::$word->_PLG_SL_SUB2;?></p>
  </div>
  <div class="column shrink mobile-100 phone-100">
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->_PLG_SL_SUB7;?></a>
  </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small bold text vertical-padding"><?php echo Lang::$word->_PLG_SL_NOSLIDER;?></p>
</div>
<?php else:?>
<div class="row phone-block-1 mobile-block-1 tablet-block-2 screen-block-2 gutters">
  <?php foreach($this->data as $row):?>
  <div class="column" id="item_<?php echo $row->id;?>">
    <div class="yoyo attached segment content-center">
      <a href="<?php echo Url::url(Router::$path . "/edit", $row->id);?>"><img src="<?php echo APLUGINURL . 'slider/view/images/' . $row->layout;?>.png" class="yoyo basic image" alt=""></a>
      <div class="yoyo half space divider"></div>
      <p class="yoyo small bold text"><?php echo $row->title;?></p>
      <div class="yoyo divider"></div>
      <div class="content-center">
        <a href="<?php echo Url::url(Router::$path . "/edit", $row->id);?>" class="yoyo icon small positive button"><i class="icon pencil"></i></a>
        <a data-set='{"option":[{"delete": "deleteSlider","title": "<?php echo Validator::sanitize($row->title, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>", "url":"plugins_/slider"}' class="yoyo icon negative small button action">
          <i class="icon trash"></i>
        </a>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>