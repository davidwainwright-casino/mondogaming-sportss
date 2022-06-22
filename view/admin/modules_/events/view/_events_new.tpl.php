<?php
  /**
   * Events
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<h3 class="header"><?php echo Lang::$word->_MOD_EM_SUB;?></h3>
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
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="title_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->_MOD_EM_VENUE;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->_MOD_EM_VENUE;?>" name="venue_<?php echo $lang->abbr?>">
            </div>
          </div>
        </div>
        <div class="yoyo fields">
          <div class="field">
            <textarea class="altpost" name="body_<?php echo $lang->abbr;?>"></textarea>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>

  <div class="yoyo form card">
    <div class="content">
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_EM_CONTACT;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_EM_CONTACT;?>" name="contact_person">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_EM_PHONE;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_EM_PHONE;?>" name="contact_phone">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_EM_EMAIL;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_EM_EMAIL;?>" name="contact_email">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_EM_DATE_S;?>
            <i class="icon asterisk"></i></label>
          <div class="row">
            <div class="column screen-70 tablet-70">
              <div class="yoyo fluid right icon input" data-datepicker="true">
                <input name="date_start" type="text" placeholder="<?php echo Lang::$word->_MOD_EM_DATE_ST;?>">
                <i class="icon date"></i>
              </div>
            </div>
            <div class="column">
              <div class="yoyo fluid calendar left right input" data-timepicker="true">
                <input name="time_start" type="text" placeholder="<?php echo Lang::$word->_MOD_EM_TIME_ST;?>">
                <i class="icon clock"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_EM_DATE_SE;?>
            <i class="icon asterisk"></i></label>
          <div class="row">
            <div class="column screen-70 tablet-70">
              <div class="yoyo fluid right icon input" data-datepicker="true">
                <input name="date_end" type="text" placeholder="<?php echo Lang::$word->_MOD_EM_DATE_ST;?>">
                <i class="icon date"></i>
              </div>
            </div>
            <div class="column">
              <div class="yoyo fluid calendar left right input" data-timepicker="true">
                <input name="time_end" type="text" placeholder="<?php echo Lang::$word->_MOD_EM_TIME_ET;?>">
                <i class="icon clock"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_EM_COLOUR;?></label>
          <div class="yoyo fluid right action input">
            <input type="text" value="#202020" name="color" readonly>
            <div class="yoyo big empty label link" data-color="true" style="background:#202020">
          </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PUBLISHED;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="active" type="radio" value="1" id="active_1"  checked="checked">
          <label for="active_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="active" type="radio" value="0" id="active_0">
          <label for="active_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
  </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/modules", "events/");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="modules_/events" data-action="processEvent" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_MOD_EM_TITLE2;?></button>
  </div>
</form>