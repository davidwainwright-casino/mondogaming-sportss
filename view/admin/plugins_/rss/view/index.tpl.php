<?php
  /**
   * Rss
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::checkPlugAcl('rss')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments, 3)): case "edit": ?>
<!-- Start edit -->
<h3>
  <?php echo Lang::$word->_PLG_RSS_TITLE2;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form segment">
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->NAME;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo fluid big input">
          <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->title;?>" name="title">
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_RSS_URL;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->_PLG_RSS_URL;?>" value="<?php echo $this->data->url;?>" name="url">
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_RSS_SHOW_DATE;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="show_date" type="radio" value="1" id="show_date_1" <?php Validator::getChecked($this->data->show_date, 1);?>>
          <label for="show_date_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="show_date" type="radio" value="0" id="show_date_0" <?php Validator::getChecked($this->data->show_date, 0);?>>
          <label for="show_date_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_RSS_SHOW_DESC;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="show_desc" type="radio" value="1" id="show_desc_1" <?php Validator::getChecked($this->data->show_desc, 1);?>>
          <label for="show_desc_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="show_desc" type="radio" value="0" id="show_desc_0" <?php Validator::getChecked($this->data->show_desc, 0);?>>
          <label for="show_desc_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_RSS_ITEMS;?>
          <i class="icon asterisk"></i></label>
        <input data-ranger='{"step":1,"from":1, "to":30, "format":"item", "tip": false, "range":false}' type="text" name="items" value="<?php echo $this->data->items;?>" class="rangers">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_RSS_BODYTRIM;?>
          <i class="icon asterisk"></i></label>
        <input data-ranger='{"step":10,"from":10, "to":300, "format":"item", "tip": false, "range":words}' type="text" name="max_words" value="<?php echo $this->data->max_words;?>" class="rangers">
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/plugins", "rss");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="plugins_/rss" data-action="processRss" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->SAVECONFIG;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>
<?php break;?>
<?php case "new": ?>
<!-- Start new -->
<h3>
  <?php echo Lang::$word->_PLG_RSS_SUB2;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form segment">
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->NAME;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo big transparent down input">
          <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="title">
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_RSS_URL;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->_PLG_RSS_URL;?>"  name="url">
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_RSS_SHOW_DATE;?></label>
        <div class="yoyo inline fields">
          <div class="yoyo checkbox small radio slider field">
            <input name="show_date" type="radio" value="1" checked="checked">
            <label><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox small radio slider field">
            <input name="show_date" type="radio" value="0">
            <label><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_RSS_SHOW_DESC;?></label>
        <div class="yoyo inline fields">
          <div class="yoyo checkbox small radio slider field">
            <input name="show_desc" type="radio" value="1" checked="checked">
            <label><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox small radio slider field">
            <input name="show_desc" type="radio" value="0">
            <label><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_RSS_ITEMS;?>
          <i class="icon asterisk"></i></label>
        <input data-ranger='{"step":1,"from":1, "to":30, "format":"item", "tip": false, "range":false}' type="text" name="items" value="5" class="rangers">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_RSS_BODYTRIM;?>
          <i class="icon asterisk"></i></label>
        <input data-ranger='{"step":10,"from":10, "to":300, "format":"words", "tip": false, "range":false}' type="text" name="max_words" value="50" class="rangers">
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/plugins", "rss");?>" class="yoyo button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="plugins_/rss" data-action="processRss" name="dosubmit" class="yoyo secondary button"><?php echo Lang::$word->_PLG_RSS_TITLE1;?></button>
  </div>
</form>
<?php break;?>
<?php default: ?>
<!-- Start default -->
<div class="row gutters align-middle">
  <div class="column mobile-100 phone-100">
    <h3><?php echo Lang::$word->_PLG_RSS_TITLE;?></h3>
    <p class="yoyo small text"><?php echo Lang::$word->_PLG_RSS_SUB1;?></p>
  </div>
  <div class="column shrink mobile-100 phone-100">
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->_PLG_RSS_NEW;?></a>
  </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small bold text vertical-padding"><?php echo Lang::$word->_PLG_RSS_NORSS;?></p>
</div>
<?php else:?>
<div class="row phone-block-1 mobile-block-1 tablet-block-2 screen-block-2 gutters">
  <?php foreach($this->data as $row):?>
  <div class="column" id="item_<?php echo $row->id;?>">
    <div class="yoyo segment">
      <h4><?php echo $row->title;?></h4>
      <p><?php echo $row->url;?></p>
      <div class="yoyo divider"></div>
      <div class="content-center">
        <a href="<?php echo Url::url(Router::$path . "/edit", $row->id);?>" class="yoyo icon positive button"><i class="icon pencil"></i></a>
        <a data-set='{"option":[{"delete": "deleteRss","title": "<?php echo Validator::sanitize($row->title, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>", "url":"plugins_/rss"}' class="yoyo icon negative button action">
          <i class="icon trash"></i>
        </a>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>
<?php break;?>
<?php endswitch;?>