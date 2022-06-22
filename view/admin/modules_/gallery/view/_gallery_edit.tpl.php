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
<h3 class="header">
  <?php echo Lang::$word->_MOD_GA_TITLE1;?>
</h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form card">
    <ul class="yoyo basic wide tabs">
      <?php foreach($this->langlist as $lang):?>
      <li<?php echo ($lang->abbr == $this->core->lang) ? ' class="active"' : null;?>><a style="background:<?php echo $lang->color;?>;color:#fff" data-tab="#lang_<?php echo $lang->abbr;?>"><span class="flag icon <?php echo $lang->abbr;?>"></span><?php echo $lang->name;?></a>
      </li>
      <?php endforeach;?>
    </ul>
    <div class="content">
      <?php foreach($this->langlist as $lang):?>
      <div id="lang_<?php echo $lang->abbr;?>" class="yoyo tab item">
        <div class="yoyo fields">
          <div class="field five wide">
            <label><?php echo Lang::$word->NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->{'title_' . $lang->abbr};?>" name="title_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field five wide">
            <label><?php echo Lang::$word->ITEMSLUG;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->ITEMSLUG;?>" value="<?php echo $this->data->{'slug_' . $lang->abbr};?>" name="slug_<?php echo $lang->abbr?>">
            </div>
          </div>
        </div>
        <div class="yoyo fields">
          <div class="field">
            <label><?php echo Lang::$word->DESCRIPTION;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->DESCRIPTION;?>" value="<?php echo $this->data->{'description_' . $lang->abbr};?>" name="description_<?php echo $lang->abbr?>">
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_THUMBW;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_GA_THUMBW;?>" value="<?php echo $this->data->thumb_w;?>" name="thumb_w">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_THUMBH;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_GA_THUMBH;?>" value="<?php echo $this->data->thumb_h;?>" name="thumb_h">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_COLS;?>
            <i class="icon asterisk"></i></label>
          <input data-ranger='{"step":50,"from":100, "to":600, "format":"px", "tip": false, "range":false}' type="text" name="cols" value="<?php echo $this->data->cols;?>" class="rangers">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_WMARK;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="watermark" type="radio" value="1" id="watermark_1" <?php Validator::getChecked($this->data->watermark, 1); ?>>
            <label for="watermark_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="watermark" type="radio" value="0" id="watermark_0" <?php Validator::getChecked($this->data->watermark, 0); ?>>
            <label for="watermark_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_LIKE;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="likes" type="radio" value="1" id="likes_1" <?php Validator::getChecked($this->data->likes, 1); ?>>
            <label for="likes_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="likes" type="radio" value="0" id="likes_0" <?php Validator::getChecked($this->data->likes, 0); ?>>
            <label for="likes_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
      <a class="yoyo right floating tiny icon button" data-trigger="#uResize" data-type="slide down" data-transition="true" ><i class="icon chevron down"></i></a>
      <div class="yoyo basic segment hide-all" id="uResize">
        <h4><?php echo Lang::$word->_MOD_GA_RESIZE_TH;?></h4>
        <p class="yoyo small negative text"><?php echo Lang::$word->_MOD_GA_INFO;?></p>
        <div class="yoyo space divider"></div>
        <div class="yoyo fields">
          <div class="field">
            <div class="yoyo checkbox radio fitted inline">
              <input name="resize" type="radio" value="thumbnail" id="thumbnail_1" checked="checked">
              <label for="thumbnail_1">Thumbnail</label>
            </div>
            <div class="yoyo checkbox radio fitted inline">
              <input name="resize" type="radio" value="resize" id="resize_1" >
              <label for="resize_1">Resize</label>
            </div>
            <div class="yoyo checkbox radio fitted inline">
              <input name="resize" type="radio" value="bestFit" id="bestFit_1" >
              <label for="bestFit_1">Best Fit</label>
            </div>
            <div class="yoyo checkbox radio fitted inline">
              <input name="resize" type="radio" value="fitToHeight" id="fitToHeight_1" >
              <label for="fitToHeight_1">Fit to Height</label>
            </div>
            <div class="yoyo checkbox radio fitted inline">
              <input name="resize" type="radio" value="fitToWidth" id="inline_1" >
              <label for="inline_1">Fit to Width</label>
            </div>
          </div>
        </div>
        <div class="content-center">
          <button type="button" name="imgprop" id="doResize" class="yoyo negative tiny button"><?php echo Lang::$word->GO;?></button>
        </div>
      </div>
    </div>
  </div>
<div class="content-center">
  <a href="<?php echo Url::url("/admin/modules", "gallery");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
  <button type="button" data-url="modules_/gallery"  data-action="processGallery" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_MOD_GA_SUB2;?></button>
</div>
<input type="hidden" name="id" value="<?php echo $this->data->id;?>">
<input type="hidden" name="dir" value="<?php echo $this->data->dir;?>">
</form>