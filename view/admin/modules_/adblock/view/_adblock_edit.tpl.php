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
<h3 class="header"><?php echo Lang::$word->_MOD_AB_EDIT;?></h3>
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
          <div class="field">
            <label><?php echo Lang::$word->NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->{'title_' . $lang->abbr};?>" name="title_<?php echo $lang->abbr?>">
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_AB_SUB;?>
            <i class="icon asterisk"></i></label>
          <div class="row">
            <div class="column">
              <div class="yoyo fluid right icon input" id="fromdate">
                <input name="start_date" type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB10;?>" value="<?php echo $this->data->start_date;?>">
                <i class="icon date"></i>
              </div>
            </div>
            <div class="column">
              <div class="yoyo fluid right icon input" id="enddate">
                <input name="end_date" type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB11;?>" value="<?php echo $this->data->end_date;?>">
                <i class="icon date"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_AB_SUB1;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB1;?>" value="<?php echo $this->data->total_views_allowed;?>" name="max_views">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_AB_SUB2;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB2;?>" value="<?php echo $this->data->total_clicks_allowed;?>" name="max_clicks">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_AB_SUB3;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB3;?>" value="<?php echo $this->data->minimum_ctr;?>" name="min_ctr">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <?php if($this->data->image):?>
          <label><?php echo Lang::$word->_MOD_AB_SUB4;?>
            <i class="icon asterisk"></i></label>
          <input type="file" data-buttonText="<?php echo Lang::$word->BROWSE;?>" name="image" id="image" class="filefield" data-input="false">
          <div class="yoyo space divider"></div>
          <label><?php echo Lang::$word->_MOD_AB_SUB5;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB5;?>"value="<?php echo $this->data->image_link;?>" name="image_link">
          <div class="yoyo space divider"></div>
          <label><?php echo Lang::$word->_MOD_AB_SUB6;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB6;?>"value="<?php echo $this->data->image_alt;?>" name="image_alt">
          <input type="hidden" name="html" value="">
          <?php else:?>
          <label><?php echo Lang::$word->_MOD_AB_SUB7;?>
            <i class="icon asterisk"></i></label>
          <textarea name="html" placeholder="<?php echo Lang::$word->_MOD_AB_SUB7;?>"><?php echo $this->data->html;?></textarea>
          <?php endif;?>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_AB_SUB4;?></label>
          <?php if($this->data->image):?>
          <img src="<?php echo  FPLUGINURL . $this->data->plugin_id . '/' . $this->data->image;?>" class="yoyo normal image">
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/modules", "adblock/");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="modules_/adblock" data-action="processCampaign" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_MOD_AB_UPDATE;?></button>
  </div>
  <input type="hidden" name="banner_type" value="<?php echo $this->data->image ? "yes" : "no";?>">
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
  <input type="hidden" name="plugin_id" value="<?php echo $this->data->plugin_id;?>">
</form>