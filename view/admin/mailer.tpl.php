<?php
  /**
   * Mailer
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_newsletter')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<h3><?php echo Lang::$word->META_T24;?></h3>
<p class="yoyo small text"><?php echo Lang::$word->NL_INFO1;?></p>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo segment form">
    <div class="yoyo fields">
      <div class="field five wide disabled">
        <label><?php echo Lang::$word->NL_FROM;?></label>
        <input type="text" disabled placeholder="<?php echo Lang::$word->NL_FROM;?>" value="<?php echo App::Core()->site_email;?>" name="site_email">
      </div>
      <div class="field five wide">
        <label><?php echo Lang::$word->NL_RCPT;?> <i class="icon asterisk"></i></label>
        <?php if(Validator::get('email')):?>
        <input type="text" placeholder="<?php echo Lang::$word->NL_RCPT;?>" value="<?php echo Validator::get('email');?>" readonly name="recipient">
        <?php else:?>
        <select name="recipient" class="yoyo fluid selection dropdown">
          <option value="all"><?php echo Lang::$word->NL_UALL;?></option>
          <option value="free"><?php echo Lang::$word->NL_UAREG;?></option>
          <option value="paid"><?php echo Lang::$word->NL_UPAID;?></option>
          <option value="newsletter"><?php echo Lang::$word->NL_UNLS;?></option>
        </select>
        <?php endif;?>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->NL_SUBJECT;?> <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->NL_SUBJECT;?>" value="<?php echo $this->data->{'subject' . Lang::$lang};?>" name="subject">
      </div>
      <div class="field five wide">
        <label><?php echo Lang::$word->NL_ATTACH;?></label>
        <input type="file" name="attachment" id="attachment" class="filefield" data-input="false">
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->NL_BODY;?></label>
      <textarea name="body" class="altpost"><?php echo str_replace("[SITEURL]", SITEURL, $this->data->{'body' . Lang::$lang});?></textarea>
      <p class="column yoyo small negative text half-left-padding"> <?php echo Lang::$word->NOTEVAR;?></p>
    </div>
  </div>
  <div class="content-center">
    <button type="button" data-action="processMailer" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->NL_SEND;?></button>
  </div>
</form>