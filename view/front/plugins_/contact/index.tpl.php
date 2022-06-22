<?php
  /**
   * Contact
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="yoyo form ctc">
  <form id="yoyo_form" name="yoyo_form" method="post">
    <div class="yoyo block fields ctc">
      <div class="field">
        <label><?php echo Lang::$word->NAME;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php if (App::Auth()->is_User()) echo App::Auth()->name;?>" name="name">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->M_EMAIL;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->M_EMAIL;?>" value="<?php if (App::Auth()->is_User()) echo App::Auth()->email;?>" name="email">
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->M_PHONE;?>
        </label>
        <input type="text" placeholder="<?php echo Lang::$word->M_PHONE;?>" name="phone">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ET_SUBJECT;?>
        </label>
        <select name="subject" class="yoyo fluid dropdown">
          <option value=""><?php echo Lang::$word->CF_SUBJECT_1;?></option>
          <option value="<?php echo Lang::$word->CF_SUBJECT_2;?>"><?php echo Lang::$word->CF_SUBJECT_2;?></option>
          <option value="<?php echo Lang::$word->CF_SUBJECT_3;?>"><?php echo Lang::$word->CF_SUBJECT_3;?></option>
          <option value="<?php echo Lang::$word->CF_SUBJECT_4;?>"><?php echo Lang::$word->CF_SUBJECT_4;?></option>
          <option value="<?php echo Lang::$word->CF_SUBJECT_5;?>"><?php echo Lang::$word->CF_SUBJECT_5;?></option>
          <option value="<?php echo Lang::$word->CF_SUBJECT_6;?>"><?php echo Lang::$word->CF_SUBJECT_6;?></option>
          <option value="<?php echo Lang::$word->CF_SUBJECT_7;?>"><?php echo Lang::$word->CF_SUBJECT_7;?></option>
		  <option value="<?php echo Lang::$word->CF_SUBJECT_8;?>"><?php echo Lang::$word->CF_SUBJECT_8;?></option>
          <option value="<?php echo Lang::$word->CF_SUBJECT_9;?>"><?php echo Lang::$word->CF_SUBJECT_9;?></option>
        </select>
      </div>
    </div>
    <div class="yoyo block fields">
      <div class="field">
        <label><?php echo Lang::$word->MESSAGE;?>
          <i class="icon asterisk"></i></label>
        <textarea class="small" placeholder="<?php echo Lang::$word->MESSAGE;?>" name="notes"></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CAPTCHA;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo right labeled fluid input">
          <input name="captcha" placeholder="<?php echo Lang::$word->CAPTCHA;?>" type="text">
          <div class="yoyo simple passive button captcha"><?php echo Session::captcha();?></div>
        </div>
      </div>
      <div class="field">
        <div class="yoyo checkbox">
          <input name="agree" type="checkbox" value="1" id="agree">
          <label for="agree"><a href="<?php echo Url::url('/' . App::Core()->system_slugs->policy[0]->{'slug' . Lang::$lang});?>" class="secondary dashed"><small><?php echo Lang::$word->AGREE;?></small></a>
          </label>
        </div>
      </div>
    </div>
    <div class="field">
      <button type="button" data-hide="true" data-action="processContact" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->CF_SEND;?></button>
    </div>
  </form>
</div>