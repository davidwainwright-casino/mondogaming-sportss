<?php
  /**
   * Gallery
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
    <h3><?php echo Lang::$word->_MOD_GA_TITLE;?></h3>
    <p class="yoyo small text"><?php echo Lang::$word->_MOD_GA_SUB;?></p>
  </div>
  <div class="column shrink mobile-100 phone-100">
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i>
      <?php echo Lang::$word->_MOD_GA_NEW;?></a>
  </div>
  <div class="column shrink">
    <a class="yoyo small basic icon button" id="reorder"><i class="icon apps"></i></a>
  </div>
</div>
<div class="hide-all" id="dragNotice"><p class="content-center yoyo primary semi icon text middle"> <i class="icon info sign"></i> Drag images around to sort</p></div>
<div class="yoyo big space divider"></div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small thick caps text"><?php echo Lang::$word->_MOD_GA_NOGAL;?></p>
</div>
<?php else:?>
<div class="masonry wide" id="sortable">
  <?php foreach($this->data as $row):?>
  <div class="item" id="item_<?php echo $row->id;?>" data-id="<?php echo $row->id;?>">
    <div class="yoyo attached card">
      <div class="image photo"><img src="<?php echo $row->poster ? FMODULEURL . 'gallery/data/' . $row->dir. '/thumbs/' . $row->poster : UPLOADURL . '/blank.jpg';?>" class="yoyo basic rounded image"></div>
      <div class="content-center">
        <h4 class="header"><?php echo $row->{'title' . Lang::$lang};?></h4>
        <a class="yoyo positive icon button" href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>"><i class="icon pencil"></i></a>
        <a data-set='{"option":[{"delete": "deleteGallery","title": "<?php echo Validator::sanitize($row->{'title' . Lang::$lang}, "chars");?>","id":<?php echo $row->id;?>, "dir":"<?php echo $row->dir;?>"}],"action":"delete","parent":"#item_<?php echo $row->id;?>","url":"modules_/gallery"}' class="yoyo negative icon button action"><i class="icon trash"></i></a>
      </div>
      <div class="content">
        <div class="row align-middle">
          <div class="column">
            <a href="<?php echo Url::url(Router::$path, "photos/" . $row->id);?>" class="yoyo small button">
              <?php echo Lang::$word->_MOD_GA_PHOTOS;?>
              <?php echo $row->pics;?>
            </a>
          </div>
          <div class="column content-right">
            <div class="yoyo small basic passive button">
              <?php echo Lang::$word->LIKES;?>
              <?php echo $row->likes;?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>