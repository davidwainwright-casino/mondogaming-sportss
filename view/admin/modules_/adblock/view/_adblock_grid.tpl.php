<?php
  /**
   * Adblock
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
    <h3><?php echo Lang::$word->_MOD_AB_TITLE;?></h3>
    <p><?php echo Lang::$word->_MOD_AB_INFO;?></p>
  </div>
  <div class="column shrink mobile-100 phone-100">
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i>
      <?php echo Lang::$word->_MOD_AB_NEW;?></a>
  </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small bold caps text"><?php echo Lang::$word->_MOD_AB_NO_CMP;?></p>
</div>
<?php else:?>
<div class="row screen-block-3 tablet-block-2 mobile-block-2 mobile-block-1 phone-block-1 gutters align-center">
  <?php foreach($this->data as $row):?>
  <div class="column">
    <div class="yoyo attached segment content-center" id="item_<?php echo $row->id;?>">
      <div class="yoyo top right attached simple button" data-content="<?php echo Adblock::isOnlineStr($row);?>"><span class="yoyo <?php echo Adblock::isOnline($row) ? "positive" : "negative";?> ring label"></span>
      </div>
      <a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>"><img src="<?php echo AMODULEURL;?>adblock/view/images/<?php echo $row->image ? "image.png" : "html.png";?>" class="yoyo basic medium image"></a>
      <div class="yoyo space divider"></div>
      <h4><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="inverted"><?php echo $row->{'title' . Lang::$lang};?></a>
      </h4>
      <div class="yoyo space divider"></div>
      <div class="yoyo basic label">
        <?php echo Lang::$word->_MOD_AB_SUB9;?>
        <?php echo $row->total_views;?>
      </div>
      <div class="yoyo basic label">
        <?php echo Lang::$word->_MOD_AB_SUB8;?>
        <?php echo $row->total_clicks;?>
      </div>
      <div class="yoyo space divider"></div>
      <div class="content">
        <a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="yoyo positive icon button"><i class="icon pencil"></i></a>
        <a data-set='{"option":[{"delete": "deleteCampaign","title": "<?php echo Validator::sanitize($row->{'title' . Lang::$lang}, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>","url":"modules_/adblock"}' class="yoyo negative icon button action">
          <i class="icon trash"></i></a>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>