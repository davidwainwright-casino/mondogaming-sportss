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
<h3 class="header"><?php echo Lang::$word->_MOD_TML_SUB12;?></h3>
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
        <div class="yoyo block fields">
          <div class="field">
            <label><?php echo Lang::$word->NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->{'title_' . $lang->abbr};?>" name="title_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field">
            <textarea class="altpost" name="body_<?php echo $lang->abbr;?>"><?php echo Url::out_url($this->data->{'body_' . $lang->abbr});?></textarea>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_TML_RMORE;?></label>
          <div class="yoyo fluid labeled input">
            <div class="yoyo basic label"> http:: </div>
            <input placeholder="<?php echo Lang::$word->_MOD_TML_RMORE;?>" value="<?php echo $this->data->readmore;?>" type="text" name="readmore">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="yoyo form segment">
    <?php if($this->data->type == "iframe" ):?>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_IURL;?></label>
        <div class="yoyo labeled fluid input">
          <div class="yoyo basic label"> http:: </div>
          <input placeholder="<?php echo Lang::$word->_MOD_TML_IURL;?>" value="<?php echo $this->data->dataurl;?>" type="text" name="dataurl">
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_IHEIGHT;?></label>
        <div class="yoyo right labeled fluid input">
          <input placeholder="<?php echo Lang::$word->_MOD_TML_IHEIGHT;?>" value="<?php echo $this->data->height;?>" type="text" name="height">
          <div class="yoyo basic label">px</div>
        </div>
      </div>
    </div>
    <?php else:?>
    <a class="multipick yoyo secondary button"><i class="open folder icon"></i><?php echo Lang::$word->_MOD_TML_SUB16;?></a>
    <div class="yoyo space divider"></div>
    <div class="row phone-block-1 mobile-block-2 tablet-block-3 screen-block-4 gutters content-center" id="sortable">
      <?php if($this->imagedata):?>
      <?php foreach($this->imagedata as $irow):?>
      <div class="column">
        <div class="yoyo simple attached card">
          <a class="yoyo middle small white icon attached button remove"><i class="icon negative trash"></i></a>
          <img src="<?php echo UPLOADURL . '/' . $irow;?>" alt="" class="yoyo shadow rounded image">
          <input type="hidden" name="images[]" value="<?php echo $irow;?>">
        </div>
      </div>
      <?php endforeach;?>
      <?php endif;?>
    </div>
    <?php endif;?>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/modules/timeline/items", $this->row->id);?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="modules_/timeline" data-action="processItem" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_MOD_TML_SUB13;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
  <input type="hidden" name="type" value="<?php echo $this->data->type;?>">
  <input type="hidden" name="tid" value="<?php echo $this->data->tid;?>">
</form>