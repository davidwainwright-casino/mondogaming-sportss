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
  <?php echo Lang::$word->_MOD_GA_NEW;?>
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
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="title_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field five wide">
            <label><?php echo Lang::$word->ITEMSLUG;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->ITEMSLUG;?>" name="slug_<?php echo $lang->abbr?>">
            </div>
          </div>
        </div>
        <div class="yoyo fields">
          <div class="field">
            <label><?php echo Lang::$word->DESCRIPTION;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->DESCRIPTION;?>" name="description_<?php echo $lang->abbr?>">
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_THUMBW;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_GA_THUMBW;?>" name="thumb_w">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_THUMBH;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_GA_THUMBH;?>" name="thumb_h">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_COLS;?>
            <i class="icon asterisk"></i></label>
          <input data-ranger='{"step":50,"from":100, "to":600, "format":"px", "tip": false, "range":false}' type="text" name="cols" value="300" class="rangers">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_WMARK;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="watermark" type="radio" value="1" id="watermark_1">
            <label for="watermark_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="watermark" type="radio" value="0" id="watermark_0" checked="checked">
            <label for="watermark_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_LIKE;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="likes" type="radio" value="1" id="likes_1" checked="checked">
            <label for="likes_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="likes" type="radio" value="0" id="likes_0">
            <label for="likes_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GA_RESIZE_THE;?></label>
          <select name="resize" class="yoyo fluid dropdown">
            <option value="thumbnail">Thumbnail</option>
            <option value="resize">Resize</option>
            <option value="bestFit">Best Fit</option>
            <option value="fitToHeight">Fit to Height</option>
            <option value="fitToWidth">Fit to Width</option>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/modules", "gallery");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="modules_/gallery"  data-action="processGallery" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_MOD_GA_SUB3;?></button>
  </div>
</form>